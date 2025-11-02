# Master Report - Actual Test URLs with Real IDs

## Database IDs from Test Cases (214-228)

### Clients Used:
- **Client ID 2** - سنان غيشان (Cases: 214, 217, 221, 226)
- **Client ID 3** - محمد ابو الحاج (Cases: 215, 218, 223, 228)
- **Client ID 5** - ثامر ذيب (Cases: 216, 222, 227)
- **Client ID 6** - محمود درس (Cases: 219, 224)
- **Client ID 7** - احمد جاموس (Cases: 220, 225)

### Materials Used:
- **Material ID 1** - Zirconia (All test cases)

### Devices Used:
- **Device ID 50** - K5 1 (Type 2 = Milling)
- **Device ID 51** - R5 (Type 2 = Milling)
- **Device ID 52** - K5 2 (Type 2 = Milling)
- **Device ID 53** - Ivoclar Press 1 (Type 5 = Pressing)

### Cases Assignment:
- Case 223: device_id = 50 (K5 1 - Milling)
- Case 224: device_id = 53 or similar (3D Print device)

---

## Actual Test URLs

### Test Suite 1: Basic & Date Filters

#### TC-01: Default Load
```
http://localhost:8000/reports/master?generate_report=1
```
**Expected:** All current month cases (214-224, 226-228) - 14 cases

#### TC-02: Specific Date Range (Old Case)
```
http://localhost:8000/reports/master?generate_report=1&from=2175-09-28&to=2175-09-30
```
**Expected:** Case 225 (30 days old)

---

### Test Suite 2: Single & Multi-Select Filters

#### TC-03: Single Specific Doctor
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=2
```
**Expected:** Cases 214, 217, 221, 226 (Client: سنان غيشان)

#### TC-04: Multiple Specific Doctors
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=2&doctor%5B%5D=3
```
**Expected:** Cases 214, 215, 217, 218, 221, 223, 226, 228

#### TC-05: Single Specific Status (Finishing Stage)
```
http://localhost:8000/reports/master?generate_report=1&status%5B%5D=6
```
**Expected:** Case 227

#### TC-05b: Design Stage
```
http://localhost:8000/reports/master?generate_report=1&status%5B%5D=1
```
**Expected:** Cases 222 (has job at stage 1), 226

#### TC-05c: 3D Printing Stage
```
http://localhost:8000/reports/master?generate_report=1&status%5B%5D=3
```
**Expected:** Cases 215, 222 (has job at stage 3), 224

