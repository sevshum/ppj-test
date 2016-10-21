;(function($) {
    
    CMS.media = {
        init: function(container) {
            var $container = $(container),
                $active = null,
                $form = $('#media-form'),
                $submit = $('#media-form').find(':submit'),
                fields = [], i, id;
            
            $form.find('.form-control').each(function() {
                fields.push($(this).attr('id'));
            });
            
            CMS.$body.on('click', '#drag-zone', function() {
               $(this).closest('.dropbox').find('input').click(); 
            });
            
            $container.on('submit.ajax', '.image-item-form', function() {
                CMS.submitForm($(this), function(err, data) {
                    if (err) $submit.removeClass("disabled");
                });
                return false;
            }).on('click', '.image-item', function(e) {
                e.preventDefault();    
                $active = $(this);
                $container.find('.image-item').removeClass('active');
                $active.addClass('active');
                id = $active.data('id');
                for (i = 0; i < fields.length; i++) {
                    $form.find('#' + fields[i]).val($active.find('#' + fields[i] + '-' + id).val());
                }
                $submit.addClass('disabled');
            });
            
            $form.on('submit', function() {
                if ($submit.hasClass('disabled') || $active === null) return false;
                $submit.addClass('disabled');
                for (i = 0; i < fields.length; i++) {
                    $active.find('#' + fields[i] + '-' + id).val($form.find('#' + fields[i]).val());
                }
                $active.find('form').submit();
                return false;
            }).find('input, textarea').on('keyup', function(e) {
                $submit.removeClass("disabled");
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
