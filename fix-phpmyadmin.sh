#!/bin/bash

echo "Enabling phpMyAdmin configuration in Apache..."

# Create symlink to enable phpMyAdmin config
sudo ln -sf /etc/phpmyadmin/apache.conf /etc/apache2/conf-available/phpmyadmin.conf

# Enable the configuration
sudo a2enconf phpmyadmin

# Restart Apache
sudo service apache2 restart

echo ""
echo "Done! phpMyAdmin should now be accessible at:"
echo "http://localhost/phpmyadmin"
echo ""
echo "If you still get ERR_CONNECTION_REFUSED from Windows:"
echo "1. Make sure Windows Firewall isn't blocking port 80"
echo "2. Try accessing from WSL itself: curl http://localhost/phpmyadmin"
echo "3. You may need to access via WSL IP instead of localhost"