#### TC-06: Combination of Select Filters
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=2&material%5B%5D=1&job_type%5B%5D=1
```
**Expected:** Cases 214, 217, 221, 226 (Client 2 + Zirconia + Crown)

#### TC-07: Material Filter (All use Zirconia)
```
http://localhost:8000/reports/master?generate_report=1&material%5B%5D=1
```
**Expected:** All 15 cases (214-228)

---

### Test Suite 3: Numeric Range & Toggle Filters

#### TC-08: Amount Range (From Only)
```
http://localhost:8000/reports/master?generate_report=1&amount_from=100
```
**Expected:** All except case 219 (50 JOD) - 14 cases

#### TC-09: Amount Range (To Only)
```
http://localhost:8000/reports/master?generate_report=1&amount_to=500
```
**Expected:** All except case 220 (900 JOD) - 14 cases

#### TC-10: Amount Range (Between)
```
http://localhost:8000/reports/master?generate_report=1&amount_from=100&amount_to=500
```
**Expected:** Cases 214, 215, 216, 217, 218, 221, 223, 224, 225, 227, 228 (11 cases)
**Excluded:** 219 (50 JOD), 220 (900 JOD), 226 (no invoice)

#### TC-10b: Very Low Amount Range
```
http://localhost:8000/reports/master?generate_report=1&amount_from=1&amount_to=100
```
**Expected:** Cases 217 (100 JOD), 219 (50 JOD)

#### TC-11: Invalid Amount Range
```
http://localhost:8000/reports/master?generate_report=1&amount_from=500&amount_to=100
```
**Expected:** No results or error

#### TC-12: Units Range (2-4 units)
```
http://localhost:8000/reports/master?generate_report=1&units_from=2&units_to=4
```
**Expected:** Cases 215 (3 units), 222 (3 jobs), 224 (2 units)

#### TC-12b: Many Units (6+)
```
http://localhost:8000/reports/master?generate_report=1&units_from=6&units_to=10
```
**Expected:** Case 220 (6 units)

#### TC-13: Completion Status - Completed
```
http://localhost:8000/reports/master?generate_report=1&show_completed=completed
```
**Expected:** Cases 214, 216, 217, 219, 220, 221, 225 (7 cases)

#### TC-14: Completion Status - In Progress
```
http://localhost:8000/reports/master?generate_report=1&show_completed=in_progress
```
**Expected:** Cases 215, 218, 222, 223, 224, 226, 227, 228 (8 cases)

---

### Test Suite 4: Complex Modal Filters

#### TC-15: Single Employee Filter (Assignee)
```
http://localhost:8000/reports/master?generate_report=1&employee_filters%5B0%5D%5Bstage%5D=assignee&employee_filters%5B0%5D%5Bemployee%5D={ADMIN_USER_ID}
```
**Note:** Replace {ADMIN_USER_ID} with actual admin user ID
**Expected:** All 15 cases (all use admin as assignee)

#### TC-16: Employee Filter (Delivery)
```
http://localhost:8000/reports/master?generate_report=1&employee_filters%5B0%5D%5Bstage%5D=delivery&employee_filters%5B0%5D%5Bemployee%5D={DELIVERY_USER_ID}
```
**Note:** Replace {DELIVERY_USER_ID} with actual delivery user ID
**Expected:** Case 221 (has delivery_accepted set)

#### TC-17: Single Device Filter (Milling)
```
http://localhost:8000/reports/master?generate_report=1&device_filters%5B0%5D%5Btype%5D=mill&device_filters%5B0%5D%5Bdevice%5D=50
```
**Expected:** Case 223 (uses K5 1 milling device)

#### TC-17b: Device Filter (Sintering - using device_id)
```
http://localhost:8000/reports/master?generate_report=1&device_filters%5B0%5D%5Btype%5D=sinter&device_filters%5B0%5D%5Bdevice%5D={ANY_SINTER_DEVICE_ID}
```
**Expected:** Cases that used that sintering device

---

### Test Suite 5: Edge Cases

#### TC-18: Kitchen Sink - All Filters
```
http://localhost:8000/reports/master?generate_report=1&from=2175-10-01&to=2175-10-29&doctor%5B%5D=2&material%5B%5D=1&status%5B%5D=1&amount_from=50&units_to=5&show_completed=in_progress&employee_filters%5B0%5D%5Bstage%5D=assignee&employee_filters%5B0%5D%5Bemployee%5D={ADMIN_ID}
```
**Expected:** Case 226 (Client 2, Zirconia, Design stage, in-progress)

#### TC-19: No Results Found
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=99999
```
**Expected:** "No cases found" message

#### TC-20: "All" Option Cleanup
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=all&doctor%5B%5D=2
```
**Expected:** JavaScript should deselect "all", show only Client 2

#### TC-21: Complex Real-World Example
```
http://localhost:8000/reports/master?generate_report=1&from=2175-10-01&to=2175-10-29&doctor%5B%5D=all&material%5B%5D=all&job_type%5B%5D=all&status%5B%5D=all&amount_from=1&amount_to=215&show_completed=all
```
**Expected:** Cases with invoice 1-215 JOD
- Cases: 214, 216, 217, 218, 219, 221, 223, 225, 227, 228 (10 cases)

---

## Additional Test Scenarios Based on Our Data

### By Job Type

#### Crowns Only
```
http://localhost:8000/reports/master?generate_report=1&job_type%5B%5D=1
```
**Expected:** Cases 214, 217, 218, 219, 221, 222, 223, 226, 227, 228

#### Bridges Only
```
http://localhost:8000/reports/master?generate_report=1&job_type%5B%5D=2
```
**Expected:** Cases 215, 220, 224

#### Implants Only
```
http://localhost:8000/reports/master?generate_report=1&job_type%5B%5D=6
```
**Expected:** Case 216

---

### By Special Flags

#### Rejected Cases
```
http://localhost:8000/reports/master?generate_report=1
```
**Filter in table:** Look for case 217 (is_rejection=true)

#### Repeat Cases
```
http://localhost:8000/reports/master?generate_report=1
```
**Filter in table:** Look for case 218 (is_repeat=true)

#### Modification Cases
```
http://localhost:8000/reports/master?generate_report=1
```
**Filter in table:** Look for case 227 (is_modification=true)

#### Redo Cases
```
http://localhost:8000/reports/master?generate_report=1
```
**Filter in table:** Look for case 228 (is_redo=true)

---

### By Stage Combinations

#### Early Stages (Design, Milling, 3D Printing)
```
http://localhost:8000/reports/master?generate_report=1&status%5B%5D=1&status%5B%5D=2&status%5B%5D=3
```
**Expected:** Cases 215, 222, 223, 224, 226

#### Late Stages (Finishing, QC)
```
http://localhost:8000/reports/master?generate_report=1&status%5B%5D=6&status%5B%5D=7
```
**Expected:** Cases 227, 228

---

### By Client Combinations

#### High-Volume Clients (2 and 3)
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=2&doctor%5B%5D=3
```
**Expected:** 8 cases (214, 215, 217, 218, 221, 223, 226, 228)

