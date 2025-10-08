#!/bin/bash

# Bitra Tjänster - Quick Setup Script
# This script helps you set up the application quickly

echo "🎉 Welcome to Bitra Tjänster Setup!"
echo "=================================="
echo ""

# Check PHP version
echo "Checking PHP version..."
php_version=$(php -r "echo PHP_VERSION;")
echo "✓ PHP version: $php_version"

# Check Composer
if ! command -v composer &> /dev/null; then
    echo "❌ Composer not found! Please install Composer first."
    exit 1
fi
echo "✓ Composer is installed"

# Check Node
if ! command -v node &> /dev/null; then
    echo "❌ Node.js not found! Please install Node.js first."
    exit 1
fi
echo "✓ Node.js is installed"

# Check NPM
if ! command -v npm &> /dev/null; then
    echo "❌ NPM not found! Please install NPM first."
    exit 1
fi
echo "✓ NPM is installed"

echo ""
echo "📦 Installing dependencies..."

# Install Composer dependencies
echo "Installing Composer dependencies..."
composer install

# Install NPM dependencies
echo "Installing NPM dependencies..."
npm install

# Setup environment file
if [ ! -f .env ]; then
    echo ""
    echo "📝 Setting up environment file..."
    cp .env.example .env
    php artisan key:generate
    echo "✓ Environment file created and key generated"
    echo ""
    echo "⚠️  IMPORTANT: Please edit .env file and configure your database settings!"
    echo ""
    read -p "Press enter when you have configured the database in .env..."
else
    echo "✓ .env file already exists"
fi

# Ask about database migration
echo ""
read -p "Do you want to run database migrations now? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate
    
    # Ask about seeding
    read -p "Do you want to seed the database with sample data? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        php artisan db:seed
        echo ""
        echo "✓ Database seeded successfully!"
        echo ""
        echo "📧 Default admin login:"
        echo "   Email: admin@bitratjanster.se"
        echo "   Password: password"
    fi
fi

# Build assets
echo ""
echo "🎨 Building assets..."
npm run build

# Create storage link
echo ""
echo "🔗 Creating storage link..."
php artisan storage:link

# Set permissions
echo ""
echo "🔒 Setting permissions..."
chmod -R 775 storage bootstrap/cache

echo ""
echo "✅ Setup completed successfully!"
echo ""
echo "🚀 To start the development server, run:"
echo "   php artisan serve"
echo ""
echo "Then visit: http://localhost:8000"
echo ""
echo "📚 For more information, check:"
echo "   - README.md"
echo "   - GETTING_STARTED.md"
echo "   - INSTALLATION.md"
echo ""
echo "Happy coding! 🎉"

