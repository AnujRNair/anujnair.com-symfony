require([
    'jquery',
    'prism',
    'prism-components'
], function ($, Prism, components) {

    // On demand loading of Prism languages
    // ---------------------------------------------------------------------
    var baseComponentPath = '/bower_components/prism/components/',
        lang = /\blang(?:uage)?-(?!\*)(\w+)\b/i,
        elements = document.querySelectorAll('code[class*="language-"], [class*="language-"] code, code[class*="lang-"], [class*="lang-"] code'),
        language,
        promises = [],
        loaded = [];

    // Find and load all necessary languages
    for (var i = 0, element; element = elements[i++];) {
        language = lang.exec(element.className)[1];
        if (language in components.languages && loaded.indexOf(language) < 0) {
            var request = $.ajax(baseComponentPath + 'prism-' + language + '.min.js', {
                method: 'GET',
                dataType: 'script',
                cache: true
            });
            promises.push(request);
            loaded.push(language);
        }
    }

    // Load the line numbers plugin
    promises.push(
        $.ajax('/bower_components/prism/plugins/line-numbers/prism-line-numbers.min.js', {
            method: 'GET',
            dataType: 'script',
            cache: false
        })
    );

    // Rehighlight the page with all of the new languages which have been loaded
    $.when.apply(null, promises).done(function () {
        Prism.highlightAll();
    });

});