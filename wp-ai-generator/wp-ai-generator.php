# File: wp-ai-generator/wp-ai-generator.php
<?php
/**
 * Plugin Name: Gush Ai WP AI Generator
 * Plugin URI: https://github.com/gush-ia/wp-ai-generator
 * Description: A plugin to generate WordPress posts, WooCommerce product descriptions, and more using AI.
 * Version: 1.0.0
 * Author: Olayiwola Emmanuel
 * Author URI: https://sstore.com.ng
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-ai-generator
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'WP_AI_GENERATOR_VERSION', '1.0.0' );
define( 'WP_AI_GENERATOR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_AI_GENERATOR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include necessary files
require_once WP_AI_GENERATOR_PLUGIN_DIR . 'includes/class-wp-ai-generator.php';
require_once WP_AI_GENERATOR_PLUGIN_DIR . 'includes/class-wp-ai-generator-activator.php';
require_once WP_AI_GENERATOR_PLUGIN_DIR . 'includes/class-wp-ai-generator-deactivator.php';

// Register activation and deactivation hooks
register_activation_hook( __FILE__, array( 'WP_AI_Generator_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WP_AI_Generator_Deactivator', 'deactivate' ) );

// Initialize the plugin
function wp_ai_generator_init() {
    $plugin = new WP_AI_Generator();
    $plugin->run();
}
add_action( 'plugins_loaded', 'wp_ai_generator_init' );