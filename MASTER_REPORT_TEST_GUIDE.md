# Master Report Testing Guide

## Overview
This document provides comprehensive test scenarios for the Master Report using the 15 test cases created by `MasterReportTestCasesSeeder`.

**Test Cases Created:** 15 cases (IDs 199-213)
**Date:** October 29, 2025

---

## Test Case Reference

| Case ID | Description | Doctor | Material | Job Type | Stage | Invoice | Key Features |
|---------|-------------|--------|----------|----------|-------|---------|--------------|
| 199 | Basic Completed Crown | Client 1 | Zircon | Crown | -1 (Completed) | 150 JOD | All stages completed |
| 200 | Bridge In-Progress | Client 2 | Emax | Bridge | 3 (3D Printing) | 450 JOD | 3 units, In-progress |
| 201 | Implant with Abutments | Client 3 | Zircon | Implant | -1 (Completed) | 200 JOD | Has abutment & implant |
| 202 | Failed/Rejected Case | Client 1 | Acrylic | Crown | -1 (Completed) | 100 JOD | Has failure log |
| 203 | Repeat Case | Client 2 | Telescopic/Zircon | Crown | 5 (Pressing) | 120 JOD | is_repeat=true |
| 204 | Low Amount Case | Client 4 | Acrylic | Crown | -1 (Completed) | 50 JOD | Low invoice amount |
| 205 | High Amount Case | Client 5 | Zircon | Bridge | -1 (Completed) | 900 JOD | 6 units, High amount |
| 206 | Employee Assignment | Client 1 | Emax | Crown | -1 (Completed) | 180 JOD | Has assignee & delivery |
| 207 | Multiple Materials | Client 3 | Zircon+Emax+Acrylic | 3 Crowns | Mixed (1,2,3) | 380 JOD | 3 jobs, 3 materials |
| 208 | Milling Device Test | Client 2 | Zircon | Crown | 2 (Milling) | 150 JOD | Has device_id |
| 209 | 3D Printing Device | Client 4 | Acrylic | Bridge | 3 (3D Printing) | 220 JOD | 2 units, Has device_id |
| 210 | Old Date Case | Client 5 | Telescopic/Emax | Crown | -1 (Completed) | 160 JOD | 30 days ago |
| 211 | Recent Case (Today) | Client 1 | Zircon | Crown | 1 (Design) | None | Today's date |
| 212 | Modification Case | Client 3 | Emax | Crown | 6 (Finishing) | 140 JOD | is_modification=true |
| 213 | Redo Case | Client 2 | Zircon | Crown | 7 (QC) | 170 JOD | is_redo=true |

---

## Filter Testing Scenarios

### 1. Date Range Filter

#### Test 1.1: Current Month (Default)
**Steps:**
1. Go to Master Report
2. Use default date range (first of month to today)

**Expected Results:**
- Should show cases: 199, 200, 201, 202, 203, 204, 205, 206, 207, 208, 209, 211, 212, 213 (14 cases)
- Should NOT show: 210 (30 days ago)

#### Test 1.2: Last Month
**Steps:**
1. Set date range: 30 days ago to 25 days ago

**Expected Results:**
- Should show only case: 210

#### Test 1.3: Today Only
**Steps:**
1. Set from/to date to today's date

**Expected Results:**
- Should show: 211 (Recent case created today)
- May also show other recent cases depending on initial_delivery_date

---

### 2. Doctor/Client Filter

#### Test 2.1: Single Doctor
**Steps:**
1. Select "Client 1" only

**Expected Results:**
- Should show cases: 199, 202, 206, 211 (4 cases)

#### Test 2.2: Multiple Doctors
**Steps:**
1. Select "Client 2" and "Client 3"

**Expected Results:**
- Should show cases: 200, 201, 203, 207, 208, 212, 213 (7 cases)

#### Test 2.3: All Doctors
**Steps:**
1. Select "All" or leave blank

**Expected Results:**
- Should show all 15 cases (199-213)

---

### 3. Material Filter

#### Test 3.1: Zircon Only
**Steps:**
1. Select "Zircon" material

**Expected Results:**
- Should show cases: 199, 201, 205, 207 (has Zircon job), 208, 211, 213 (7 cases)

#### Test 3.2: Emax Only
**Steps:**
1. Select "Emax" material

**Expected Results:**
- Should show cases: 200, 206, 207 (has Emax job), 212 (4 cases)

#### Test 3.3: Acrylic Only
**Steps:**
1. Select "Acrylic" material

**Expected Results:**
- Should show cases: 202, 204, 207 (has Acrylic job), 209 (4 cases)

#### Test 3.4: Multiple Materials
**Steps:**
1. Select "Zircon" + "Emax"

**Expected Results:**
- Should show all cases that have Zircon OR Emax

---

### 4. Job Type Filter

#### Test 4.1: Crown Only
**Steps:**
1. Select "Crown" job type

