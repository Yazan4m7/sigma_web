# SIGMA Database Operations Guide

This document details what database changes occur when users perform operations in the SIGMA dental lab management system.

---

## 1. ASSIGN TO ME
**Route**: `/assign-case/{caseId}/{stage}`
**Controller**: `CaseController@assignToMe` (line 1102)
**User Action**: Employee clicks "Assign to Me" button in operations dashboard

### Database Changes:

#### `jobs` table:
- `assignee` → Set to current user ID (Auth()->user()->id)
- `is_set` → Set to `1`
- `is_active` → Set to `1` (except for stages 2 and 3: Milling and 3D Printing)

#### `case_logs` table:
- New record created with:
  - `user_id` → Current user ID
  - `case_id` → Case ID
  - `stage` → Varies by stage (see sub-stage mapping below)
  - `is_completion` → `0` (assignment, not completion)

### Sub-Stage Mapping for Logs:
- Stage 2 (Milling) → `2.1` (MILLING_SET)
- Stage 3 (3D Printing) → `3.1` (PRINTING_SET)
- Stage 4 (Sintering) → `4.1` (SINTERING_SET)
- Stage 5 (Pressing) → `5.1` (PRESSING_START)
- Stage 8 (Delivery) → `8.1` (DELIVERY_ASSIGN)
- Other stages → Same as stage number

---

## 2. SET (Batch Operation)
**Route**: `/set-multiple-cases` or `/set-on-device`
**Controller**: `OperationsUpgrade@setMultipleCases` (line 170)
**User Action**: Employee selects multiple cases from "Waiting" tab and sets them on a device (Milling/3D Printing/Sintering/Pressing)

### Database Changes:

#### `builds` table:
- New record created with:
  - `name` → Build name from user input (or auto-generated for Sintering: "Sintering-{id}")
  - `device_used` → Selected device ID
  - `set_at` → Current timestamp
  - `started_at` → Current timestamp (for Sintering only, others remain NULL until activated)

#### `jobs` table (for all selected jobs):
- `is_set` → Set to `1`
- `is_active` → Set to `0` (except Sintering which is set to `1`)
- `device_id` → Selected device ID
- `assignee` → Current user ID
- `milling_build_id` → Build ID (if stage 2 - Milling)
- `printing_build_id` → Build ID (if stage 3 - 3D Printing)
- `sintering_build_id` → Build ID (if stage 4 - Sintering)
- `pressing_build_id` → Build ID (if stage 5 - Pressing)
- `type_id` → Material type ID (if provided, for 3D Printing)

#### `case_logs` table:
- One record per case (not per job) with:
  - `user_id` → Current user ID
  - `case_id` → Case ID
  - `stage` → Sub-stage (2.1, 3.1, 4.1, or 5.1)
  - `is_completion` → `0`

---

## 3. ACTIVATE/START (Batch Operation)
**Route**: `/activate-multiple-cases`
**Controller**: `OperationsUpgrade@activateMultipleCases` (line 293)
**User Action**: Employee clicks "Start" button on a build to begin processing

### Database Changes:

#### `builds` table:
- `started_at` → Current timestamp (if previously NULL)

#### `jobs` table (for all jobs in the build):
- `is_active` → Set to `1`

#### `case_logs` table:
- One record per case with:
  - `user_id` → Current user ID
  - `case_id` → Case ID
  - `stage` → Sub-stage (2.2, 3.2, 4.2, or 5.2)
  - `is_completion` → `0`

### Sub-Stage Mapping:
- Stage 2 → `2.2` (MILLING_START)
- Stage 3 → `3.2` (PRINTING_START)
- Stage 4 → `4.2` (SINTERING_START)
- Stage 5 → `5.2` (PRESSING_START)

---

## 4. FINISH/COMPLETE CASE
**Route**: `/finish-case/{caseId}/{stage}`
**Controller**: `CaseController@finishCaseStage` (line 1158)
**User Action**: Employee clicks "Complete" or "Finish" button for a case in their active tab

