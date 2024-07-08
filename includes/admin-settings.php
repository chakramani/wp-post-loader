<?php
if (!defined('ABSPATH')) {
    exit;
}

function wp_custom_post_type_admin_enqueue_scripts($hook_suffix) {
    if ($hook_suffix === 'settings_page_wp-post-loader') {
        // Enqueue Select2 CSS and JS
        wp_enqueue_style('wppl-admin-css', plugin_dir_url(__FILE__) . 'css/wppl-admin-style.css', array(), rand(), true);
        wp_enqueue_style('select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');
        wp_enqueue_script('select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), null, true);

        // Custom script to initialize Select2
        wp_enqueue_script('wppl-admin-js', plugin_dir_url(__FILE__) . 'js/wppl-admin-script.js', array('jquery', 'select2-js'), rand(), true);
    }
}
add_action('admin_enqueue_scripts', 'wp_custom_post_type_admin_enqueue_scripts');

function wppl_add_admin_menu() {
    add_options_page(
        'WP Post Loader Settings',
        'WP Post Loader',
        'manage_options',
        'wp-post-loader',
        'wppl_options_page'
    );
}
add_action('admin_menu', 'wppl_add_admin_menu');

function wppl_settings_init() {
    register_setting('wppl_options', 'wppl_settings');

    add_settings_section(
        'wppl_section',
        __('WP Post Loader Settings', 'wp-post-loader'),
        null,
        'wppl_options'
    );

    add_settings_field(
        'wppl_custom_post_type',
        __('Custom Post Type', 'wp-post-loader'),
        'wppl_custom_post_type_render',
        'wppl_options',
        'wppl_section'
    );
    add_settings_field(
        'wppl_posts_per_page',
        __('Posts Per Page', 'wp-post-loader'),
        'wppl_posts_per_page_render',
        'wppl_options',
        'wppl_section'
    );
}
add_action('admin_init', 'wppl_settings_init');

function wppl_custom_post_type_render() {
    $options = get_option('wppl_settings');
    $args = array(
        'public'   => true,
        '_builtin' => false
    );
    $post_types = get_post_types($args, 'objects');
    $selected_post_type = $options['wppl_custom_post_type'] ?? [];
    ?>
    <select name="wppl_settings[wppl_custom_post_type][]" id="wp_custom_post_type_field" class="wppl-select2" multiple>
        <?php foreach ($post_types as $post_type) { ?>
            <option value="<?php echo esc_attr($post_type->name); ?>" <?php echo in_array($post_type->name, (array)$selected_post_type) ? 'selected' : ''; ?>>
                <?php echo esc_html($post_type->labels->name); ?>
            </option>
        <?php } ?>
    </select>
    <?php
}

function wppl_posts_per_page_render() {
    $options = get_option('wppl_settings');
    ?>
    <input type="number" name="wppl_settings[wppl_posts_per_page]" value="<?php echo isset($options['wppl_posts_per_page']) ? esc_attr($options['wppl_posts_per_page']) : ''; ?>" />
    <?php
}

function wppl_options_page() {
    ?>
    <form action="options.php" method="post">
        <h2>WP Post Loader Settings</h2>
        <?php
        settings_fields('wppl_options');
        do_settings_sections('wppl_options');
        submit_button();
        ?>
    </form>
    <?php
}
