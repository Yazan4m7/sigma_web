// cypress/e2e/appointments.cy.js
describe('Appointment Scheduling', () => {
    beforeEach(() => {
        // Just login, no database seeding
        cy.login('dentist@sigma.com', 'password')
    })

    it('schedules an appointment', () => {
        cy.visit('/appointments/create')

        // Patient selection - handles async loading!
        cy.get('#patient_search').type('Smith')
        cy.get('.patient-dropdown').should('be.visible')
        cy.contains('.patient-option', 'John Smith').click()

        // Date and time
        cy.get('#appointment_date').type('2024-12-25')
        cy.get('#time_slot').select('10:00 AM')

        // Treatment
        cy.get('#treatment_type').select('Cleaning')

        cy.get('#save-appointment').click()

        cy.contains('Appointment scheduled').should('be.visible')
    })
})
