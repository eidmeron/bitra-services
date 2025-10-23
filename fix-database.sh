#!/bin/bash

# Bitra Tjänster - Database Fix Script
# This script fixes the MySQL 8.0+ syntax issue

echo "🔧 Fixing Bitra Database Setup..."

# Read database credentials from .env
if [ ! -f .env ]; then
    echo "❌ Error: .env file not found!"
    exit 1
fi

DB_HOST=$(grep DB_HOST .env | cut -d '=' -f2 | tr -d ' ')
DB_PORT=$(grep DB_PORT .env | cut -d '=' -f2 | tr -d ' ')
DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f2 | tr -d ' ')
DB_USERNAME=$(grep DB_USERNAME .env | cut -d '=' -f2 | tr -d ' ')
DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d '=' -f2 | tr -d ' ')

echo "Database: $DB_DATABASE"
echo "User: $DB_USERNAME"
echo "Host: $DB_HOST:$DB_PORT"

echo ""
echo "🔑 Please enter your MySQL root password:"

# Create the database and user with MySQL 8.0+ compatible syntax
mysql -h"$DB_HOST" -P"$DB_PORT" -u root -p << EOF
-- Drop existing user if exists (to avoid conflicts)
DROP USER IF EXISTS '$DB_USERNAME'@'%';

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS \`$DB_DATABASE\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user with MySQL 8.0+ syntax
CREATE USER '$DB_USERNAME'@'%' IDENTIFIED BY '$DB_PASSWORD';

-- Grant privileges (separate from user creation)
GRANT ALL PRIVILEGES ON \`$DB_DATABASE\`.* TO '$DB_USERNAME'@'%';

-- Flush privileges
FLUSH PRIVILEGES;

-- Verify the setup
SELECT 'Database and user created successfully!' as Status;
SELECT User, Host FROM mysql.user WHERE User = '$DB_USERNAME';
SHOW GRANTS FOR '$DB_USERNAME'@'%';
EOF

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Database setup completed successfully!"
    echo ""
    echo "🧪 Testing connection..."
    
    # Test the connection
    if php artisan tinker --execute="echo 'Testing DB connection...'; try { DB::connection()->getPdo(); echo '✅ Database connection successful'; } catch (Exception \$e) { echo '❌ Database connection failed: ' . \$e->getMessage(); }" 2>/dev/null; then
        echo "✅ Database connection verified"
        echo ""
        echo "🚀 You can now run your deployment script:"
        echo "   ./deploy.sh"
    else
        echo "❌ Database connection test failed"
        echo "Please check your .env file configuration"
    fi
else
    echo "❌ Database setup failed"
    echo "Please check your MySQL root password and try again"
fi
