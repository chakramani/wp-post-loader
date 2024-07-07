<?php
if (!defined('ABSPATH')) {
    exit;
}


function wppl_register_shortcodes()
{
    add_shortcode('wp_post_loader', 'wppl_post_loader_shortcode');
}
add_action('init', 'wppl_register_shortcodes');

function wppl_post_loader_shortcode($atts)
{
    $options = get_option('wppl_settings');
    $post_per_page = $options['wppl_posts_per_page'];
    $atts = shortcode_atts(
        [
            'posts_per_page' => $post_per_page,
        ],
        $atts,
        'wp_post_loader'
    );

    $options = get_option('wppl_settings');
    $custom_post_type = $options['wppl_custom_post_type'];

    $query = new WP_Query([
        'post_type' => $custom_post_type,
        'posts_per_page' => $atts['posts_per_page'],
        'paged' => 1,
    ]);

    ob_start();
    if ($query->have_posts()) {
        echo '<div id="wppl-posts-container" class="custom-post-type-cards"> ';
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
        echo '<button id="wppl-load-more" data-page="1" data-posts-per-page="' . esc_attr($atts['posts_per_page']) . '">' . __('Load More', 'wp-post-loader') . '</button>';
    } else {
        echo __('No posts found.', 'wp-post-loader');
    }
    wp_reset_postdata();

    return ob_get_clean();
}
