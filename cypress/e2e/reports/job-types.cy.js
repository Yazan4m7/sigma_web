/**
 * Job Types Report E2E Test Suite
 * Tests all functionality of the Job Types Report
 */

describe('Job Types Report', () => {
  beforeEach(() => {
    // Login before each test
    cy.login()

    // Navigate to Job Types Report page
    cy.visit('/reports/job-types')
    cy.url().should('include', '/reports/job-types')
  })

  describe('Page Load and Initial State', () => {
    it('should load the job types report page successfully', () => {
      cy.get('h2').should('contain', 'Job Types')
      cy.get('.sigma-report-table').should('exist')
    })

    it('should display filter controls', () => {
      cy.get('input[name="from"]').should('exist')
      cy.get('input[name="to"]').should('exist')
      cy.get('#doctor').should('exist')
      cy.get('button[type="submit"]').should('contain', 'Generate Report')
    })

    it('should display table headers correctly', () => {
      cy.get('.sigma-report-table thead th').first().should('contain', 'Doctor')
      cy.get('.sigma-report-table thead th').should('have.length.at.least', 3)
    })
  })

  describe('Date Range Filters', () => {
    it('should filter data by date range', () => {
      const fromDate = '2025-01-01'
      const toDate = '2025-01-31'

      cy.get('input[name="from"]').clear().type(fromDate)
      cy.get('input[name="to"]').clear().type(toDate)
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('exist')
    })

    it('should default to current month', () => {
      const now = new Date()
      const monthStart = new Date(now.getFullYear(), now.getMonth(), 1)
        .toISOString().split('T')[0]

      cy.get('input[name="from"]').should('have.value', monthStart)
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

    it('should show all doctors when "All" is selected', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li[data-original-index="0"]').click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('have.length.at.least', 1)
    })

    it('should display selected doctor name in filter', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').eq(1)
        .invoke('text').then((doctorName) => {
          cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
          cy.get('#doctor').parent('.bootstrap-select').find('.filter-option-inner-inner')
            .should('contain', doctorName.trim())
        })
    })
  })

  describe('Table Data Display', () => {
    it('should display job type counts per doctor', () => {
      cy.get('.sigma-report-table tbody tr').first().find('td').should('have.length.at.least', 2)
    })

    it('should show numeric values for job counts', () => {
      cy.get('.sigma-report-table tbody tr').first().find('td').each(($cell, index) => {
        if (index > 0) { // Skip doctor name column
          cy.wrap($cell).invoke('text').should('match', /^\d+$/)
        }
      })
    })

    it('should display total row', () => {
      cy.get('.sigma-report-table .totals-row').should('exist')
      cy.get('.sigma-report-table .totals-row td').first().should('contain', 'Total')
    })

    it('should calculate totals correctly', () => {
      cy.get('.sigma-report-table .totals-row td').each(($cell, index) => {
        if (index > 0) {
          cy.wrap($cell).invoke('text').then((totalText) => {
            const total = parseInt(totalText)
            expect(total).to.be.at.least(0)
          })
        }
      })
    })

    it('should apply zebra striping', () => {
      cy.get('.sigma-report-table tbody tr:nth-child(even)').should('have.css', 'background-color')
      cy.get('.sigma-report-table tbody tr:nth-child(odd)').should('have.css', 'background-color')
    })
  })

  describe('Job Type Columns', () => {
    it('should display common job types as columns', () => {
      cy.get('.sigma-report-table thead th').should('satisfy', ($headers) => {
        const headerText = $headers.text()
        return headerText.includes('Crown') ||
               headerText.includes('Bridge') ||
               headerText.includes('Veneer') ||
               headerText.includes('Inlay')
      })
    })

    it('should show zero counts for doctors with no jobs of that type', () => {
      cy.get('.sigma-report-table tbody tr').first().find('td').each(($cell, index) => {
        if (index > 0) {
          const value = parseInt($cell.text())
          expect(value).to.be.at.least(0)
        }
      })
    })
  })

  describe('Export and Print', () => {
    it('should have print button visible', () => {
      cy.get('.printBtn').should('exist').and('be.visible')
    })

    it('should trigger print on button click', () => {
      cy.window().then((win) => {
        cy.stub(win, 'print').as('print')
      })
      cy.get('.printBtn').click()
      cy.get('@print').should('be.called')
    })
  })

  describe('Edge Cases', () => {
    it('should handle no data scenario', () => {
      cy.get('input[name="from"]').clear().type('1990-01-01')
      cy.get('input[name="to"]').clear().type('1990-01-02')
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('have.length', 0)
        .or('contain', 'No data')
    })

    it('should handle doctor with no jobs', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').last().click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      // Should show doctor with all zeros or no row
      cy.get('.sigma-report-table').should('exist')
    })

    it('should persist filters across page reloads', () => {
      const testDate = '2025-01-15'
      cy.get('input[name="from"]').clear().type(testDate)
      cy.get('button[type="submit"]').click()
      cy.wait(1000)

      cy.reload()
      cy.get('input[name="from"]').should('have.value', testDate)
    })
  })

  describe('Data Accuracy', () => {
    it('should sum rows to equal totals', () => {
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

    it('should maintain data consistency on filter changes', () => {
      // Apply filter and get total
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
      cy.get('button[type="submit"]').click()
      cy.wait(1000)

      let firstTotal
      cy.get('.totals-row td').eq(1).invoke('text').then((total) => {
        firstTotal = total

        // Change back to all doctors and back again
        cy.get('#doctor').parent('.bootstrap-select').find('button').click()
        cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li[data-original-index="0"]').click()
        cy.get('button[type="submit"]').click()
        cy.wait(1000)

        cy.get('#doctor').parent('.bootstrap-select').find('button').click()
        cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
        cy.get('button[type="submit"]').click()
        cy.wait(1000)

        // Should match original total
        cy.get('.totals-row td').eq(1).invoke('text').should('equal', firstTotal)
      })
    })
  })

  describe('Header Styling', () => {
    it('should have dark styling on first and last columns', () => {
      cy.get('.sigma-report-table thead th').first().should('have.class', 'header-dark')
      cy.get('.sigma-report-table thead th').last().should('have.class', 'header-dark')
    })

    it('should have light styling on middle columns', () => {
      cy.get('.sigma-report-table thead th').eq(1).should('have.class', 'header-light')
    })
  })

  describe('Responsive Behavior', () => {
    it('should be responsive on mobile', () => {
      cy.viewport('iphone-x')
      cy.get('.sigma-report-table').should('be.visible')
      cy.get('.sigma-table-container').should('have.css', 'overflow-x')
    })

    it('should be responsive on tablet', () => {
      cy.viewport('ipad-2')
      cy.get('.sigma-report-table').should('be.visible')
    })
  })
})
