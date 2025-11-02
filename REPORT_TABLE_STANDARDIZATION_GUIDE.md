# SIGMA Report Table Standardization Guide

This guide provides instructions for applying consistent styling to all report tables in the SIGMA system, based on the reference customer/financial table design.

## Files Updated

### 1. New CSS File Created
- **File**: `/public/assets/css/standardized-report-tables.css`
- **Purpose**: Contains all standardized report table styling based on the reference image

### 2. Files Created/Updated

**New CSS Files Created:**
- `/public/assets/css/standardized-report-tables.css` - Main standardized styling
- `/public/assets/css/report-table-force-override.css` - High specificity overrides

**Report Files to Update:**
All report files in `/resources/views/reports/` need to be updated:
- `case-materials-report.blade.php`
- `jobTypes.blade.php` 
- `numOfUnits.blade.php`
- `QC.blade.php` ✅ (CSS links added)
- `implants.blade.php` ✅ (Fully updated with new structure)
- `repeats.blade.php`

## Standardization Steps for Each Report

### Step 1: Add CSS Links
Add BOTH CSS files to override old styles completely:
```blade
<link href="{{ asset('assets/css/standardized-report-tables.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/report-table-force-override.css') }}" rel="stylesheet">
```

⚠️ **Important**: The `report-table-force-override.css` file uses maximum CSS specificity to ensure old styles don't interfere.

### Step 2: Update Table Structure
Replace existing table classes with standardized ones:

#### Before (Old Structure):
```blade
<table class="sigma-table printable" border="1" style="border-collapse:collapse;">
    <thead>
        <tr class="tableHeaderRow">
            <th class="table-cell">Column 1</th>
            <th class="table-cell">Column 2</th>
        </tr>
    </thead>
    <tbody>
        <tr class="dataRow">
            <td class="table-cell">Data 1</td>
            <td class="table-cell">Data 2</td>
        </tr>
    </tbody>
</table>
```

#### After (New Structure):
```blade
<div class="sigma-report-table-container">
    <table class="sigma-report-table printable">
        <thead>
            <tr>
                <th class="sigma-col-customer">Column 1</th>
                <th class="text-center">Column 2</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="primary-text">Data 1</td>
                <td class="text-center">Data 2</td>
                <td class="text-right">Data 3</td>
            </tr>
            <tr class="totals-row">
                <td class="primary-text">Totals</td>
                <td class="text-center"><strong>Total 1</strong></td>
                <td class="text-right"><strong>Total 2</strong></td>
            </tr>
        </tbody>
    </table>
    
    <!-- Add pagination section -->
    <div class="sigma-report-pagination">
        <div class="sigma-rows-per-page">
            <span>Rows per page:</span>
            <select>
                <option>10</option>
                <option>25</option>
                <option>50</option>
            </select>
        </div>
        <div class="sigma-pagination-nav">
            <div class="sigma-pagination-info">1-10 of 97</div>
            <button class="sigma-pagination-btn disabled">‹</button>
            <div class="sigma-pagination-info">1/10</div>
            <button class="sigma-pagination-btn disabled">›</button>
        </div>
    </div>
</div>
```

## CSS Classes Reference

### Table Container & Structure
- `.sigma-report-table-container` - Main container with border radius and shadow
- `.sigma-report-table` - Main table class
- `.totals-row` - Special styling for total/summary rows

### Header Classes  
- Standard `<th>` elements get blue-gray background automatically
- `.text-center` - Center-aligned headers
- `.text-right` - Right-aligned headers (for numeric columns)

### Column Type Classes
- `.sigma-col-customer` - Customer name column (min-width: 200px)
- `.sigma-col-status` - Status column (width: 100px, centered)
- `.sigma-col-currency` - Currency column (width: 120px, right-aligned)
- `.sigma-col-date` - Date column (width: 110px)
- `.sigma-col-actions` - Actions column (width: 80px, centered)

