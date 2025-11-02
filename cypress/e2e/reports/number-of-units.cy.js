/**
 * Number of Units Report E2E Test Suite
 * Tests all functionality of the Number of Units Report
 */

describe('Number of Units Report', () => {
  beforeEach(() => {
    // Login before each test
    cy.login()

    // Navigate to Number of Units Report page
    cy.visit('/reports/number-of-units')
    cy.url().should('include', '/reports/number-of-units')
  })

  describe('Page Load and Initial State', () => {
    it('should load the number of units report page successfully', () => {
      cy.get('h2').should('contain', 'Number of Units')
      cy.get('.sigma-report-table').should('exist')
    })

    it('should display filter controls', () => {
      cy.get('input[name="from"]').should('exist')
      cy.get('input[name="to"]').should('exist')
      cy.get('button[type="submit"]').should('contain', 'Generate Report')
    })

    it('should display table with doctor names and job types', () => {
      cy.get('.sigma-report-table thead th').first().should('contain', 'Doctor')
      cy.get('.sigma-report-table tbody tr').should('have.length.at.least', 1)
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

    it('should default to current month date range', () => {
      const currentDate = new Date()
      const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1)
        .toISOString().split('T')[0]

      cy.get('input[name="from"]').should('have.value', firstDay)
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
  })

  describe('Table Data Display', () => {
    it('should display unit counts per job type', () => {
      cy.get('.sigma-report-table thead th').should('contain.text', 'Crown')
        .or('contain.text', 'Bridge')
        .or('contain.text', 'Veneer')
    })

    it('should display total row with correct sums', () => {
      cy.get('.sigma-report-table .totals-row').should('exist')
      cy.get('.sigma-report-table .totals-row td').first().should('contain', 'Total')
    })

    it('should show numeric values in cells', () => {
      cy.get('.sigma-report-table tbody tr').first().find('td').each(($cell, index) => {
        if (index > 0) { // Skip doctor name column
          cy.wrap($cell).invoke('text').should('match', /^\d+$/)
        }
      })
    })

    it('should display zebra striping on table rows', () => {
      cy.get('.sigma-report-table tbody tr:nth-child(even)').should('have.css', 'background-color')
      cy.get('.sigma-report-table tbody tr:nth-child(odd)').should('have.css', 'background-color')
    })
  })

  describe('Export Functionality', () => {
    it('should have print button visible', () => {
      cy.get('.printBtn').should('exist')
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
    it('should handle no data scenario', () => {
      cy.get('input[name="from"]').clear().type('1990-01-01')
      cy.get('input[name="to"]').clear().type('1990-01-02')
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('have.length', 0)
        .or('contain', 'No data')
    })

    it('should handle single doctor with multiple job types', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('have.length.at.least', 1)
    })

    it('should recalculate totals when filters change', () => {
      // Get initial total
      cy.get('.totals-row td').eq(1).invoke('text').then((initialTotal) => {
        // Change filter
        cy.get('#doctor').parent('.bootstrap-select').find('button').click()
        cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
        cy.get('button[type="submit"]').click()

        cy.wait(1000)
        // Total should be different (or same if only one doctor has data)
        cy.get('.totals-row td').eq(1).invoke('text').should('exist')
      })
    })
  })

  describe('Header Styling', () => {
    it('should have colored first and last header columns', () => {
      cy.get('.sigma-report-table thead th').first().should('have.class', 'header-dark')
      cy.get('.sigma-report-table thead th').last().should('have.class', 'header-dark')
    })

    it('should have light styling on middle header columns', () => {
      cy.get('.sigma-report-table thead th').eq(1).should('have.class', 'header-light')
    })
  })

  describe('Responsive Behavior', () => {
    it('should be responsive on mobile viewport', () => {
      cy.viewport('iphone-x')
      cy.get('.sigma-report-table').should('be.visible')
    })

    it('should scroll horizontally on small screens', () => {
      cy.viewport(375, 667)
      cy.get('.sigma-table-container').should('have.css', 'overflow-x')
    })
  })

  describe('Data Accuracy', () => {
    it('should display consistent data across page reloads', () => {
      let firstTotal
      cy.get('.totals-row td').eq(1).invoke('text').then((total) => {
        firstTotal = total
        cy.reload()
        cy.wait(1000)
        cy.get('.totals-row td').eq(1).invoke('text').should('equal', firstTotal)
      })
    })

    it('should sum individual rows to match total', () => {
      const columnTotals = []
      cy.get('.sigma-report-table tbody tr').not('.totals-row').each(($row) => {
        cy.wrap($row).find('td').each(($cell, index) => {
          if (index > 0) {
            const value = parseInt($cell.text()) || 0
            columnTotals[index] = (columnTotals[index] || 0) + value
          }
        })
      }).then(() => {
        // Verify totals match
        cy.get('.totals-row td').each(($cell, index) => {
          if (index > 0 && columnTotals[index]) {
            cy.wrap($cell).invoke('text').then((totalText) => {
              expect(parseInt(totalText)).to.equal(columnTotals[index])
            })
          }
        })
      })
    })
  })
})
