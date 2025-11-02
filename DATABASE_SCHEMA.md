# SIGMA Database Schema Documentation

This document provides the inferred database schema for the SIGMA dental laboratory management system based on model analysis and relationships.

## Core Tables

### `cases` Table
Primary table for managing dental cases/patients.

```sql
cases
├── id (PRIMARY KEY)
├── doctor_id (FK → clients.id)
├── patient_name
├── initial_delivery_date (datetime)
├── actual_delivery_date (datetime)
├── delivered_to_client (boolean)
├── contains_modification (boolean)
├── created_by
├── current_status
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

**Relationships:**
- `belongsTo('App\client', 'doctor_id', 'id')` - Client/Doctor
- `hasMany('App\job', 'case_id', 'id')` - Jobs
- `hasMany('App\note', 'case_id', 'id')` - Notes
- `hasMany('App\file', 'case_id', 'id')` - Photos
- `hasMany('App\caseTag', 'case_id', 'id')` - Tags
- `hasOne('App\discount', 'case_id', 'id')` - Discount
- `hasOne('App\invoice', 'case_id', 'id')` - Invoice
- `hasMany('App\abutmentDeliveryRecord', 'case_id', 'id')` - Abutment deliveries
- `hasMany('App\caseLog', 'case_id', 'id')` - Logs

---

### `jobs` Table
Individual jobs/units within each case.

```sql
jobs
├── id (PRIMARY KEY)
├── case_id (FK → cases.id)
├── type (FK → job_types.id) -- 1=Crown, 2=Bridge, 3=Implant, 4=Abutment
├── type_id (FK → types.id) -- Sub-types
├── material_id (FK → materials.id)
├── unit_num (TEXT) -- Comma-separated units e.g., "11,12,13"
├── stage (INT) -- 1-8 workflow stages, -1=completed
├── assignee (FK → users.id)
├── abutment (FK → abutments.id)
├── implant (FK → implants.id)
├── original_job_id (FK → jobs.id) -- For repeat/redo jobs
├── device_id (FK → devices.id)
├── milling_build_id (FK → builds.id)
├── printing_build_id (FK → builds.id)
├── pressing_build_id (FK → builds.id)
├── delivery_accepted (FK → users.id) -- Delivery driver
├── is_rejection (BOOLEAN)
├── is_repeat (BOOLEAN)
├── is_modification (BOOLEAN)
├── is_redo (BOOLEAN)
├── has_been_rejected (BOOLEAN)
├── repeated_job_id (FK → jobs.id)
├── modified_job_id (FK → jobs.id)
├── redone_job_id (FK → jobs.id)
├── is_set (BOOLEAN)
├── is_active (BOOLEAN)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

**Relationships:**
- `belongsTo('App\sCase', 'case_id', 'id')` - Case
- `belongsTo('App\material', 'material_id', 'id')` - Material
- `belongsTo('App\Type', 'type_id', 'id')` - Sub-type
- `belongsTo('App\JobType', 'type', 'id')` - Job type
- `belongsTo('App\abutment', 'abutment', 'id')` - Abutment
- `belongsTo('App\implant', 'implant', 'id')` - Implant
- `belongsTo('App\User', 'assignee', 'id')` - Assigned user
- `belongsTo('App\User', 'delivery_accepted', 'id')` - Delivery driver
- `belongsTo('App\Job', 'original_job_id', 'id')` - Original job
- `hasMany('App\abutmentDeliveryRecord', 'job_id', 'id')` - Abutment deliveries

---

### `clients` Table
Dental clinics and doctors.

```sql
clients
├── id (PRIMARY KEY)
├── name
├── email (likely)
├── phone (likely)
├── address (likely)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

**Relationships:**
- `hasMany('App\clientDiscount', 'client_id', 'id')` - Discounts
- `hasMany('App\sCase', 'doctor_id', 'id')` - Cases

---

### `materials` Table
Dental materials (Zirconia, E-max, PEEK, etc.).

```sql
materials
├── id (PRIMARY KEY)
├── name
├── price (DECIMAL)
├── count_as_unit (BOOLEAN) -- General unit counting
├── count_in_units_counts_report (BOOLEAN) -- Units report filter
├── count_in_job_types_report (BOOLEAN) -- Job types report filter
├── count_in_implants_report (BOOLEAN) -- Implants report filter
├── count_in_qc_report (BOOLEAN) -- QC report filter
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

**Relationships:**
- `hasMany('App\materialJobtype', 'material_id', 'id')` - Job type associations
- `belongsToMany('App\Type', 'material_types', 'material_id', 'type_id')` - Material types

---

### `job_types` Table
Job categories (Crown, Bridge, Implant, Abutment).

