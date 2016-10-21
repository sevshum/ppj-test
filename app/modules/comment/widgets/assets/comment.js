;(function($) {
    
    yii.comment = {
        init: function() {
            var $container = $('#comments'),
                $textarea = $('#comment-content'),
                $parent = $('#comment-parent_id'),
                $authorBox = $('#reply-author-box');
        
            $container.on('click', '.reply-comment', function(e) {
                e.preventDefault();
                var $el = $(this);
                $textarea.focus();
                if (false && $textarea.val() === '') {
                    var t = $textarea.val($el.data('author') + ', ')[0];
                    var range = t.createTextRange();
                    range.collapse(true);
                    range.moveStart('character', $el.data('author').length + 2);
                    range.select();
                }
                $authorBox.show().find('span').html($el.data('author'));
                $parent.val($el.data('id'));
            });
            $authorBox.on('click', '.remove-reply', function(e) {
                e.preventDefault();
                $parent.val('');
                $authorBox.hide();
                $textarea.focus();
            });
            
        }
    };
    $('#pjax-comments').on('pjax:end',   function() {
        yii.comment.init();
    });
})(jQuery);


