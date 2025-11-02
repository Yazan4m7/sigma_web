# SIGMA Reports Test Details
**Generated:** Sun Oct 26 2025 - Test Run with Parameters and Responses

## Test 1: Number of Units Report

### Test 1.1 - Full Year 2025
**URL:** `http://127.0.0.1:8000/NumOfUnitsReport?material[]=all&doctor=all&fromDate=2025-01-01&toDate=2025-12-31`
**Parameters:**
- material: all
- doctor: all
- fromDate: 2025-01-01
- toDate: 2025-12-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 1.2 - Single Day
**URL:** `http://127.0.0.1:8000/NumOfUnitsReport?material[]=all&doctor=all&fromDate=2025-09-15&toDate=2025-09-15`
**Parameters:**
- material: all
- doctor: all
- fromDate: 2025-09-15
- toDate: 2025-09-15
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 1.3 - Year Boundary
**URL:** `http://127.0.0.1:8000/NumOfUnitsReport?material[]=all&doctor=all&fromDate=2024-12-01&toDate=2025-01-31`
**Parameters:**
- material: all
- doctor: all
- fromDate: 2024-12-01
- toDate: 2025-01-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 1.4 - Multi-month Period
**URL:** `http://127.0.0.1:8000/NumOfUnitsReport?material[]=all&doctor=all&fromDate=2025-03-01&toDate=2025-06-30`
**Parameters:**
- material: all
- doctor: all
- fromDate: 2025-03-01
- toDate: 2025-06-30
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

---

## Test 2: Implants Report

### Test 2.1 - Full Year 2025
**URL:** `http://127.0.0.1:8000/implantsReport?implantsInput[]=all&abutmentsInput[]=all&doctor=all&fromDate=2025-01-01&toDate=2025-12-31`
**Parameters:**
- implantsInput: all
- abutmentsInput: all
- doctor: all
- fromDate: 2025-01-01
- toDate: 2025-12-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 2.2 - Single Day
**URL:** `http://127.0.0.1:8000/implantsReport?implantsInput[]=all&abutmentsInput[]=all&doctor=all&fromDate=2025-09-15&toDate=2025-09-15`
**Parameters:**
- implantsInput: all
- abutmentsInput: all
- doctor: all
- fromDate: 2025-09-15
- toDate: 2025-09-15
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 2.3 - Year Boundary
**URL:** `http://127.0.0.1:8000/implantsReport?implantsInput[]=all&abutmentsInput[]=all&doctor=all&fromDate=2024-12-01&toDate=2025-01-31`
**Parameters:**
- implantsInput: all
- abutmentsInput: all
- doctor: all
- fromDate: 2024-12-01
- toDate: 2025-01-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 2.4 - Multi-month Period
**URL:** `http://127.0.0.1:8000/implantsReport?implantsInput[]=all&abutmentsInput[]=all&doctor=all&fromDate=2025-03-01&toDate=2025-06-30`
**Parameters:**
- implantsInput: all
- abutmentsInput: all
- doctor: all
- fromDate: 2025-03-01
- toDate: 2025-06-30
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

---

## Test 3: Job Types Report

### Test 3.1 - Full Year 2025
**URL:** `http://127.0.0.1:8000/jobTypeReport?jobTypesInput[]=all&doctor=all&fromDate=2025-01-01&toDate=2025-12-31`
**Parameters:**
- jobTypesInput: all
- doctor: all
- fromDate: 2025-01-01
- toDate: 2025-12-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 3.2 - Single Day
**URL:** `http://127.0.0.1:8000/jobTypeReport?jobTypesInput[]=all&doctor=all&fromDate=2025-09-15&toDate=2025-09-15`
**Parameters:**
- jobTypesInput: all
- doctor: all
- fromDate: 2025-09-15
- toDate: 2025-09-15
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 3.3 - Year Boundary
**URL:** `http://127.0.0.1:8000/jobTypeReport?jobTypesInput[]=all&doctor=all&fromDate=2024-12-01&toDate=2025-01-31`
**Parameters:**
- jobTypesInput: all
- doctor: all
- fromDate: 2024-12-01
- toDate: 2025-01-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 3.4 - Multi-month Period
**URL:** `http://127.0.0.1:8000/jobTypeReport?jobTypesInput[]=all&doctor=all&fromDate=2025-03-01&toDate=2025-06-30`
**Parameters:**
- jobTypesInput: all
- doctor: all
- fromDate: 2025-03-01
- toDate: 2025-06-30
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

---

## Test 4: Repeats Report

