#!/bin/bash

# ---------------------------------------------
# Automated Setup for Laravel Project + MySQL
# ---------------------------------------------

# Update package list and install necessary packages
echo "Updating package list..."
sudo apt update -y

# Install PHP and required extensions
echo "Installing PHP and extensions..."
sudo apt install -y php php-cli php-fpm php-mbstring php-xml php-mysql php-curl unzip

# Install Composer (PHP dependency manager)
echo "Installing Composer..."
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js and NPM (required for Laravel Mix if using it)
echo "Installing Node.js and NPM..."
sudo apt install -y nodejs npm

# Install Laravel project dependencies
echo "Installing Laravel dependencies using Composer..."
composer install

# Install MySQL server
echo "Installing MySQL..."
sudo apt-get install -y mysql-server
sudo service mysql start

# Securing MySQL installation (optional but recommended)
echo "Securing MySQL installation..."
sudo mysql_secure_installation

# Prompt for MySQL root password (secure method)
echo "Please enter the MySQL root password:"
read -s DB_PASSWORD

# Create a new MySQL database and user (adjust as needed)
echo "Setting up MySQL database and user..."
mysql -u root -p -e "
  CREATE DATABASE ecommerce;
  CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
  GRANT ALL PRIVILEGES ON ecommerce.* TO 'laravel_user'@'localhost';
  FLUSH PRIVILEGES;
"

# Copy .env.example to .env if it doesn't exist yet
echo "Creating .env file from .env.example..."
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Prompt for sensitive credentials and update .env file
echo "Updating .env file with sensitive credentials..."

# Prompt for Mail Password
echo "Please enter your Mail Password (used for sending emails via SMTP):"
read -s MAIL_PASSWORD

# Prompt for PayPal Client ID
echo "Please enter your PayPal Client ID:"
read PAYPAL_CLIENT_ID

# Update .env file with sensitive credentials
sed -i "s/DB_PASSWORD=secret/DB_PASSWORD=$DB_PASSWORD/" .env
sed -i "s/MAIL_PASSWORD=your_mail_password/MAIL_PASSWORD=$MAIL_PASSWORD/" .env
sed -i "s/PAYPAL_CLIENT_ID=YOUR_PAYPAL_CLIENT_ID_HERE/PAYPAL_CLIENT_ID=$PAYPAL_CLIENT_ID/" .env

# Import eCommerce SQL database into MySQL (if SQL file exists in root directory)
if [ -f "ecommerce.sql" ]; then
  echo "Importing eCommerce database from ecommerce.sql..."
  mysql -u laravel_user -p"$DB_PASSWORD" ecommerce < ecommerce.sql
else
  echo "No ecommerce.sql file found in the root directory!"
fi

# Run migrations to create tables in MySQL
echo "Running Laravel migrations..."
php artisan migrate

# Set up Laravel development server to run on all interfaces (0.0.0.0)
echo "Starting Laravel development server..."
php artisan serve --host=0.0.0.0 --port=8000 &

# Success message
echo "Setup complete! You can access your Laravel app at http://localhost:8000"
echo "Database 'ecommerce' is ready with user 'laravel_user'."
