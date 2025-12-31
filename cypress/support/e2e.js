// cypress/support/e2e.js
// Import commands
import './commands'

// Login no-op (auth disabled in routes); just ensure we're not on /login
Cypress.Commands.add('login', () => {
    cy.visit('/home')
    cy.url({ timeout: 20000 }).should('not.include', '/login')
})

Cypress.Commands.add('logout', () => {
    cy.get('#logout-button').click() // Adjust selector to match your app
})
