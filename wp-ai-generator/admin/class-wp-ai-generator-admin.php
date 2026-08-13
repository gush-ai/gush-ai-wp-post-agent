# File: wp-ai-generator/admin/class-wp-ai-generator-admin.php
<?php
class WP_AI_Generator_Admin {
    private $api;

    public function __construct($api) {
        $this->api = $api;
    }

    public function add_admin_menu() {
        add_menu_page(
            'AI Generator',
            'AI Generator',
            'manage_options',
            'wp-ai-generator',
            array($this, 'display_admin_page'),
            'dashicons-admin-generic',
            6
        );

        add_submenu_page(
            'wp-ai-generator',
            'Settings',
            'Settings',
            'manage_options',
            'wp-ai-generator-settings',
            array($this, 'display_settings_page')
        );
    }

    public function display_admin_page() {
        $models = $this->api->get_available_models();
        include WP_AI_GENERATOR_PLUGIN_DIR . 'admin/partials/wp-ai-generator-admin-display.php';
    }

    public function display_settings_page() {
        $models = $this->api->get_available_models();
        include WP_AI_GENERATOR_PLUGIN_DIR . 'admin/partials/wp-ai-generator-admin-settings.php';
    }

    public function register_settings() {
        register_setting('wp_ai_generator_settings', 'wp_ai_generator_api_key');
        register_setting('wp_ai_generator_settings', 'wp_ai_generator_default_model');
        register_setting('wp_ai_generator_settings', 'wp_ai_generator_max_tokens');
        register_setting('wp_ai_generator_settings', 'wp_ai_generator_temperature');

        add_settings_section(
            'wp_ai_generator_main_section',
            'Main Settings',
            array($this, 'render_main_section'),
            'wp-ai-generator-settings'
        );

        add_settings_field(
            'wp_ai_generator_api_key',
            'API Key',
            array($this, 'render_api_key_field'),
            'wp-ai-generator-settings',
            'wp_ai_generator_main_section'
        );

        add_settings_field(
            'wp_ai_generator_default_model',
            'Default Model',
            array($this, 'render_model_field'),
            'wp-ai-generator-settings',
            'wp_ai_generator_main_section'
        );

        add_settings_field(
            'wp_ai_generator_max_tokens',
            'Max Tokens',
            array($this, 'render_max_tokens_field'),
            'wp-ai-generator-settings',
            'wp_ai_generator_main_section'
        );

        add_settings_field(
            'wp_ai_generator_temperature',
            'Temperature',
            array($this, 'render_temperature_field'),
            'wp-ai-generator-settings',
            'wp_ai_generator_main_section'
        );
    }

    public function render_main_section() {
        echo '<p>Configure the main settings for the AI Generator plugin.</p>';
    }

    public function render_api_key_field() {
        $api_key = get_option('wp_ai_generator_api_key');
        echo '<input type="text" name="wp_ai_generator_api_key" value="' . esc_attr($api_key) . '" class="regular-text">';
    }

    public function render_model_field() {
        $models = $this->api->get_available_models();
        $current_model = get_option('wp_ai_generator_default_model');

        echo '<select name="wp_ai_generator_default_model">';
        foreach ($models as $model) {
            $selected = ($model['id'] === $current_model) ? 'selected' : '';
            echo '<option value="' . esc_attr($model['id']) . '" ' . $selected . '>' . esc_html($model['name']) . '</option>';
        }
        echo '</select>';
    }

    public function render_max_tokens_field() {
        $max_tokens = get_option('wp_ai_generator_max_tokens', 1000);
        echo '<input type="number" name="wp_ai_generator_max_tokens" value="' . esc_attr($max_tokens) . '" min="1" max="4096">';
    }

    public function render_temperature_field() {
        $temperature = get_option('wp_ai_generator_temperature', 0.7);
        echo '<input type="number" name="wp_ai_generator_temperature" value="' . esc_attr($temperature) . '" min="0" max="1" step="0.1">';
    }
}