**Expected Results:**
- Should show cases: 199, 202, 203, 204, 206, 207, 208, 211, 212, 213 (10 cases)

#### Test 4.2: Bridge Only
**Steps:**
1. Select "Bridge" job type

**Expected Results:**
- Should show cases: 200, 205, 209 (3 cases)

#### Test 4.3: Implant Only
**Steps:**
1. Select "Implant" job type

**Expected Results:**
- Should show case: 201 (1 case)

---

### 5. Completion Status Filter

#### Test 5.1: Completed Only
**Steps:**
1. Select "Completed" from completion status dropdown

**Expected Results:**
- Should show cases: 199, 201, 202, 204, 205, 206, 210 (7 cases)
- All should have actual_delivery_date set
- All jobs should be at stage -1

#### Test 5.2: In-Progress Only
**Steps:**
1. Select "In Progress" from completion status dropdown

**Expected Results:**
- Should show cases: 200, 203, 207, 208, 209, 211, 212, 213 (8 cases)
- All should have actual_delivery_date = null OR have jobs not at stage -1

#### Test 5.3: All Cases
**Steps:**
1. Select "All" from completion status dropdown

**Expected Results:**
- Should show all 15 cases

---

### 6. Workflow Stage Filter

#### Test 6.1: Design Stage (1)
**Steps:**
1. Select "Design" stage

**Expected Results:**
- Should show cases with at least one job at stage 1
- Should include: 207 (job at stage 1), 211 (at Design)

#### Test 6.2: Milling Stage (2)
**Steps:**
1. Select "Milling" stage

**Expected Results:**
- Should show cases with at least one job at stage 2
- Should include: 207 (job at stage 2), 208 (at Milling)

#### Test 6.3: 3D Printing Stage (3)
**Steps:**
1. Select "3D Printing" stage

**Expected Results:**
- Should show cases: 200 (at 3D Printing), 207 (job at stage 3), 209

#### Test 6.4: Pressing Stage (5)
**Steps:**
1. Select "Pressing" stage

**Expected Results:**
- Should show case: 203 (Repeat case at Pressing)

#### Test 6.5: Finishing Stage (6)
**Steps:**
1. Select "Finishing" stage

**Expected Results:**
- Should show case: 212 (Modification at Finishing)

#### Test 6.6: QC Stage (7)
**Steps:**
1. Select "QC" stage

**Expected Results:**
- Should show case: 213 (Redo case at QC)

#### Test 6.7: Multiple Stages
**Steps:**
1. Select "Design" + "Milling" + "3D Printing"

**Expected Results:**
- Should show all cases with jobs in any of these stages

---

### 7. Invoice Amount Range Filter

#### Test 7.1: Low Range (0-100 JOD)
**Steps:**
1. Set amount_from: 0
2. Set amount_to: 100

**Expected Results:**
- Should show cases: 202 (100 JOD), 204 (50 JOD) (2 cases)

#### Test 7.2: Medium Range (100-200 JOD)
**Steps:**
1. Set amount_from: 100
2. Set amount_to: 200

**Expected Results:**
- Should show cases: 199, 201, 202, 203, 206, 208, 210, 212, 213 (9 cases)

#### Test 7.3: High Range (500+ JOD)
**Steps:**
1. Set amount_from: 500
2. Set amount_to: 1000

**Expected Results:**
- Should show case: 205 (900 JOD) (1 case)

#### Test 7.4: Minimum Only
**Steps:**
1. Set amount_from: 200
2. Leave amount_to blank

**Expected Results:**
- Should show all cases with invoice >= 200 JOD

---

### 8. Number of Units Filter

#### Test 8.1: Single Unit (1)
**Steps:**
1. Set units_from: 1
2. Set units_to: 1

**Expected Results:**
- Should show all cases with exactly 1 job
- Most cases have 1 unit per job

#### Test 8.2: Multiple Units (2-4)
**Steps:**
1. Set units_from: 2
2. Set units_to: 4

**Expected Results:**
- Should show cases: 200 (3 units), 209 (2 units)

#### Test 8.3: Many Units (6+)
**Steps:**
1. Set units_from: 6
2. Set units_to: 10

**Expected Results:**
- Should show case: 205 (6 units Bridge)

---

### 9. Abutment & Implant Filters

#### Test 9.1: Specific Abutment
**Steps:**
1. Select an abutment from the dropdown

**Expected Results:**
- Should show case: 201 (has abutment)

#### Test 9.2: Specific Implant
**Steps:**
1. Select an implant from the dropdown

**Expected Results:**
- Should show case: 201 (has implant)

#### Test 9.3: Both Abutment & Implant
**Steps:**
1. Select both abutment and implant

**Expected Results:**
- Should show case: 201 (has both)

---

### 10. Failure Type Filter

#### Test 10.1: Specific Failure Cause
**Steps:**
1. Select a failure cause from dropdown

