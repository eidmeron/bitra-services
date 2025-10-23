-- Bitra Tjänster - Database Setup Script
-- Compatible with MySQL 8.0+ and MariaDB 10.3+

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `bitra` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user if it doesn't exist (MySQL 8.0+ syntax)
CREATE USER IF NOT EXISTS 'bitra'@'%' IDENTIFIED BY 'Aa052@759';

-- Grant privileges (separate from user creation for MySQL 8.0+)
GRANT ALL PRIVILEGES ON `bitra`.* TO 'bitra'@'%';

-- Alternative for older MySQL versions (uncomment if needed)
-- GRANT ALL PRIVILEGES ON `bitra`.* TO 'bitra'@'%' IDENTIFIED BY 'Aa052@759';

-- Flush privileges to ensure changes take effect
FLUSH PRIVILEGES;

-- Show current users and privileges
SELECT User, Host FROM mysql.user WHERE User = 'bitra';
SHOW GRANTS FOR 'bitra'@'%';
