=== Bitra Booking Forms ===
Contributors: bitraservices
Tags: booking, forms, embed, iframe, shortcode
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Embed Bitra booking forms seamlessly into your WordPress site using shortcodes.

== Description ==

**Bitra Booking Forms** allows you to easily embed booking forms from Bitra Services into your WordPress site using simple shortcodes.

= Features =

* 🎯 **Easy Embedding** - Use simple shortcodes to embed forms
* 📱 **Responsive Design** - Forms adapt to all screen sizes
* ⚡ **Fast Loading** - Optimized for performance
* 🎨 **Customizable** - Adjust iframe height and more
* 🔒 **Secure** - No data stored on your WordPress site
* 🌐 **Multiple Forms** - Embed multiple forms on different pages

= How to Use =

1. Install and activate the plugin
2. Go to Settings → Bitra Forms
3. Configure your Bitra API URL
4. Get your form token from Bitra dashboard
5. Use shortcode in posts/pages: `[bitra_form token="your-token"]`

= Shortcode Examples =

**Basic Usage:**
`[bitra_form token="abc123xyz"]`

**Custom Height:**
`[bitra_form token="abc123xyz" height="800"]`

**AJAX Embedding (No iframe):**
`[bitra_form token="abc123xyz" type="ajax"]`

= Shortcode Parameters =

* **token** (required) - Your form token from Bitra dashboard
* **height** (optional) - iframe height in pixels (default: 1200)
* **type** (optional) - "iframe" or "ajax" (default: iframe)

== Installation ==

= From WordPress Admin =

1. Go to Plugins → Add New
2. Search for "Bitra Booking Forms"
3. Click "Install Now" and then "Activate"
4. Go to Settings → Bitra Forms to configure

= Manual Installation =

1. Download the plugin zip file
2. Extract to `/wp-content/plugins/bitra-booking-forms/`
3. Activate the plugin through WordPress admin
4. Go to Settings → Bitra Forms to configure

== Frequently Asked Questions ==

= Do I need a Bitra Services account? =

Yes, you need an active Bitra Services account to use this plugin. Visit https://bitraservices.se to create an account.

= Where do I find my form token? =

Log in to your Bitra dashboard, go to Forms, and copy the public token for the form you want to embed.

= Can I embed multiple forms? =

Yes! You can embed as many forms as you want on different pages using different tokens.

= Is the form data secure? =

Yes, all data is processed and stored securely on Bitra servers. Your WordPress site only embeds the form - no sensitive data is stored locally.

= Can I customize the form appearance? =

The form appearance is controlled by Bitra Services. You can adjust the iframe height using the shortcode parameter.

= What's the difference between iframe and ajax embedding? =

* **iframe** (recommended): Form loads in an iframe, completely isolated. Best for most use cases.
* **ajax**: Form content is loaded directly into the page. May have styling conflicts but integrates better visually.

== Screenshots ==

1. Plugin settings page
2. Form embedded on a WordPress page
3. Shortcode examples
4. Mobile responsive view

== Changelog ==

= 1.0.0 =
* Initial release
* iframe embedding support
* AJAX embedding support
* Customizable height
* Admin settings page
* Responsive design
* Loading states
* Error handling

== Upgrade Notice ==

= 1.0.0 =
Initial release of Bitra Booking Forms plugin.

== Support ==

For support and documentation, visit:
https://bitraservices.se/support

== Privacy Policy ==

This plugin embeds forms from Bitra Services. When users interact with embedded forms, their data is sent to and processed by Bitra Services according to their privacy policy.

No user data is stored by this plugin on your WordPress installation.

== Credits ==

Developed by Bitra Services
https://bitraservices.se

