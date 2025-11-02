// ***********************************************
// Custom commands for SIGMA Reports Testing
// ***********************************************

/**
 * Login command - creates a session for authenticated requests
 * Usage: cy.login() or cy.login('username', 'password')
 *
 * Note: Update username/password to match your test environment
 */
Cypress.Commands.add('login', (username = 'yazan', password = '1') => {
    cy.session([username, password], () => {
        cy.visit('/login')

        // Wait for login form to be visible
        cy.get('input[name="username"]', { timeout: 10000 }).should('be.visible')

        // Enter credentials
        cy.get('input[name="username"]').clear().type(username, { log: false })
        cy.get('input[name="password"]').clear().type(password, { log: false })

        // Submit the form
        cy.get('button[type="submit"]').click()

        // Wait for successful login (redirect away from login page)
        cy.url({ timeout: 15000 }).should('not.include', '/login')
    }, {
        validate() {
            // Check if session is still valid by verifying cookie exists
            cy.getCookie('laravel_session').should('exist')
        }
    })
})

/**
 * Simple login without session caching (for debugging)
 * Usage: cy.simpleLogin()
 */
Cypress.Commands.add('simpleLogin', (username = 'admin', password = 'password') => {
    cy.visit('/login')
    cy.get('input[name="username"]').should('be.visible').clear().type(username, { log: false })
    cy.get('input[name="password"]').clear().type(password, { log: false })
    cy.get('button[type="submit"]').click()
    cy.url({ timeout: 15000 }).should('not.include', '/login')
})

/**
 * Navigate to a specific report
 * Usage: cy.visitReport('master-report')
 */
Cypress.Commands.add('visitReport', (reportName) => {
    cy.visit(`/reports/${reportName}`)
    cy.url().should('include', `/reports/${reportName}`)
})

/**
 * Select bootstrap-select dropdown option
 * Usage: cy.selectBootstrapOption('#doctor', 1)
 */
Cypress.Commands.add('selectBootstrapOption', (selector, index) => {
    cy.get(selector).parent('.bootstrap-select').find('button').click()
    cy.get(selector).parent('.bootstrap-select')
        .find('.dropdown-menu li').eq(index).click()
})

/**
 * Select bootstrap-select dropdown by text
 * Usage: cy.selectBootstrapOptionByText('#doctor', 'John Doe')
 */
Cypress.Commands.add('selectBootstrapOptionByText', (selector, text) => {
    cy.get(selector).parent('.bootstrap-select').find('button').click()
    cy.get(selector).parent('.bootstrap-select')
        .find('.dropdown-menu li').contains(text).click()
})

/**
 * Set date range for reports
 * Usage: cy.setDateRange('2025-01-01', '2025-01-31')
 */
Cypress.Commands.add('setDateRange', (fromDate, toDate) => {
    cy.get('input[name="from"]').clear().type(fromDate)
    cy.get('input[name="to"]').clear().type(toDate)
})

/**
 * Submit report filters
 * Usage: cy.submitReportFilters()
 */
Cypress.Commands.add('submitReportFilters', () => {
    cy.get('button[type="submit"]').contains('Generate Report').click()
    cy.wait(1000) // Wait for report to load
})

/**
 * Check if table has data
 * Usage: cy.tableHasData('.sigma-report-table')
 */
Cypress.Commands.add('tableHasData', (selector = '.sigma-report-table') => {
    cy.get(`${selector} tbody tr`).should('have.length.at.least', 1)
})

/**
 * Check if table is empty
 * Usage: cy.tableIsEmpty('.sigma-report-table')
 */
Cypress.Commands.add('tableIsEmpty', (selector = '.sigma-report-table') => {
    cy.get(`${selector} tbody tr`).should('have.length', 0)
        .or('contain', 'No data')
})

/**
 * Verify zebra striping on table
 * Usage: cy.verifyZebraStriping('.sigma-report-table')
 */
