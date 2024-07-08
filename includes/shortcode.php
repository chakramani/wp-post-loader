<?php
if (!defined('ABSPATH')) {
    exit;
}

function wppl_register_shortcodes() {
    $options = get_option('wppl_settings');
    $custom_post_types = $options['wppl_custom_post_type'] ?? [];

    foreach ($custom_post_types as $post_type) {
        add_shortcode("wp_post_loader_{$post_type}", function($atts) use ($post_type) {
            return wppl_post_loader_shortcode($atts, $post_type);
        });
    }
}
add_action('init', 'wppl_register_shortcodes');

function wppl_post_loader_shortcode($atts, $post_type) {
    $options = get_option('wppl_settings');
    $post_per_page = $options['wppl_posts_per_page'] ?? 10;

    $atts = shortcode_atts(
        [
            'posts_per_page' => $post_per_page,
        ],
        $atts,
        "wp_post_loader_{$post_type}"
    );

    // Prepare WP_Query arguments
    $query_args = [
        'post_type' => $post_type,
        'posts_per_page' => $atts['posts_per_page'],
        'paged' => 1,
    ];

    $query = new WP_Query($query_args);

    ob_start();
    if ($query->have_posts()) {
        echo '<div id="wppl-posts-container-' . esc_attr($post_type) . '" class="custom-post-type-cards">';
        while ($query->have_posts()) {
            $query->the_post(); ?>
            <div class="custom-post-type-card">
                <?php if (has_post_thumbnail()) { ?>
                    <div class="custom-post-type-card-image">
                        <?php the_post_thumbnail('medium'); ?>
                    </div>
                <?php } ?>
                <a href="<?php echo get_the_permalink(); ?>">
                    <div class="custom-post-type-card-content">
                        <h2 class="custom-post-type-card-title"><?php the_title(); ?></h2>
                    </div>
                </a>
            </div>
            <?php
        }
        echo '</div>';
        echo '<button id="wppl-load-more" data-page="1" data-posts-per-page="' . esc_attr($atts['posts_per_page']) . '" data-post-type="' . esc_attr($post_type) . '">' . __('Load More', 'wp-post-loader') . '</button>';
    } else {
        echo __('No posts found.', 'wp-post-loader');
    }
    wp_reset_postdata();

    return ob_get_clean();
}
