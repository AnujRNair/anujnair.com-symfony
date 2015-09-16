/*global require, document */
require(['config'], function () {
    "use strict";
    require([
        'components/syntaxHighlighter',
        'bootstrap',
        'components/bbcodePreview',
        'components/analytics'
    ], function (syntaxHighlighter) {
        syntaxHighlighter.highlight(true);
    });
});