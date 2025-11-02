# SIGMA Reports E2E Test Suite

Comprehensive end-to-end test suite for all SIGMA dental laboratory management reports using Cypress.

## Reports Covered

1. **Master Report** (`master-report.cy.js`)
2. **Number of Units** (`number-of-units.cy.js`)
3. **Job Types** (`job-types.cy.js`)
4. **QC Report** (`qc.cy.js`)
5. **Repeats Report** (`repeats.cy.js`)
6. **Implants Report** (`implants.cy.js`)
7. **Materials Report** (`materials.cy.js`)

## Test Structure

Each report test file follows this structure:

```javascript
describe('Report Name', () => {
  beforeEach(() => {
    // Login and navigate to report
  })

  describe('Page Load and Initial State', () => {
    // Basic page loading tests
  })

  describe('Filters', () => {
    // Filter functionality tests
  })

  describe('Table Data Display', () => {
    // Data accuracy and display tests
  })

  describe('Export and Print', () => {
    // Export functionality tests
  })

  describe('Edge Cases', () => {
    // Error handling and edge case tests
  })

  describe('Responsive Behavior', () => {
    // Mobile and tablet testing
  })
})
```

## Running Tests

### Run All Reports Tests

```bash
npx cypress run --spec "cypress/e2e/reports/**/*.cy.js"
```

### Run Specific Report

```bash
npx cypress run --spec "cypress/e2e/reports/master-report.cy.js"
```

### Open Cypress Test Runner

```bash
npx cypress open
```

Then navigate to `e2e/reports` folder and select the test file.

### Run in Headless Mode

```bash
npx cypress run --spec "cypress/e2e/reports/master-report.cy.js" --headless
```

### Run on Specific Browser

```bash
npx cypress run --spec "cypress/e2e/reports/**/*.cy.js" --browser chrome
```

## Custom Commands

The test suite includes custom commands defined in `cypress/support/commands.js`:

### Authentication
- `cy.login(email, password)` - Login with session caching
- `cy.visitReport(reportName)` - Navigate to specific report

### Form Helpers
- `cy.selectBootstrapOption(selector, index)` - Select dropdown option by index
- `cy.selectBootstrapOptionByText(selector, text)` - Select dropdown option by text
- `cy.setDateRange(fromDate, toDate)` - Set date range filters
- `cy.submitReportFilters()` - Submit report form

### Table Validation
- `cy.tableHasData(selector)` - Verify table has rows
- `cy.tableIsEmpty(selector)` - Verify table is empty
- `cy.verifyZebraStriping(selector)` - Check zebra striping
- `cy.verifyTotalsRow(selector)` - Verify totals row exists
- `cy.verifyColumnTotals(selector)` - Calculate and verify column sums
- `cy.verifyHeaderStyling(selector)` - Check header color styling

### Export & Print
- `cy.triggerPrint()` - Test print functionality
- `cy.checkExportButtons()` - Verify Excel, PDF, CSV buttons exist

### Utilities
- `cy.waitForDataTable(selector)` - Wait for DataTables initialization
- `cy.setCurrentMonthRange()` - Set current month date range
- `cy.checkResponsive(viewport)` - Test responsive behavior

## Test Coverage

Each report test file covers:

### ✅ Page Load
- Page loads successfully
- All filter controls present
- Table structure correct
- Headers displayed properly

### ✅ Filters
- Date range filtering
- Doctor/client selection
- Material/job type/implant filters
- Multi-select dropdowns
- Filter combinations
- Filter state persistence

### ✅ Data Display
- Table renders correctly
- Data accuracy
- Zebra striping
- Totals row calculation
- Header styling (dark first/last, light middle)

### ✅ Export & Print
- Print button functionality
- Excel export (Master Report only)
- PDF export (Master Report only)
- CSV export (Master Report only)

