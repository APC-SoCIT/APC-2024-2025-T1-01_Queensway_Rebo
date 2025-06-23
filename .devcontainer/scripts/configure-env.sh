#!/bin/bash

# Copy .env.example to .env if it doesn't exist
if [ ! -f .env ]; then
    echo "Copying .env.example to .env..."
    cp .env.example .env
fi

# Set up database connection in .env
echo "Configuring environment variables..."
sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mariadb/g' .env
sed -i 's/DB_PORT=3306/DB_PORT=3306/g' .env
sed -i 's/DB_DATABASE=homestead/DB_DATABASE=laravel_ecommerce/g' .env
sed -i 's/DB_USERNAME=root/DB_USERNAME=root/g' .env
sed -i 's/DB_PASSWORD=secret/DB_PASSWORD=secret/g' .env
