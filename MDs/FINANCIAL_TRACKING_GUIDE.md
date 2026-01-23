# Client Financial Tracking System

## Overview
This system tracks financial metrics and payment risk for each doctor/client in the Sigma dental lab system.

## Features

### 1. Income/Outcome Percentage
- **Income %**: Percentage of payments received vs total transactions (last 12 months)
- **Outcome %**: Percentage of invoices issued vs total transactions (last 12 months)
- **Formula**: 
  - Income % = (Total Payments / (Total Invoices + Total Payments)) × 100
  - Outcome % = (Total Invoices / (Total Invoices + Total Payments)) × 100

### 2. Approximate Monthly Income
- Calculates average monthly income from each client
- Based on last 6 months of payment data
- **Formula**: Total Payments (6 months) / 6

### 3. Risk Level Assessment
Automatically categorizes clients into 4 risk levels:

#### Low Risk (Green)
- Pays within 30 days
- No overdue balance
- Good payment history

#### Medium Risk (Yellow)
- Pays within 30-45 days
- Outstanding balance < $3,000
- Slightly delayed payments

#### High Risk (Orange)
- Pays within 45-60 days
- Outstanding balance $3,000-$5,000
- Frequently delayed payments
- Average payment time > 60 days

#### Critical Risk (Red)
- Over 60 days overdue
- Outstanding balance > $10,000 with 30+ days overdue
- Outstanding balance > $5,000 with 15+ days overdue
- Severe payment issues

### 4. Additional Metrics
- **Average Payment Days**: Average time between invoice and payment
- **Current Overdue Days**: Days since oldest unpaid invoice
- **Outstanding Balance**: Current amount owed

## Installation

### Step 1: Run Migration
```bash
php artisan migrate
```

This creates the following columns in the `clients` table:
- `income_percentage` (decimal)
- `outcome_percentage` (decimal)
- `approx_income` (decimal)
- `risk_level` (enum: low, medium, high, critical)
- `avg_payment_days` (integer)
- `current_overdue_days` (integer)
- `outstanding_balance` (decimal)
- `last_financial_update` (timestamp)

### Step 2: Initial Data Population
Update financial metrics for all clients:
```bash
php artisan clients:update-financial-metrics
```

Update for a specific client:
```bash
php artisan clients:update-financial-metrics --client_id=123
```

### Step 3: Schedule Automatic Updates
Add to `app/Console/Kernel.php` in the `schedule()` method:

```php
// Update financial metrics daily at 2 AM
$schedule->command('clients:update-financial-metrics')
         ->dailyAt('02:00')
         ->withoutOverlapping();
```

## Usage in Code

### Update Metrics Manually
```php
$client = Client::find(1);
$client->updateFinancialMetrics();
```

### Get Formatted Display
```php
// Income/Outcome with colors
echo $client->getIncomeOutcomeDisplay();
// Output: ↑ 65.5% / ↓ 34.5%

// Risk badge with color
echo $client->getRiskLevelBadge();
// Output: <span class="badge badge-success">Low Risk</span>

// Risk explanation
echo $client->getRiskExplanation();
// Output: "Currently 15 days overdue | Average payment time: 45 days | Outstanding balance: $2,500.00"
```

### Query by Risk Level
```php
// Get all high-risk clients
$highRiskClients = Client::whereIn('risk_level', ['high', 'critical'])
    ->orderBy('current_overdue_days', 'desc')
    ->get();

// Get clients with high outstanding balance
$highBalanceClients = Client::where('outstanding_balance', '>', 5000)
    ->orderBy('outstanding_balance', 'desc')
    ->get();
```

## Display in Views

### Example: Client List Table
```blade
<table class="table">
    <thead>
        <tr>
            <th>Client Name</th>
            <th>Income/Outcome</th>
            <th>Approx Income</th>
            <th>Risk Level</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clients as $client)
        <tr>
            <td>{{ $client->name }}</td>
            <td>{!! $client->getIncomeOutcomeDisplay() !!}</td>
            <td>${{ number_format($client->approx_income, 2) }}/mo</td>
            <td>{!! $client->getRiskLevelBadge() !!}</td>
            <td>
                <small class="text-muted">
                    {{ $client->getRiskExplanation() }}
                </small>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
```

### Example: Client Dashboard Widget
```blade
<div class="card">
    <div class="card-header">
        <h5>{{ $client->name }} - Financial Overview</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Income/Outcome</h6>
                <p>{!! $client->getIncomeOutcomeDisplay() !!}</p>
            </div>
            <div class="col-md-6">
                <h6>Monthly Income</h6>
                <p>${{ number_format($client->approx_income, 2) }}</p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <h6>Risk Level</h6>
                <p>{!! $client->getRiskLevelBadge() !!}</p>
            </div>
            <div class="col-md-6">
                <h6>Outstanding</h6>
                <p>${{ number_format($client->outstanding_balance, 2) }}</p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <small class="text-muted">
                    {{ $client->getRiskExplanation() }}
                </small>
            </div>
        </div>
    </div>
</div>
```

## Risk Level Thresholds (Customizable)

You can adjust risk thresholds in `app/client.php` in the `calculateRiskLevel()` method:

```php
// Current thresholds:
// Critical: overdue > 60 days OR (balance > $10k AND overdue > 30 days)
// High: overdue > 45 days OR avg > 60 days OR (balance > $5k AND overdue > 15 days)
// Medium: overdue > 30 days OR avg > 45 days OR balance > $3k
// Low: Everything else
```

## Monitoring & Alerts

### Daily Summary Email (Optional)
Create a scheduled task to email high-risk clients summary:

```php
// In app/Console/Kernel.php
$schedule->call(function () {
    $highRiskClients = Client::whereIn('risk_level', ['high', 'critical'])
        ->get();
    
    if ($highRiskClients->count() > 0) {
        Mail::to('admin@sigmalab.com')->send(
            new HighRiskClientsAlert($highRiskClients)
        );
    }
})->dailyAt('08:00');
```

## Performance Considerations

- Financial metrics are cached in the database
- Updates run via scheduled command (not on every page load)
- Calculations use indexed columns (doctor_id, created_at, status)
- Last update timestamp tracked for monitoring

## Troubleshooting

### Metrics Not Updating
```bash
# Check last update time
php artisan tinker
>>> Client::find(1)->last_financial_update

# Force update
>>> Client::find(1)->updateFinancialMetrics()
```

### Incorrect Risk Levels
- Verify invoice and payment data is correct
- Check date ranges in calculations
- Review threshold values in `calculateRiskLevel()`

### Performance Issues
- Ensure database indexes exist on:
  - `invoices.doctor_id`
  - `invoices.created_at`
  - `payments.doctor_id`
  - `payments.created_at`

## Future Enhancements

Potential additions:
1. **AI-Powered Predictions**: Use machine learning to predict payment delays
2. **Trend Analysis**: Track changes in payment behavior over time
3. **Automated Alerts**: Send notifications when risk level changes
4. **Payment Reminders**: Automatic reminders for overdue invoices
5. **Credit Limits**: Set credit limits based on risk level
6. **Seasonal Adjustments**: Account for seasonal payment patterns

## Support

For questions or issues, contact the development team or refer to the main application documentation.
