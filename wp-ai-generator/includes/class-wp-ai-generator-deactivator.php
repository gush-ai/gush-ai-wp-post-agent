# File: ...
<?php
class WP_AI_Generator_Deactivator {
    public static function deactivate() {
        // Clear scheduled cron jobs
        self::clear_scheduled_jobs();

        // Remove capabilities
        self::remove_capabilities();

        // Clean up options
        self::cleanup_options();
    }

    private static function clear_scheduled_jobs() {
        $timestamp = wp_next_scheduled('wp_ai_generator_daily_task');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'wp_ai_generator_daily_task');
        }
    }

    private static function remove_capabilities() {
        $admin_role = get_role('administrator');
        if ($admin_role) {
            $admin_role->remove_cap('manage_ai_generator');
            $admin_role->remove_cap('generate_ai_content');
        }
    }

    private static function cleanup_options() {
        $options = array(
            'max_tokens',
            'temperature',
            'model',
            'enabled_post_types'
        );

        foreach ($options as $option) {
            delete_option('wp_ai_generator_' . $option);
        }
    }
}