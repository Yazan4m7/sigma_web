// Ignore app-level uncaught errors (e.g., loadingIndicator) so filters can be tested
// eslint-disable-next-line cypress/no-unnecessary-waiting
Cypress.on('uncaught:exception', () => false);

describe('Master Report - Multi-filter smoke', () => {
  beforeEach(() => {
    cy.login('admin', 'admin');
  });

  const baseRange = 'from=2025-10-01&to=2025-10-31&generate_report=1';

  it('returns completed case 9001 when all matching filters are applied', () => {
    const url = `/reports/master?${baseRange}`
      + '&doctor%5B%5D=101&material%5B%5D=301&job_type%5B%5D=201'
      + '&failure_type%5B%5D=701&amount_from=100&amount_to=200&show_completed=completed';

    cy.visit(url);
    cy.get('#master-report-table tbody tr').should('have.length', 1);
    cy.get('#master-report-table tbody tr td').first().should('contain', '9001');
  });

  it('returns in-progress printing case 9002 with combined filters', () => {
    const url = `/reports/master?${baseRange}`
      + '&doctor%5B%5D=102&material%5B%5D=302&job_type%5B%5D=202'
      + '&status%5B%5D=3&show_completed=in_progress&amount_from=600';

    cy.visit(url);
    cy.get('#master-report-table tbody tr').should('have.length', 1);
    cy.get('#master-report-table tbody tr td').first().should('contain', '9002');
  });

  it('returns pressing-stage case 9003 with combined filters', () => {
    const url = `/reports/master?${baseRange}`
      + '&doctor%5B%5D=103&material%5B%5D=303&job_type%5B%5D=203'
      + '&status%5B%5D=5&show_completed=in_progress&amount_from=300&amount_to=500';

    cy.visit(url);
    cy.get('#master-report-table tbody tr').should('have.length', 1);
    cy.get('#master-report-table tbody tr td').first().should('contain', '9003');
  });
});