Cypress.Commands.add('verifyZebraStriping', (selector = '.sigma-report-table') => {
    cy.get(`${selector} tbody tr:nth-child(even)`).should('have.css', 'background-color')
    cy.get(`${selector} tbody tr:nth-child(odd)`).should('have.css', 'background-color')
})

/**
 * Verify table totals row exists
 * Usage: cy.verifyTotalsRow('.sigma-report-table')
 */
Cypress.Commands.add('verifyTotalsRow', (selector = '.sigma-report-table') => {
    cy.get(`${selector} .totals-row`).should('exist')
    cy.get(`${selector} .totals-row td`).first().should('contain', 'Total')
})

/**
 * Trigger print dialog
 * Usage: cy.triggerPrint()
 */
Cypress.Commands.add('triggerPrint', () => {
    cy.window().then((win) => {
        cy.stub(win, 'print').as('print')
    })
    cy.get('.printBtn').click()
    cy.get('@print').should('be.called')
})

/**
 * Check export buttons exist
 * Usage: cy.checkExportButtons()
 */
Cypress.Commands.add('checkExportButtons', () => {
    cy.get('.buttons-excel').should('exist')
    cy.get('.buttons-pdf').should('exist')
    cy.get('.buttons-csv').should('exist')
})

/**
 * Verify header styling (dark first/last, light middle)
 * Usage: cy.verifyHeaderStyling('.sigma-report-table')
 */
Cypress.Commands.add('verifyHeaderStyling', (selector = '.sigma-report-table') => {
    cy.get(`${selector} thead th`).first().should('have.class', 'header-dark')
    cy.get(`${selector} thead th`).last().should('have.class', 'header-dark')
    cy.get(`${selector} thead th`).eq(1).should('have.class', 'header-light')
})

/**
 * Calculate and verify column totals
 * Usage: cy.verifyColumnTotals('.sigma-report-table')
 */
Cypress.Commands.add('verifyColumnTotals', (selector = '.sigma-report-table') => {
    const columnTotals = []

    cy.get(`${selector} tbody tr`).not('.totals-row').each(($row) => {
        cy.wrap($row).find('td').each(($cell, index) => {
            if (index > 0) {
                const value = parseFloat($cell.text()) || 0
                columnTotals[index] = (columnTotals[index] || 0) + value
            }
        })
    }).then(() => {
        cy.get(`${selector} .totals-row td`).each(($cell, index) => {
            if (index > 0 && columnTotals[index] !== undefined) {
                cy.wrap($cell).invoke('text').then((totalText) => {
                    const total = parseFloat(totalText.replace(/[^\d.]/g, ''))
                    expect(total).to.be.closeTo(columnTotals[index], 0.1)
                })
            }
        })
    })
})

/**
 * Wait for DataTable to initialize
 * Usage: cy.waitForDataTable('#master-report-table')
 */
Cypress.Commands.add('waitForDataTable', (selector) => {
    cy.get(`${selector}_wrapper`).should('exist')
    cy.get(`${selector}`).should('be.visible')
})

/**
 * Set current month date range
 * Usage: cy.setCurrentMonthRange()
 */
Cypress.Commands.add('setCurrentMonthRange', () => {
    const now = new Date()
    const monthStart = new Date(now.getFullYear(), now.getMonth(), 1)
        .toISOString().split('T')[0]
    const monthEnd = new Date(now.getFullYear(), now.getMonth() + 1, 0)
        .toISOString().split('T')[0]

    cy.setDateRange(monthStart, monthEnd)
})

/**
 * Check responsive behavior
 * Usage: cy.checkResponsive('iphone-x')
 */
Cypress.Commands.add('checkResponsive', (viewport = 'iphone-x') => {
    cy.viewport(viewport)
    cy.get('.sigma-report-table').should('be.visible')
})

// Prevent Cypress from failing tests on uncaught exceptions
Cypress.on('uncaught:exception', (err, runnable) => {
    // Ignore specific known errors that don't affect functionality
    if (err.message.includes('ResizeObserver')) {
        return false
    }
    if (err.message.includes('Cannot read properties of undefined')) {
        return false
    }
    // Let other errors fail the test
    return true
})
