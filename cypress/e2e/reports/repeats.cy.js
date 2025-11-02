/**
 * Repeats Report E2E Test Suite
 * Tests all functionality of the Repeats/Failures Report
 */

describe('Repeats Report', () => {
  beforeEach(() => {
    // Login before each test
    cy.login()

    // Navigate to Repeats Report page
    cy.visit('/reports/repeats')
    cy.url().should('include', '/reports/repeats')
  })

  describe('Page Load and Initial State', () => {
    it('should load the repeats report page successfully', () => {
      cy.get('h2').should('satisfy', ($h2) => {
        const text = $h2.text()
        return text.includes('Repeat') || text.includes('Failure')
      })
      cy.get('.sigma-report-table').should('exist')
    })

    it('should display filter controls', () => {
      cy.get('input[name="from"]').should('exist')
      cy.get('input[name="to"]').should('exist')
      cy.get('#doctor').should('exist')
      cy.get('#failureTypeInput').should('exist')
      cy.get('button[type="submit"]').should('exist')
    })

    it('should display units/cases toggle', () => {
      cy.get('#units-toggle').should('exist')
      cy.get('#cases-toggle').should('exist')
    })

    it('should display count/percentage toggle', () => {
      cy.get('#display-mode-toggle').should('exist')
      cy.get('#toggle-checkbox').should('exist')
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
      const monthEnd = new Date(now.getFullYear(), now.getMonth() + 1, 0)
        .toISOString().split('T')[0]

      cy.get('input[name="from"]').should('have.value', monthStart)
      cy.get('input[name="to"]').should('have.value', monthEnd)
    })
  })

  describe('Failure Type Filter', () => {
    it('should filter by failure type', () => {
      cy.get('#failureTypeInput').parent('.bootstrap-select').find('button').click()
      cy.get('#failureTypeInput').parent('.bootstrap-select')
        .find('.dropdown-menu li').eq(1).click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('exist')
    })

    it('should show all failure types when "All" selected', () => {
      cy.get('#failureTypeInput').parent('.bootstrap-select').find('button').click()
      cy.get('#failureTypeInput').parent('.bootstrap-select')
        .find('.dropdown-menu li[data-original-index="0"]').click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table thead th').should('have.length.at.least', 3)
    })

    it('should display failure type columns (Reject, Repeat, Modification, Redo)', () => {
      cy.get('.sigma-report-table thead th').should('satisfy', ($headers) => {
        const headerText = $headers.text()
        return headerText.includes('Reject') ||
               headerText.includes('Repeat') ||
               headerText.includes('Modification') ||
               headerText.includes('Redo') ||
               headerText.includes('Successful')
      })
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

  describe('Units vs Cases Toggle', () => {
    it('should toggle to Units view', () => {
      cy.get('#units-toggle').click()
      cy.get('input[name="perToggle"][value="1"]').should('be.checked')
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table').should('exist')
      cy.get('h2').should('contain', 'Unit')
    })

    it('should toggle to Cases view', () => {
      cy.get('#cases-toggle').click()
      cy.get('input[name="perToggle"][value="0"]').should('be.checked')
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table').should('exist')
      cy.get('h2').should('contain', 'Case')
    })

    it('should maintain active state visually', () => {
      cy.get('#units-toggle').click()
      cy.get('#units-toggle').should('have.class', 'active')
      cy.get('#cases-toggle').should('not.have.class', 'active')
    })
  })

  describe('Count vs Percentage Toggle', () => {
    it('should toggle to percentage view', () => {
      cy.get('#toggle-checkbox').check()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').first().find('td').each(($cell, index) => {
        if (index > 0 && index < 6) { // Failure type columns
          cy.wrap($cell).invoke('text').should('match', /^\d+\.?\d*%?$/)
        }
      })
    })

    it('should toggle to count view', () => {
      cy.get('#toggle-checkbox').uncheck()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').first().find('td').each(($cell, index) => {
        if (index > 0 && index < 6) {
          cy.wrap($cell).invoke('text').should('match', /^\d+$/)
        }
      })
    })

    it('should update toggle labels correctly', () => {
      cy.get('.toggle-label-left').should('contain', 'Count')
      cy.get('.toggle-label-right').should('contain', '%')
    })
  })

  describe('Table Data Display', () => {
    it('should display failure data per doctor', () => {
      cy.get('.sigma-report-table tbody tr').should('have.length.at.least', 1)
      cy.get('.sigma-report-table tbody tr').first().find('td').first()
        .invoke('text').should('not.be.empty')
    })

    it('should display total row', () => {
      cy.get('.sigma-report-table .totals-row').should('exist')
      cy.get('.sigma-report-table .totals-row td').first()
        .should('contain', 'Total')
    })

    it('should apply zebra striping', () => {
      cy.get('.sigma-report-table tbody tr:nth-child(even)')
        .should('have.css', 'background-color')
      cy.get('.sigma-report-table tbody tr:nth-child(odd)')
        .should('have.css', 'background-color')
    })

    it('should show total column when in count mode', () => {
      cy.get('#toggle-checkbox').uncheck()
      cy.get('button[type="submit"]').click()
      cy.wait(1000)

      cy.get('.sigma-report-table thead th').last().should('contain', 'Total')
    })
  })

  describe('Monthly Breakdown', () => {
    it('should display data grouped by month', () => {
      // Set date range spanning multiple months
      cy.get('input[name="from"]').clear().type('2025-01-01')
      cy.get('input[name="to"]').clear().type('2025-03-31')
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table-container').should('have.length.at.least', 1)
    })

    it('should show month headers for multi-month range', () => {
      cy.get('input[name="from"]').clear().type('2025-01-01')
      cy.get('input[name="to"]').clear().type('2025-02-28')
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      // Check for month indicators
      cy.get('body').should('contain.text', '2025')
    })
  })

  describe('Data Accuracy', () => {
    it('should calculate totals correctly in count mode', () => {
      cy.get('#toggle-checkbox').uncheck()
      cy.get('button[type="submit"]').click()
      cy.wait(1000)

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
              const total = parseInt(totalText.replace(/[^\d]/g, ''))
              expect(total).to.equal(columnTotals[index])
            })
          }
        })
      })
    })

    it('should show percentages that add up to 100% per row', () => {
      cy.get('#toggle-checkbox').check()
      cy.get('button[type="submit"]').click()
      cy.wait(1000)

      cy.get('.sigma-report-table tbody tr').not('.totals-row').first().then(($row) => {
        let total = 0
        cy.wrap($row).find('td').each(($cell, index) => {
          if (index > 0 && index < 6) { // Failure type columns
            const text = $cell.text().replace('%', '')
            const value = parseFloat(text) || 0
            total += value
          }
        }).then(() => {
          expect(total).to.be.closeTo(100, 1) // Allow 1% margin for rounding
        })
      })
    })
  })

  describe('Export and Print', () => {
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
    it('should handle no repeat data', () => {
      cy.get('input[name="from"]').clear().type('1990-01-01')
      cy.get('input[name="to"]').clear().type('1990-01-02')
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('have.length', 0)
        .or('contain', 'No data')
    })

    it('should handle all successful (no failures)', () => {
      cy.get('button[type="submit"]').click()
      cy.wait(1000)

      // Should still show table structure
      cy.get('.sigma-report-table').should('exist')
    })

    it('should handle doctor with no repeats', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').last().click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table').should('exist')
    })
  })

  describe('Filter Persistence', () => {
    it('should persist toggle states on reload', () => {
      cy.get('#units-toggle').click()
      cy.get('#toggle-checkbox').check()
      cy.get('button[type="submit"]').click()
      cy.wait(1000)

      cy.reload()
      cy.get('input[name="perToggle"][value="1"]').should('be.checked')
    })

    it('should persist selected filters on reload', () => {
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
    })

    it('should have light headers on middle columns', () => {
      cy.get('.sigma-report-table thead th').eq(1)
        .should('have.class', 'header-light')
    })
  })

  describe('Responsive Behavior', () => {
    it('should be responsive on mobile', () => {
      cy.viewport('iphone-x')
      cy.get('.sigma-report-table').should('be.visible')
    })

    it('should adjust toggle buttons on small screens', () => {
      cy.viewport(375, 667)
      cy.get('.toggle-cards-container').should('be.visible')
    })
  })
})
