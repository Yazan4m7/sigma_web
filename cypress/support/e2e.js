// cypress/support/e2e.js
// Import commands
import './commands'

// Laravel-specific commands
Cypress.Commands.add('login', (email = 'yazan', password = '1') => {
    cy.visit('/login')
    cy.get('input[name="username"]').type(email)
    cy.get('input[name="password"]').type(password)
    cy.get('button[type="submit"]').click()
    cy.url().should('not.include', '/login')
})

Cypress.Commands.add('logout', () => {
    cy.get('#logout-button').click() // Adjust selector to match your app
})


