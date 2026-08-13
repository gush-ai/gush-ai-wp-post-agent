# File: wp-ai-generator/admin/partials/wp-ai-generator-admin-display.php
<?php
// Check if models are available
if (empty($models)) {
    echo '<div class="notice notice-error"><p>No models available. Please check your API key in the settings.</p></div>';
    return;
}

// Display the main form
?>
<div class="wrap">
    <h1>AI Generator</h1>

    <form method="post" action="" id="ai-generator-form">
        <table class="form-table">
            <tr>
                <th scope="row"><label for="post_type">Post Type</label></th>
                <td>
                    <select name="post_type" id="post_type">
                        <?php
                        $post_types = get_post_types(array('public' => true), 'objects');
                        foreach ($post_types as $post_type) {
                            echo '<option value="' . esc_attr($post_type->name) . '">' . esc_html($post_type->label) . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="topic">Topic/Keywords</label></th>
                <td>
                    <input type="text" name="topic" id="topic" class="regular-text" required>
                    <p class="description">Enter the main topic or keywords for your content</p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="length">Content Length</label></th>
                <td>
                    <select name="length" id="length">
                        <option value="short">Short (200-500 words)</option>
                        <option value="medium" selected>Medium (500-1000 words)</option>
                        <option value="long">Long (1000+ words)</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="model">AI Model</label></th>
                <td>
                    <select name="model" id="model">
                        <?php
                        foreach ($models as $model) {
                            $selected = ($model['id'] === get_option('wp_ai_generator_default_model')) ? 'selected' : '';
                            echo '<option value="' . esc_attr($model['id']) . '" ' . $selected . '>' . esc_html($model['name']) . '</option>';
                        }
                        ?>
                    </select>
                    <p class="description">Select the AI model to use for content generation</p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="temperature">Creativity Level</label></th>
                <td>
                    <input type="range" name="temperature" id="temperature" min="0" max="1" step="0.1" value="<?php echo esc_attr(get_option('wp_ai_generator_temperature', 0.7)); ?>">
                    <span id="temperature-value"><?php echo esc_html(get_option('wp_ai_generator_temperature', 0.7)); ?></span>
                    <p class="description">Lower values make the output more focused and deterministic, higher values make it more creative and varied</p>
                </td>
            </tr>
        </table>

        <?php wp_nonce_field('ai_generator_action', 'ai_generator_nonce'); ?>
        <p class="submit">
            <input type="submit" name="generate_content" id="generate-content" class="button button-primary" value="Generate Content">
        </p>
    </form>

    <div id="generated-content" style="display: none;">
        <h2>Generated Content</h2>
        <div id="content-preview"></div>
        <p>
            <button id="edit-content" class="button">Edit Content</button>
            <button id="publish-content" class="button button-primary">Publish</button>
        </p>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Update temperature display
    $('#temperature').on('input', function() {
        $('#temperature-value').text($(this).val());
    });

    // Form submission
    $('#ai-generator-form').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var nonce = $('#ai_generator_nonce').val();

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'generate_ai_content',
                nonce: nonce,
                form_data: formData
            },
            beforeSend: function() {
                $('#generate-content').prop('disabled', true).val('Generating...');
            },
            success: function(response) {
                if (response.success) {
                    $('#content-preview').html(response.data.content);
                    $('#generated-content').show();
                    $('html, body').animate({
                        scrollTop: $('#generated-content').offset().top
                    }, 1000);
                } else {
                    alert('Error: ' + response.data.message);
                }
            },
            error: function() {
                alert('An error occurred while generating content.');
            },
            complete: function() {
                $('#generate-content').prop('disabled', false).val('Generate Content');
            }
        });
    });

    // Edit content button
    $('#edit-content').on('click', function() {
        var content = $('#content-preview').html();
        $('#content-preview').html('<textarea id="content-editor" style="width: 100%; height: 300px;">' + content + '</textarea>');
        $(this).hide();
        $('#publish-content').show();
    });

    // Publish content button
    $('#publish-content').on('click', function() {
        var content = $('#content-editor').val();
        var postType = $('#post_type').val();
        var topic = $('#topic').val();

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'publish_ai_content',
                content: content,
                post_type: postType,
                title: topic
            },
            beforeSend: function() {
                $('#publish-content').prop('disabled', true).text('Publishing...');
            },
            success: function(response) {
                if (response.success) {
                    alert('Content published successfully!');
                    window.location.href = response.data.edit_link;
                } else {
                    alert('Error: ' + response.data.message);
                }
            },
            error: function() {
                alert('An error occurred while publishing content.');
            },
            complete: function() {
                $('#publish-content').prop('disabled', false).text('Publish');
            }
        });
    });
});
</script>