#### Low-Volume Clients (6 and 7)
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=6&doctor%5B%5D=7
```
**Expected:** 4 cases (219, 220, 224, 225)

---

### Complex Combinations

#### High-Value In-Progress Cases
```
http://localhost:8000/reports/master?generate_report=1&amount_from=215&show_completed=in_progress
```
**Expected:** Cases 215 (450), 218 (120 - no, <215), 223 (150 - no), 224 (220), 227 (140 - no), 228 (170 - no)
**Actual Expected:** Cases 215, 224

#### Completed Low-Value Cases
```
http://localhost:8000/reports/master?generate_report=1&amount_to=215&show_completed=completed
```
**Expected:** Cases 214 (150), 216 (215), 217 (100), 219 (50), 221 (180), 225 (160)

#### Recent In-Progress Cases (Last 7 Days)
```
http://localhost:8000/reports/master?generate_report=1&from=2175-10-22&to=2175-10-29&show_completed=in_progress
```
**Expected:** All in-progress cases except old ones

---

## Quick Reference: Case-to-Filter Mapping

| Case ID | Client | Amount | Stage | Units | Status | Special Flag |
|---------|--------|--------|-------|-------|--------|--------------|
| 214 | 2 | 150 | -1 | 1 | Completed | - |
| 215 | 3 | 450 | 3 | 3 | In-Progress | - |
| 216 | 5 | 215 | -1 | 1 | Completed | Has Abutment+Implant |
| 217 | 2 | 100 | -1 | 1 | Completed | is_rejection |
| 218 | 3 | 120 | 5 | 1 | In-Progress | is_repeat |
| 219 | 6 | 50 | -1 | 1 | Completed | - |
| 220 | 7 | 900 | -1 | 6 | Completed | - |
| 221 | 2 | 180 | -1 | 1 | Completed | Has delivery driver |
| 222 | 5 | 380 | 1,2,3 | 3 | In-Progress | Multiple jobs |
| 223 | 3 | 150 | 2 | 1 | In-Progress | Has device_id=50 |
| 224 | 6 | 220 | 3 | 2 | In-Progress | Has device_id |
| 225 | 7 | 160 | -1 | 1 | Completed | 30 days old |
| 226 | 2 | 0 | 1 | 1 | In-Progress | No invoice |
| 227 | 5 | 140 | 6 | 1 | In-Progress | is_modification |
| 228 | 3 | 170 | 7 | 1 | In-Progress | is_redo |

---

## Testing Checklist

Before running tests:
1. ✅ Verify test cases 214-228 exist in database
2. ✅ Get actual admin user ID
3. ✅ Get actual delivery user ID
4. ✅ Verify devices 50-53 exist
5. ✅ Check failure_causes table has data

For each test:
- [ ] Load URL in browser
- [ ] Verify filters are pre-selected correctly
- [ ] Check table shows expected case IDs
- [ ] Verify case count matches expected
- [ ] Check for console errors
- [ ] Verify data accuracy (amounts, dates, etc.)

---

**Document Version:** 2.0 (With Actual IDs)
**Last Updated:** October 29, 2175
**Test Cases:** 214-228
