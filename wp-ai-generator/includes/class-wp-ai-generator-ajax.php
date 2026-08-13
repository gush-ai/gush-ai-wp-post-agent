# File: wp-ai-generator/includes/class-wp-ai-generator-ajax.php
<?php
class WP_AI_Generator_Ajax {
    public function __construct() {
        add_action( 'wp_ajax_wp_ai_generator_generate_content', array( $this, 'generate_content' ) );
    }

    public function generate_content() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'You do not have sufficient permissions to perform this action.' );
        }

        parse_str( $_POST['data'], $form_data );

        $post_type = sanitize_text_field( $form_data['post_type'] );
        $topic = sanitize_text_field( $form_data['topic'] );
        $keywords = sanitize_text_field( $form_data['keywords'] );
        $length = sanitize_text_field( $form_data['length'] );

        // Here you would integrate with your AI service to generate the content
        $content = $this->generate_ai_content( $post_type, $topic, $keywords, $length );

        if ( $content ) {
            wp_send_json_success( $content );
        } else {
            wp_send_json_error( 'Failed to generate content. Please try again.' );
        }
    }

    private function generate_ai_content( $post_type, $topic, $keywords, $length ) {
        // Implement your AI content generation logic here
        // This is a placeholder for the actual implementation
        return 'Generated content for ' . $post_type . ' about ' . $topic . ' with keywords ' . $keywords . ' and length ' . $length;
    }
}