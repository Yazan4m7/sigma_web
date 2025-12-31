// Ignore app-level uncaught errors during navigation
Cypress.on('uncaught:exception', () => false);

const baseRange = 'from=2025-10-01&to=2025-10-31&generate_report=1';

const assertCaseIds = (expected) => {
  cy.get('#master-report-table tbody tr td:first-child')
    .then(($cells) => {
      const ids = [...$cells].map((c) => parseInt(c.innerText.trim(), 10)).filter((n) => !Number.isNaN(n));
      expect(ids.sort((a, b) => a - b)).to.deep.equal(expected);
    });
};

describe('Master Report - Filter coverage', () => {
  beforeEach(() => {
    cy.visit('/reports/master?from=2025-09-01&to=2025-10-31&generate_report=1');
  });

  it('filters by doctor', () => {
    cy.visit(`/reports/master?${baseRange}&doctor%5B%5D=101`);
    assertCaseIds([9001]);
  });

  it('filters by material', () => {
    cy.visit(`/reports/master?${baseRange}&material%5B%5D=302`);
    assertCaseIds([9002]);
  });

  it('filters by job type', () => {
    cy.visit(`/reports/master?${baseRange}&job_type%5B%5D=203`);
    assertCaseIds([9003]);
  });

  it('filters by failure cause', () => {
    cy.visit(`/reports/master?${baseRange}&failure_type%5B%5D=701`);
    assertCaseIds([9001]);
  });

  it('filters by abutment and implant', () => {
    cy.visit(`/reports/master?${baseRange}&abutments%5B%5D=501`);
    assertCaseIds([9001]);
    cy.visit(`/reports/master?${baseRange}&implants%5B%5D=602`);
    assertCaseIds([9003]);
  });

  it('filters by workflow stage and completion toggle', () => {
    cy.visit(`/reports/master?${baseRange}&status%5B%5D=3&show_completed=in_progress`);
    assertCaseIds([9002]);

    cy.visit(`/reports/master?${baseRange}&show_completed=completed`);
    assertCaseIds([9001]);

    cy.visit(`/reports/master?${baseRange}&show_completed=in_progress`);
    assertCaseIds([9002, 9003]);
  });

  it('filters by amount ranges', () => {
    cy.visit(`/reports/master?${baseRange}&amount_from=100&amount_to=200`);
    assertCaseIds([9001]);

    cy.visit(`/reports/master?${baseRange}&amount_from=600`);
    assertCaseIds([9002]);

    cy.visit(`/reports/master?${baseRange}&amount_from=300&amount_to=500`);
    assertCaseIds([9003]);
  });

  it('filters by units ranges', () => {
    cy.visit(`/reports/master?${baseRange}&units_from=2&units_to=2`);
    assertCaseIds([9001]);

    cy.visit(`/reports/master?${baseRange}&units_from=3&units_to=3`);
    assertCaseIds([9002]);

    cy.visit(`/reports/master?${baseRange}&units_from=4&units_to=10`);
    assertCaseIds([9003]);
  });

  it('filters by material type', () => {
    cy.visit(`/reports/master?${baseRange}&material_type%5B%5D=401`);
    assertCaseIds([9001]);
  });

  it('filters by device (printing device 802)', () => {
    const url = `/reports/master?${baseRange}`
      + '&device_filters[0][type]=print&device_filters[0][device]=802';
    cy.visit(url);
    assertCaseIds([9002]);
  });

  it('filters by employee at specific stage (printing user 1)', () => {
    const url = `/reports/master?${baseRange}`
      + '&employee_filters[0][stage]=printing&employee_filters[0][employee]=1';
    cy.visit(url);
    assertCaseIds([9002]);
  });

  it('filters by device + material + stage + units (pressing case 9003)', () => {
    const url = `/reports/master?${baseRange}`
      + '&device_filters[0][type]=press&device_filters[0][device]=803'
      + '&material%5B%5D=303&status%5B%5D=5&units_from=4&units_to=10';
    cy.visit(url);
    assertCaseIds([9003]);
  });

  it('filters by employee + device + material (completed mill case 9001)', () => {
    const url = `/reports/master?${baseRange}`
      + '&employee_filters[0][stage]=milling&employee_filters[0][employee]=1'
      + '&device_filters[0][type]=mill&device_filters[0][device]=801'
      + '&material%5B%5D=301';
    cy.visit(url);
    assertCaseIds([9001]);
  });

  it('returns empty when date range misses all cases', () => {
    cy.visit('/reports/master?from=2024-01-01&to=2024-01-31&generate_report=1');
    cy.get('#master-report-table tbody tr').should('have.length', 0);
  });
});
