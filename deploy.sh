#!/bin/bash

# Bitra Tjänster - Deployment Script
# This script handles the deployment process for production

echo "🚀 Starting Bitra Tjänster Deployment..."

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ Error: .env file not found!"
    echo "Please copy .env.example to .env and configure it first."
    exit 1
fi

# Function to check MySQL connection
check_mysql_connection() {
    echo "🔍 Checking MySQL connection..."
    if php artisan tinker --execute="echo 'Testing DB connection...'; try { DB::connection()->getPdo(); echo '✅ Database connection successful'; } catch (Exception \$e) { echo '❌ Database connection failed: ' . \$e->getMessage(); exit(1); }" > /dev/null 2>&1; then
        echo "✅ Database connection verified"
        return 0
    else
        echo "❌ Database connection failed"
        return 1
    fi
}

# Function to setup database user (MySQL 8.0+ compatible)
setup_database_user() {
    echo "🗄️  Setting up database user..."
    
    # Read database credentials from .env
    DB_HOST=$(grep DB_HOST .env | cut -d '=' -f2 | tr -d ' ')
    DB_PORT=$(grep DB_PORT .env | cut -d '=' -f2 | tr -d ' ')
    DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f2 | tr -d ' ')
    DB_USERNAME=$(grep DB_USERNAME .env | cut -d '=' -f2 | tr -d ' ')
    DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d '=' -f2 | tr -d ' ')
    
    echo "Database: $DB_DATABASE"
    echo "User: $DB_USERNAME"
    echo "Host: $DB_HOST:$DB_PORT"
    
    # Create SQL commands for MySQL 8.0+ compatibility
    mysql -h"$DB_HOST" -P"$DB_PORT" -u root -p << EOF
-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS \`$DB_DATABASE\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user if it doesn't exist (MySQL 8.0+ syntax)
CREATE USER IF NOT EXISTS '$DB_USERNAME'@'%' IDENTIFIED BY '$DB_PASSWORD';

-- Grant privileges (separate from user creation for MySQL 8.0+)
GRANT ALL PRIVILEGES ON \`$DB_DATABASE\`.* TO '$DB_USERNAME'@'%';

-- Flush privileges to ensure changes take effect
FLUSH PRIVILEGES;

-- Show current users and privileges
SELECT User, Host FROM mysql.user WHERE User = '$DB_USERNAME';
SHOW GRANTS FOR '$DB_USERNAME'@'%';
EOF

    if [ $? -eq 0 ]; then
        echo "✅ Database user setup completed"
    else
        echo "❌ Database user setup failed"
        echo "Please run the database setup manually:"
        echo "mysql -u root -p < database-setup.sql"
        exit 1
    fi
}

# Pull latest changes
echo "📥 Pulling latest changes..."
git pull origin main

# Setup database user (MySQL 8.0+ compatible)
setup_database_user

# Check database connection
if ! check_mysql_connection; then
    echo "❌ Database connection failed. Please check your .env configuration."
    exit 1
fi

# Install/Update Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Install/Update NPM dependencies
echo "📦 Installing NPM dependencies..."
npm install

# Build assets
echo "🎨 Building production assets..."
npm run build

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Clear and cache config
echo "⚙️  Optimizing configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear old caches
echo "🧹 Clearing old caches..."
php artisan cache:clear

# Set permissions
echo "🔒 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Create storage link if not exists
echo "🔗 Creating storage link..."
php artisan storage:link

# Restart queue workers (if using)
echo "🔄 Restarting queue workers..."
php artisan queue:restart

echo "✅ Deployment completed successfully!"
echo ""
echo "📋 Post-deployment checklist:"
echo "  - Check that the site is accessible"
echo "  - Test login functionality"
echo "  - Verify booking form works"
echo "  - Check admin panel"
echo "  - Monitor error logs: storage/logs/laravel.log"
echo ""
echo "🎉 Happy booking!"

