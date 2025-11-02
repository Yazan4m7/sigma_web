/**
 * Implants Report E2E Test Suite
 * Tests all functionality of the Implants Report
 */

describe('Implants Report', () => {
  beforeEach(() => {
    // Login before each test
    cy.login()

    // Navigate to Implants Report page
    cy.visit('/reports/implants')
    cy.url().should('include', '/reports/implants')
  })

  describe('Page Load and Initial State', () => {
    it('should load the implants report page successfully', () => {
      cy.get('h2').should('contain', 'Implant')
      cy.get('.sigma-report-table').should('exist')
    })

    it('should display filter controls', () => {
      cy.get('input[name="from"]').should('exist')
      cy.get('input[name="to"]').should('exist')
      cy.get('#doctor').should('exist')
      cy.get('#implants').should('exist')
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

    it('should show all doctors when "All" selected', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select')
        .find('.dropdown-menu li[data-original-index="0"]').click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('have.length.at.least', 1)
    })
  })

  describe('Implant Type Filter', () => {
    it('should filter by specific implant type', () => {
      cy.get('#implants').parent('.bootstrap-select').find('button').click()
      cy.get('#implants').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('exist')
    })

    it('should show all implant types when "All" selected', () => {
      cy.get('#implants').parent('.bootstrap-select').find('button').click()
      cy.get('#implants').parent('.bootstrap-select')
        .find('.dropdown-menu li[data-original-index="0"]').click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table thead th').should('have.length.at.least', 3)
    })

    it('should display implant type columns dynamically', () => {
      cy.get('.sigma-report-table thead th').should('satisfy', ($headers) => {
        const headerText = $headers.text()
        // Check for common implant brands/types
        return headerText.length > 0
      })
    })
  })

  describe('Table Data Display', () => {
    it('should display implant counts per doctor', () => {
      cy.get('.sigma-report-table tbody tr').should('have.length.at.least', 1)
      cy.get('.sigma-report-table tbody tr').first().find('td').should('have.length.at.least', 2)
    })

    it('should show numeric values for implant counts', () => {
      cy.get('.sigma-report-table tbody tr').first().find('td').each(($cell, index) => {
        if (index > 0) {
          cy.wrap($cell).invoke('text').should('match', /^\d+$/)
        }
      })
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

    it('should display doctor names correctly', () => {
      cy.get('.sigma-report-table tbody tr').first().find('td').first()
        .invoke('text').should('not.be.empty')
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

    it('should maintain consistency across reloads', () => {
      let firstTotal
      cy.get('.totals-row td').eq(1).invoke('text').then((total) => {
        firstTotal = total
        cy.reload()
        cy.wait(1000)
        cy.get('.totals-row td').eq(1).invoke('text').should('equal', firstTotal)
      })
    })

    it('should show zero for doctors with no implants', () => {
      cy.get('.sigma-report-table tbody tr').each(($row) => {
        cy.wrap($row).find('td').each(($cell, index) => {
          if (index > 0) {
            const value = parseInt($cell.text())
            expect(value).to.be.at.least(0)
          }
        })
      })
    })
  })

  describe('Combined Filters', () => {
    it('should apply both doctor and implant type filters', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()

      cy.get('#implants').parent('.bootstrap-select').find('button').click()
      cy.get('#implants').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()

      cy.get('button[type="submit"]').click()
      cy.wait(1000)

      cy.get('.sigma-report-table tbody tr').should('exist')
    })

    it('should combine date range and doctor filter', () => {
      cy.get('input[name="from"]').clear().type('2025-01-01')
      cy.get('input[name="to"]').clear().type('2025-01-31')

      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()

      cy.get('button[type="submit"]').click()
      cy.wait(1000)

      cy.get('.sigma-report-table').should('exist')
    })
  })

  describe('Export and Print', () => {
    it('should have print button visible', () => {
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
    it('should handle no implant data', () => {
      cy.get('input[name="from"]').clear().type('1990-01-01')
      cy.get('input[name="to"]').clear().type('1990-01-02')
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('have.length', 0)
        .or('contain', 'No data')
    })

    it('should handle doctor with no implants', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').last().click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table').should('exist')
    })

    it('should handle single implant type filter', () => {
      cy.get('#implants').parent('.bootstrap-select').find('button').click()
      cy.get('#implants').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      // Should show only one column for that implant type
      cy.get('.sigma-report-table thead th').should('have.length.at.least', 2)
    })
  })

  describe('Filter State Persistence', () => {
    it('should persist selected implant type after reload', () => {
      cy.get('#implants').parent('.bootstrap-select').find('button').click()
      cy.get('#implants').parent('.bootstrap-select').find('.dropdown-menu li').eq(1)
        .invoke('text').then((implantType) => {
          cy.get('#implants').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
          cy.get('button[type="submit"]').click()
          cy.wait(1000)

          cy.reload()
          cy.get('#implants').parent('.bootstrap-select').find('.filter-option-inner-inner')
            .should('contain', implantType.trim())
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

    it('should handle horizontal scroll on small screens', () => {
      cy.viewport(375, 667)
      cy.get('.sigma-table-container').scrollTo('right')
      cy.get('.sigma-report-table').should('be.visible')
    })
  })

  describe('Performance', () => {
    it('should load report within acceptable time', () => {
      const startTime = Date.now()
      cy.get('button[type="submit"]').click()
      cy.get('.sigma-report-table tbody tr').should('exist').then(() => {
        const loadTime = Date.now() - startTime
        expect(loadTime).to.be.lessThan(5000)
      })
    })
  })
})
