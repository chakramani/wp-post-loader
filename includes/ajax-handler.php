<?php
if (!defined('ABSPATH')) {
    exit;
}


function wppl_load_more_posts() {
    $paged = $_POST['page'] + 1;
    $posts_per_page = $_POST['posts_per_page'];
    $post_type = sanitize_text_field($_POST['post_type']);
    $options = get_option('wppl_settings');
    $custom_post_type = $options['wppl_custom_post_type'];

    $query = new WP_Query([
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
    ]);

    if ($query->have_posts()) {
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
    } else {
        echo '0';
    }
    wp_reset_postdata();

    die();
}
add_action('wp_ajax_wppl_load_more', 'wppl_load_more_posts');
add_action('wp_ajax_nopriv_wppl_load_more', 'wppl_load_more_posts');
