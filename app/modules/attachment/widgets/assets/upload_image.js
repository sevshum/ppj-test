var App = App || {};
;(function($) {
    App.uploadImage = {
        init: function(container) {
            var $container = $(container);

            // Check allowed upload limit and update count
            $(".hint-image-count").text($("#dropbox").data("limit") - $container.find('li').length);
            
            $('body').on('click', '#drag-zone', function() {
               $(this).closest('.dropbox').find('input').click(); 
            });
            $container.on('click.image-delete', '.image-delete', function(e) {
                e.preventDefault();
                var $el = $(this);
                if (confirm($el.data('confirming'))) {
                    $.post($el.attr('href'), function(rsp) {
                        if (rsp.success) {
                            $container.find(rsp.target).remove();
                            // Check allowed upload limit and update count
                            $(".hint-image-count").text($("#dropbox").data("limit") - $container.find('li').length);
                        }
                    }, 'json');
                }
            }).on('click.image-rotate', '.image-rotate', function(e) {
                e.preventDefault();
                var $el = $(this);
                if ($el.hasClass('disabled')) {
                    return;
                }
                $el.addClass('disabled').hide();
                $.post($el.attr('href'), function(rsp) {
                    $el.removeClass('disabled').show();
                    if (rsp.success) {
                        $container.find(rsp.target).find('.img').attr('src', rsp.url);
                    } else {
                        alert(rsp.message);
                    }
                }, 'json');
            });
            $container.sortable({
                update: function(event, ui) {
                    $.post($container.data('sort'), $(this).sortable('serialize'), function(rsp) {
                        if (!rsp.success) {
                            alert('Server error.');
                        }
                    }, 'json');
                }
            });
        }
    };
    
})(jQuery);
