# Quick Start - Master Report Testing

**Test Cases Created:** Cases 214-228 (15 total)
**Status:** ✅ Ready to test
**Server:** Running at http://localhost:8000

---

## 🚀 Start Testing NOW (Copy & Paste URLs)

### Prerequisites:
1. Open browser
2. Navigate to: http://localhost:8000/login
3. Login with admin credentials
4. Keep this file open side-by-side

---

## Test Suite 1: Basic Filters (Quick Tests)

### ✅ TC-01: Default Load - ALL CASES
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1
```
**Expected:** Should see approximately 14 cases (214-224, 226-228)
**Actual case IDs shown:** _______________
**Pass/Fail:** [ ]

---

### ✅ TC-02: Old Date Range - CASE 225
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&from=2025-09-28&to=2025-09-30
```
**Expected:** Only Case 225 (30 days old)
**Actual case IDs shown:** _______________
**Pass/Fail:** [ ]

---

### ✅ TC-03: Single Doctor (Client 2) - 4 CASES
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=2
```
**Expected:** Cases 214, 217, 221, 226 (4 cases total)
**Actual case IDs shown:** _______________
**Pass/Fail:** [ ]

---

### ✅ TC-04: Multiple Doctors (2 & 3) - 8 CASES
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=2&doctor%5B%5D=3
```
**Expected:** Cases 214, 215, 217, 218, 221, 223, 226, 228 (8 cases)
**Actual case IDs shown:** _______________
**Pass/Fail:** [ ]

---

## Test Suite 2: Workflow Stage Filters

### ✅ TC-05a: Finishing Stage - 1 CASE
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&status%5B%5D=6
```
**Expected:** Case 227 only
**Actual case IDs shown:** _______________
**Pass/Fail:** [ ]

---

### ✅ TC-05b: Design Stage - 2 CASES
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&status%5B%5D=1
```
**Expected:** Cases 222, 226 (2 cases)
**Actual case IDs shown:** _______________
**Pass/Fail:** [ ]

---

### ✅ TC-05c: 3D Printing Stage - 3 CASES
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&status%5B%5D=3
```
**Expected:** Cases 215, 222, 224 (3 cases)
**Actual case IDs shown:** _______________
**Pass/Fail:** [ ]

---

## Test Suite 3: Amount Range Filters

### ✅ TC-08: Amount From (>=100) - 14 CASES
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&amount_from=100
```
**Expected:** All except Case 219 (which is 50 JOD) - 14 cases
**Actual case count:** _______________
**Pass/Fail:** [ ]

---

### ✅ TC-09: Amount To (<=500) - 14 CASES
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&amount_to=500
```
**Expected:** All except Case 220 (which is 900 JOD) - 14 cases
**Actual case count:** _______________
**Pass/Fail:** [ ]

---

### ✅ TC-10: Amount Range (100-500) - 11 CASES
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&amount_from=100&amount_to=500
```
**Expected:** Cases 214, 215, 216, 217, 218, 221, 223, 224, 225, 227, 228 (11 cases)
**Excludes:** 219 (50 JOD), 220 (900 JOD), 226 (no invoice)
**Actual case IDs shown:** _______________
**Pass/Fail:** [ ]

---

### ✅ TC-10b: Low Amount Range (1-100) - 2 CASES
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&amount_from=1&amount_to=100
```
**Expected:** Cases 217 (100 JOD), 219 (50 JOD) - 2 cases
**Actual case IDs shown:** _______________
**Pass/Fail:** [ ]

---

## Test Suite 4: Completion Status

### ✅ TC-13: Completed Only - 7 CASES
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&show_completed=completed
```
**Expected:** Cases 214, 216, 217, 219, 220, 221, 225 (7 cases)
**Actual case IDs shown:** _______________
**Pass/Fail:** [ ]

---

### ✅ TC-14: In-Progress Only - 8 CASES
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&show_completed=in_progress
```
**Expected:** Cases 215, 218, 222, 223, 224, 226, 227, 228 (8 cases)
**Actual case IDs shown:** _______________
**Pass/Fail:** [ ]

---

## Test Suite 5: Units Range

### ✅ TC-12: Units Range (2-4) - 3 CASES
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&units_from=2&units_to=4
```
**Expected:** Cases 215 (3 units), 222 (3 jobs), 224 (2 units) - 3 cases
**Actual case IDs shown:** _______________
**Pass/Fail:** [ ]

