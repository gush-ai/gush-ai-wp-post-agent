# File: wp-ai-generator/includes/class-wp-ai-generator-api.php
<?php
class WP_AI_Generator_API {
    private $api_key;
    private $api_url = 'https://ai.sstore.ng/api-access';
    private $available_models = array();

    public function __construct($api_key) {
        $this->api_key = $api_key;
        $this->load_available_models();
    }

    public function generate_content($prompt, $model = null, $params = array()) {
        $endpoint = '/generate-content';
        $default_params = array(
            'max_tokens' => 1000,
            'temperature' => 0.7,
            'top_p' => 1.0,
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.0
        );

        $params = wp_parse_args($params, $default_params);

        if ($model) {
            $params['model'] = $model;
        }

        $response = wp_remote_post($this->api_url . $endpoint, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode(array_merge(array('prompt' => $prompt), $params))
        ));

        if (is_wp_error($response)) {
            return new WP_Error('api_error', $response->get_error_message());
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($body['error'])) {
            return new WP_Error('api_error', $body['error']['message']);
        }

        return $body;
    }

    private function load_available_models() {
        $endpoint = '/models';
        $response = wp_remote_get($this->api_url . $endpoint, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->api_key
            )
        ));

        if (!is_wp_error($response)) {
            $body = json_decode(wp_remote_retrieve_body($response), true);
            if (isset($body['data'])) {
                $this->available_models = $body['data'];
            }
        }
    }

    public function get_available_models() {
        return $this->available_models;
    }

    public function get_model_info($model_id) {
        foreach ($this->available_models as $model) {
            if ($model['id'] === $model_id) {
                return $model;
            }
        }
        return null;
    }
}