require([
    'jquery'
], function ($) {
    "use strict";

    $(document).ready(function () {
        var $previewUrl,
            $target,
            $form;

        $('[data-preview-src]').click(function () {
            $previewUrl = $(this).data('preview-src');
            $target = $($(this).attr('href'));
            $form = $($(this).data('form'));

            $.ajax($previewUrl, {
                method: 'POST',
                data: $form.serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $target.html('<div class="loader"></div>');
                }
            }).done(function (response) {
                if (response.parsed && response.parsed.length > 0) {
                    var decoded = $('<textarea />').html(response.parsed).text();
                    $target.html(decoded);
                } else {
                    $target.html('There was nothing to parse!');
                }
            }).error(function () {
                $target.html('There was an issue parsing your text!');
            });
        });
    });

});