# File: wp-ai-generator/templates/admin-page.php
<div class="wrap">
    <h1>WP AI Generator</h1>
    <form id="wp-ai-generator-form">
        <table class="form-table">
            <tr>
                <th scope="row"><label for="post_type">Post Type</label></th>
                <td>
                    <select name="post_type" id="post_type">
                        <option value="post">Post</option>
                        <option value="product">Product Description</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="topic">Topic</label></th>
                <td><input type="text" name="topic" id="topic" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="keywords">Keywords (comma separated)</label></th>
                <td><input type="text" name="keywords" id="keywords" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="length">Length</label></th>
                <td>
                    <select name="length" id="length">
                        <option value="short">Short</option>
                        <option value="medium">Medium</option>
                        <option value="long">Long</option>
                    </select>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Generate Content">
        </p>
    </form>
    <div id="wp-ai-generator-result"></div>
</div>