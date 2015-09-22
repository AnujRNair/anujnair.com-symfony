/*global require, document */
require(['config'], function () {
    "use strict";
    require([
        'components/syntaxHighlighter',
        'components/ga',
        'bootstrap',
        'components/bbcodePreview'
    ], function (syntaxHighlighter) {
        syntaxHighlighter.highlight(true);
    });
});