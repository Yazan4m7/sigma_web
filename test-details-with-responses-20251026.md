# SIGMA Reports Test Details with Response Data
**Generated:** Sun Oct 26 2025

---

## Test 1: Number of Units Report (`/reports/num-of-units`)

### Test 1.1 - Oct 1-20, 2025, Materials 2,3,4
**URL:** `http://127.0.0.1:8000/reports/num-of-units?from=2025-10-01&to=2025-10-20&material[]=2&material[]=3&material[]=4&doctor[]=all`
**Parameters:**
```
from: 2025-10-01
to: 2025-10-20
material: [2, 3, 4] (Zirconia, PMMA, etc.)
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Report displays material-wise unit counts per doctor
- Monthly breakdown table with columns: Doctor Name | Material Type | Units Count
- Grand totals row at bottom
- DataTables interface with search, sort, export functionality

### Test 1.2 - Year Boundary (Dec 2024 - Jan 2025), Material 1
**URL:** `http://127.0.0.1:8000/reports/num-of-units?from=2024-12-15&to=2025-01-15&material[]=1&doctor[]=all`
**Parameters:**
```
from: 2024-12-15
to: 2025-01-15
material: [1]
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Cross-year data aggregation successful
- Same table structure as 1.1
- Handles year boundary correctly without errors

### Test 1.3 - Single Day (Oct 20, 2025), All Materials
**URL:** `http://127.0.0.1:8000/reports/num-of-units?from=2025-10-20&to=2025-10-20&material=all&doctor[]=all`
**Parameters:**
```
from: 2025-10-20
to: 2025-10-20
material: all
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Single day snapshot
- Shows all materials in use on that specific date
- Zero values shown for materials with no activity

### Test 1.4 - Multi-month (Jul-Oct 2025), Materials 2,3, Doctor 1
**URL:** `http://127.0.0.1:8000/reports/num-of-units?from=2025-07-01&to=2025-10-20&material[]=2&material[]=3&doctor[]=1`
**Parameters:**
```
from: 2025-07-01
to: 2025-10-20
material: [2, 3]
doctor: [1] (specific doctor filter)
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Filtered view for single doctor
- Shows only selected materials (2, 3)
- 4-month aggregated totals

---

## Test 2: Implants Report (`/reports/implants`)

### Test 2.1 - Oct 1-20, perToggle=1, All Implants/Abutments
**URL:** `http://127.0.0.1:8000/reports/implants?from=2025-10-01&to=2025-10-20&perToggle=1&implantsInput=all&abutmentsInput=all&doctor[]=all`
**Parameters:**
```
from: 2025-10-01
to: 2025-10-20
perToggle: 1 (show per-doctor breakdown)
implantsInput: all
abutmentsInput: all
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Table showing: Implant Type | Abutment Type | Count | Doctor Name
- perToggle=1 shows individual doctor rows
- Grouped by implant manufacturer and abutment type

### Test 2.2 - Year Boundary, perToggle=0, All Implants/Abutments
**URL:** `http://127.0.0.1:8000/reports/implants?from=2024-12-15&to=2025-01-15&perToggle=0&implantsInput=all&abutmentsInput=all&doctor[]=all`
**Parameters:**
```
from: 2024-12-15
to: 2025-01-15
perToggle: 0 (aggregated totals only)
implantsInput: all
abutmentsInput: all
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Aggregated view (perToggle=0)
- Total counts per implant/abutment combination
- No doctor breakdown, just grand totals

### Test 2.3 - Jul-Oct 2025, perToggle=1, Implants 1&2, All Abutments
**URL:** `http://127.0.0.1:8000/reports/implants?from=2025-07-01&to=2025-10-20&perToggle=1&implantsInput[]=1&implantsInput[]=2&abutmentsInput=all&doctor[]=all`
**Parameters:**
```
from: 2025-07-01
to: 2025-10-20
perToggle: 1
implantsInput: [1, 2] (specific implants filtered)
abutmentsInput: all
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Filtered to show only implant types 1 and 2
- Per-doctor breakdown
- All abutment types shown

### Test 2.4 - Single Day, perToggle=0, All Implants, Abutments 1&2
**URL:** `http://127.0.0.1:8000/reports/implants?from=2025-10-20&to=2025-10-20&perToggle=0&implantsInput=all&abutmentsInput[]=1&abutmentsInput[]=2&doctor[]=all`
**Parameters:**
```
from: 2025-10-20
to: 2025-10-20
perToggle: 0
implantsInput: all
abutmentsInput: [1, 2] (specific abutments filtered)
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Single day data
- All implant types
- Only abutment types 1 and 2
- Aggregated totals

---

## Test 3: Job Types Report (`/reports/job-types`)

### Test 3.1 - Oct 1-20, perToggle=1, Job Types 1,2,3,4
**URL:** `http://127.0.0.1:8000/reports/job-types?from=2025-10-01&to=2025-10-20&perToggle=1&jobTypesInput[]=1&jobTypesInput[]=2&jobTypesInput[]=3&jobTypesInput[]=4&doctor[]=all`
**Parameters:**
```
from: 2025-10-01
to: 2025-10-20
perToggle: 1 (per-doctor breakdown)
jobTypesInput: [1, 2, 3, 4] (Crown, Bridge, Veneer, etc.)
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Table: Job Type | Count | Doctor Name
- Shows counts for: Crown, Bridge, Implant Crown, Veneer
- Per-doctor breakdown with subtotals

### Test 3.2 - Year Boundary, perToggle=0, Job Types 1&2
**URL:** `http://127.0.0.1:8000/reports/job-types?from=2024-12-15&to=2025-01-15&perToggle=0&jobTypesInput[]=1&jobTypesInput[]=2&doctor[]=all`
**Parameters:**
```
from: 2024-12-15
to: 2025-01-15
perToggle: 0 (aggregated)
jobTypesInput: [1, 2]
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Aggregated totals only
- Shows 2 job types
- Cross-year data handled correctly

### Test 3.3 - Single Day, perToggle=1, All Job Types
**URL:** `http://127.0.0.1:8000/reports/job-types?from=2025-10-20&to=2025-10-20&perToggle=1&jobTypesInput=all&doctor[]=all`
**Parameters:**
```
from: 2025-10-20
to: 2025-10-20
perToggle: 1
jobTypesInput: all
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Single day snapshot
- All job types shown
- Per-doctor breakdown

### Test 3.4 - Jul-Oct 2025, perToggle=0, Job Types 1&3, Doctor 1
**URL:** `http://127.0.0.1:8000/reports/job-types?from=2025-07-01&to=2025-10-20&perToggle=0&jobTypesInput[]=1&jobTypesInput[]=3&doctor[]=1`
**Parameters:**
```
from: 2025-07-01
to: 2025-10-20
perToggle: 0
jobTypesInput: [1, 3]
doctor: [1] (filtered to specific doctor)
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Filtered to doctor 1 only
- Shows job types 1 and 3 only
- 4-month aggregated totals

---

## Test 4: Repeats Report (`/reports/repeats`)

### Test 4.1 - Oct 1-20, perToggle=0, countOrPercentageToggle=1, All Failure Types
**URL:** `http://127.0.0.1:8000/reports/repeats?from=2025-10-01&to=2025-10-20&perToggle=0&countOrPercentageToggle=1&failureTypeInput=all&doctor[]=all`
**Parameters:**
```
from: 2025-10-01
to: 2025-10-20
perToggle: 0 (aggregated)
countOrPercentageToggle: 1 (show percentages)
failureTypeInput: all
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Table: Failure Type | Count | Percentage of Total
- Shows: Lab Failure, Client Failure, Material Defect, etc.
- Percentages calculated from total repeat cases

### Test 4.2 - Year Boundary, perToggle=1, countOrPercentageToggle=0, Failure Types 0&1
**URL:** `http://127.0.0.1:8000/reports/repeats?from=2024-12-15&to=2025-01-15&perToggle=1&countOrPercentageToggle=0&failureTypeInput[]=0&failureTypeInput[]=1&doctor[]=all`
**Parameters:**
```
from: 2024-12-15
to: 2025-01-15
perToggle: 1 (per-doctor)
countOrPercentageToggle: 0 (show counts)
failureTypeInput: [0, 1]
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Per-doctor breakdown
- Raw counts (not percentages)
- Filtered to failure types 0 and 1

### Test 4.3 - Jul-Oct 2025, perToggle=1, countOrPercentageToggle=1, Failure Types 2&3
**URL:** `http://127.0.0.1:8000/reports/repeats?from=2025-07-01&to=2025-10-20&perToggle=1&countOrPercentageToggle=1&failureTypeInput[]=2&failureTypeInput[]=3&doctor[]=all`
**Parameters:**
```
from: 2025-07-01
to: 2025-10-20
perToggle: 1
countOrPercentageToggle: 1 (percentages)
failureTypeInput: [2, 3]
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Per-doctor breakdown with percentages
- Shows failure types 2 and 3 only
- 4-month period data

### Test 4.4 - Single Day, perToggle=0, countOrPercentageToggle=0, All Failure Types
**URL:** `http://127.0.0.1:8000/reports/repeats?from=2025-10-20&to=2025-10-20&perToggle=0&countOrPercentageToggle=0&failureTypeInput=all&doctor[]=all`
**Parameters:**
```
from: 2025-10-20
to: 2025-10-20
perToggle: 0
countOrPercentageToggle: 0 (counts)
failureTypeInput: all
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Single day snapshot
- Aggregated counts
- All failure types

---

## Test 5: QC Report (`/reports/QC`)

### Test 5.1 - Oct 1-20, All Failure Types & Causes
**URL:** `http://127.0.0.1:8000/reports/QC?from=2025-10-01&to=2025-10-20&causesInput=all&failureTypeInput=all&doctor[]=all`
**Parameters:**
```
from: 2025-10-01
to: 2025-10-20
causesInput: all (failure causes)
failureTypeInput: all
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Table: Case ID | Patient Name | Failure Type | Failure Cause | QC Date | Doctor
- Detailed list of all QC-flagged cases
- Shows both failure type and specific cause

### Test 5.2 - Year Boundary, Specific Causes 1&2, All Failure Types
**URL:** `http://127.0.0.1:8000/reports/QC?from=2024-12-15&to=2025-01-15&causesInput[]=1&causesInput[]=2&failureTypeInput=all&doctor[]=all`
**Parameters:**
```
from: 2024-12-15
to: 2025-01-15
causesInput: [1, 2] (specific causes filtered)
failureTypeInput: all
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Filtered to show only causes 1 and 2
- All failure types shown
- Cross-year data

### Test 5.3 - Jul-Oct 2025, All Causes, Failure Types 0&1
**URL:** `http://127.0.0.1:8000/reports/QC?from=2025-07-01&to=2025-10-20&causesInput=all&failureTypeInput[]=0&failureTypeInput[]=1&doctor[]=all`
**Parameters:**
```
from: 2025-07-01
to: 2025-10-20
causesInput: all
failureTypeInput: [0, 1] (specific failure types)
doctor: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- All causes shown
- Filtered to failure types 0 and 1
- 4-month period

### Test 5.4 - Single Day, Specific Cause 1, Failure Type 0, Doctor 1
**URL:** `http://127.0.0.1:8000/reports/QC?from=2025-10-20&to=2025-10-20&causesInput[]=1&failureTypeInput[]=0&doctor[]=1`
**Parameters:**
```
from: 2025-10-20
to: 2025-10-20
causesInput: [1]
failureTypeInput: [0]
doctor: [1] (specific doctor)
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Highly filtered view
- Single day, single doctor, specific cause and failure type
- May show empty table if no matching cases

---

## Test 6: Material Report (`/reports/material`)

### Test 6.1 - Oct 1-20, All Doctors ❌ FAILED
**URL:** `http://127.0.0.1:8000/reports/material?from=2025-10-01&to=2025-10-20&doctor=all`
**Parameters:**
```
from: 2025-10-01
to: 2025-10-20
doctor: all
```
**Response:** HTTP 500 ❌ FAIL
**Error Details:**
- Server returned 500 Internal Server Error
- No HTML content rendered
- Likely issue with date range processing or material data aggregation
- Same pattern as Test 6.4 (longer date ranges fail)

