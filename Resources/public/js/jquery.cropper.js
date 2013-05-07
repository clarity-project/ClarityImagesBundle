(function($) {
    $.fn.cropper = function(options) {
        var settings = {
            selector: 'crop',
            action: '',
        };

        if (options) { 
          $.extend(settings, options);
        }

        var $element = $('img['+settings.selector+'="target"]');

        $('['+settings.selector+'="sizes"]').on('change', function() {
            c = {
                x: 0, y:0, w: 0, h: 0
            }

            store(c);
            init();
        });

        /**
         * 
         * @param  object c [description]
         * @return undefined
         */
        var store = function(c)
        {
            $('['+settings.selector+'="x"]').val(c.x);
            $('['+settings.selector+'="y"]').val(c.y);
            $('['+settings.selector+'="w"]').val(c.w);
            $('['+settings.selector+'="h"]').val(c.h);
        }

        /**
         * @param  object $element
         * @return 
         */
        var init = function()
        {
            if ($('['+settings.selector+'="sizes"]').val().toString().length > 0) {   
                var sizes = $('[crop="sizes"]').val().toString().split('_');
                var width = sizes[0];
                var height = sizes[1];
                var ratio = 0;
                if (height != 0 && width != 0) {
                    ratio = width / height;
                }
                $element.Jcrop({
                    aspectRatio: ratio,
                    minSize: [width, height],
                    onSelect: store,
                    onChange: store
                });
            }
        }

        init($element);

    }
})(jQuery)