---

### ✅ TC-12b: Many Units (6+) - 1 CASE
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&units_from=6&units_to=10
```
**Expected:** Case 220 only (6 units)
**Actual case IDs shown:** _______________
**Pass/Fail:** [ ]

---

## Test Suite 6: Job Type Filters

### ✅ EXTRA-01: Crowns Only - 10 CASES
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&job_type%5B%5D=1
```
**Expected:** Cases 214, 217, 218, 219, 221, 222, 223, 226, 227, 228 (10 cases)
**Actual case count:** _______________
**Pass/Fail:** [ ]

---

### ✅ EXTRA-02: Bridges Only - 3 CASES
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&job_type%5B%5D=2
```
**Expected:** Cases 215, 220, 224 (3 cases)
**Actual case IDs shown:** _______________
**Pass/Fail:** [ ]

---

### ✅ EXTRA-03: Implants Only - 1 CASE
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&job_type%5B%5D=6
```
**Expected:** Case 216 only
**Actual case IDs shown:** _______________
**Pass/Fail:** [ ]

---

## Test Suite 7: Edge Cases

### ✅ TC-19: No Results (Invalid Doctor) - 0 CASES
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=99999
```
**Expected:** "No cases found" message
**Actual result:** _______________
**Pass/Fail:** [ ]

---

### ✅ TC-21: Complex Combination - ~10 CASES
**Paste this URL:**
```
http://localhost:8000/reports/master?generate_report=1&from=2025-10-01&to=2025-10-29&doctor%5B%5D=all&material%5B%5D=all&job_type%5B%5D=all&status%5B%5D=all&amount_from=1&amount_to=200&show_completed=all
```
**Expected:** Cases with invoice 1-200 JOD
**Cases:** 214, 216, 217, 218, 219, 221, 223, 225, 227, 228
**Actual case IDs shown:** _______________
**Pass/Fail:** [ ]

---

## Quick Reference: Test Case Mapping

| Case ID | Client | Patient | Amount (JOD) | Stage | Status | Special |
|---------|--------|---------|--------------|-------|--------|---------|
| 214 | 2 | Test Patient A | 150 | Completed | Completed | Basic |
| 215 | 3 | Test Patient B | 450 | 3D Printing | In-Progress | 3 units |
| 216 | 5 | Test Patient C | 200 | Completed | Completed | Implant |
| 217 | 2 | Test Patient D | 100 | Completed | Completed | Rejected |
| 218 | 3 | Test Patient E | 120 | Pressing | In-Progress | Repeat |
| 219 | 6 | Test Patient F | 50 | Completed | Completed | Low amount |
| 220 | 7 | Test Patient G | 900 | Completed | Completed | High/6 units |
| 221 | 2 | Test Patient H | 180 | Completed | Completed | Delivery |
| 222 | 5 | Test Patient I | 380 | Multi-stage | In-Progress | 3 jobs |
| 223 | 3 | Test Patient J | 150 | Milling | In-Progress | Device |
| 224 | 6 | Test Patient K | 220 | 3D Printing | In-Progress | 2 units |
| 225 | 7 | Test Patient L | 160 | Completed | Completed | 30 days old |
| 226 | 2 | Test Patient M | 0 | Design | In-Progress | No invoice |
| 227 | 5 | Test Patient N | 140 | Finishing | In-Progress | Modification |
| 228 | 3 | Test Patient O | 170 | QC | In-Progress | Redo |

---

## Testing Tips

1. **Look for data-case-id attribute** in the table rows
2. **Count visible rows** to verify case count
3. **Check filter dropdowns** to ensure they're pre-selected correctly
4. **Watch for console errors** (F12 Developer Tools)
5. **Test in order** - start with TC-01 first

---

## Summary Tracking

**Tests Completed:** _____ / 21
**Tests Passed:** _____
**Tests Failed:** _____
**Pass Rate:** _____%

---

**Created:** October 29, 2025
**Test Cases:** 214-228 (15 total)
**Status:** Ready for manual browser testing
