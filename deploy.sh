#!/bin/bash
# SIGMA Quick Deploy - No password prompt

SERVER="root@161.35.46.18"
PASSWORD="sigma\$S1lab"
REMOTE_PATH="/var/www/sigma"

echo "========================================="
echo "   SIGMA Quick Deploy"
echo "========================================="
echo ""

# Function to sync with password embedded
sync_files() {
    expect << EOF
set timeout -1
spawn rsync -avzh --progress --stats --itemize-changes \
  --exclude '.git' \
  --exclude 'node_modules' \
  --exclude 'vendor' \
  --exclude 'storage' \
  --exclude '.env' \
  --exclude '.reports' \
  --exclude '*.md' \
  --exclude 'tests' \
  app/ resources/ routes/ config/ public/js/ public/css/ \
  $SERVER:$REMOTE_PATH/
expect {
    "*yes/no*" { send "yes\r"; exp_continue }
    "*password:*" { send "$PASSWORD\r" }
}
expect eof
EOF
}

# Function to run remote commands with password
run_remote() {
    expect << EOF
set timeout -1
spawn ssh $SERVER "cd $REMOTE_PATH && \$1"
expect {
    "*yes/no*" { send "yes\r"; exp_continue }
    "*password:*" { send "$PASSWORD\r" }
}
expect eof
EOF
}

# Check if expect is available
if ! command -v expect &> /dev/null; then
    echo "ERROR: 'expect' not found!"
    echo ""
    echo "SOLUTION: Use the Windows batch file instead:"
    echo "  deploy.bat"
    echo ""
    exit 1
fi

# Sync files
echo "[1/2] Syncing files..."
sync_files

echo ""
echo "[2/2] Clearing caches on server..."
run_remote "php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear"

echo ""
echo "========================================="
echo "   ✓ Deploy Complete!"
echo "========================================="
echo ""
echo "Synced:"
echo "  - app/"
echo "  - resources/"
echo "  - routes/"
echo "  - config/"
echo "  - public/js/"
echo "  - public/css/"
echo ""
