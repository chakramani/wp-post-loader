jQuery(document).ready(function($) {
    $('#wppl-load-more').on('click', function() {
        var button = $(this);
        var data = {
            'action': 'wppl_load_more',
            'page': button.data('page'),
            'posts_per_page': button.data('posts-per-page'),
            'post_type': button.data('post-type')
        };

        $.ajax({
            url: wppl_ajax_obj.ajax_url,
            data: data,
            type: 'POST',
            success: function(response) {
                if (response != '0') {
                    button.data('page', data.page + 1);
                    $('.custom-post-type-cards').append(response);
                } else {
                    button.text('No more posts');
                    button.prop('disabled', true);
                }
                // if (response) {
                //     button.data('page', page + 1);
                //     $('#wppl-posts-container-' + postType).append(response);
                //     button.text('Load More');
                // } else {
                //     button.text('No more posts');
                //     button.prop('disabled', true);
                // }
            }
        });
    });

    // make shortable custom post type card
    $('.custom-post-type-cards').sortable();
});
