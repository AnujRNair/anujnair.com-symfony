require([
    'jquery',
    'components/syntaxHighlighter'
], function ($, syntaxHighlighter) {
    "use strict";

    $(document).ready(function () {
        var $previewUrl,
            $target,
            $form;

        $('[data-preview-src]').click(function () {
            // Where we should post to, to get the preview
            $previewUrl = $(this).data('preview-src');
            // The DOM element to drop the preview into
            $target = $($(this).attr('href'));
            // The form to POST
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
                    // Decode the text and add it to the target
                    var decoded = $('<textarea />').html(response.parsed).text();
                    $target.html(decoded);
                    // Re-highlight the page
                    syntaxHighlighter.highlight(false);
                } else {
                    $target.html('There was nothing to parse!');
                }
            }).error(function () {
                $target.html('There was an issue parsing your text!');
            });
        });
    });

});