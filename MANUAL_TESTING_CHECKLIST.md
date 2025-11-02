# Master Report - Manual Testing Checklist

## Pre-Test Setup

### ✅ Prerequisites
1. [ ] Log into SIGMA system as admin user
2. [ ] Verify test cases 199-213 exist in database
3. [ ] Open browser developer console (F12) to monitor for errors
4. [ ] Have this checklist ready for marking off completed tests

### Test Data Reference
- **Client IDs:** 2, 3, 5, 6, 7
- **Material ID:** 1 (Zirconia)
- **Cases:** 199-213 (15 total)
- **Job Types:** 1=Crown, 2=Bridge, 6=Implant

---

## Test Suite 1: Basic & Date Filters

### ✅ TC-01: Default Load
**URL to paste in browser:**
```
http://localhost:8000/reports/master?generate_report=1
```

**Steps:**
1. [ ] Paste URL and press Enter
2. [ ] Wait for page to load completely
3. [ ] Check that report shows cases

**Expected Results:**
- [ ] Default date range shown (first of month to today)
- [ ] Table displays cases
- [ ] Should see approximately 14 cases (199-209, 211-213)
- [ ] Case 210 may not appear (30 days old)

**Actual Results:**
- Case Count: ___________
- Case IDs visible: ________________________________
- Any errors: ________________________________

---

### ✅ TC-02: Specific Date Range (Old Case)
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&from=2025-09-28&to=2025-09-30
```

**Expected Results:**
- [ ] Date inputs show: from=2025-09-28, to=2025-09-30
- [ ] Table shows only Case 210
- [ ] Case count = 1

**Actual Results:**
- Case IDs: ___________
- ✅ Pass / ❌ Fail

---

## Test Suite 2: Doctor/Client Filters

### ✅ TC-03: Single Doctor (Client 2)
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=2
```

**Expected Results:**
- [ ] Doctor dropdown shows "سنان غيشان" selected
- [ ] Cases shown: 199, 202, 206, 211
- [ ] Case count = 4

**Actual Results:**
- Case IDs: ___________
- ✅ Pass / ❌ Fail

---

###  ✅ TC-04: Multiple Doctors (2 and 3)
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=2&doctor%5B%5D=3
```

**Expected Results:**
- [ ] Two doctors selected in dropdown
- [ ] Cases: 199, 200, 202, 203, 206, 208, 211, 213
- [ ] Case count = 8

**Actual Results:**
- Case IDs: ___________
- ✅ Pass / ❌ Fail

---

## Test Suite 3: Workflow Stage Filters

### ✅ TC-05a: Finishing Stage
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&status%5B%5D=6
```

**Expected Results:**
- [ ] Workflow Stage dropdown shows "Finishing" selected
- [ ] Case 212 shown
- [ ] Case count = 1

**Actual Results:**
- Case IDs: ___________
- ✅ Pass / ❌ Fail

---

### ✅ TC-05b: Design Stage
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&status%5B%5D=1
```

**Expected Results:**
- [ ] Cases: 207, 211
- [ ] Case count = 2

**Actual Results:**
- Case IDs: ___________
- ✅ Pass / ❌ Fail

---

### ✅ TC-05c: 3D Printing Stage
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&status%5B%5D=3
```

**Expected Results:**
- [ ] Cases: 200, 207, 209
- [ ] Case count = 3

**Actual Results:**
- Case IDs: ___________
- ✅ Pass / ❌ Fail

---

## Test Suite 4: Amount Range Filters

### ✅ TC-08: Amount From (>=100)
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&amount_from=100
```

**Expected Results:**
- [ ] Amount From input = 100
- [ ] All cases except 204 (50 JOD)
- [ ] Case count ≈ 14

**Actual Results:**
- Case count: ___________
- Missing Case 204: [ ] Yes [ ] No
- ✅ Pass / ❌ Fail

---

### ✅ TC-09: Amount To (<=500)
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&amount_to=500
```

**Expected Results:**
- [ ] Amount To input = 500
- [ ] All cases except 205 (900 JOD)
- [ ] Case count ≈ 14

**Actual Results:**
- Missing Case 205: [ ] Yes [ ] No
- ✅ Pass / ❌ Fail

---

### ✅ TC-10: Amount Range (100-500)
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&amount_from=100&amount_to=500
```

**Expected Results:**
- [ ] Both amount fields filled
- [ ] Cases: 199, 200, 201, 202, 203, 206, 208, 209, 210, 212, 213
- [ ] Excludes: 204 (50 JOD), 205 (900 JOD), 211 (no invoice)
- [ ] Case count = 11

**Actual Results:**
- Case IDs: ___________
- ✅ Pass / ❌ Fail

---

### ✅ TC-10b: Low Amount Range (1-100)
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&amount_from=1&amount_to=100
```

**Expected Results:**
- [ ] Cases: 202 (100 JOD), 204 (50 JOD)
- [ ] Case count = 2

**Actual Results:**
- Case IDs: ___________
- ✅ Pass / ❌ Fail

---

## Test Suite 5: Units Range Filters

### ✅ TC-12: Units 2-4
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&units_from=2&units_to=4
```

**Expected Results:**
- [ ] Cases: 200 (3 units), 207 (3 jobs), 209 (2 units)
- [ ] Case count ≈ 3 (plus any single-unit cases)

**Actual Results:**
- Case IDs with 2-4 units: ___________
- ✅ Pass / ❌ Fail

---

### ✅ TC-12b: Many Units (6+)
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&units_from=6&units_to=10
```

**Expected Results:**
- [ ] Case 205 (6 units)
- [ ] Case count = 1

