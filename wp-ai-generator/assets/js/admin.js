# File: wp-ai-generator/assets/js/admin.js
jQuery(document).ready(function($) {
    $('#wp-ai-generator-form').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var resultDiv = $('#wp-ai-generator-result');

        resultDiv.html('<p>Generating content, please wait...</p>');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'wp_ai_generator_generate_content',
                data: formData
            },
            success: function(response) {
                if (response.success) {
                    resultDiv.html('<div class="notice notice-success"><p>' + response.data + '</p></div>');
                } else {
                    resultDiv.html('<div class="notice notice-error"><p>' + response.data + '</p></div>');
                }
            },
            error: function(xhr, status, error) {
                resultDiv.html('<div class="notice notice-error"><p>An error occurred: ' + error + '</p></div>');
            }
        });
    });
});