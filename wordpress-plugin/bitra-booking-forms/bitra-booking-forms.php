<?php
/**
 * Plugin Name: Bitra Booking Forms
 * Plugin URI: https://bitraservices.se
 * Description: Embed Bitra booking forms seamlessly into your WordPress site using shortcodes
 * Version: 1.0.0
 * Author: Bitra Services
 * Author URI: https://bitraservices.se
 * Text Domain: bitra-booking-forms
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Plugin constants
define('BITRA_BOOKING_VERSION', '1.0.0');
define('BITRA_BOOKING_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('BITRA_BOOKING_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main Plugin Class
 */
class Bitra_Booking_Forms {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        // Initialize plugin
        add_action('init', array($this, 'init'));
        
        // Register shortcode
        add_shortcode('bitra_form', array($this, 'form_shortcode'));
        
        // Admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        
        // Enqueue styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        load_plugin_textdomain('bitra-booking-forms', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    /**
     * Enqueue frontend styles
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            'bitra-booking-forms',
            BITRA_BOOKING_PLUGIN_URL . 'assets/style.css',
            array(),
            BITRA_BOOKING_VERSION
        );
    }
    
    /**
     * Shortcode handler
     * Usage: [bitra_form token="your-form-token"]
     * Optional: [bitra_form token="your-form-token" height="800"]
     */
    public function form_shortcode($atts) {
        // Default attributes
        $atts = shortcode_atts(array(
            'token' => '',
            'height' => '1200',
            'type' => 'iframe', // iframe or ajax
        ), $atts, 'bitra_form');
        
        // Validate token
        if (empty($atts['token'])) {
            return '<div class="bitra-error">⚠️ Error: Form token is required. Usage: [bitra_form token="your-token"]</div>';
        }
        
        // Get API URL from settings
        $api_url = get_option('bitra_api_url', 'http://127.0.0.1:8000');
        $api_url = rtrim($api_url, '/');
        
        // Build form URL
        $form_url = $api_url . '/form/' . esc_attr($atts['token']);
        
        // Return based on type
        if ($atts['type'] === 'ajax') {
            return $this->render_ajax_form($form_url, $atts['token']);
        } else {
            return $this->render_iframe_form($form_url, $atts['height']);
        }
    }
    
    /**
     * Render iframe form
     */
    private function render_iframe_form($form_url, $height) {
        $output = '<div class="bitra-booking-form-container">';
        $output .= '<iframe ';
        $output .= 'src="' . esc_url($form_url) . '" ';
        $output .= 'width="100%" ';
        $output .= 'height="' . esc_attr($height) . 'px" ';
        $output .= 'frameborder="0" ';
        $output .= 'scrolling="auto" ';
        $output .= 'class="bitra-booking-iframe" ';
        $output .= 'allowfullscreen>';
        $output .= '</iframe>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Render AJAX form (embedded directly)
     */
    private function render_ajax_form($form_url, $token) {
        $api_url = get_option('bitra_api_url', 'http://127.0.0.1:8000');
        $api_url = rtrim($api_url, '/');
        $ajax_url = $api_url . '/api/public/form/' . esc_attr($token) . '/html';
        
        $output = '<div class="bitra-booking-form-ajax" id="bitra-form-' . esc_attr($token) . '">';
        $output .= '<div class="bitra-loading">⏳ Laddar formulär...</div>';
        $output .= '</div>';
        $output .= '<script>
            (function() {
                fetch("' . esc_url($ajax_url) . '")
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById("bitra-form-' . esc_attr($token) . '").innerHTML = html;
                    })
                    .catch(error => {
                        document.getElementById("bitra-form-' . esc_attr($token) . '").innerHTML = "<div class=\"bitra-error\">❌ Error loading form. Please try again later.</div>";
                        console.error("Bitra Form Error:", error);
                    });
            })();
        </script>';
        
        return $output;
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('Bitra Booking Forms', 'bitra-booking-forms'),
            __('Bitra Forms', 'bitra-booking-forms'),
            'manage_options',
            'bitra-booking-forms',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('bitra_booking_forms', 'bitra_api_url');
        register_setting('bitra_booking_forms', 'bitra_default_height');
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>⚙️ <?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="notice notice-info">
                <p><strong>📋 How to Use:</strong></p>
                <ol>
                    <li>Configure your Bitra API URL below</li>
                    <li>Get your form token from Bitra dashboard</li>
                    <li>Use shortcode in posts/pages: <code>[bitra_form token="YOUR-TOKEN"]</code></li>
                </ol>
            </div>
            
            <form method="post" action="options.php">
                <?php settings_fields('bitra_booking_forms'); ?>
                <?php do_settings_sections('bitra_booking_forms'); ?>
                
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">
                            <label for="bitra_api_url"><?php _e('Bitra API URL', 'bitra-booking-forms'); ?></label>
                        </th>
                        <td>
                            <input 
                                type="url" 
                                name="bitra_api_url" 
                                id="bitra_api_url" 
                                value="<?php echo esc_attr(get_option('bitra_api_url', 'http://127.0.0.1:8000')); ?>" 
                                class="regular-text"
                                placeholder="https://bitraservices.se"
                            />
                            <p class="description">
                                <?php _e('Your Bitra Services installation URL (without trailing slash)', 'bitra-booking-forms'); ?>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="bitra_default_height"><?php _e('Default iframe Height (px)', 'bitra-booking-forms'); ?></label>
                        </th>
                        <td>
                            <input 
                                type="number" 
                                name="bitra_default_height" 
                                id="bitra_default_height" 
                                value="<?php echo esc_attr(get_option('bitra_default_height', '1200')); ?>" 
                                class="small-text"
                                min="400"
                                max="3000"
                            />
                            <p class="description">
                                <?php _e('Default height for form iframes (can be overridden in shortcode)', 'bitra-booking-forms'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
            
            <hr>
            
            <h2>📖 <?php _e('Shortcode Examples', 'bitra-booking-forms'); ?></h2>
            
            <div class="card">
                <h3><?php _e('Basic Usage (iframe)', 'bitra-booking-forms'); ?></h3>
                <code>[bitra_form token="abc123xyz"]</code>
                <p class="description"><?php _e('Embeds form in an iframe with default height', 'bitra-booking-forms'); ?></p>
            </div>
            
            <div class="card">
                <h3><?php _e('Custom Height', 'bitra-booking-forms'); ?></h3>
                <code>[bitra_form token="abc123xyz" height="800"]</code>
                <p class="description"><?php _e('Embeds form with custom height', 'bitra-booking-forms'); ?></p>
            </div>
            
            <div class="card">
                <h3><?php _e('AJAX Embedding (No iframe)', 'bitra-booking-forms'); ?></h3>
                <code>[bitra_form token="abc123xyz" type="ajax"]</code>
                <p class="description"><?php _e('Embeds form directly without iframe (experimental)', 'bitra-booking-forms'); ?></p>
            </div>
            
            <hr>
            
            <h2>🆘 <?php _e('Support', 'bitra-booking-forms'); ?></h2>
            <p>
                <?php _e('For support, please visit:', 'bitra-booking-forms'); ?>
                <a href="https://bitraservices.se/support" target="_blank">https://bitraservices.se/support</a>
            </p>
        </div>
        <?php
    }
}

// Initialize plugin
function bitra_booking_forms_init() {
    return Bitra_Booking_Forms::get_instance();
}

// Start plugin
add_action('plugins_loaded', 'bitra_booking_forms_init');

