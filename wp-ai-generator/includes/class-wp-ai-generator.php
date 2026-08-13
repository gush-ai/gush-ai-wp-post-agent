# File: wp-ai-generator/includes/class-wp-ai-generator.php
<?php
class WP_AI_Generator {
    public function run() {
        // Add actions and filters here
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
    }

    public function add_admin_menu() {
        add_menu_page(
            'WP AI Generator',
            'AI Generator',
            'manage_options',
            'wp-ai-generator',
            array( $this, 'render_admin_page' ),
            'dashicons-admin-generic',
            6
        );
    }

    public function enqueue_admin_scripts( $hook ) {
        if ( 'toplevel_page_wp-ai-generator' !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'wp-ai-generator-admin',
            WP_AI_GENERATOR_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            WP_AI_GENERATOR_VERSION
        );

        wp_enqueue_script(
            'wp-ai-generator-admin',
            WP_AI_GENERATOR_PLUGIN_URL . 'assets/js/admin.js',
            array( 'jquery' ),
            WP_AI_GENERATOR_VERSION,
            true
        );
    }

    public function render_admin_page() {
        include_once WP_AI_GENERATOR_PLUGIN_DIR . 'templates/admin-page.php';
    }
}