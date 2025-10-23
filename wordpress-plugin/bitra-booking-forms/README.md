# Bitra Booking Forms - WordPress Plugin

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-5.0+-green.svg)
![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)
![License](https://img.shields.io/badge/license-GPLv2-orange.svg)

Embed Bitra booking forms seamlessly into your WordPress site using shortcodes.

## 🎯 Features

- ✅ **Easy Integration** - Simple shortcode-based embedding
- ✅ **Responsive Design** - Works perfectly on all devices
- ✅ **Multiple Embedding Methods** - iframe or AJAX
- ✅ **Customizable** - Control height and appearance
- ✅ **Lightweight** - Minimal impact on site performance
- ✅ **Secure** - No data stored locally
- ✅ **Multiple Forms** - Embed different forms on different pages

## 📦 Installation

### Method 1: WordPress Admin

1. Download the plugin ZIP file
2. Go to **Plugins → Add New**
3. Click **Upload Plugin**
4. Choose the ZIP file and click **Install Now**
5. Click **Activate Plugin**

### Method 2: FTP Upload

1. Extract the plugin ZIP file
2. Upload the `bitra-booking-forms` folder to `/wp-content/plugins/`
3. Activate the plugin through the **Plugins** menu in WordPress

### Method 3: Direct Install

```bash
cd /path/to/wordpress/wp-content/plugins/
git clone [repository-url] bitra-booking-forms
```

## ⚙️ Configuration

1. Go to **Settings → Bitra Forms**
2. Enter your **Bitra API URL** (e.g., `https://bitraservices.se`)
3. Set the **Default iframe Height** (optional, default: 1200px)
4. Click **Save Changes**

## 📝 Usage

### Basic Shortcode

```
[bitra_form token="your-form-token"]
```

### Advanced Usage

#### Custom Height

```
[bitra_form token="your-form-token" height="800"]
```

#### AJAX Embedding (No iframe)

```
[bitra_form token="your-form-token" type="ajax"]
```

## 🔧 Shortcode Parameters

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `token` | string | Yes | - | Your form token from Bitra dashboard |
| `height` | integer | No | 1200 | iframe height in pixels |
| `type` | string | No | iframe | Embedding method: "iframe" or "ajax" |

## 📋 Examples

### Example 1: Booking Form on Contact Page

```
[bitra_form token="abc123xyz"]
```

### Example 2: Compact Form in Sidebar Widget

```
[bitra_form token="abc123xyz" height="600"]
```

### Example 3: Multiple Forms

**Service 1 Page:**
```
[bitra_form token="service1-token"]
```

**Service 2 Page:**
```
[bitra_form token="service2-token"]
```

## 🎨 Styling

The plugin includes default styling that makes forms look great out of the box. If you need custom styling, add CSS to your theme:

```css
/* Custom iframe container styling */
.bitra-booking-form-container {
    max-width: 800px;
    margin: 3rem auto;
}

/* Custom iframe styling */
.bitra-booking-iframe {
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}
```

## 🔒 Security

- All form data is processed securely by Bitra Services
- No sensitive data is stored on your WordPress site
- Forms are embedded via secure HTTPS
- Follows WordPress coding standards

## 🐛 Troubleshooting

### Form Not Loading?

1. Check your API URL in settings (no trailing slash)
2. Verify your form token is correct
3. Check browser console for errors
4. Ensure your site allows iframes

### Styling Issues?

1. Try AJAX embedding instead: `type="ajax"`
2. Check for CSS conflicts with your theme
3. Increase iframe height if content is cut off

### Iframe Not Responsive?

1. Ensure your theme is responsive
2. Check viewport meta tag in theme header
3. Test with default WordPress theme

## 📞 Support

- **Documentation:** https://bitraservices.se/docs
- **Support:** https://bitraservices.se/support
- **Email:** support@bitraservices.se

## 📄 License

This plugin is licensed under GPL v2 or later.

```
Copyright (C) 2025 Bitra Services

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## 🚀 Changelog

### Version 1.0.0 (2025-01-17)

**Initial Release**
- iframe embedding support
- AJAX embedding support
- Admin settings page
- Responsive design
- Loading and error states
- Shortcode with multiple parameters
- Custom height control
- Comprehensive documentation

## 🤝 Contributing

We welcome contributions! Feel free to submit issues and pull requests.

## 📊 Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Active Bitra Services account

## 🎉 Credits

Developed with ❤️ by [Bitra Services](https://bitraservices.se)

---

**Made in Sweden 🇸🇪**