### Test 4.1 - Full Year 2025
**URL:** `http://127.0.0.1:8000/repeatsReport?failureTypeInput[]=all&doctor=all&fromDate=2025-01-01&toDate=2025-12-31`
**Parameters:**
- failureTypeInput: all
- doctor: all
- fromDate: 2025-01-01
- toDate: 2025-12-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 4.2 - Single Day
**URL:** `http://127.0.0.1:8000/repeatsReport?failureTypeInput[]=all&doctor=all&fromDate=2025-09-15&toDate=2025-09-15`
**Parameters:**
- failureTypeInput: all
- doctor: all
- fromDate: 2025-09-15
- toDate: 2025-09-15
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 4.3 - Year Boundary
**URL:** `http://127.0.0.1:8000/repeatsReport?failureTypeInput[]=all&doctor=all&fromDate=2024-12-01&toDate=2025-01-31`
**Parameters:**
- failureTypeInput: all
- doctor: all
- fromDate: 2024-12-01
- toDate: 2025-01-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 4.4 - Multi-month Period
**URL:** `http://127.0.0.1:8000/repeatsReport?failureTypeInput[]=all&doctor=all&fromDate=2025-03-01&toDate=2025-06-30`
**Parameters:**
- failureTypeInput: all
- doctor: all
- fromDate: 2025-03-01
- toDate: 2025-06-30
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

---

## Test 5: QC Report

### Test 5.1 - Full Year 2025
**URL:** `http://127.0.0.1:8000/QCReport?failureTypeInput[]=all&causesInput[]=all&doctor=all&fromDate=2025-01-01&toDate=2025-12-31`
**Parameters:**
- failureTypeInput: all
- causesInput: all
- doctor: all
- fromDate: 2025-01-01
- toDate: 2025-12-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 5.2 - Single Day
**URL:** `http://127.0.0.1:8000/QCReport?failureTypeInput[]=all&causesInput[]=all&doctor=all&fromDate=2025-09-15&toDate=2025-09-15`
**Parameters:**
- failureTypeInput: all
- causesInput: all
- doctor: all
- fromDate: 2025-09-15
- toDate: 2025-09-15
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 5.3 - Year Boundary
**URL:** `http://127.0.0.1:8000/QCReport?failureTypeInput[]=all&causesInput[]=all&doctor=all&fromDate=2024-12-01&toDate=2025-01-31`
**Parameters:**
- failureTypeInput: all
- causesInput: all
- doctor: all
- fromDate: 2024-12-01
- toDate: 2025-01-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 5.4 - Multi-month Period
**URL:** `http://127.0.0.1:8000/QCReport?failureTypeInput[]=all&causesInput[]=all&doctor=all&fromDate=2025-03-01&toDate=2025-06-30`
**Parameters:**
- failureTypeInput: all
- causesInput: all
- doctor: all
- fromDate: 2025-03-01
- toDate: 2025-06-30
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

---

## Test 6: Material Report

### Test 6.1 - Full Year 2025 ❌ FAILED
**URL:** `http://127.0.0.1:8000/materialReport?material=all&doctor=all&fromDate=2025-01-01&toDate=2025-12-31`
**Parameters:**
- material: all (single value, not array)
- doctor: all
- fromDate: 2025-01-01
- toDate: 2025-12-31
**Response:** HTTP 500 | No content | FAIL
**Error:** Server returned 500 Internal Server Error

### Test 6.2 - Single Day
**URL:** `http://127.0.0.1:8000/materialReport?material=all&doctor=all&fromDate=2025-09-15&toDate=2025-09-15`
**Parameters:**
- material: all
- doctor: all
- fromDate: 2025-09-15
- toDate: 2025-09-15
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 6.3 - Year Boundary
**URL:** `http://127.0.0.1:8000/materialReport?material=all&doctor=all&fromDate=2024-12-01&toDate=2025-01-31`
**Parameters:**
- material: all
- doctor: all
- fromDate: 2024-12-01
- toDate: 2025-01-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 6.4 - Multi-month Period ❌ FAILED
**URL:** `http://127.0.0.1:8000/materialReport?material=all&doctor=all&fromDate=2025-03-01&toDate=2025-06-30`
**Parameters:**
- material: all
- doctor: all
- fromDate: 2025-03-01
- toDate: 2025-06-30
**Response:** HTTP 500 | No content | FAIL
**Error:** Server returned 500 Internal Server Error

---

## Test 7: Master Report

