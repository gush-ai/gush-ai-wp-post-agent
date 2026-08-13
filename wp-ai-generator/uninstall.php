# File: ...
<?php
// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Drop custom database tables
global $wpdb;
$table_name = $wpdb->prefix . 'ai_generator_content';
$wpdb->query("DROP TABLE IF EXISTS $table_name");

// Delete all plugin options
$options = array(
    'max_tokens',
    'temperature',
    'model',
    'enabled_post_types',
    'api_key'
);

foreach ($options as $option) {
    delete_option('wp_ai_generator_' . $option);
}

// Remove any transients
delete_transient('wp_ai_generator_api_status');
delete_transient('wp_ai_generator_model_list');

// Clear scheduled hooks
wp_clear_scheduled_hook('wp_ai_generator_daily_maintenance');

// Remove capabilities
$admin_role = get_role('administrator');
if ($admin_role) {
    $admin_role->remove_cap('generate_ai_content');
    $admin_role->remove_cap('manage_ai_generator_settings');
}

// Remove any user meta
$wpdb->query("DELETE FROM $wpdb->usermeta WHERE meta_key LIKE 'wp_ai_generator_%'");