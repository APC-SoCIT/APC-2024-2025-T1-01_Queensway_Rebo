#!/bin/bash
# This script will be executed when MySQL starts for the first time.

echo "Starting MySQL Database Import"

# Import the database dump into MySQL
if [ -f /docker-entrypoint-initdb.d/database.sql ]; then
    echo "Importing database..."
    mysql -u root -prootpassword ecommerce < /docker-entrypoint-initdb.d/database.sql
    echo "Database imported successfully!"
else
    echo "No database dump found, skipping import."
fi

# Exit script
exit 0