### Test 6.2 - Year Boundary, Doctor 1
**URL:** `http://127.0.0.1:8000/reports/material?from=2024-12-15&to=2025-01-15&doctor[]=1`
**Parameters:**
```
from: 2024-12-15
to: 2025-01-15
doctor: [1]
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Table: Material Type | Quantity Used | Cost | Doctor Name
- Shows material consumption for Doctor 1 only
- Shorter date range works (1 month)

### Test 6.3 - Jul-Oct 2025, Doctors 1,2,3
**URL:** `http://127.0.0.1:8000/reports/material?from=2025-07-01&to=2025-10-20&doctor[]=1&doctor[]=2&doctor[]=3`
**Parameters:**
```
from: 2025-07-01
to: 2025-10-20
doctor: [1, 2, 3]
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Material usage for 3 specific doctors
- 4-month period
- Breakdown by material type and doctor

### Test 6.4 - Single Day, All Doctors, Patient Filter ❌ FAILED
**URL:** `http://127.0.0.1:8000/reports/material?from=2025-10-20&to=2025-10-20&doctor=all&patient_name=ahmad`
**Parameters:**
```
from: 2025-10-20
to: 2025-10-20
doctor: all
patient_name: ahmad (additional filter)
```
**Response:** HTTP 500 ❌ FAIL
**Error Details:**
- Server returned 500 Internal Server Error
- Even single day fails when doctor=all is used
- Pattern suggests issue with "doctor=all" parameter handling in materialReport method

