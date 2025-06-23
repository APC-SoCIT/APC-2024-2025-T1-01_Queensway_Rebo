#!/bin/bash
set -e

# Start MariaDB service
service mysql start

# Wait for MariaDB to be ready
until mysqladmin ping --silent; do
  echo "Waiting for MariaDB to start..."
  sleep 2
done

# Create DB if not exists
mysql -u root -e "CREATE DATABASE IF NOT EXISTS ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import ecommerce.sql if not imported yet (check by tables count)
TABLE_COUNT=$(mysql -u root -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='ecommerce';" -s -N)

if [ "$TABLE_COUNT" -eq 0 ]; then
  echo "Importing ecommerce.sql"
  mysql -u root ecommerce < /workspaces/APC-2024-2025-T1-01_Queensway_Rebo/ecommerce.sql
else
  echo "Database already has tables, skipping import"
fi

exec "$@"
