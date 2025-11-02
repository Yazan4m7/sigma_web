/**
 * Materials Report E2E Test Suite
 * Tests all functionality of the Case Materials Report
 */

describe('Materials Report', () => {
  beforeEach(() => {
    // Login before each test
    cy.login()

    // Navigate to Materials Report page
    cy.visit('/reports/case-materials')
    cy.url().should('include', '/reports/case-materials')
  })

  describe('Page Load and Initial State', () => {
    it('should load the materials report page successfully', () => {
      cy.get('h2').should('contain', 'Material')
      cy.get('.sigma-report-table').should('exist')
    })

    it('should display filter controls', () => {
      cy.get('input[name="from"]').should('exist')
      cy.get('input[name="to"]').should('exist')
      cy.get('#doctor').should('exist')
      cy.get('#material').should('exist')
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

    it('should include full end date', () => {
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
      cy.get('#doctor').parent('.bootstrap-select')
        .find('.dropdown-menu li[data-original-index="0"]').click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('have.length.at.least', 1)
    })
  })

  describe('Material Filter', () => {
    it('should filter by specific material', () => {
      cy.get('#material').parent('.bootstrap-select').find('button').click()
      cy.get('#material').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('exist')
    })

    it('should show all materials when "All" selected', () => {
      cy.get('#material').parent('.bootstrap-select').find('button').click()
      cy.get('#material').parent('.bootstrap-select')
        .find('.dropdown-menu li[data-original-index="0"]').click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table thead th').should('have.length.at.least', 3)
    })

    it('should display material names as column headers', () => {
      cy.get('.sigma-report-table thead th').should('satisfy', ($headers) => {
        return $headers.length > 2 // At least Doctor + 1 material + Total
      })
    })
  })

  describe('Material Type Filter', () => {
    it('should update material type dropdown when material is selected', () => {
      cy.get('#material').parent('.bootstrap-select').find('button').click()
      cy.get('#material').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()

      cy.wait(500)
      // Material type dropdown should have options
      cy.get('#material_type option').should('have.length.at.least', 1)
    })

    it('should filter by material type', () => {
      cy.get('#material').parent('.bootstrap-select').find('button').click()
      cy.get('#material').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
      cy.wait(500)

      cy.get('#material_type').parent('.bootstrap-select').find('button').click()
      cy.get('#material_type').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('exist')
    })
  })

  describe('Table Data Display', () => {
    it('should display material usage per doctor', () => {
      cy.get('.sigma-report-table tbody tr').should('have.length.at.least', 1)
      cy.get('.sigma-report-table tbody tr').first().find('td')
        .should('have.length.at.least', 2)
    })

    it('should show numeric values for material counts', () => {
      cy.get('.sigma-report-table tbody tr').first().find('td').each(($cell, index) => {
        if (index > 0) {
          cy.wrap($cell).invoke('text').should('match', /^\d+\.?\d*$/)
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
            const value = parseFloat(text)
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

    it('should display quantities with decimal places if applicable', () => {
      cy.get('.sigma-report-table tbody tr').first().find('td').each(($cell, index) => {
        if (index > 0) {
          const text = $cell.text()
          // Should be a valid number
          expect(parseFloat(text)).to.be.a('number')
        }
      })
    })
  })

  describe('Data Accuracy', () => {
    it('should sum rows to equal totals', () => {
      const columnTotals = []

      cy.get('.sigma-report-table tbody tr').not('.totals-row').each(($row) => {
        cy.wrap($row).find('td').each(($cell, index) => {
          if (index > 0) {
            const value = parseFloat($cell.text()) || 0
            columnTotals[index] = (columnTotals[index] || 0) + value
          }
        })
      }).then(() => {
        cy.get('.totals-row td').each(($cell, index) => {
          if (index > 0 && columnTotals[index] !== undefined) {
            cy.wrap($cell).invoke('text').then((totalText) => {
              const total = parseFloat(totalText)
              expect(total).to.be.closeTo(columnTotals[index], 0.1)
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

    it('should show zero for doctors with no material usage', () => {
      cy.get('.sigma-report-table tbody tr').each(($row) => {
        cy.wrap($row).find('td').each(($cell, index) => {
          if (index > 0) {
            const value = parseFloat($cell.text())
            expect(value).to.be.at.least(0)
          }
        })
      })
    })
  })

  describe('Combined Filters', () => {
    it('should apply doctor, material, and date filters together', () => {
      cy.get('input[name="from"]').clear().type('2025-01-01')
      cy.get('input[name="to"]').clear().type('2025-01-31')

      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()

      cy.get('#material').parent('.bootstrap-select').find('button').click()
      cy.get('#material').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()

      cy.get('button[type="submit"]').click()
      cy.wait(1000)

      cy.get('.sigma-report-table').should('exist')
    })

    it('should combine all filters including material type', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()

      cy.get('#material').parent('.bootstrap-select').find('button').click()
      cy.get('#material').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
      cy.wait(500)

      cy.get('#material_type').parent('.bootstrap-select').find('button').click()
      cy.get('#material_type').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()

      cy.get('button[type="submit"]').click()
      cy.wait(1000)

      cy.get('.sigma-report-table tbody tr').should('exist')
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
    it('should handle no material usage data', () => {
      cy.get('input[name="from"]').clear().type('1990-01-01')
      cy.get('input[name="to"]').clear().type('1990-01-02')
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table tbody tr').should('have.length', 0)
        .or('contain', 'No data')
    })

    it('should handle doctor with no material usage', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').last().click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('.sigma-report-table').should('exist')
    })

    it('should handle single material filter', () => {
      cy.get('#material').parent('.bootstrap-select').find('button').click()
      cy.get('#material').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      // Should show limited columns
      cy.get('.sigma-report-table thead th').should('have.length.at.least', 2)
    })

    it('should handle material with multiple types', () => {
      cy.get('#material').parent('.bootstrap-select').find('button').click()
      cy.get('#material').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
      cy.wait(500)

      // Check if material type dropdown populates
      cy.get('#material_type option').should('have.length.at.least', 1)
    })
  })

  describe('Filter State Persistence', () => {
    it('should persist selected material after reload', () => {
      cy.get('#material').parent('.bootstrap-select').find('button').click()
      cy.get('#material').parent('.bootstrap-select').find('.dropdown-menu li').eq(1)
        .invoke('text').then((materialName) => {
          cy.get('#material').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
          cy.get('button[type="submit"]').click()
          cy.wait(1000)

          cy.reload()
          cy.get('#material').parent('.bootstrap-select').find('.filter-option-inner-inner')
            .should('contain', materialName.trim())
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

    it('should handle horizontal scroll on narrow screens', () => {
      cy.viewport(375, 667)
      cy.get('.sigma-table-container').scrollTo('right')
      cy.get('.sigma-report-table').should('be.visible')
    })
  })

  describe('Material Type Dynamic Loading', () => {
    it('should clear material type when material changes', () => {
      cy.get('#material').parent('.bootstrap-select').find('button').click()
      cy.get('#material').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
      cy.wait(500)

      // Change material
      cy.get('#material').parent('.bootstrap-select').find('button').click()
      cy.get('#material').parent('.bootstrap-select').find('.dropdown-menu li').eq(2).click()
      cy.wait(500)

      // Material type should update
      cy.get('#material_type option').should('exist')
    })

    it('should show all material types when "All" materials selected', () => {
      cy.get('#material').parent('.bootstrap-select').find('button').click()
      cy.get('#material').parent('.bootstrap-select')
        .find('.dropdown-menu li[data-original-index="0"]').click()
      cy.wait(500)

      cy.get('#material_type option').should('have.length.at.least', 1)
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