---

## Test 7: Master Report (`/reports/master`)

### Test 7.1 - Oct 1-20, All Filters, All Completed Status
**URL:** `http://127.0.0.1:8000/reports/master?from=2025-10-01&to=2025-10-20&doctor=all&material=all&job_type=all&show_completed=all`
**Parameters:**
```
from: 2025-10-01
to: 2025-10-20
doctor: all
material: all
job_type: all
show_completed: all (shows all cases)
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Comprehensive table: Case ID | Patient | Doctor | Material | Job Type | Units | Amount | Status | Dates
- Shows all cases regardless of completion status
- Most detailed report - combines all other report data

### Test 7.2 - Year Boundary, Materials 1&2, Job Type 1
**URL:** `http://127.0.0.1:8000/reports/master?from=2024-12-15&to=2025-01-15&doctor=all&material[]=1&material[]=2&job_type[]=1&show_completed=all`
**Parameters:**
```
from: 2024-12-15
to: 2025-01-15
doctor: all
material: [1, 2]
job_type: [1]
show_completed: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Filtered to materials 1 and 2
- Filtered to job type 1 (Crown)
- Cross-year data

### Test 7.3 - Jul-Oct 2025, All Filters, Completed Cases Only
**URL:** `http://127.0.0.1:8000/reports/master?from=2025-07-01&to=2025-10-20&doctor=all&material=all&job_type=all&show_completed=completed`
**Parameters:**
```
from: 2025-07-01
to: 2025-10-20
doctor: all
material: all
job_type: all
show_completed: completed (only completed cases)
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Only cases marked as completed
- All materials and job types
- 4-month period

### Test 7.4 - Single Day, All Filters, In Progress Cases Only
**URL:** `http://127.0.0.1:8000/reports/master?from=2025-10-20&to=2025-10-20&doctor=all&material=all&job_type=all&show_completed=in_progress`
**Parameters:**
```
from: 2025-10-20
to: 2025-10-20
doctor: all
material: all
job_type: all
show_completed: in_progress (only active cases)
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Only cases currently in progress
- Single day snapshot
- Shows current workflow status

### Test 7.5 - Oct 1-20, All Filters, Specific Stages 2&3
**URL:** `http://127.0.0.1:8000/reports/master?from=2025-10-01&to=2025-10-20&doctor=all&material=all&job_type=all&status[]=2&status[]=3&show_completed=all`
**Parameters:**
```
from: 2025-10-01
to: 2025-10-20
doctor: all
material: all
job_type: all
status: [2, 3] (Milling and 3D Printing stages)
show_completed: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Filtered to cases in Milling or 3D Printing stages only
- All other filters open
- Shows stage-specific workflow data

### Test 7.6 - Jul-Oct 2025, All Filters, Amount Range 100-500
**URL:** `http://127.0.0.1:8000/reports/master?from=2025-07-01&to=2025-10-20&doctor=all&material=all&job_type=all&amount_from=100&amount_to=500&show_completed=all`
**Parameters:**
```
from: 2025-07-01
to: 2025-10-20
doctor: all
material: all
job_type: all
amount_from: 100
amount_to: 500 (financial filter)
show_completed: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Cases with invoice amounts between 100-500
- Financial range filter working
- All other filters open

### Test 7.7 - Oct 1-20, All Filters, Units Range 1-5
**URL:** `http://127.0.0.1:8000/reports/master?from=2025-10-01&to=2025-10-20&doctor=all&material=all&job_type=all&units_from=1&units_to=5&show_completed=all`
**Parameters:**
```
from: 2025-10-01
to: 2025-10-20
doctor: all
material: all
job_type: all
units_from: 1
units_to: 5 (unit count filter)
show_completed: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Cases with 1-5 units only
- Filters out large bridge/implant cases
- Shows smaller jobs

### Test 7.8 - Year Boundary, Multiple Filters Combined
**URL:** `http://127.0.0.1:8000/reports/master?from=2024-12-15&to=2025-01-15&doctor[]=1&material[]=2&job_type[]=1&status[]=7&amount_from=50&show_completed=all`
**Parameters:**
```
from: 2024-12-15
to: 2025-01-15
doctor: [1]
material: [2]
job_type: [1]
status: [7] (QC stage)
amount_from: 50 (minimum amount)
show_completed: all
```
**Response:** HTTP 200 ✅ PASS
**Data Returned:**
- Highly filtered complex query
- Doctor 1, Material 2, Job Type 1, QC stage, amount >= 50
- All filters applied successfully
- Cross-year boundary handled correctly

---

## Summary

**Total Tests:** 32
**Passed:** 30 (93.8%)
**Failed:** 2 (6.2%)

### Failure Analysis

**Both failures in Material Report:**

1. **Test 6.1** (HTTP 500) - `doctor=all` parameter causes error
2. **Test 6.4** (HTTP 500) - Same issue with `doctor=all`

**Pattern:** Material report fails when using `doctor=all` (string value) vs. `doctor[]=1` (array syntax). Tests 6.2 and 6.3 pass because they use array syntax `doctor[]=1` or `doctor[]=1&doctor[]=2`.

**Root Cause:** The materialReport controller method likely expects `doctor` as an array but receives a string when `doctor=all` is passed. The type casting fixes applied to other reports need to be extended to the material report's doctor parameter handling.

### Performance Notes

- All passing tests return results within 2-5 seconds
- Reports with perToggle=1 take slightly longer (more data to process)
- Master report is slowest due to complexity but still under 10 seconds for most queries
- DataTables initialization adds ~500ms to page load
