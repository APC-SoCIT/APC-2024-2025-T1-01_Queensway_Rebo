#!/bin/bash

# Import the eCommerce SQL database
if [ -f /workspace/ecommerce.sql ]; then
    echo "Importing database..."
    mysql -u root -psecret laravel_ecommerce < /workspace/ecommerce.sql
else
    echo "No ecommerce.sql file found!"
fi