### Data Cell Classes
- `.primary-text` - Main text (customer names, etc.) - Bold, dark color
- `.secondary-text` - Secondary text (phone numbers, etc.) - Lighter, smaller
- `.text-center` - Center-aligned data
- `.text-right` - Right-aligned data (for numbers)

### Currency & Numbers
- `.currency` - Currency values with tabular numbers
- `.currency.positive` - Green color for positive amounts
- `.currency.negative` - Red color for negative amounts

### Status Badges
```blade
<span class="sigma-status-badge paid">Paid</span>
<span class="sigma-status-badge open">Open</span>
<span class="sigma-status-badge inactive">Inactive</span>
<span class="sigma-status-badge pending">Pending</span>
<span class="sigma-status-badge completed">Completed</span>
<span class="sigma-status-badge cancelled">Cancelled</span>
```

## Example Customer Table Structure
Based on the reference image, here's the complete table structure:

```blade
<div class="sigma-report-table-container">
    <table class="sigma-report-table">
        <thead>
            <tr>
                <th class="sigma-col-customer">Customer</th>
                <th class="sigma-col-status">Status</th>
                <th class="text-right">Rate</th>
                <th class="text-right">Balance</th>
                <th class="text-right">Deposit</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="primary-text">Ralph Edwards</div>
                    <div class="secondary-text">(405) 555-0128</div>
                </td>
                <td class="text-center">
                    <span class="sigma-status-badge open">Open</span>
                </td>
                <td class="text-right">
                    <span class="currency">$78.00</span>
                    <div class="currency-symbol">USD</div>
                </td>
                <td class="text-right">
                    <span class="currency negative">-$105.55</span>
                    <div class="currency-symbol">USD</div>
                </td>
                <td class="text-right">
                    <span class="currency">$293.01</span>
                    <div class="currency-symbol">USD</div>
                </td>
            </tr>
            <!-- More rows... -->
        </tbody>
    </table>
    
    <div class="sigma-report-pagination">
        <div class="sigma-rows-per-page">
            <span>Rows per page:</span>
            <select>
                <option>10</option>
            </select>
        </div>
        <div class="sigma-pagination-nav">
            <div class="sigma-pagination-info">1-10 of 97</div>
            <button class="sigma-pagination-btn disabled">‹</button>
            <div class="sigma-pagination-info">1/10</div>
            <button class="sigma-pagination-btn disabled">›</button>
        </div>
    </div>
</div>
```

## Design Specifications Matched

✅ **Header row**: Blue-gray background (#8b9dc3) with white text
✅ **Clean column headers**: Proper spacing and typography
✅ **Alternating row colors**: White and light gray (#f8f9fa)
✅ **Consistent typography**: Standard font weights and sizes
✅ **Proper alignment**: Left for text, right for numbers, center for status
✅ **Status badges**: Colored backgrounds (green/blue/gray)
✅ **Consistent padding**: 1rem vertical, 1.25rem horizontal
✅ **Bottom pagination**: "Rows per page" dropdown and navigation

## Benefits of Standardization

1. **Consistent User Experience**: All reports have the same look and feel
2. **Professional Appearance**: Matches modern table design standards
3. **Better Readability**: Proper alignment and spacing improve data comprehension
4. **Responsive Design**: Tables adapt to different screen sizes
5. **Print-Friendly**: Optimized styles for printing reports
6. **Maintenance**: Single CSS file to update styling across all reports

## Next Steps

1. Apply these changes to all remaining report files
2. Test each report to ensure proper rendering
3. Update any custom JavaScript that depends on old CSS classes
4. Consider implementing actual pagination functionality where needed
5. Add accessibility improvements (ARIA labels, keyboard navigation)

## Notes

- The existing `reports-modern.css` and `repeats-report-enhanced.css` files can still be used for specific enhancements
- The new standardized CSS takes precedence for table structure
- Print styles are included in the standardized CSS file
- Mobile responsive breakpoints are set at 768px