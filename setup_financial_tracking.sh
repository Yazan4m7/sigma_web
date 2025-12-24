#!/bin/bash

echo "=========================================="
echo "Sigma Lab - Financial Tracking Setup"
echo "=========================================="
echo ""

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Step 1: Run migration
echo -e "${YELLOW}Step 1: Running database migration...${NC}"
php artisan migrate --path=database/migrations/2025_01_15_000001_add_financial_tracking_to_clients_table.php

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Migration completed successfully${NC}"
else
    echo -e "${RED}✗ Migration failed${NC}"
    exit 1
fi

echo ""

# Step 2: Update financial metrics for all clients
echo -e "${YELLOW}Step 2: Calculating financial metrics for all clients...${NC}"
echo "This may take a few minutes depending on the number of clients..."
php artisan clients:update-financial-metrics

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Financial metrics updated successfully${NC}"
else
    echo -e "${RED}✗ Update failed${NC}"
    exit 1
fi

echo ""

# Step 3: Show summary
echo -e "${YELLOW}Step 3: Displaying summary...${NC}"
php artisan clients:update-financial-metrics --client_id=1 2>/dev/null || echo "Run 'php artisan clients:update-financial-metrics' to see detailed summary"

echo ""
echo -e "${GREEN}=========================================="
echo "Setup Complete!"
echo "==========================================${NC}"
echo ""
echo "Next steps:"
echo "1. Review FINANCIAL_TRACKING_GUIDE.md for usage instructions"
echo "2. Add scheduled task to app/Console/Kernel.php:"
echo "   \$schedule->command('clients:update-financial-metrics')->dailyAt('02:00');"
echo "3. Update your client views to display the new metrics"
echo ""
echo "Commands available:"
echo "  - Update all clients: php artisan clients:update-financial-metrics"
echo "  - Update specific client: php artisan clients:update-financial-metrics --client_id=123"
echo ""
