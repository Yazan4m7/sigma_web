# SIGMA Reports Testing Plan

## Report Analysis & Testing Strategy

### 1. Number of Units Report
**Purpose**: Track successful material consumption by doctor over time
**Test Scenarios**:
- Create cases with multiple materials (Zirconia, Composite, etc.)
- Verify successful jobs are counted (no failure flags)
- Test monthly aggregation with `actual_delivery_date`
- Validate doctor filtering works properly

**Expected Behavior**:
```
Dr. Smith    | Zirconia: 15 | Composite: 8  | All: 23
Dr. Jones    | Zirconia: 12 | Composite: 5  | All: 17
Totals       |         27   |          13   |     40
```

### 2. Repeats Report  
**Purpose**: Quality analysis - track failure patterns per doctor/time
**Test Scenarios**:
- Create jobs with different failure flags (is_rejection, is_repeat, etc.)
- Test Unit vs Case toggle functionality
- Verify Count vs Percentage toggle
- Test failure type filtering (Reject, Repeat, Modification, Redo, Successful)

**Expected Behavior**:
- Unit Mode: Counts individual units based on `unit_num` field parsing
- Case Mode: Counts unique cases containing failures
- Percentage Mode: Shows (failed/total)*100 with proper formatting

### 3. Implants Report
**Purpose**: Track implant/abutment combinations by doctor over time
**Test Scenarios**:
- Create jobs with implant and abutment relationships
- Test implant filtering (Nobel, Straumann, etc.)
- Test abutment filtering (Straight, Angled, etc.)
- Verify monthly aggregation works

### 4. QC Report
**Purpose**: Detailed quality control failure analysis
**Test Scenarios**:
- Create failure logs with different causes
- Test failure type filtering
- Verify cause filtering works
- Test date range filtering

### 5. Job Types Report
**Purpose**: Production analysis by job type (Crown, Bridge, Veneer, etc.)
**Test Scenarios**:
- Create jobs with different job types
- Test job type filtering
- Verify unit vs case counting
- Test monthly aggregation

### 6. Materials Report
**Purpose**: Case-material analysis with financial data
**Test Scenarios**:
- Create cases with material associations
- Test doctor filtering
- Verify patient name search
- Test financial calculations (invoices)

## Database Relationships for Testing

### Core Entities:
```
cases (sCase)
├── doctor_id → clients.id
├── actual_delivery_date (for monthly grouping)
└── jobs
    ├── material_id → materials.id
    ├── type → job_types.id
    ├── type_id → types.id (material sub-types)
    ├── implant → implants.id
    ├── abutment → abutments.id
    ├── unit_num (comma-separated: "1,2,3")
    └── failure flags:
        ├── is_rejection (0/1)
        ├── is_repeat (0/1)
        ├── is_modification (0/1)
        └── is_redo (0/1)
```

### Test Data Requirements:
1. **Clients**: At least 3 doctors with different names
2. **Cases**: Multiple cases per doctor with different delivery dates
3. **Jobs**: Various combinations of materials, types, success/failure states
4. **Materials**: Different materials with `count_in_units_counts_report` flags
5. **Implants/Abutments**: Different brands and types
6. **Failure Logs**: QC failures with causes and types

## Testing Approach:
1. **Manual Web Interface Testing**: Access each report URL and verify UI
2. **Data Validation**: Check report calculations match expected business logic
3. **Filter Testing**: Verify all filter combinations work properly
4. **Edge Case Testing**: Empty data sets, single records, boundary dates
5. **Performance Testing**: Large date ranges and data sets

## Expected Issues to Watch For:
1. Division by zero in percentage calculations
2. Null date handling in monthly grouping
3. Empty filter arrays causing "all" selection bugs
4. Unit number parsing errors (comma-separated values)
5. Relationship loading performance with large datasets

## Success Criteria:
- All reports load without errors
- Calculations match business logic
- Filters work as expected
- Data aggregation is accurate
- UI displays properly formatted results