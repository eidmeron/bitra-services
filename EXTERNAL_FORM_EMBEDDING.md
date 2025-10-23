# 🌐 External Form Embedding Guide

This guide explains how to embed Bitra booking forms into external websites using the WordPress plugin or direct JavaScript integration.

## 📋 Quick Start

### For WordPress Sites

1. **Install the Plugin**: Upload the `bitra-booking-forms` plugin to your WordPress site
2. **Configure API URL**: Go to Settings → Bitra Forms and set your API URL to `https://bitra.se`
3. **Get Form Token**: From your Bitra dashboard, copy the form's public token
4. **Add Shortcode**: Use `[bitra_form token="YOUR_TOKEN"]` in any post or page

### For Any Website (HTML/JavaScript)

```html
<div id="bitra-form"></div>
<script src="https://bitra.se/wordpress-shortcode.js" data-form-token="YOUR_TOKEN" data-type="ajax"></script>
```

## 🔧 Available Form Tokens

After fresh migration, the available forms are:

| Form Name | Token | Status |
|-----------|-------|--------|
| Hemstädning - Bokningsformulär | `AEgdP0ZeI7qaUUCGJk2Df4588joRy229` | ✅ Active |
| Gräsklippning - Bokningsformulär | `UO2bPMPNFhYKEOCySvrb6eHBR95ayLRR` | ✅ Active |

## 📖 Integration Methods

### Method 1: WordPress Shortcode

```php
// Basic usage
[bitra_form token="AEgdP0ZeI7qaUUCGJk2Df4588joRy229"]

// With custom height
[bitra_form token="AEgdP0ZeI7qaUUCGJk2Df4588joRy229" height="800"]

// AJAX embedding (no iframe)
[bitra_form token="AEgdP0ZeI7qaUUCGJk2Df4588joRy229" type="ajax"]
```

### Method 2: Direct HTML/JavaScript

```html
<!-- AJAX Embedding (Recommended) -->
<div id="bitra-form"></div>
<script src="https://bitra.se/wordpress-shortcode.js" 
        data-form-token="AEgdP0ZeI7qaUUCGJk2Df4588joRy229" 
        data-type="ajax">
</script>

<!-- Iframe Embedding -->
<div id="bitra-form"></div>
<script src="https://bitra.se/wordpress-shortcode.js" 
        data-form-token="AEgdP0ZeI7qaUUCGJk2Df4588joRy229" 
        data-type="iframe" 
        data-height="1200">
</script>
```

### Method 3: Manual JavaScript API

```html
<div id="bitra-form"></div>
<script>
// Wait for the script to load
window.addEventListener('load', function() {
    if (window.BitraForms) {
        window.BitraForms.loadForm('bitra-form', 'AEgdP0ZeI7qaUUCGJk2Df4588joRy229', {
            type: 'ajax'  // or 'iframe'
        });
    }
});
</script>
```

## ⚙️ Configuration Options

| Option | Description | Default | Values |
|--------|-------------|---------|--------|
| `data-form-token` | **Required.** Form token from Bitra dashboard | - | String |
| `data-type` | Embedding method | `iframe` | `ajax`, `iframe` |
| `data-height` | Height for iframe | `1200px` | CSS height value |
| `data-container` | Custom container ID | `bitra-form-{token}` | String |

## 🎨 Styling

The embedded forms come with built-in responsive styling that works well on most websites. The forms automatically adapt to:

- **Mobile devices**: Responsive design with touch-friendly inputs
- **Dark/Light themes**: Adapts to parent website's color scheme
- **Custom fonts**: Inherits from parent website's font family

### Custom Styling

You can override the default styles by adding CSS after the form loads:

```css
.bitra-booking-form-container .btn-primary {
    background: #your-brand-color !important;
}

.bitra-booking-form-container .form-container {
    border-radius: 20px !important;
}
```

## 🔍 Testing

### Test Page
Visit `https://bitra.se/test-embed.html` to see all embedding methods in action.

### API Endpoints
- **Form HTML**: `https://bitra.se/api/public/form/{token}/html`
- **Form Page**: `https://bitra.se/form/{token}`

### Example Test
```bash
# Test API endpoint
curl "https://bitra.se/api/public/form/AEgdP0ZeI7qaUUCGJk2Df4588joRy229/html"

# Test form page
curl "https://bitra.se/form/AEgdP0ZeI7qaUUCGJk2Df4588joRy229"
```

## 🚨 Troubleshooting

### Common Issues

1. **"Form not found" Error**
   - ✅ Check that the token is correct
   - ✅ Verify the form is active in Bitra dashboard
   - ✅ Ensure the API URL is set to `https://bitra.se`

2. **"Loading..." Never Stops**
   - ✅ Check browser console for JavaScript errors
   - ✅ Verify internet connection
   - ✅ Try iframe method instead of AJAX

3. **Styling Issues**
   - ✅ Check for CSS conflicts with parent website
   - ✅ Use iframe method for better isolation
   - ✅ Add custom CSS overrides

4. **CORS Errors**
   - ✅ The API is configured to allow cross-origin requests
   - ✅ If issues persist, use iframe method

### Debug Mode

Enable debug mode by adding `data-debug="true"` to see detailed error messages:

```html
<script src="https://bitra.se/wordpress-shortcode.js" 
        data-form-token="AEgdP0ZeI7qaUUCGJk2Df4588joRy229" 
        data-debug="true">
</script>
```

## 📱 Mobile Optimization

The embedded forms are fully responsive and optimized for mobile devices:

- **Touch-friendly inputs**: Large tap targets
- **Mobile keyboards**: Proper input types for better UX
- **Responsive layout**: Adapts to all screen sizes
- **Fast loading**: Optimized for mobile networks

## 🔒 Security

- **CSRF Protection**: All forms include CSRF tokens
- **Input Validation**: Server-side validation for all inputs
- **Rate Limiting**: Protection against spam submissions
- **HTTPS Only**: All communications encrypted

## 📞 Support

For technical support or questions:

- **Email**: support@bitra.se
- **Documentation**: https://bitra.se/docs
- **Test Page**: https://bitra.se/test-embed.html

## 🎯 Best Practices

1. **Use AJAX embedding** for better user experience
2. **Test on multiple devices** before going live
3. **Monitor form submissions** in Bitra dashboard
4. **Keep tokens secure** and don't share publicly
5. **Update tokens** if forms are regenerated

---

**Last Updated**: After fresh migration with new form tokens
**Version**: 1.0.0
**API URL**: https://bitra.se