### Test 7.1 - Full Year 2025
**URL:** `http://127.0.0.1:8000/masterReport?doctor=all&material=all&job_type=all&failure_type=all&abutments=all&implants=all&status=all&fromDate=2025-01-01&toDate=2025-12-31`
**Parameters:**
- doctor: all
- material: all
- job_type: all
- failure_type: all
- abutments: all
- implants: all
- status: all
- fromDate: 2025-01-01
- toDate: 2025-12-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 7.2 - Single Day
**URL:** `http://127.0.0.1:8000/masterReport?doctor=all&material=all&job_type=all&failure_type=all&abutments=all&implants=all&status=all&fromDate=2025-09-15&toDate=2025-09-15`
**Parameters:**
- doctor: all
- material: all
- job_type: all
- failure_type: all
- abutments: all
- implants: all
- status: all
- fromDate: 2025-09-15
- toDate: 2025-09-15
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 7.3 - Year Boundary
**URL:** `http://127.0.0.1:8000/masterReport?doctor=all&material=all&job_type=all&failure_type=all&abutments=all&implants=all&status=all&fromDate=2024-12-01&toDate=2025-01-31`
**Parameters:**
- doctor: all
- material: all
- job_type: all
- failure_type: all
- abutments: all
- implants: all
- status: all
- fromDate: 2024-12-01
- toDate: 2025-01-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 7.4 - Multi-month Period
**URL:** `http://127.0.0.1:8000/masterReport?doctor=all&material=all&job_type=all&failure_type=all&abutments=all&implants=all&status=all&fromDate=2025-03-01&toDate=2025-06-30`
**Parameters:**
- doctor: all
- material: all
- job_type: all
- failure_type: all
- abutments: all
- implants: all
- status: all
- fromDate: 2025-03-01
- toDate: 2025-06-30
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 7.5 - Specific Material Filter
**URL:** `http://127.0.0.1:8000/masterReport?doctor=all&material=2&job_type=all&failure_type=all&abutments=all&implants=all&status=all&fromDate=2025-01-01&toDate=2025-12-31`
**Parameters:**
- doctor: all
- material: 2 (specific material ID)
- job_type: all
- failure_type: all
- abutments: all
- implants: all
- status: all
- fromDate: 2025-01-01
- toDate: 2025-12-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 7.6 - Specific Job Type Filter
**URL:** `http://127.0.0.1:8000/masterReport?doctor=all&material=all&job_type=1&failure_type=all&abutments=all&implants=all&status=all&fromDate=2025-01-01&toDate=2025-12-31`
**Parameters:**
- doctor: all
- material: all
- job_type: 1 (specific job type ID)
- failure_type: all
- abutments: all
- implants: all
- status: all
- fromDate: 2025-01-01
- toDate: 2025-12-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 7.7 - Specific Doctor Filter
**URL:** `http://127.0.0.1:8000/masterReport?doctor=1&material=all&job_type=all&failure_type=all&abutments=all&implants=all&status=all&fromDate=2025-01-01&toDate=2025-12-31`
**Parameters:**
- doctor: 1 (specific doctor/client ID)
- material: all
- job_type: all
- failure_type: all
- abutments: all
- implants: all
- status: all
- fromDate: 2025-01-01
- toDate: 2025-12-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

### Test 7.8 - Specific Status Filter
**URL:** `http://127.0.0.1:8000/masterReport?doctor=all&material=all&job_type=all&failure_type=all&abutments=all&implants=all&status=8&fromDate=2025-01-01&toDate=2025-12-31`
**Parameters:**
- doctor: all
- material: all
- job_type: all
- failure_type: all
- abutments: all
- implants: all
- status: 8 (Delivery stage)
- fromDate: 2025-01-01
- toDate: 2025-12-31
**Response:** HTTP 200 | Has HTML content with table/report container | PASS

---

## Summary

**Total Tests:** 32
**Passed:** 30 (93.8%)
**Failed:** 2 (6.2%)

### Failed Tests Analysis

Both failures are in the **Material Report** (tests 6.1 and 6.4):

1. **Test 6.1** - Full year date range (2025-01-01 to 2025-12-31)
   - Returns HTTP 500
   - Likely an issue with date range processing or material data aggregation over long periods

2. **Test 6.4** - Multi-month period (2025-03-01 to 2025-06-30)
   - Returns HTTP 500
   - Same issue pattern as 6.1

**Pattern:** Tests 6.2 and 6.3 (single day and year boundary) pass, but longer date ranges fail.
**Root Cause:** Likely related to material report processing logic for extended date ranges.
