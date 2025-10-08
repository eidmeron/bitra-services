# Bitra Tjänster - Swedish Service Booking Platform

A comprehensive Swedish service booking platform built with Laravel 11, featuring dynamic form builders, real-time price calculation, ROT-avdrag (Swedish tax deduction) integration, and WordPress shortcode export capabilities.

## 🌟 Features

### Core Functionality
- **Multi-user System**: Admin, Company, and User roles with dedicated dashboards
- **Zone-based Service Management**: Cities with custom price multipliers
- **Advanced Form Builder**: Drag-and-drop interface with 15+ field types
- **Real-time Price Calculator**: Dynamic pricing with ROT-avdrag integration
- **Booking Workflow**: User → Admin → Company → Completion → Review
- **One-time & Subscription Bookings**: Flexible booking types with frequency options
- **WordPress Integration**: Generate shortcodes for external embedding
- **Public Shareable Forms**: Unique links for each service form
- **Review System**: Customer reviews with approval workflow

### Technical Features
- Laravel 11.x with Breeze authentication
- Blade templates with TailwindCSS 3.x
- Alpine.js 3.x for client-side reactivity
- MySQL 8.0+ database
- Swedish language (sv_SE) by default
- RESTful API for price calculations
- Responsive design

## 📋 Requirements

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL 8.0 or higher
- Git

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone <repository-url> bitra-tjanster
cd bitra-tjanster
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file and configure your database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bitra_tjanster
DB_USERNAME=root
DB_PASSWORD=your_password

APP_LOCALE=sv
APP_FALLBACK_LOCALE=sv
APP_FAKER_LOCALE=sv_SE
```

### 5. Database Migration & Seeding

```bash
php artisan migrate
php artisan db:seed
```

This will create:
- An admin user: `admin@bitratjanster.se` / `password`
- Sample zones and cities (Stockholm, Göteborg, Malmö, etc.)
- Sample categories (Städning, Hantverkare, Trädgård, etc.)
- Sample services with ROT-avdrag enabled

### 6. Build Frontend Assets

```bash
npm run build
```

For development:
```bash
npm run dev
```

### 7. Storage Link

```bash
php artisan storage:link
```

### 8. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000`

## 👥 Default Users

After seeding, you can login with:

**Admin Account:**
- Email: `admin@bitratjanster.se`
- Password: `password`

## 📁 Project Structure

```
bitra-tjanster/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin panel controllers
│   │   │   ├── Company/        # Company dashboard controllers
│   │   │   ├── User/           # User dashboard controllers
│   │   │   ├── PublicFormController.php
│   │   │   └── BookingSubmissionController.php
│   │   ├── Middleware/         # Role-based middleware
│   │   └── Requests/           # Form request validations
│   ├── Models/                 # Eloquent models (14 models)
│   ├── Services/               # Business logic services
│   │   ├── PriceCalculatorService.php
│   │   ├── BookingWorkflowService.php
│   │   ├── FormBuilderService.php
│   │   ├── ShortcodeGeneratorService.php
│   │   └── NotificationService.php
│   └── Helpers/
│       └── helpers.php         # Global helper functions
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/
│   ├── views/
│   │   ├── layouts/            # Layout templates
│   │   ├── admin/              # Admin views
│   │   ├── company/            # Company views
│   │   ├── user/               # User views
│   │   ├── public/             # Public form views
│   │   └── components/         # Blade components
│   ├── js/
│   │   ├── alpine/             # Alpine.js components
│   │   │   ├── form-builder.js
│   │   │   └── price-calculator.js
│   │   └── app.js
│   ├── css/
│   │   └── app.css
│   └── lang/sv/                # Swedish translations
├── routes/
│   ├── web.php                 # Public routes
│   ├── admin.php               # Admin routes
│   ├── company.php             # Company routes
│   └── user.php                # User routes
└── public/
    └── wordpress-shortcode.js  # WordPress integration
```

## 🗄️ Database Schema

### Key Tables
- `users` - Multi-role user authentication
- `companies` - Service provider companies
- `zones` & `cities` - Geographic organization with price multipliers
- `categories` & `services` - Service catalog with ROT-avdrag settings
- `forms` & `form_fields` - Dynamic form builder
- `bookings` - Service bookings with pricing breakdown
- `reviews` - Customer reviews and ratings
- `slot_times` - Availability scheduling

## 💰 Price Calculation Formula

