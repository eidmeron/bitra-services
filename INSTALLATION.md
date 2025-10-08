# Quick Installation Guide

## Prerequisites

Ensure you have the following installed:
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL 8.0+

## Step-by-Step Installation

### 1. Install Dependencies

```bash
composer install
npm install
```

### 2. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
DB_DATABASE=bitra_tjanster
DB_USERNAME=root
DB_PASSWORD=your_password

APP_LOCALE=sv
APP_FALLBACK_LOCALE=sv
```

### 3. Database Setup

```bash
php artisan migrate
php artisan db:seed
```

### 4. Build Assets

```bash
npm run build
# OR for development:
npm run dev
```

### 5. Storage Setup

```bash
php artisan storage:link
```

### 6. Start Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Default Login

- **Email:** admin@bitratjanster.se
- **Password:** password

## Production Deployment

### Additional Steps for Production:

```bash
# Optimize configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Build production assets
npm run build
```

### Environment Variables for Production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Use a strong random key
APP_KEY=

# Production database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bitra_tjanster_prod
DB_USERNAME=production_user
DB_PASSWORD=strong_password_here

# Mail configuration
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-server.com
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@bitratjanster.se"
```

### Security Checklist:

- [ ] Change default admin password
- [ ] Configure SSL/HTTPS
- [ ] Set up database backups
- [ ] Configure firewall
- [ ] Set proper file permissions
- [ ] Enable rate limiting
- [ ] Configure CORS if needed
- [ ] Set up monitoring
- [ ] Configure error logging
- [ ] Review all .env settings

## Common Issues

### Permission Denied Errors
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache
```

### Database Connection Failed
```bash
# Check MySQL is running
sudo systemctl status mysql

# Check credentials in .env
php artisan config:clear
```

### Assets Not Loading
```bash
npm run build
php artisan view:clear
php artisan cache:clear
```

## Support

For issues, check the README.md or contact support@bitratjanster.se

