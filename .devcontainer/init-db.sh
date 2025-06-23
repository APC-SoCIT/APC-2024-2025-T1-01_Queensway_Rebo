#!/bin/bash
# Wait for MySQL to be ready
until mysqladmin ping -h db -uroot -proot --silent; do
  echo "Waiting for MySQL to be ready..."
  sleep 2
done

echo "Importing ecommerce.sql..."
mysql -h db -uroot -proot ecommerce < /workspace/ecommerce.sql || echo "SQL already imported or error."