### ✅ Edge Cases
- No data scenarios
- Invalid date ranges
- Empty filters
- Single vs multiple selections
- Data consistency across reloads

### ✅ Responsive
- Mobile viewport (iPhone X)
- Tablet viewport (iPad)
- Horizontal scrolling
- Touch interactions

## Prerequisites

1. **Cypress Installation**
```bash
npm install cypress --save-dev
```

2. **Test Database**
Ensure test database has sample data:
- Multiple doctors/clients
- Cases with various statuses
- Jobs with materials, implants, job types
- QC records with different statuses
- Repeat/failure records

3. **Environment Configuration**
Update `cypress.config.js`:

```javascript
module.exports = {
  e2e: {
    baseUrl: 'http://localhost:8000',
    setupNodeEvents(on, config) {},
  },
}
```

4. **Authentication**
Update default credentials in `cypress/support/commands.js` (line 11):
```javascript
Cypress.Commands.add('login', (username = 'yazan', password = '1') => {
  // ... login logic
})
```

Or pass credentials when calling:
```javascript
cy.login('your-username', 'your-password')
```

## Best Practices Implemented

1. ✅ **Session Management** - Uses `cy.session()` for efficient login caching
2. ✅ **Independent Tests** - Each test can run standalone
3. ✅ **beforeEach Setup** - Consistent test initialization
4. ✅ **Descriptive Names** - Clear test descriptions
5. ✅ **Wait Strategies** - Proper waits for async operations
6. ✅ **Clean Selectors** - ID and class selectors, avoiding fragile XPath
7. ✅ **Error Handling** - Graceful handling of known exceptions
8. ✅ **Assertions** - Meaningful assertions with clear expectations
9. ✅ **DRY Principle** - Reusable custom commands
10. ✅ **Data-Driven** - Tests work with real data

## Troubleshooting

### Tests Failing Due to Timing
Increase wait times in custom commands:
```javascript
cy.wait(2000) // Increase from 1000ms
```

### Bootstrap-Select Not Working
Ensure bootstrap-select is initialized before interaction:
```javascript
cy.wait(500) // Wait for initialization
cy.selectBootstrapOption('#doctor', 1)
```

### DataTables Not Loading
Use the custom command:
```javascript
cy.waitForDataTable('#master-report-table')
```

### Session Issues
Clear sessions between test runs:
```javascript
Cypress.session.clearAllSavedSessions()
```

## CI/CD Integration

### GitHub Actions Example

```yaml
name: Cypress Tests

on: [push, pull_request]

jobs:
  cypress-run:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - uses: cypress-io/github-action@v5
        with:
          spec: cypress/e2e/reports/**/*.cy.js
          browser: chrome
```

### GitLab CI Example

```yaml
cypress:
  image: cypress/browsers:latest
  script:
    - npm ci
    - npx cypress run --spec "cypress/e2e/reports/**/*.cy.js"
```

## Maintenance

When adding new filters or features to reports:

1. Update corresponding test file
2. Add new test cases in appropriate `describe` block
3. Update custom commands if needed
4. Run full test suite to ensure no regressions
5. Update this README if new patterns emerge

## Test Data Requirements

For comprehensive testing, ensure your test database includes:

- **Doctors/Clients**: At least 5 different doctors
- **Cases**: Mix of completed and in-progress (20+ cases)
- **Date Range**: Cases spanning multiple months
- **Materials**: At least 5 different materials with types
- **Job Types**: Crown, Bridge, Veneer, Inlay, etc.
- **Implants**: Multiple implant brands/types
- **Devices**: Mills, 3D Printers, Furnaces
- **Employees**: Users assigned to different stages
- **QC Records**: Mix of pass/fail statuses
- **Repeats**: Various failure types (Reject, Repeat, Modification, Redo)

## Support

For issues or questions:
1. Check test output for specific failure details
2. Review Cypress documentation: https://docs.cypress.io
3. Check SIGMA application logs
4. Verify test database state
