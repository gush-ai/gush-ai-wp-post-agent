# File: ...
<?php
class WP_AI_Generator_Activator {
    public static function activate() {
        // Create database tables
        self::create_tables();

        // Set default options
        self::set_default_options();

        // Schedule cron jobs
        self::schedule_cron_jobs();

        // Add capabilities
        self::add_capabilities();
    }

    private static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $table_name = $wpdb->prefix . 'ai_generator_content';
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            post_id mediumint(9) NOT NULL,
            content longtext NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id),
            KEY post_id (post_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    private static function set_default_options() {
        $defaults = array(
            'max_tokens' => 1000,
            'temperature' => 0.7,
            'model' => 'gpt-3.5-turbo',
            'enabled_post_types' => array('post', 'page')
        );

        foreach ($defaults as $option => $value) {
            if (!get_option('wp_ai_generator_' . $option)) {
                add_option('wp_ai_generator_' . $option, $value);
            }
        }
    }

    private static function schedule_cron_jobs() {
        if (!wp_next_scheduled('wp_ai_generator_cleanup')) {
            wp_schedule_event(time(), 'daily', 'wp_ai_generator_cleanup');
        }
    }

    private static function add_capabilities() {
        $admin_role = get_role('administrator');
        if ($admin_role) {
            $admin_role->add_cap('manage_ai_generator');
        }
    }
}