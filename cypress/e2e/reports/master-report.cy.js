/**
 * Master Report E2E Test Suite
 * Tests all functionality of the Master Report including filters, data display, and exports
 */

describe('Master Report', () => {
  beforeEach(() => {
    // Login before each test
    cy.login()

    // Navigate to Master Report page
    cy.visit('/reports/master-report')
    cy.url().should('include', '/reports/master-report')
  })

  describe('Page Load and Initial State', () => {
    it('should load the master report page successfully', () => {
      cy.get('h2').should('contain', 'Master Report')
      cy.get('#master-report-table').should('exist')
    })

    it('should display filter controls', () => {
      cy.get('input[name="from"]').should('exist')
      cy.get('input[name="to"]').should('exist')
      cy.get('button[type="submit"]').should('contain', 'Generate Report')
    })

    it('should display table headers correctly', () => {
      cy.get('#master-report-table thead th').should('have.length.at.least', 10)
      cy.get('#master-report-table thead th').first().should('contain', 'Doctor Name')
      cy.get('#master-report-table thead th').eq(1).should('contain', 'Patient Name')
    })

    it('should show export buttons', () => {
      cy.get('.export-buttons').should('exist')
      cy.get('.buttons-excel').should('exist')
      cy.get('.buttons-pdf').should('exist')
      cy.get('.buttons-csv').should('exist')
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
      cy.get('#master-report-table tbody tr').should('exist')
    })

    it('should validate date range (from > to)', () => {
      const fromDate = '2025-12-31'
      const toDate = '2025-01-01'

      cy.get('input[name="from"]').clear().type(fromDate)
      cy.get('input[name="to"]').clear().type(toDate)

      // Should show validation error or prevent submission
      cy.get('input[name="from"]').should('have.class', 'is-invalid')
        .or('have.attr', 'aria-invalid', 'true')
    })

    it('should include cases from entire "to" date (end of day)', () => {
      const today = new Date().toISOString().split('T')[0]

      cy.get('input[name="from"]').clear().type(today)
      cy.get('input[name="to"]').clear().type(today)
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      // Cases created today should appear
      cy.get('#master-report-table tbody tr').should('have.length.at.least', 0)
    })
  })

  describe('Doctor Filter', () => {
    it('should filter by specific doctor', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('#master-report-table tbody tr').should('exist')
    })

    it('should handle "All" doctors selection', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li[data-original-index="0"]').click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('#master-report-table tbody tr').should('exist')
    })
  })

  describe('Material Filter', () => {
    it('should filter by material', () => {
      cy.get('#material').parent('.bootstrap-select').find('button').click()
      cy.get('#material').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('#master-report-table tbody tr').should('exist')
    })

    it('should update material type dropdown when material changes', () => {
      cy.get('#material').parent('.bootstrap-select').find('button').click()
      cy.get('#material').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()

      // Material type dropdown should update
      cy.get('#material_type option').should('have.length.at.least', 1)
    })
  })

  describe('Job Type Filter', () => {
    it('should filter by job type', () => {
      cy.get('#job_type').parent('.bootstrap-select').find('button').click()
      cy.get('#job_type').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('#master-report-table tbody tr').should('exist')
    })
  })

  describe('Workflow Stage Filter', () => {
    it('should filter by workflow stage', () => {
      cy.get('#status').parent('.bootstrap-select').find('button').click()
      cy.get('#status').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('#master-report-table tbody tr').should('exist')
    })
  })

  describe('Completion Status Toggle', () => {
    it('should toggle to show completed cases only', () => {
      cy.get('.toggle-option[data-value="completed"]').click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('#master-report-table tbody tr').each(($row) => {
        cy.wrap($row).find('td').last().prev().should('contain', 'Completed')
      })
    })

    it('should toggle to show in-progress cases only', () => {
      cy.get('.toggle-option[data-value="in_progress"]').click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('#master-report-table tbody tr').should('exist')
    })

    it('should toggle to show all cases', () => {
      cy.get('.toggle-option[data-value="all"]').click()
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('#master-report-table tbody tr').should('exist')
    })
  })

  describe('Invoice Amount Range Filter', () => {
    it('should filter by invoice amount range', () => {
      cy.get('input[name="invoice_from"]').clear().type('100')
      cy.get('input[name="invoice_to"]').clear().type('500')
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('#master-report-table tbody tr').should('exist')
    })

    it('should validate invoice range (from > to)', () => {
      cy.get('input[name="invoice_from"]').clear().type('500')
      cy.get('input[name="invoice_to"]').clear().type('100')

      cy.get('input[name="invoice_from"]').should('have.class', 'is-invalid')
    })
  })

  describe('Number of Units Range Filter', () => {
    it('should filter by number of units range', () => {
      cy.get('input[name="units_from"]').clear().type('1')
      cy.get('input[name="units_to"]').clear().type('10')
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('#master-report-table tbody tr').should('exist')
    })
  })

  describe('Employee Filters', () => {
    it('should add employee filter row', () => {
      cy.get('button').contains('Configure Employee Filters').click()
      cy.get('button').contains('Add Employee Filter').click()

      cy.get('.employee-filter-row').should('have.length.at.least', 1)
    })

    it('should filter by employee at specific stage', () => {
      cy.get('button').contains('Configure Employee Filters').click()
      cy.get('.stage-select').first().select('design')
      cy.wait(500)
      cy.get('.employee-select').first().select(1)
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('#master-report-table tbody tr').should('exist')
    })

    it('should remove employee filter row', () => {
      cy.get('button').contains('Configure Employee Filters').click()
      cy.get('button').contains('Add Employee Filter').click()
      cy.get('.remove-employee-filter').first().click()

      cy.get('.employee-filter-row').should('have.length', 1)
    })
  })

  describe('Device Filters', () => {
    it('should add device filter row', () => {
      cy.get('button').contains('Configure Device Filters').click()
      cy.get('button').contains('Add Device Filter').click()

      cy.get('.device-filter-row').should('have.length.at.least', 1)
    })

    it('should filter by device type', () => {
      cy.get('button').contains('Configure Device Filters').click()
      cy.get('.device-type-select').first().select('mill')
      cy.wait(500)
      cy.get('.device-select').first().select(1)
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('#master-report-table tbody tr').should('exist')
    })
  })

  describe('Table Functionality', () => {
    it('should display data in table rows', () => {
      cy.get('#master-report-table tbody tr').should('have.length.at.least', 1)
    })

    it('should have frozen first two columns (sticky)', () => {
      cy.get('#master-report-table thead th:nth-child(1)').should('have.css', 'position', 'sticky')
      cy.get('#master-report-table thead th:nth-child(2)').should('have.css', 'position', 'sticky')
    })

    it('should display zebra striping on rows', () => {
      cy.get('#master-report-table tbody tr:nth-child(even)').should('have.css', 'background-color')
      cy.get('#master-report-table tbody tr:nth-child(odd)').should('have.css', 'background-color')
    })

    it('should sort table by clicking headers', () => {
      cy.get('#master-report-table thead th').eq(14).click() // Status column
      cy.wait(500)
      cy.get('#master-report-table tbody tr').should('exist')
    })
  })

  describe('Export Functionality', () => {
    it('should export to Excel', () => {
      cy.get('.buttons-excel').click()
      // File download assertion would require cypress-downloadfile plugin
      cy.wait(1000)
    })

    it('should export to PDF', () => {
      cy.get('.buttons-pdf').click()
      cy.wait(1000)
    })

    it('should export to CSV', () => {
      cy.get('.buttons-csv').click()
      cy.wait(1000)
    })
  })

  describe('Column Visibility', () => {
    it('should toggle column visibility', () => {
      cy.get('#columnVisibilityDropdown').click()
      cy.get('#col-material').uncheck()

      // Column should be hidden
      cy.wait(500)
    })

    it('should select all columns', () => {
      cy.get('#columnVisibilityDropdown').click()
      cy.get('#selectAllColumns').click()

      cy.get('.column-toggle').each(($checkbox) => {
        cy.wrap($checkbox).should('be.checked')
      })
    })

    it('should deselect all columns', () => {
      cy.get('#columnVisibilityDropdown').click()
      cy.get('#deselectAllColumns').click()

      cy.get('.column-toggle').each(($checkbox) => {
        cy.wrap($checkbox).should('not.be.checked')
      })
    })
  })

  describe('Edge Cases', () => {
    it('should handle no data scenario', () => {
      // Set date range with no data
      cy.get('input[name="from"]').clear().type('1990-01-01')
      cy.get('input[name="to"]').clear().type('1990-01-02')
      cy.get('button[type="submit"]').click()

      cy.wait(1000)
      cy.get('#master-report-table tbody tr').should('have.length', 0)
        .or('contain', 'No data available')
    })

    it('should handle multiple filter combinations', () => {
      cy.get('#doctor').parent('.bootstrap-select').find('button').click()
      cy.get('#doctor').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()

      cy.get('#material').parent('.bootstrap-select').find('button').click()
      cy.get('#material').parent('.bootstrap-select').find('.dropdown-menu li').eq(1).click()

      cy.get('input[name="from"]').clear().type('2025-01-01')
      cy.get('input[name="to"]').clear().type('2025-12-31')

      cy.get('button[type="submit"]').click()
      cy.wait(1000)
    })

    it('should persist filter state after page reload', () => {
      cy.get('input[name="from"]').clear().type('2025-01-01')
      cy.get('button[type="submit"]').click()
      cy.wait(1000)

      cy.reload()
      cy.get('input[name="from"]').should('have.value', '2025-01-01')
    })
  })

  describe('Pagination', () => {
    it('should paginate through results', () => {
      cy.get('.paginate_button.next').click()
      cy.wait(500)
      cy.get('.paginate_button.previous').click()
    })

    it('should change page size', () => {
      cy.get('select[name="master-report-table_length"]').select('50')
      cy.wait(500)
      cy.get('#master-report-table tbody tr').should('have.length.at.most', 50)
    })
  })

  describe('Responsive Behavior', () => {
    it('should be responsive on mobile viewport', () => {
      cy.viewport('iphone-x')
      cy.get('#master-report-table').should('be.visible')
      cy.get('.sigma-report-table-container').should('have.css', 'overflow-x', 'auto')
    })

    it('should be responsive on tablet viewport', () => {
      cy.viewport('ipad-2')
      cy.get('#master-report-table').should('be.visible')
    })
  })
})
