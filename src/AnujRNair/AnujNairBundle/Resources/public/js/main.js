/*global require, document */
require(['config'], function () {
    "use strict";
    require([
        'ga',
        'components/syntaxHighlighter',
        'bootstrap',
        'components/bbcodePreview'
    ], function (ga, syntaxHighlighter) {
        ga("send", "pageview");
        syntaxHighlighter.highlight(true);
    });
});