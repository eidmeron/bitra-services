# 🚀 Production Deployment Guide - Bitra Tjänster

This comprehensive guide will help you deploy Bitra Tjänster to a production server.

## 📋 Prerequisites

### Server Requirements
- **OS**: Ubuntu 20.04 LTS or later (recommended)
- **PHP**: 8.1 or higher
- **Web Server**: Nginx or Apache
- **Database**: MySQL 8.0+ or MariaDB 10.3+
- **Node.js**: 18.x or later
- **Memory**: Minimum 1GB RAM (2GB+ recommended)
- **Storage**: Minimum 10GB free space

### Required PHP Extensions
```bash
php-cli
php-fpm
php-mysql
php-mbstring
php-xml
php-bcmath
php-curl
php-zip
php-gd
php-intl
```

### Tools
- Composer
- NPM
- Git
- SSL certificate (Let's Encrypt recommended)

## 🔧 Step-by-Step Deployment

### 1. Server Setup (Ubuntu)

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd php8.2-intl -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js 18.x
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# Install Nginx
sudo apt install nginx -y

# Install MySQL
sudo apt install mysql-server -y
sudo mysql_secure_installation
```

### 2. Create Database

```bash
# Login to MySQL
sudo mysql -u root -p

# Create database and user
CREATE DATABASE bitra_tjanster CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'bitra_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON bitra_tjanster.* TO 'bitra_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Clone and Setup Application

```bash
# Navigate to web root
cd /var/www

# Clone repository
sudo git clone <your-repository-url> bitra-tjanster
cd bitra-tjanster

# Set ownership
sudo chown -R www-data:www-data /var/www/bitra-tjanster
sudo chmod -R 755 /var/www/bitra-tjanster

# Set special permissions
sudo chmod -R 775 storage bootstrap/cache
```

### 4. Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Edit .env file
sudo nano .env
```

### Production `.env` Configuration:

```env
APP_NAME="Bitra Tjänster"
APP_ENV=production
APP_KEY=  # Will be generated
APP_DEBUG=false
APP_URL=https://your-domain.com
APP_LOCALE=sv
APP_FALLBACK_LOCALE=sv

LOG_CHANNEL=daily
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bitra_tjanster
DB_USERNAME=bitra_user
DB_PASSWORD=strong_password_here

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Mail Configuration (Example with SendGrid)
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@bitratjanster.se"
MAIL_FROM_NAME="${APP_NAME}"

# Performance
CACHE_PREFIX=bitra
```

### 5. Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Generate application key
php artisan key:generate

# Install Node dependencies
npm ci

# Build production assets
npm run build
```

### 6. Run Migrations

```bash
# Run migrations
php artisan migrate --force

# Seed database
php artisan db:seed --force
```

### 7. Optimize Application

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Create storage link
php artisan storage:link

# Optimize Composer autoloader
composer dump-autoload --optimize
```

### 8. Configure Nginx

Create Nginx configuration:

```bash
sudo nano /etc/nginx/sites-available/bitra-tjanster
```

Add this configuration:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/bitra-tjanster/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Increase upload size for company logos
    client_max_body_size 10M;
}
```

Enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/bitra-tjanster /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 9. Install SSL Certificate

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtain certificate
sudo certbot --nginx -d your-domain.com -d www.your-domain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

### 10. Setup Queue Worker (Optional)

Create systemd service:

```bash
sudo nano /etc/systemd/system/bitra-queue.service
```

Add:

```ini
[Unit]
Description=Bitra Tjänster Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
Group=www-data
Restart=always
RestartSec=3
ExecStart=/usr/bin/php /var/www/bitra-tjanster/artisan queue:work --sleep=3 --tries=3

[Install]
WantedBy=multi-user.target
```

Enable and start:

```bash
sudo systemctl enable bitra-queue
sudo systemctl start bitra-queue
```

### 11. Setup Scheduler

Add to crontab:

```bash
sudo crontab -e -u www-data
```

Add line:

```
* * * * * cd /var/www/bitra-tjanster && php artisan schedule:run >> /dev/null 2>&1
```

### 12. Setup Backups

Create backup script:

```bash
sudo nano /usr/local/bin/backup-bitra.sh
```

Add:

```bash
#!/bin/bash
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/bitra"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u bitra_user -p'your_password' bitra_tjanster | gzip > $BACKUP_DIR/db_$TIMESTAMP.sql.gz

# Backup files
tar -czf $BACKUP_DIR/files_$TIMESTAMP.tar.gz /var/www/bitra-tjanster

# Keep only last 7 days
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completed: $TIMESTAMP"
```

Make executable and add to cron:

```bash
sudo chmod +x /usr/local/bin/backup-bitra.sh
sudo crontab -e
```

Add daily backup at 2 AM:

```
0 2 * * * /usr/local/bin/backup-bitra.sh >> /var/log/bitra-backup.log 2>&1
```

## 🔐 Security Hardening

### 1. Firewall Configuration

```bash
# Enable UFW
sudo ufw enable

# Allow SSH
sudo ufw allow 22/tcp

# Allow HTTP/HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Check status
sudo ufw status
```

### 2. Secure File Permissions

```bash
# Set proper ownership
sudo chown -R www-data:www-data /var/www/bitra-tjanster

# Secure permissions
sudo find /var/www/bitra-tjanster -type f -exec chmod 644 {} \;
sudo find /var/www/bitra-tjanster -type d -exec chmod 755 {} \;

# Storage and cache writable
sudo chmod -R 775 /var/www/bitra-tjanster/storage
sudo chmod -R 775 /var/www/bitra-tjanster/bootstrap/cache
```

### 3. Disable Unnecessary PHP Functions

Edit php.ini:

```bash
sudo nano /etc/php/8.2/fpm/php.ini
```

Add:

```ini
disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source
```

### 4. Rate Limiting

Already configured in Laravel. Monitor and adjust in:
`config/sanctum.php` and middleware.

## 📊 Monitoring

### 1. Setup Log Monitoring

```bash
# Install logwatch
sudo apt install logwatch -y

# View logs
tail -f /var/www/bitra-tjanster/storage/logs/laravel.log
```

### 2. Monitor Queue

```bash
php artisan queue:monitor
```

### 3. Check Application Status

```bash
# Check queue workers
sudo systemctl status bitra-queue

# Check Nginx
sudo systemctl status nginx

# Check PHP-FPM
sudo systemctl status php8.2-fpm
```

## 🔄 Updating Application

Use the deployment script:

```bash
cd /var/www/bitra-tjanster
sudo -u www-data ./deploy.sh
```

Or manually:

```bash
# Pull changes
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

## ✅ Post-Deployment Checklist

- [ ] Site accessible via HTTPS
- [ ] Admin login works
- [ ] Booking form submits successfully
- [ ] Email sending works
- [ ] File uploads work
- [ ] Price calculator functions
- [ ] WordPress shortcode works
- [ ] SSL certificate auto-renews
- [ ] Backups running daily
- [ ] Queue workers running
- [ ] Logs being monitored
- [ ] Firewall configured
- [ ] Database backups automated
- [ ] Error pages customized
- [ ] Analytics installed
- [ ] Performance optimized
- [ ] Security headers set
- [ ] Rate limiting active

## 🐛 Troubleshooting

### Permission Issues
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### Cache Issues
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Database Connection
```bash
# Test connection
php artisan tinker
> DB::connection()->getPdo();
```

### Queue Not Processing
```bash
sudo systemctl restart bitra-queue
sudo systemctl status bitra-queue
```

## 📞 Support

For deployment assistance:
- Email: support@bitratjanster.se
- Check logs: `storage/logs/laravel.log`
- Nginx logs: `/var/log/nginx/error.log`

## 🎉 Success!

Your Bitra Tjänster platform is now deployed and ready for production use!

**Important**: Change the default admin password immediately after first login.

---

**Version**: 1.0.0  
**Last Updated**: 2024-10-08

