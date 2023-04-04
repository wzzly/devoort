jQuery(document).ready(function($) {
    $('.advanced-search input[type="checkbox"]').on('change', function() {
        var data = {
            'action': 'ajax_search',
            'nonce': ajax_search_object.nonce,
            'search': $('form.advanced-search').serialize()
        };

        $.post(ajax_search_object.ajaxurl, data, function(response) {
            // Replace the search results content with the new search results
            $('.archive-wrapper').html(response);
        });
    });
});