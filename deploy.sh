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

# Pull latest changes
echo "📥 Pulling latest changes..."
git pull origin main

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

