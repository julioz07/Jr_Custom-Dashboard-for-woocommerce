<?php
/**
 * Plugin Name: WooCustom Dashboard
 * Plugin URI: https://github.com/julio-cr/woo-custom-dashboard
 * Description: A modular and extensible plugin to customize the WooCommerce customer dashboard with enhanced features and a modern interface.
 * Version: 1.4.1
 * Author: Júlio Rodrigues
 * Author URI: https://julio-cr.pt
 * Text Domain: woo-custom-dashboard
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 7.0
 * WC tested up to: 8.0
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * 
 * Woo: 12345:342928dfsfhsf8429842374wdf4234sfd
 * WC requires at least: 7.0
 * WC tested up to: 8.0
 * Woo: 12345:342928dfsfhsf8429842374wdf4234sfd
 * WooCommerce: 7.0
 *
 * @package WooCustom_Dashboard
 * @author Júlio Rodrigues
 * @link https://julio-cr.pt
 * @link https://www.linkedin.com/in/juliocesarrodrigues07/
 */

// Declare HPOS compatibility
add_action('before_woocommerce_init', function() {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('WCD_VERSION', '1.4.1');
define('WCD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WCD_PLUGIN_URL', plugin_dir_url(__FILE__));

// Check if WooCommerce is active
function wcd_check_woocommerce() {
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', function() {
            echo '<div class="error"><p>';
            echo esc_html__('O WooCustom Dashboard requer que o WooCommerce esteja instalado e ativo.', 'woo-custom-dashboard');
            echo '</p></div>';
        });
        return false;
    }
    return true;
}

// Initialize plugin
function wcd_init() {
    if (!wcd_check_woocommerce()) {
        return;
    }

    // Load dashboard enhancement class
    require_once WCD_PLUGIN_DIR . 'includes/class-wcd-dashboard.php';
    new WCD_Dashboard();
}
add_action('plugins_loaded', 'wcd_init');
