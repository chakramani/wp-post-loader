<?php
/*
Plugin Name: WP Post Loader
Description: Display and load more custom post types using AJAX.
Version: 1.0
Author: Chakramani Joshi
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Include necessary files.
include_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';
include_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';
include_once plugin_dir_path(__FILE__) . 'includes/ajax-handler.php';

function wppl_enqueue_scripts() {
    wp_enqueue_style('wppl-style', plugins_url('/css/style.css', __FILE__));
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('wppl-script', plugins_url('/js/wppl-script.js', __FILE__), ['jquery'], null, true);
    wp_localize_script('wppl-script', 'wppl_ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'wppl_enqueue_scripts');