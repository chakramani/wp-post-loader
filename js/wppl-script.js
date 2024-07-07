jQuery(document).ready(function($) {
    $('#wppl-load-more').on('click', function() {
        var button = $(this);
        var data = {
            'action': 'wppl_load_more',
            'page': button.data('page'),
            'posts_per_page': button.data('posts-per-page')
        };

        $.ajax({
            url: wppl_ajax_obj.ajax_url,
            data: data,
            type: 'POST',
            success: function(response) {
                if (response != '0') {
                    button.data('page', data.page + 1);
                    $('#wppl-posts-container').append(response);
                } else {
                    button.text('No more posts');
                    button.prop('disabled', true);
                }
            }
        });
    });
    $('.custom-post-type-cards').sortable();
});