### Database Changes:

#### `jobs` table:
- `assignee` → Set to `NULL`
- `stage` → Incremented to next stage (see stage progression below)
- `is_active` → Set to `NULL`
- `is_set` → Set to `NULL`
- `device_id` → Set to `NULL`

#### Special Case - Moving to QC (Stage 7):
**Condition**: Only if ALL jobs in the case are in Finishing (stage 6)
- If condition not met → Error: "Not all jobs are in finishing stage"
- If condition met → Jobs move to stage 7

#### Special Case - Completing Case (Moving to -1):
When finishing from Delivery (stage 8), final stage is -1 (completed):

##### `cases` table:
- `delivered_to_client` → Set to `1`
- `actual_delivery_date` → Set based on case type:
  - **Modification cases** (`contains_modification = 1`):
    - Looks up `failure_logs` table for `old_delivery_date`
    - Sets `actual_delivery_date` to the original delivery date
  - **Repeat cases** (`first_case_if_repeated` IS NOT NULL):
    - Looks up original case's `actual_delivery_date`
    - Preserves the original delivery date
  - **Normal cases**:
    - Sets `actual_delivery_date` to current timestamp

##### `notes` table:
- New note created documenting delivery type and date preservation

##### `invoices` table (via `applyInvoice` function):
- `status` → Set to `1`
- `date_applied` → Current timestamp

##### `clients` table:
- `balance` → Increased by invoice amount

#### `case_logs` table:
- One record per case with:
  - `user_id` → Current user ID
  - `case_id` → Case ID
  - `stage` → Sub-stage for completion (see mapping below)
  - `is_completion` → `1`

### Stage Progression:
- Stage 1 (Design) → Stage 2 (Milling)
- Stage 2 (Milling) → Stage 3 (3D Printing) OR Stage 4 (Sintering)
- Stage 3 (3D Printing) → Stage 4 (Sintering)
- Stage 4 (Sintering) → Stage 5 (Pressing) OR Stage 6 (Finishing)
- Stage 5 (Pressing) → Stage 6 (Finishing)
- Stage 6 (Finishing) → Stage 7 (QC) *only if all jobs are ready*
- Stage 7 (QC) → Stage 8 (Delivery)
- Stage 8 (Delivery) → Stage -1 (Completed)

### Completion Sub-Stage Mapping:
- Stage 1 → `1` (DESIGN_COMPLETE)
- Stage 2 → `2.3` (MILLING_COMPLETE)
- Stage 3 → `3.3` (PRINTING_COMPLETE)
- Stage 4 → `4.3` (SINTERING_COMPLETE)
- Stage 5 → `5.3` (PRESSING_COMPLETE)
- Stage 6 → `6` (FINISHING_COMPLETE)
- Stage 7 → `7` (QC_COMPLETE)
- Stage 8 → `8.3` (DELIVERY_COMPLETE)

---

## 5. ASSIGN AND FINISH
**Route**: `/assign-and-finish-case/{caseId}/{stage}`
**Controller**: `CaseController@assignAndFinish` (line 1139)
**User Action**: Employee clicks "Assign & Finish" (completes case without assignment)

### Database Changes:
This operation performs **BOTH** operations in sequence:
1. First executes "Assign to Me" (see Section 1)
2. Then executes "Finish Case" (see Section 4)

---

## 6. DELIVERED IN BOX
**Route**: `/finish-case/{caseId}` (no stage parameter)
**Controller**: `CaseController@deliveredInBox` (line 1322)
**User Action**: Delivery employee marks case as delivered to client in-box

### Database Changes:

#### `jobs` table:
- `assignee` → Set to `NULL`
- `stage` → Set to `-1` (completed)

#### `cases` table:
- `actual_delivery_date` → Current timestamp
- `delivered_to_client` → Set to `1`

