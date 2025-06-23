#!/bin/bash

# Wait for MariaDB to be ready
until mysqladmin ping -h "localhost" --silent; do
  echo "Waiting for MariaDB to be ready..."
  sleep 2
done

echo "MariaDB is ready. Importing ecommerce.sql..."
mysql -uroot -proot ecommerce < /workspace/ecommerce.sql || echo "SQL already imported or error."
