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
            // The DOM element to drop the preview into (href is an #id)
            $target = $($(this).attr('href'));
            // The form to POST (form is an #id)
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
                    var errorHtml = '<h4>There was an error parsing your comment!</h4><ul>';
                    $.each(response.errors, function(key, errors) {
                        $.each(errors, function (i, error) {
                            errorHtml += '<li>' + key.charAt(0).toUpperCase() + key.slice(1) + ': ' + error + '</li>'
                        });
                    });
                    errorHtml += '</ul>';
                    $target.html(errorHtml);
                }
            }).error(function () {
                $target.html('<h4>There was an issue parsing your text!</h4>');
            });
        });
    });

});