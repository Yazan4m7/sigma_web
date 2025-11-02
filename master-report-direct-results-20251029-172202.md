# Master Report Direct Database Test Results

**Test Date:** 2025-10-29 17:22:02
**Total Tests:** 17
**Tests Passed:** 11 ✅
**Tests Failed:** 6 ❌
**Pass Rate:** 64.7%

---

## TC-01: Default Load

**Expected:** All current month cases

**Actual Result:**
- Case Count: 224
- Case IDs: 1, 2, 3, 4, 5, 7, 8, 9, 10, 11, 12, 15, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 137, 138, 139, 140, 141, 142, 143, 144, 145, 146, 147, 148, 149, 150, 151, 152, 153, 154, 155, 156, 157, 158, 159, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 206, 207, 208, 209, 210, 211, 212, 213, 214, 215, 216, 217, 218, 219, 220, 221, 222, 223, 224, 225, 226, 227, 228

---

## TC-02: Specific Date Range (Old Case)

**Expected:** Case 225

**Actual Result:**
- Case Count: 0
- Case IDs: 

---

## TC-03: Single Doctor (Client 2)

**Expected:** Cases 214, 217, 221, 226 (4 cases)

**Actual Result:**
- Case Count: 4
- Case IDs: 214, 217, 221, 226

---

## TC-04: Multiple Doctors (2, 3)

**Expected:** Cases 214, 215, 217, 218, 221, 223, 226, 228 (8 cases)

**Actual Result:**
- Case Count: 8
- Case IDs: 214, 215, 217, 218, 221, 223, 226, 228

---

## TC-05a: Workflow Stage - Finishing

**Expected:** Case 227 (1 case)

**Actual Result:**
- Case Count: 1
- Case IDs: 227

---

## TC-05b: Workflow Stage - Design

**Expected:** Cases 222, 226 (2 cases)

**Actual Result:**
- Case Count: 2
- Case IDs: 222, 226

---

## TC-05c: Workflow Stage - 3D Printing

**Expected:** Cases 215, 222, 224 (3 cases)

**Actual Result:**
- Case Count: 3
- Case IDs: 215, 222, 224

---

## TC-08: Amount Range - From Only (>=100)

**Expected:** All except 219 (14 cases)

**Result:** ERROR
**Error:** SQLSTATE[42S22]: Column not found: 1054 Unknown column 'total_cost' in 'where clause' (SQL: select * from `cases` where exists (select * from `invoices` where `cases`.`id` = `invoices`.`case_id` and `total_cost` >= 100 and `invoices`.`deleted_at` is null) and `id` between 214 and 228 and `cases`.`deleted_at` is null order by `id` asc)

---

## TC-09: Amount Range - To Only (<=500)

**Expected:** All except 220 (14 cases)

**Result:** ERROR
**Error:** SQLSTATE[42S22]: Column not found: 1054 Unknown column 'total_cost' in 'where clause' (SQL: select * from `cases` where exists (select * from `invoices` where `cases`.`id` = `invoices`.`case_id` and `total_cost` <= 500 and `invoices`.`deleted_at` is null) and `id` between 214 and 228 and `cases`.`deleted_at` is null order by `id` asc)

---

## TC-10: Amount Range - Between (100-500)

**Expected:** Cases 214, 215, 216, 217, 218, 221, 223, 224, 225, 227, 228 (11 cases)

**Result:** ERROR
**Error:** SQLSTATE[42S22]: Column not found: 1054 Unknown column 'total_cost' in 'where clause' (SQL: select * from `cases` where exists (select * from `invoices` where `cases`.`id` = `invoices`.`case_id` and `total_cost` between 100 and 500 and `invoices`.`deleted_at` is null) and `id` between 214 and 228 and `cases`.`deleted_at` is null order by `id` asc)

---

## TC-10b: Low Amount Range (1-100)

**Expected:** Cases 217, 219 (2 cases)

**Result:** ERROR
**Error:** SQLSTATE[42S22]: Column not found: 1054 Unknown column 'total_cost' in 'where clause' (SQL: select * from `cases` where exists (select * from `invoices` where `cases`.`id` = `invoices`.`case_id` and `total_cost` between 1 and 100 and `invoices`.`deleted_at` is null) and `id` between 214 and 228 and `cases`.`deleted_at` is null order by `id` asc)

---

## TC-12: Units Range (2-4)

**Expected:** Cases 215, 222, 224 (3 cases)

**Actual Result:**
- Case Count: 3
- Case IDs: 215, 222, 224

---

## TC-13: Completion Status - Completed

**Expected:** Cases 214, 216, 217, 219, 220, 221, 225 (7 cases)

**Result:** ERROR
**Error:** SQLSTATE[42S22]: Column not found: 1054 Unknown column 'is_completed' in 'where clause' (SQL: select * from `cases` where `is_completed` = 1 and `id` between 214 and 228 and `cases`.`deleted_at` is null order by `id` asc)

---

## TC-14: Completion Status - In Progress

**Expected:** Cases 215, 218, 222, 223, 224, 226, 227, 228 (8 cases)

**Result:** ERROR
**Error:** SQLSTATE[42S22]: Column not found: 1054 Unknown column 'is_completed' in 'where clause' (SQL: select * from `cases` where (`is_completed` = 0 or `is_completed` is null) and `id` between 214 and 228 and `cases`.`deleted_at` is null order by `id` asc)

---

## EXTRA-01: Job Type - Crowns Only

**Expected:** 10 cases

**Actual Result:**
- Case Count: 11
- Case IDs: 214, 217, 218, 219, 221, 222, 223, 225, 226, 227, 228

---

## EXTRA-02: Job Type - Bridges Only

**Expected:** Cases 215, 220, 224 (3 cases)

**Actual Result:**
- Case Count: 3
- Case IDs: 215, 220, 224

---

## EXTRA-03: Job Type - Implants Only

**Expected:** Case 216 (1 case)

**Actual Result:**
- Case Count: 1
- Case IDs: 216

---

