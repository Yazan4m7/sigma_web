# SIGMA Reports Comprehensive Testing Plan

**Generated:** 2025-10-20
**Purpose:** Test all 7 reports with edge case scenarios using direct URL access with query parameters

## Testing Methodology

- **Access Method:** Direct URL access with query parameters (simulating form submission)
- **Server:** Laravel development server (http://localhost:8000)
- **Validation:** HTTP 200 status, no PHP errors, data rendering, filter accuracy

## Edge Case Date Ranges

| Scenario | From Date | To Date | Purpose |
|----------|-----------|---------|---------|
| Year Boundary | 2024-12-15 | 2025-01-15 | Test month calculation across year boundary |
| Single Day | 2025-10-20 | 2025-10-20 | Test with single day range |
| Multi-Month | 2025-07-01 | 2025-10-20 | Test 4-month period aggregation |
| Current Month | 2025-10-01 | 2025-10-20 | Test current partial month |

---

## Report 1: Number of Units Report

**Route:** `/reports/num-of-units`
**Purpose:** Material-based units reporting

### Test URLs

#### Test 1.1: Current Month with Multiple Materials
```
http://localhost:8000/reports/num-of-units?from=2025-10-01&to=2025-10-20&material[]=2&material[]=3&material[]=4&doctor[]=all
```

#### Test 1.2: Year Boundary with Single Material
```
http://localhost:8000/reports/num-of-units?from=2024-12-15&to=2025-01-15&material[]=1&doctor[]=all
```

#### Test 1.3: Single Day with All Materials
```
http://localhost:8000/reports/num-of-units?from=2025-10-20&to=2025-10-20&material=all&doctor[]=all
```

#### Test 1.4: Multi-Month with Specific Client
```
http://localhost:8000/reports/num-of-units?from=2025-07-01&to=2025-10-20&material[]=2&material[]=3&doctor[]=1
```

### Expected Validations
- ✓ Monthly breakdown columns for each selected month
- ✓ Material totals per client
- ✓ Lab-level totals
- ✓ Correct unit counts (not case counts)

---

## Report 2: Implants Report

**Route:** `/reports/implants`
**Purpose:** Implants and abutments tracking

### Test URLs

#### Test 2.1: Current Month - Units Mode
```
http://localhost:8000/reports/implants?from=2025-10-01&to=2025-10-20&perToggle=1&implantsInput=all&abutmentsInput=all&doctor[]=all
```

#### Test 2.2: Year Boundary - Cases Mode
```
http://localhost:8000/reports/implants?from=2024-12-15&to=2025-01-15&perToggle=0&implantsInput=all&abutmentsInput=all&doctor[]=all
```

#### Test 2.3: Multi-Month - Specific Implants
```
http://localhost:8000/reports/implants?from=2025-07-01&to=2025-10-20&perToggle=1&implantsInput[]=1&implantsInput[]=2&abutmentsInput=all&doctor[]=all
```

#### Test 2.4: Single Day - Specific Abutments
```
http://localhost:8000/reports/implants?from=2025-10-20&to=2025-10-20&perToggle=0&implantsInput=all&abutmentsInput[]=1&abutmentsInput[]=2&doctor[]=all
```

### Expected Validations
- ✓ Toggle between units and cases works correctly
- ✓ Monthly columns for selected date range
- ✓ Abutment totals per client
- ✓ Implant filtering works

---

## Report 3: Job Types Report

**Route:** `/reports/job-types`
**Purpose:** Job type breakdown

### Test URLs

#### Test 3.1: Current Month - Units Mode
```
http://localhost:8000/reports/job-types?from=2025-10-01&to=2025-10-20&perToggle=1&jobTypesInput[]=1&jobTypesInput[]=2&jobTypesInput[]=3&jobTypesInput[]=4&doctor[]=all
```

#### Test 3.2: Year Boundary - Cases Mode
```
http://localhost:8000/reports/job-types?from=2024-12-15&to=2025-01-15&perToggle=0&jobTypesInput[]=1&jobTypesInput[]=2&doctor[]=all
```

#### Test 3.3: Single Day - All Job Types
```
http://localhost:8000/reports/job-types?from=2025-10-20&to=2025-10-20&perToggle=1&jobTypesInput=all&doctor[]=all
```

#### Test 3.4: Multi-Month - Specific Job Types
```
http://localhost:8000/reports/job-types?from=2025-07-01&to=2025-10-20&perToggle=0&jobTypesInput[]=1&jobTypesInput[]=3&doctor[]=1
```

### Expected Validations
- ✓ Job type columns render correctly
- ✓ Monthly breakdowns
- ✓ Client-level totals
- ✓ Lab-level totals

---

## Report 4: Repeats Report

**Route:** `/reports/repeats`
**Purpose:** Failure tracking (rejections, repeats, modifications, redos)

### Test URLs

#### Test 4.1: Current Month - Count Mode, All Failure Types
```
http://localhost:8000/reports/repeats?from=2025-10-01&to=2025-10-20&perToggle=0&countOrPercentageToggle=1&failureTypeInput=all&doctor[]=all
```

#### Test 4.2: Year Boundary - Percentage Mode, Specific Failures
```
http://localhost:8000/reports/repeats?from=2024-12-15&to=2025-01-15&perToggle=1&countOrPercentageToggle=0&failureTypeInput[]=0&failureTypeInput[]=1&doctor[]=all
```

#### Test 4.3: Multi-Month - Units + Count
```
http://localhost:8000/reports/repeats?from=2025-07-01&to=2025-10-20&perToggle=1&countOrPercentageToggle=1&failureTypeInput[]=2&failureTypeInput[]=3&doctor[]=all
```

#### Test 4.4: Single Day - Cases + Percentage
```
http://localhost:8000/reports/repeats?from=2025-10-20&to=2025-10-20&perToggle=0&countOrPercentageToggle=0&failureTypeInput=all&doctor[]=all
```

### Expected Validations
- ✓ Toggle between count/percentage works
- ✓ Toggle between cases/units works
- ✓ Failure type filters applied correctly
- ✓ Monthly breakdown displayed

---

## Report 5: QC Report

**Route:** `/reports/QC`
**Purpose:** Quality control failure tracking

### Test URLs

#### Test 5.1: Current Month - All Causes
```
http://localhost:8000/reports/QC?from=2025-10-01&to=2025-10-20&causesInput=all&failureTypeInput=all&doctor[]=all
```

#### Test 5.2: Year Boundary - Specific Causes
```
http://localhost:8000/reports/QC?from=2024-12-15&to=2025-01-15&causesInput[]=1&causesInput[]=2&failureTypeInput=all&doctor[]=all
```

#### Test 5.3: Multi-Month - Specific Failure Types
```
http://localhost:8000/reports/QC?from=2025-07-01&to=2025-10-20&causesInput=all&failureTypeInput[]=0&failureTypeInput[]=1&doctor[]=all
```

#### Test 5.4: Single Day - Combined Filters
```
http://localhost:8000/reports/QC?from=2025-10-20&to=2025-10-20&causesInput[]=1&failureTypeInput[]=0&doctor[]=1
```

### Expected Validations
- ✓ Failure logs displayed by month
- ✓ Amount of cases per month
- ✓ Amount of failed units total
- ✓ Cause filtering works

---

## Report 6: Material Report

**Route:** `/reports/material`
**Purpose:** Material usage by case

### Test URLs

#### Test 6.1: Current Month - All Clients
```
http://localhost:8000/reports/material?from=2025-10-01&to=2025-10-20&doctor=all
```

#### Test 6.2: Year Boundary - Specific Client
```
http://localhost:8000/reports/material?from=2024-12-15&to=2025-01-15&doctor[]=1
```

#### Test 6.3: Multi-Month - Multiple Clients
```
http://localhost:8000/reports/material?from=2025-07-01&to=2025-10-20&doctor[]=1&doctor[]=2&doctor[]=3
```

#### Test 6.4: Single Day with Patient Name Search
```
http://localhost:8000/reports/material?from=2025-10-20&to=2025-10-20&doctor=all&patient_name=ahmad
```

### Expected Validations
- ✓ Cases listed with materials
- ✓ Total amount calculated
- ✓ Date filtering works
- ✓ Client filtering works
- ✓ Patient name search works

---

## Report 7: Master Report

**Route:** `/reports/master`
**Purpose:** Comprehensive case reporting with all filters

### Test URLs

#### Test 7.1: Current Month - Basic Filters
```
http://localhost:8000/reports/master?from=2025-10-01&to=2025-10-20&doctor=all&material=all&job_type=all&show_completed=all
```

#### Test 7.2: Year Boundary - Material + Job Type Filter
```
http://localhost:8000/reports/master?from=2024-12-15&to=2025-01-15&doctor=all&material[]=1&material[]=2&job_type[]=1&show_completed=all
```

#### Test 7.3: Multi-Month - Completion Status Filter
```
http://localhost:8000/reports/master?from=2025-07-01&to=2025-10-20&doctor=all&material=all&job_type=all&show_completed=completed
```

#### Test 7.4: Single Day - In Progress Only
```
http://localhost:8000/reports/master?from=2025-10-20&to=2025-10-20&doctor=all&material=all&job_type=all&show_completed=in_progress
```

#### Test 7.5: Current Month - Workflow Stage Filter
```
http://localhost:8000/reports/master?from=2025-10-01&to=2025-10-20&doctor=all&material=all&job_type=all&status[]=2&status[]=3&show_completed=all
```

#### Test 7.6: Multi-Month - Amount Range Filter
```
http://localhost:8000/reports/master?from=2025-07-01&to=2025-10-20&doctor=all&material=all&job_type=all&amount_from=100&amount_to=500&show_completed=all
```

#### Test 7.7: Current Month - Units Range Filter
```
http://localhost:8000/reports/master?from=2025-10-01&to=2025-10-20&doctor=all&material=all&job_type=all&units_from=1&units_to=5&show_completed=all
```

#### Test 7.8: Year Boundary - Complex Multi-Filter
```
http://localhost:8000/reports/master?from=2024-12-15&to=2025-01-15&doctor[]=1&material[]=2&job_type[]=1&status[]=7&amount_from=50&show_completed=all
```

### Expected Validations
- ✓ All 22 table columns render correctly (Case ID, Doctor, Patient, Material, Job Type, Created Date, Delivery Date, 4 Device columns, 8 Employee columns, Status, Amount, Actions)
- ✓ Sticky columns work (Case ID, Doctor, Patient)
- ✓ Date filtering with conditional logic (actual_delivery_date for completed, initial_delivery_date for in-progress)
- ✓ Material filtering
- ✓ Job type filtering
- ✓ Completion status toggle (all/completed/in_progress)
- ✓ Workflow stage filtering
- ✓ Amount range validation
- ✓ Units range validation
- ✓ View case button works
- ✓ Column visibility dropdown works
- ✓ Export buttons (Excel, PDF, CSV) work
- ✓ DataTables pagination and sorting work

---

## Test Execution Checklist

For each test URL:

1. **HTTP Response**
   - [ ] Returns 200 OK status
   - [ ] No HTTP errors (404, 500, etc.)

2. **PHP Errors**
   - [ ] No PHP exceptions visible in HTML
   - [ ] No undefined variable warnings
   - [ ] No SQL errors

3. **Data Rendering**
   - [ ] Table renders with proper structure
   - [ ] Headers match expected columns
   - [ ] Data rows populated (or "No data" message if appropriate)
   - [ ] Totals/summaries calculated correctly

4. **Filter Accuracy**
   - [ ] Date range reflected correctly
   - [ ] Selected filters shown in UI
   - [ ] Data matches filter criteria
   - [ ] URL parameters preserved after render

5. **Visual Integrity**
   - [ ] CSS loaded properly
   - [ ] Table styling correct
   - [ ] No layout breaks
   - [ ] Responsive behavior (if applicable)

---

## Expected Database Schema Notes

### Key Tables
- `cases` (sCase model) - patient cases
- `jobs` - case units/jobs
- `clients` - dental clinics
- `materials` - dental materials
- `job_types` - job type definitions
- `implants` - implant types
- `abutments` - abutment types
- `failure_logs` - QC failure tracking
- `failure_causes` - failure cause definitions
- `devices` - manufacturing equipment
- `users` - employees
- `case_logs` - case history/stage tracking
- `invoices` - case invoicing
- `builds` (milling_builds, printing_builds, etc.) - manufacturing batches

### Key Relationships
- Case → Jobs (one-to-many)
- Job → Material (many-to-one)
- Job → JobType (many-to-one)
- Job → Builds (milling_build_id, printing_build_id, etc.)
- Build → Device (device_used relationship)
- Case → Client (doctor_id)
- Case → CaseLogs (tracking employees by stage)
- Case → Invoice (one-to-one)

---

## Post-Testing Actions

1. **Generate Test Results Summary**
   - Create markdown table with all test results
   - Mark PASS/FAIL for each scenario
   - Note any errors or issues found

2. **Bug Documentation**
   - Document any bugs discovered
   - Include test URL that triggered the bug
   - Provide error messages/screenshots if applicable

3. **Performance Notes**
   - Note any slow-loading reports
   - Identify queries that might need optimization
   - Check for N+1 query issues

---

## Testing Tips

1. **Authentication:** Ensure you're logged in as admin/reports user before testing
2. **Cache Clearing:** Run `php artisan cache:clear` and `php artisan view:clear` between tests if needed
3. **Browser Console:** Check browser console for JavaScript errors
4. **Network Tab:** Monitor network requests to see actual queries
5. **Laravel Log:** Monitor `storage/logs/laravel.log` for PHP errors

---

**End of Testing Plan**
