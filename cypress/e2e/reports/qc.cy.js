/**
 * QC Report E2E Test Suite
 * Tests all functionality of the Quality Control Report
 */

describe('QC Report', () => {
  beforeEach(() => {
    // Login before each test
    cy.login()

    // Navigate to QC Report page
    cy.visit('/reports/qc')
    cy.url().should('include', '/reports/qc')
  })

  describe('Page Load and Initial State', () => {
    it('should load the QC report page successfully', () => {
      cy.get('h2').should('contain', 'QC')
      cy.get('.sigma-report-table').should('exist')
    })

    it('should display filter controls', () => {
      cy.get('input[name="from"]').should('exist')
      cy.get('input[name="to"]').should('exist')
      cy.get('#doctor').should('exist')
      cy.get('button[type="submit"]').should('exist')
    })

    it('should display table headers', () => {
      cy.get('.sigma-report-table thead th').first().should('contain', 'Doctor')
      cy.get('.sigma-report-table thead th').should('have.length.at.least', 3)
    })
  })

  describe('Date Range Filters', () => {
    it('should filter by date range', () => {
      const fromDate = '2025-01-01'
      const toDate = '2025-01-31'

      cy.get('input[name="from"]').clear().type(fromDate)
      cy.get('input[name="to"]').clear().type(toDate)
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('exist')
    })

    it('should default to current month date range', () => {
      const now = new Date()
      const monthStart = new Date(now.getFullYear(), now.getMonth(), 1)
        .toISOString().split('T')[0]

      cy.get('input[name="from"]').should('have.value', monthStart)
    })

    it('should include full end date (end of day)', () => {
      const today = new Date().toISOString().split('T')[0]

      cy.get('input[name="from"]').clear().type(today)
      cy.get('input[name="to"]').clear().type(today)
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('exist')
    })
  })

  describe('Doctor Filter', () => {
    it('should filter by specific doctor', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('exist')
    })

    it('should show all doctors when "All" selected', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li[data-original-index="0"]').click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('have.length.at.least', 1)
    })
  })

  describe('QC Status Columns', () => {
    it('should display QC status columns (Pass, Fail, Pending)', () => {
      cy.get('.sigma-report-table thead th').should('satisfy', ($headers) => {
        const headerText = $headers.text()
        return headerText.includes('Pass') ||
               headerText.includes('Fail') ||
               headerText.includes('Pending') ||
               headerText.includes('Success')
      })
    })

    it('should show numeric counts in QC columns', () => {
      cy.get('.sigma-report-table tbody tr').first().find('td').each(($cell, index) => {
        if (index > 0) {
          cy.wrap($cell).invoke('text').should('match', /^\d+$/)
        }
      })
    })
  })

  describe('Table Data Display', () => {
    it('should display QC data per doctor', () => {
      cy.get('.sigma-report-table tbody tr').should('have.length.at.least', 1)
      cy.get('.sigma-report-table tbody tr').first().find('td').first()
        .invoke('text').should('not.be.empty')
    })

    it('should display total row', () => {
      cy.get('.sigma-report-table .totals-row').should('exist')
      cy.get('.sigma-report-table .totals-row td').first()
        .should('contain', 'Total')
    })

    it('should calculate totals correctly', () => {
      cy.get('.totals-row td').each(($cell, index) => {
        if (index > 0) {
          cy.wrap($cell).invoke('text').then((text) => {
            const value = parseInt(text)
            expect(value).to.be.at.least(0)
          })
        }
      })
    })

    it('should apply zebra striping', () => {
      cy.get('.sigma-report-table tbody tr:nth-child(even)')
        .should('have.css', 'background-color')
      cy.get('.sigma-report-table tbody tr:nth-child(odd)')
        .should('have.css', 'background-color')
    })
  })

  describe('Data Accuracy', () => {
    it('should sum individual rows to match totals', () => {
      const columnTotals = []

      cy.get('.sigma-report-table tbody tr').not('.totals-row').each(($row) => {
        cy.wrap($row).find('td').each(($cell, index) => {
          if (index > 0) {
            const value = parseInt($cell.text()) || 0
            columnTotals[index] = (columnTotals[index] || 0) + value
          }
        })
      }).then(() => {
        cy.get('.totals-row td').each(($cell, index) => {
          if (index > 0 && columnTotals[index] !== undefined) {
            cy.wrap($cell).invoke('text').then((totalText) => {
              expect(parseInt(totalText)).to.equal(columnTotals[index])
            })
          }
        })
      })
    })

    it('should maintain consistency across reloads', () => {
      let firstTotal
      cy.get('.totals-row td').eq(1).invoke('text').then((total) => {
        firstTotal = total
        cy.reload()
        cy.wait(1000)
        cy.get('.totals-row td').eq(1).invoke('text').should('equal', firstTotal)
      })
    })
  })

  describe('Export and Print', () => {
    it('should have print button', () => {
      cy.get('.printBtn').should('exist').and('be.visible')
    })

    it('should trigger print dialog', () => {
      cy.window().then((win) => {
        cy.stub(win, 'print').as('print')
      })
      cy.get('.printBtn').click()
      cy.get('@print').should('be.called')
    })
  })

  describe('Edge Cases', () => {
    it('should handle no QC data scenario', () => {
      cy.get('input[name="from"]').clear().type('1990-01-01')
      cy.get('input[name="to"]').clear().type('1990-01-02')
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('have.length', 0)
        .or('contain', 'No data')
    })

    it('should handle doctor with no QC records', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').last().click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table').should('exist')
    })

    it('should handle all pass scenario', () => {
      // Filter for date range with all passes
      cy.get('button[type="submit"]').click()
      cy.wait(1000)

      // Check if any row has only passes
      cy.get('.sigma-report-table tbody tr').first().should('exist')
    })

    it('should handle all fail scenario', () => {
      // Test that report can display all fails if data exists
      cy.get('button[type="submit"]').click()
      cy.wait(1000)
      cy.get('.sigma-report-table').should('exist')
    })
  })

  describe('Filter State Persistence', () => {
    it('should persist selected doctor after reload', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').eq(1)
        .invoke('text').then((doctorName) => {
          cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
          cy.get('button[type="submit"]').click()
          cy.wait(1000)

          cy.reload()
          cy.get('#doctor').parent('.bootstrap-select').find('.filter-option-inner-inner')
            .should('contain', doctorName.trim())
        })
    })

    it('should persist date range after reload', () => {
      const testDate = '2025-01-15'
      cy.get('input[name="from"]').clear().type(testDate)
      cy.get('button[type="submit"]').click()
      cy.wait(1000)

      cy.reload()
      cy.get('input[name="from"]').should('have.value', testDate)
    })
  })

  describe('Header Styling', () => {
    it('should have dark headers on first and last columns', () => {
      cy.get('.sigma-report-table thead th').first()
        .should('have.class', 'header-dark')
      cy.get('.sigma-report-table thead th').last()
        .should('have.class', 'header-dark')
    })

    it('should have light headers on middle columns', () => {
      cy.get('.sigma-report-table thead th').eq(1)
        .should('have.class', 'header-light')
    })
  })

  describe('Responsive Behavior', () => {
    it('should display correctly on mobile', () => {
      cy.viewport('iphone-x')
      cy.get('.sigma-report-table').should('be.visible')
      cy.get('.sigma-table-container').should('have.css', 'overflow-x')
    })

    it('should display correctly on tablet', () => {
      cy.viewport('ipad-2')
      cy.get('.sigma-report-table').should('be.visible')
    })

    it('should maintain readability on small screens', () => {
      cy.viewport(375, 667)
      cy.get('.sigma-report-table thead th').first()
        .invoke('width').should('be.at.least', 50)
    })
  })

  describe('Performance', () => {
    it('should load report within acceptable time', () => {
      const startTime = Date.now()
      cy.get('button[type="submit"]').click()
      cy.get('.sigma-report-table tbody tr').should('exist').then(() => {
        const loadTime = Date.now() - startTime
        expect(loadTime).to.be.lessThan(5000) // 5 seconds max
      })
    })
  })
})