```
Total Price = ((Base Price + Variable Additions) × City Multiplier) 
              - ((Base Price + Variable Additions) × ROT%) 
              - Discount
```

### Example:
- Base Price: 500 kr
- Variable Additions: 200 kr (from form fields)
- City Multiplier: 1.20 (Stockholm)
- ROT Deduction: 30%
- Discount: 10%

**Calculation:**
1. Subtotal: (500 + 200) × 1.20 = 840 kr
2. ROT Deduction: (500 + 200) × 0.30 = 210 kr
3. Discount: (500 + 200) × 0.10 = 70 kr
4. **Final Price: 840 - 210 - 70 = 560 kr**

## 🔧 Configuration

### ROT-avdrag Settings
Edit `config/bitra.php`:

```php
'default_rot_percent' => 30.00,
```

### Booking Settings
```php
'booking' => [
    'number_prefix' => 'BK',
    'auto_assign' => false,
    'require_admin_approval' => true,
],
```

## 📝 Form Builder Usage

### Creating a Form (Admin)

1. Navigate to `Admin > Forms > Create New Form`
2. Select a service
3. Drag and drop fields from the palette
4. Configure field settings (label, placeholder, validation, pricing)
5. Set conditional logic if needed
6. Save and activate the form

### Field Types Supported
- Text, Email, Phone, URL
- Number, Slider
- Textarea
- Select, Radio, Checkbox
- Date, Time
- File upload
- Divider, Container

### Adding Price Rules to Fields

```javascript
// Number field example
{
    "type": "number",
    "pricing_rules": {
        "price_per_unit": 100
    }
}

// Select field example
{
    "type": "select",
    "pricing_rules": {
        "options": [
            {"value": "small", "label": "Liten", "price": 100},
            {"value": "large", "label": "Stor", "price": 200}
        ]
    }
}
```

## 🔗 WordPress Integration

### Embedding Forms in WordPress

1. Create and activate a form in the admin panel
2. Go to `Forms > Shortcode`
3. Copy one of the integration methods:

**Shortcode:**
```
[bitra_form id="bitra_xxxxxxxxxxxx"]
```

**Embed Code:**
```html
<div id="bitra-form-1"></div>
<script src="https://your-domain.com/wordpress-shortcode.js" 
        data-form-token="your_token_here"></script>
```

**iFrame:**
```html
<iframe src="https://your-domain.com/form/your_token_here" 
        width="100%" height="800" frameborder="0"></iframe>
```

## 🎯 Booking Workflow

1. **User Submits Form** → Booking created with status `pending`
2. **Admin Reviews** → Assigns booking to a company (status: `assigned`)
3. **Company Reviews** → Accepts or rejects booking
   - Accept → Status: `in_progress`
   - Reject → Status: `rejected` (returned to admin)
4. **Company Completes** → Status: `completed`
5. **User Reviews** → Leaves rating and review

## 🔒 Security Features

- CSRF protection on all forms
- Role-based access control (Admin, Company, User)
- Email verification
- Password hashing with bcrypt
- SQL injection protection via Eloquent ORM
- XSS protection via Blade templating

## 🌐 API Endpoints

### Public API
- `POST /api/calculate-price` - Calculate booking price
- `GET /api/public/form/{token}/html` - Get form HTML for embedding

### Authentication Required
All admin, company, and user routes require authentication.

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter=BookingTest
```

## 📧 Email Notifications

Configure mail settings in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@bitratjanster.se"
MAIL_FROM_NAME="${APP_NAME}"
```

## 🐛 Troubleshooting

### Common Issues

**Database Connection Error:**
```bash
# Check database credentials in .env
php artisan config:cache
```

**Assets Not Loading:**
```bash
npm run build
php artisan storage:link
```

**Permission Issues:**
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [TailwindCSS Documentation](https://tailwindcss.com/docs)
- [Alpine.js Documentation](https://alpinejs.dev)
- [Skatteverket ROT-avdrag](https://www.skatteverket.se/privat/fastigheterochbostad/rotochrutarbete.4.2e56d4ba1202f95012080001979.html)

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is open-sourced software licensed under the MIT license.

## 👨‍💻 Development Team

Developed by [Your Company Name]

## 📞 Support

For support, email support@bitratjanster.se or open an issue in the repository.

---

**Note:** This is a production-ready application template. Make sure to:
- Change all default passwords before deploying
- Configure proper email settings
- Set up SSL certificate for production
- Configure proper backup strategy
- Review and update security settings
- Customize branding and styling as needed
