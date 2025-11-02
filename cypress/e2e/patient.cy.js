// cypress/e2e/patient.cy.js
describe('Patient Management', () => {
    beforeEach(() => {
        // Since you don't use seeding, just login
        cy.login()
    })

    it('creates a new patient', () => {
        cy.visit('/patients/create')

        // Fill form
        cy.get('#first_name').type('John')
        cy.get('#last_name').type('Doe')
        cy.get('#username').type('yazan')  // Fixed: added proper email
        cy.get('#phone').type('555-0123')  // Fixed: removed the 'S' at the end
        cy.get('#date_of_birth').type('1990-01-15')

        // The smart dropdown handling!
        cy.get('#insurance_provider').click()
        cy.contains('Delta Dental').click()

        cy.get('button[type="submit"]').click()

        // Verify
        cy.contains('Patient created successfully').should('be.visible')
    })
})
