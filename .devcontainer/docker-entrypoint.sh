#!/bin/bash
set -e

# Start MariaDB service
service mysql start

# Wait for MariaDB to start with timeout
timeout=30
while ! mysqladmin ping --silent; do
  echo "Waiting for MariaDB to start..."
  sleep 2
  timeout=$((timeout - 2))
  if [ $timeout -le 0 ]; then
    echo "MariaDB failed to start in time!"
    exit 1
  fi
done

# Create DB if not exists
mysql -u root -e "CREATE DATABASE IF NOT EXISTS ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import SQL only if empty
TABLE_COUNT=$(mysql -u root -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='ecommerce';" -s -N)
if [ "$TABLE_COUNT" -eq 0 ]; then
  echo "Importing ecommerce.sql"
  mysql -u root ecommerce < /workspace/ecommerce.sql
else
  echo "Database already has tables, skipping import"
fi

exec "$@"