```sql
job_types
├── id (PRIMARY KEY) -- 1=Crown, 2=Bridge, 3=Implant, 4=Abutment
├── name
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

**Relationships:**
- `hasMany('App\materialJobtype', 'jobtype_id', 'id')` - Material associations

---

### `implants` Table
Implant systems and brands.

```sql
implants
├── id (PRIMARY KEY)
├── name
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

---

### `abutments` Table
Abutment types and specifications.

```sql
abutments
├── id (PRIMARY KEY)
├── name (likely)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

---

### `failure_logs` Table
Quality control failure tracking.

```sql
failure_logs
├── id (PRIMARY KEY)
├── case_id (FK → cases.id)
├── failure_type (INT) -- 0=Rejection, 1=Repeat, 2=Modification, 3=Redo
├── cause_id (FK → failure_causes.id)
├── notes (TEXT, likely)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

**Relationships:**
- `belongsTo('App\sCase', 'case_id', 'id')` - Case
- `belongsTo('App\failureCause', 'cause_id', 'id')` - Failure cause

---

### `failure_causes` Table
Predefined failure reasons.

```sql
failure_causes
├── id (PRIMARY KEY)
├── name
├── description (likely)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

---

### `invoices` Table
Billing and invoicing.

```sql
invoices
├── id (PRIMARY KEY)
├── case_id (FK → cases.id)
├── doctor_id (FK → clients.id)
├── amount (DECIMAL)
├── status (INT) -- 1=active, likely
├── date_applied (DATE)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

**Relationships:**
- `belongsTo('App\sCase', 'case_id', 'id')` - Case
- `belongsTo('App\client', 'doctor_id', 'id')` - Client

---

### `abutment_delivery_records` Table
Tracking abutment deliveries with implant combinations.

```sql
abutment_delivery_records
├── id (PRIMARY KEY)
├── case_id (FK → cases.id)
├── job_id (FK → jobs.id)
├── abutment_id (FK → abutments.id)
├── implant_id (FK → implants.id)
├── units (TEXT) -- Comma-separated units
├── quantity (INT, likely)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

**Relationships:**
- `belongsTo('App\sCase', 'case_id', 'id')` - Case
- `belongsTo('App\job', 'job_id', 'id')` - Job
- `belongsTo('App\abutment', 'abutment_id', 'id')` - Abutment
- `belongsTo('App\implant', 'implant_id', 'id')` - Implant

---

## Supporting Tables

### `payments` Table
```sql
payments
├── id (PRIMARY KEY)
├── doctor_id (FK → clients.id)
├── amount (DECIMAL)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

### `types` Table
Sub-types for materials/jobs.
```sql
types
├── id (PRIMARY KEY)
├── name
├── is_enabled (BOOLEAN)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

### `material_types` Table (Pivot)
Many-to-many relationship between materials and types.
```sql
material_types
├── id (PRIMARY KEY)
├── material_id (FK → materials.id)
├── type_id (FK → types.id)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

### `material_jobtypes` Table (Pivot)
Many-to-many relationship between materials and job types.
```sql
material_jobtypes
├── id (PRIMARY KEY)
├── material_id (FK → materials.id)
├── jobtype_id (FK → job_types.id)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

### `users` Table
System users (employees, drivers, etc.).
```sql
users
├── id (PRIMARY KEY)
├── name
├── name_initials
├── email
├── is_admin (BOOLEAN)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

### `devices` Table
Manufacturing equipment.
```sql
devices
├── id (PRIMARY KEY)
├── name
├── type (likely)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp) -- Soft deletes
```

---

## Key Business Logic

### Workflow Stages
Jobs progress through stages 1-8:
1. **Design**
2. **Milling**
3. **3D Printing**
4. **Sintering Furnace**
5. **Pressing Furnace**
6. **Finishing**
7. **Quality Control**
8. **Delivery**
- **-1**: **Completed**

### Failure Types
- **0**: Rejection
- **1**: Repeat
- **2**: Modification
- **3**: Redo
- **4**: Successful (for reports)

### Unit Counting
Units are stored as comma-separated strings (e.g., "11,12,13") and counted using `explode(',', $unit_num)`.

### Report Data Requirements

#### For Testing Reports, Create:

1. **Cases** with varied:
   - `doctor_id` (different clients)
   - `actual_delivery_date` (spread across months)
   - `patient_name`

2. **Jobs** with:
   - Different `type` values (1-4)
   - Various `material_id` values
   - `unit_num` with different unit combinations
   - `implant` and `abutment` values for implant reports
   - Mix of failure flags (`is_rejection`, `is_repeat`, etc.)

3. **Failure Logs** with:
   - Different `failure_type` values (0-3)
   - Various `cause_id` values

4. **Invoices** linked to completed cases

5. **Abutment Delivery Records** for implant/abutment combinations

This schema supports comprehensive reporting across materials, job types, quality control, implants/abutments, and financial data.