**Expected Results:**
- Should show case: 202 (Failed/Rejected case with failure log)

---

### 11. Employee Filter

#### Test 11.1: Assignee Filter
**Steps:**
1. Add employee filter for "Assignee" stage
2. Select the specific user who was assigned

**Expected Results:**
- Should show cases assigned to that specific employee
- All test cases use admin user as assignee

#### Test 11.2: Delivery Filter
**Steps:**
1. Add employee filter for "Delivery" stage
2. Select the delivery user

**Expected Results:**
- Should show case: 206 (has delivery_accepted set)

---

### 12. Device Filter

#### Test 12.1: Milling Device
**Steps:**
1. Add device filter for "Mill" type
2. Select specific milling device

**Expected Results:**
- Should show case: 208 (has device_id for milling device)

#### Test 12.2: 3D Printing Device
**Steps:**
1. Add device filter for "Print" type
2. Select specific printing device

**Expected Results:**
- Should show case: 209 (has device_id for print device)

---

## Combined Filter Testing

### Test C1: Multi-Filter Combination
**Steps:**
1. Date range: Current month
2. Doctor: Client 1
3. Material: Zircon
4. Status: Completed

**Expected Results:**
- Should show cases: 199, 211 (if completed by now)

### Test C2: Complex Combination
**Steps:**
1. Date range: Current month
2. Material: Emax
3. Stage: 3D Printing
4. Status: In-Progress

**Expected Results:**
- Should show case: 200 (Bridge in 3D Printing with Emax)

### Test C3: Amount + Units Combination
**Steps:**
1. Amount: 400-500 JOD
2. Units: 3-4

**Expected Results:**
- Should show case: 200 (450 JOD, 3 units)

---

## Edge Cases & Special Scenarios

### Edge 1: No Results
**Test:** Select filters that should return no results (e.g., Implant type + Acrylic material)
**Expected:** Empty table with "No results found" message

### Edge 2: All Filters Applied
**Test:** Apply all available filters with compatible values
**Expected:** Should show appropriate subset of cases

### Edge 3: Case with Multiple Jobs (Different Materials)
**Test:** Filter by specific material when case 207 has multiple materials
**Expected:** Case 207 should appear if ANY of its jobs match the filter

---

## Validation Checklist

After running each test:
- ✅ Case IDs match expected results
- ✅ Case count is correct
- ✅ Patient names are visible
- ✅ Doctor names are correct
- ✅ Dates are properly formatted
- ✅ Invoice amounts are accurate
- ✅ Stage information is correct
- ✅ No errors in browser console
- ✅ No SQL errors in Laravel log

---

## Known Test Data

### Clients Used:
- Client 1 (used in cases: 199, 202, 206, 211)
- Client 2 (used in cases: 200, 203, 208, 213)
- Client 3 (used in cases: 201, 207, 212)
- Client 4 (used in cases: 204, 209)
- Client 5 (used in cases: 205, 210)

### Materials Used:
- Zircon (cases: 199, 201, 205, 207, 208, 211, 213)
- Emax (cases: 200, 206, 207, 210, 212)
- Acrylic (cases: 202, 204, 207, 209)
- Telescopic variants (cases: 203, 210)

### Job Types Used:
- Crown (most cases)
- Bridge (cases: 200, 205, 209)
- Implant (case: 201)

### Stages Represented:
- Stage -1 (Completed): 199, 201, 202, 204, 205, 206, 210
- Stage 1 (Design): 207, 211
- Stage 2 (Milling): 207, 208
- Stage 3 (3D Printing): 200, 207, 209
- Stage 5 (Pressing): 203
- Stage 6 (Finishing): 212
- Stage 7 (QC): 213

---

## Quick Reference Commands

### View Test Cases
```sql
SELECT id, patient_name, doctor_id, initial_delivery_date, actual_delivery_date
FROM cases
WHERE id BETWEEN 199 AND 213
ORDER BY id;
```

### View Jobs for Test Cases
```sql
SELECT j.id, j.case_id, j.type, j.material_id, j.stage, j.unit_num
FROM jobs j
WHERE j.case_id BETWEEN 199 AND 213
ORDER BY j.case_id, j.id;
```

### View Invoices for Test Cases
```sql
SELECT case_id, amount
FROM invoices
WHERE case_id BETWEEN 199 AND 213
ORDER BY case_id;
```

---

## Re-running Test Data

To recreate all test cases:
```bash
php artisan db:seed --class=MasterReportTestCasesSeeder
```

**Note:** This will create NEW cases with different IDs. Update this guide accordingly.

---

## Reporting Issues

When reporting test failures, include:
1. Filter combination used
2. Expected case IDs
3. Actual case IDs shown
4. Screenshots if applicable
5. Browser console errors
6. Laravel log errors (storage/logs/laravel.log)

---

**Document Version:** 1.0
**Last Updated:** October 29, 2025
**Test Data IDs:** Cases 199-213