**Actual Results:**
- Case IDs: ___________
- ✅ Pass / ❌ Fail

---

## Test Suite 6: Completion Status

### ✅ TC-13: Completed Only
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&show_completed=completed
```

**Expected Results:**
- [ ] Completion toggle shows "Completed"
- [ ] Cases: 199, 201, 202, 204, 205, 206, 210
- [ ] Case count = 7
- [ ] All have actual_delivery_date

**Actual Results:**
- Case IDs: ___________
- ✅ Pass / ❌ Fail

---

### ✅ TC-14: In-Progress Only
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&show_completed=in_progress
```

**Expected Results:**
- [ ] Completion toggle shows "In Progress"
- [ ] Cases: 200, 203, 207, 208, 209, 211, 212, 213
- [ ] Case count = 8
- [ ] None have actual_delivery_date (or have jobs not at stage -1)

**Actual Results:**
- Case IDs: ___________
- ✅ Pass / ❌ Fail

---

## Test Suite 7: Job Type Filters

### ✅ EXTRA-01: Crowns Only
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&job_type%5B%5D=1
```

**Expected Results:**
- [ ] Job Type "Crown" selected
- [ ] Most cases shown (199, 202, 203, 204, 206, 207, 208, 211, 212, 213)
- [ ] Case count ≈ 10

**Actual Results:**
- Case count: ___________
- ✅ Pass / ❌ Fail

---

### ✅ EXTRA-02: Bridges Only
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&job_type%5B%5D=2
```

**Expected Results:**
- [ ] Cases: 200, 205, 209
- [ ] Case count = 3

**Actual Results:**
- Case IDs: ___________
- ✅ Pass / ❌ Fail

---

### ✅ EXTRA-03: Implants Only
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&job_type%5B%5D=6
```

**Expected Results:**
- [ ] Case 201
- [ ] Case count = 1

**Actual Results:**
- Case IDs: ___________
- ✅ Pass / ❌ Fail

---

## Test Suite 8: Edge Cases

### ✅ TC-19: No Results
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=99999
```

**Expected Results:**
- [ ] "No cases found" message displayed
- [ ] Empty table or no table
- [ ] No errors in console

**Actual Results:**
- Message shown: ___________
- ✅ Pass / ❌ Fail

---

### ✅ TC-20: All + Specific Doctor
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=all&doctor%5B%5D=2
```

**Expected Results:**
- [ ] JavaScript should deselect "all"
- [ ] Only Client 2 selected
- [ ] Cases: 199, 202, 206, 211

**Actual Results:**
- "All" deselected: [ ] Yes [ ] No
- Case IDs: ___________
- ✅ Pass / ❌ Fail

---

### ✅ TC-21: Complex Combination
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&from=2025-10-01&to=2025-10-29&doctor%5B%5D=all&material%5B%5D=all&job_type%5B%5D=all&status%5B%5D=all&amount_from=1&amount_to=200&show_completed=all
```

**Expected Results:**
- [ ] All dropdowns show "All" selected
- [ ] Amount range: 1-200
- [ ] Cases with invoice 1-200 JOD shown
- [ ] Expected: 199, 201, 202, 203, 204, 206, 208, 210, 212, 213
- [ ] Case count ≈ 10

**Actual Results:**
- Case IDs: ___________
- ✅ Pass / ❌ Fail

---

### ✅ TC-18: Kitchen Sink (All Filters)
**URL:**
```
http://localhost:8000/reports/master?generate_report=1&from=2025-10-01&to=2025-10-29&doctor%5B%5D=2&material%5B%5D=1&status%5B%5D=1&amount_from=50&units_to=5&show_completed=in_progress
```

**Expected Results:**
- [ ] All filters populated correctly
- [ ] Very specific result set
- [ ] Likely Case 211 (Client 2, Zirconia, Design, in-progress)
- [ ] Case count ≈ 1-2

**Actual Results:**
- Case IDs: ___________
- ✅ Pass / ❌ Fail

---

## Additional Manual Checks

### Browser Console Check
- [ ] No JavaScript errors in console
- [ ] No 404 errors for assets
- [ ] No AJAX errors

### UI/UX Check
- [ ] Filters load correctly
- [ ] Dropdowns are populated
- [ ] Table renders properly
- [ ] Pagination works (if applicable)
- [ ] Export buttons work (if any)

### Data Accuracy
- [ ] Patient names display correctly
- [ ] Doctor names display correctly
- [ ] Amounts are accurate
- [ ] Dates are formatted correctly
- [ ] Job types match expected

---

## Test Summary

**Total Tests:** 26
**Tests Passed:** _____
**Tests Failed:** _____
**Pass Rate:** _____%

### Failed Tests (if any):
1. ________________________________
2. ________________________________
3. ________________________________

### Issues Found:
1. ________________________________
2. ________________________________
3. ________________________________

### Notes:
________________________________________________________________
________________________________________________________________
________________________________________________________________

---

**Tested By:** ___________
**Date:** ___________
**Time:** ___________
**Browser:** ___________
**Version:** ___________

---

## Quick Reference: Expected Case Counts

| Filter Type | Expected Count |
|-------------|---------------|
| All (default) | 14-15 |
| Client 2 | 4 |
| Client 2+3 | 8 |
| Finishing Stage | 1 |
| Design Stage | 2 |
| 3D Printing Stage | 3 |
| Amount >=100 | 14 |
| Amount <=500 | 14 |
| Amount 100-500 | 11 |
| Amount 1-100 | 2 |
| Units 2-4 | 3+ |
| Units 6+ | 1 |
| Completed | 7 |
| In-Progress | 8 |
| Crowns | 10 |
| Bridges | 3 |
| Implants | 1 |

