#!/bin/bash
# This script will be executed when MySQL starts for the first time.

echo "Starting MySQL Database Import"

# Disable foreign key checks before dropping tables
echo "Disabling foreign key checks..."
mysql -u root -prootpassword -e "USE ecommerce; SET foreign_key_checks = 0;"

# Drop existing tables
echo "Dropping existing tables..."
mysql -u root -prootpassword -e "USE ecommerce;
    DROP TABLE IF EXISTS order_items, order_histories, orders, admins, admin_password_reset_tokens, cache, faqs, migrations, password_reset_tokens, products, users;"


# Enable foreign key checks after dropping tables
echo "Enabling foreign key checks..."
mysql -u root -prootpassword -e "USE ecommerce; SET foreign_key_checks = 1;"

# Import the database dump into MySQL
if [ -f /docker-entrypoint-initdb.d/ecommerce.sql ]; then
    echo "Importing database..."
    mysql -u root -prootpassword ecommerce < /docker-entrypoint-initdb.d/ecommerce.sql
    echo "Database imported successfully!"
else
    echo "No database dump found, skipping import."
fi

# Exit script
exit 0
