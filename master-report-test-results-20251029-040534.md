# Master Report Test Results
**Test Date:** Wed Oct 29 04:05:34 +03 2025
**Base URL:** http://localhost:8000

---

## TC-01: Default Load

**URL:**
```
http://localhost:8000/reports/master?generate_report=1
```

**Expected Result:** All current month cases (14 cases: 199-209, 211-213)

**Actual Result:**
- Status: No results (empty table or error)
- Case Count: 0
- Case IDs: None

- **ERROR DETECTED:** Master Report

---

## TC-02: Specific Date Range (Old Case)

**URL:**
```
http://localhost:8000/reports/master?generate_report=1&from=2025-09-28&to=2025-09-30
```

**Expected Result:** Case 210 (30 days old)

**Actual Result:**
- Status: No cases found
- Case Count: 0
- Case IDs: None

- **ERROR DETECTED:** Master Report

---

## TC-03: Single Specific Doctor (Client 2)

**URL:**
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=2
```

**Expected Result:** Cases 199, 202, 206, 211 (4 cases)

**Actual Result:**
- Status: No results (empty table or error)
- Case Count: 0
- Case IDs: None

- **ERROR DETECTED:** Master Report

---

## TC-04: Multiple Specific Doctors (2, 3)

**URL:**
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=2&doctor%5B%5D=3
```

**Expected Result:** Cases 199, 200, 202, 203, 206, 208, 211, 213 (8 cases)

**Actual Result:**
- Status: No results (empty table or error)
- Case Count: 0
- Case IDs: None

- **ERROR DETECTED:** Master Report

---

## TC-05a: Workflow Stage - Finishing (6)

**URL:**
```
http://localhost:8000/reports/master?generate_report=1&status%5B%5D=6
```

**Expected Result:** Case 212 (1 case)

**Actual Result:**
- Status: No results (empty table or error)
- Case Count: 0
- Case IDs: None

- **ERROR DETECTED:** Master Report

---

## TC-05b: Workflow Stage - Design (1)

**URL:**
```
http://localhost:8000/reports/master?generate_report=1&status%5B%5D=1
```

**Expected Result:** Cases 207, 211 (2 cases)

**Actual Result:**
- Status: No results (empty table or error)
- Case Count: 0
- Case IDs: None

- **ERROR DETECTED:** Master Report

---

## TC-05c: Workflow Stage - 3D Printing (3)

**URL:**
```
http://localhost:8000/reports/master?generate_report=1&status%5B%5D=3
```

**Expected Result:** Cases 200, 207, 209 (3 cases)

**Actual Result:**
- Status: No results (empty table or error)
- Case Count: 0
- Case IDs: None

- **ERROR DETECTED:** Master Report

---

## TC-06: Combination Filters (Doctor+Material+JobType)

**URL:**
```
http://localhost:8000/reports/master?generate_report=1&doctor%5B%5D=2&material%5B%5D=1&job_type%5B%5D=1
```

**Expected Result:** Cases 199, 202, 206, 211 (4 cases)