#### `invoices` table (via `applyInvoice`):
- `status` → Set to `1`
- `date_applied` → Current timestamp

#### `clients` table:
- `balance` → Increased by invoice amount

#### `case_logs` table:
- New record with:
  - `user_id` → Current user ID
  - `case_id` → Case ID
  - `stage` → `8.3` (DELIVERY_COMPLETE)
  - `is_completion` → `1`

---

## 7. FINISH CASE COMPLETELY (Admin Override)
**Route**: `/finish-case-completely/{caseId}`
**Controller**: `CaseController@finishCaseCompletely` (line 1935)
**User Action**: Admin forcefully completes all stages for a case

### Database Changes:

#### `jobs` table (all jobs in the case):
- `stage` → Set to `8` (Delivery)
- `assignee` → Current user ID
- `delivery_accepted` → Current user ID

#### `case_logs` table:
Creates **multiple log entries** simulating progression through all stages:
- Completion logs for stages: 1, 2.3, 3.3, 4.3, 5.3, 6, 7, 8.3 (is_completion = 1)
- Assignment logs for stages: 1, 1, 2.1, 3.1, 4.1, 5.1, 6, 7, 8.1 (is_completion = 0)

---

## 8. SEND CASE TO DELIVERY
**Route**: `/send-to-delivery/{caseId}`
**Controller**: `CaseController@sendCaseToDelivery` (line 1146)
**User Action**: Admin/QC manually sends case to delivery without normal progression

### Database Changes:

#### `jobs` table (all jobs in the case):
- `stage` → Set to `8` (Delivery)
- `assignee` → Set to `NULL`

---

## Summary Tables

### Jobs Table Attribute Changes by Operation:

| Operation | assignee | stage | is_set | is_active | device_id | build_id |
|-----------|----------|-------|--------|-----------|-----------|----------|
| **Assign to Me** | user_id | no change | 1 | 1 (except stage 2,3) | no change | no change |
| **Set on Device** | user_id | no change | 1 | 0 (1 for sintering) | device_id | build_id |
| **Activate/Start** | no change | no change | no change | 1 | no change | no change |
| **Finish Case** | NULL | next stage | NULL | NULL | NULL | no change |
| **Delivered in Box** | NULL | -1 | no change | no change | no change | no change |

### Cases Table Changes:

| Operation | actual_delivery_date | delivered_to_client |
|-----------|---------------------|---------------------|
| **Finish Case** (to -1) | now() or preserved | 1 |
| **Delivered in Box** | now() | 1 |

### Builds Table Changes:

| Operation | set_at | started_at | device_used | name |
|-----------|--------|------------|-------------|------|
| **Set on Device** | now() | now() (sintering only) | device_id | user input |
| **Activate** | no change | now() | no change | no change |

---

## Notes:

1. **Build IDs**: Each stage has its own build ID field:
   - `milling_build_id` (stage 2)
   - `printing_build_id` (stage 3)
   - `sintering_build_id` (stage 4)
   - `pressing_build_id` (stage 5)

2. **Sub-Stages**: Manufacturing stages (2-5) use decimal notation for detailed tracking:
   - `.1` = Set on device
   - `.2` = Started/Activated
   - `.3` = Completed

3. **QC Special Rule**: Cases can only move from Finishing to QC if ALL jobs in the case are in Finishing stage.

4. **Invoice Creation**: Invoices are issued when cases finish QC (moving from stage 7 to 8) via the `issueInvoice()` function.

5. **Invoice Application**: Invoices are applied (added to client balance) when cases are completed (moving to stage -1) via the `applyInvoice()` function.

6. **Delivery Date Preservation**:
   - Modification cases preserve the original delivery date from failure logs
   - Repeat cases preserve the original case's delivery date
   - Normal cases use current timestamp

7. **Case Logs**: Every operation creates at least one log entry for tracking and audit purposes. One log per case, not per job.
