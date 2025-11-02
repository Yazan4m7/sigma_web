# SIGMA Reports Manual Testing Guide

## Web Interface Testing Plan

Since database access is restricted, here's a comprehensive manual testing plan for each report:

### 1. Number of Units Report (`/reports/num-of-units`)

**Test URLs:**
```
http://localhost:8001/reports/num-of-units
http://localhost:8001/reports/num-of-units?from=2024-08-01&to=2024-08-30
http://localhost:8001/reports/num-of-units?doctor[]=1&doctor[]=2&material[]=1&material[]=2
```

**Test Scenarios:**
- [ ] Default load (last month to today)
- [ ] Custom date range selection
- [ ] Single doctor selection
- [ ] Multiple doctor selection  
- [ ] Single material selection
- [ ] Multiple material selection
- [ ] "All" options for doctors and materials
- [ ] Empty result handling
- [ ] Print functionality

**Expected Data Structure:**
```
Month: 2024-08 Table:
Dr Name    | Zirconia | Composite | All
Dr Smith   |    15    |     8     | 23
Dr Jones   |    12    |     5     | 17
Totals     |    27    |    13     | 40

Month: All Time Table:
[Same structure but aggregated across all months]
```

### 2. Repeats Report (`/reports/repeats`)

**Test URLs:**
```
http://localhost:8001/reports/repeats
http://localhost:8001/reports/repeats?perToggle=on (Units mode)
http://localhost:8001/reports/repeats?countOrPercentageToggle=on (Percentage mode)
http://localhost:8001/reports/repeats?failureTypeInput[]=0&failureTypeInput[]=1
```

**Test Scenarios:**
- [ ] Default load (Per Case, Count mode)
- [ ] Toggle Per Unit vs Per Case
- [ ] Toggle Count vs Percentage
- [ ] Filter by specific failure types
- [ ] Date range filtering
- [ ] Doctor filtering
- [ ] Percentage calculations accuracy
- [ ] Print functionality with correct title

**Expected Failure Types:**
- 0 = Rejection
- 1 = Repeat  
- 2 = Modification
- 3 = Redo
- 4 = Successful

### 3. Implants Report (`/reports/implants`)

**Test URLs:**
```
http://localhost:8001/reports/implants
http://localhost:8001/reports/implants?implantsInput[]=1&implantsInput[]=2
http://localhost:8001/reports/implants?abutmentsInput[]=1&abutmentsInput[]=2
http://localhost:8001/reports/implants?perToggle=on
```

**Test Scenarios:**
- [ ] Default load with all implants/abutments
- [ ] Implant filtering (Nobel, Straumann, etc.)
- [ ] Abutment filtering (Straight, Angled, etc.) 
- [ ] Per Unit vs Per Case toggle
- [ ] Doctor filtering
- [ ] Date range selection
- [ ] Monthly breakdown display
- [ ] Print functionality

### 4. QC Report (`/reports/QC`)

**Test URLs:**
```
http://localhost:8001/reports/QC
http://localhost:8001/reports/QC?causesInput[]=1&causesInput[]=2
http://localhost:8001/reports/QC?failureTypeInput[]=0&failureTypeInput[]=1
```

**Test Scenarios:**
- [ ] Default load with all causes and types
- [ ] Filter by specific failure causes
- [ ] Filter by failure types
- [ ] Date range filtering
- [ ] Doctor filtering  
- [ ] Failure log data display
- [ ] Failed units count accuracy
- [ ] Cases vs units statistics
- [ ] Print functionality

### 5. Job Types Report (`/reports/job-types`)

**Test URLs:**
```
http://localhost:8001/reports/job-types
http://localhost:8001/reports/job-types?jobTypesInput[]=1&jobTypesInput[]=2
http://localhost:8001/reports/job-types?perToggle=on
```

**Test Scenarios:**
- [ ] Default load (limited to types 1,2,3,4)
- [ ] Job type filtering (Crown, Bridge, Veneer, etc.)
- [ ] Per Unit vs Per Case toggle
- [ ] Doctor filtering
- [ ] Monthly breakdown
- [ ] Date range selection
- [ ] Print functionality

### 6. Materials Report (`/reports/material`)

**Test URLs:**
```
http://localhost:8001/reports/material
http://localhost:8001/reports/material?patient_name=Smith
http://localhost:8001/reports/material?doctor[]=1&doctor[]=2
```

**Test Scenarios:**
- [ ] Default load (last 30 days)
- [ ] Patient name search
- [ ] Doctor filtering
- [ ] Date range selection
- [ ] Case materials display
- [ ] Invoice amount calculations
- [ ] Total amount accuracy

## Testing Checklist

### UI/UX Testing
- [ ] All reports load without errors
- [ ] Filters are properly positioned and sized
- [ ] Form inputs work correctly
- [ ] Submit buttons function properly
- [ ] Print buttons open print dialogs
- [ ] Tables display with consistent styling
- [ ] Responsive design works on different screen sizes

### Data Accuracy Testing  
- [ ] Date filtering works correctly
- [ ] Doctor filtering shows correct subset
- [ ] Material/Type filtering works as expected
- [ ] Toggle switches change data presentation
- [ ] Calculations match expected business logic
- [ ] Totals rows sum correctly
- [ ] Percentage calculations are accurate

### Edge Case Testing
- [ ] Empty date ranges
- [ ] No data available scenarios
- [ ] Single record datasets  
- [ ] All filters cleared (show all)
- [ ] Future date ranges
- [ ] Invalid date inputs

### Performance Testing
- [ ] Large date ranges load reasonably
- [ ] Multiple filters don't cause timeouts
- [ ] Print functionality handles large datasets
- [ ] Page responsiveness with complex filters

## Expected Business Logic

### Units vs Cases
- **Units**: Count individual items within jobs using `unit_num` parsing
- **Cases**: Count unique cases containing relevant jobs

### Success vs Failure
- **Successful**: `is_rejection=0 AND is_repeat=0 AND is_modification=0 AND is_redo=0`
- **Failed**: Any failure flag = 1

### Monthly Aggregation
- Based on `actual_delivery_date` field in cases table
- Format: YYYY-MM for grouping
- Date ranges converted to month ranges

### Percentage Calculations
- Units: `(failed_units / total_units) * 100`
- Cases: `(failed_cases / total_cases) * 100`
- Format: Float with 2 decimal places

## Success Criteria
✅ All reports accessible via URLs
✅ Filters work without JavaScript errors  
✅ Data displays in expected table format
✅ Print functionality works correctly
✅ Styling matches professional website theme
✅ No broken layouts or visual issues
✅ Toggle switches change data presentation
✅ Calculations appear logically consistent