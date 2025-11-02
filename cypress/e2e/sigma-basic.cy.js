// cypress/e2e/sigma-basic.cy.js
describe('SIGMA Basic Tests', () => {
    it('loads the login page', () => {
        cy.visit('/login')
        cy.contains('SIGMA').should('be.visible') // Or whatever text is on your login
    })

    it('has login form fields', () => {
        cy.visit('/login')
        cy.get('input[name="username"]').should('be.visible')
        cy.get('input[name="password"]').should('be.visible')
        cy.get('button[type="submit"]').should('be.visible')
    })
})
