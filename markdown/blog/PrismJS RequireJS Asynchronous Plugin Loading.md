# Using PrismJS with RequireJS and loading plugins asynchronously

Whilst developing my site, I came across a beautiful syntax highlighter called [PrismJS](http://prismjs.com) which I wanted to use, however, it is not built out of the box for use with AMD APIs such as [RequireJS](http://requirejs.org/).

I wanted to enable a few features with PrismJS and RequireJS, namely, the ability to:

* Load PrismJS through RequireJS and have it highlight code elements.
* Dynamically load the necessary language files so that code blocks can be highlighted with the correct language definitions.
* Load plugins so that I could use extra functionality, such as the line numbers plugin.
* Have all of this as optimized as possible, with as clean code as possible.


#### 1. Include PrismJS CSS
I added the PrismJS CSS to the `head` of my document - it's a tiny file when minified and is unlikely to conflict with any of your existing CSS - the main selectors are `code`, `pre`, and `.token`.


#### 2. Shim the Library
To begin, we need to make PrismJS compatible with RequireJS by adding the following configuration data:

```javascript
requirejs.config({
    baseUrl: '/bundles/anujnair/js',
    shim: {
    	prism: {
            exports: "Prism"
        },
        'prism-components': {
            exports: "components"
        },
    },
    paths: {
    	prism: '/bower_components/prism/prism',
    	'prism-components': '/bower_components/prism/components',
    }
});
```

Replace the url of the prism path with where you have stored the main prism.js file.

The reason for also shimming the components file will be shown below.

This now allows us to call the Prism Library through RequireJS, like so:

```javascript
define(['prism', 'prism-components'], function (Prism, components) {
    "use strict";

    Prism.highlightAll();
});
```

All code blocks containing the class `.language-xxx` or `.lang-xxx` will be highlighted. 


#### 3. Dynamically load only the necessary PrismJS language files
To do this, we have to consider a few things:

* We want to make sure that the language we are trying to load exists before trying to load it to avoid 404 errors. Luckily, PrismJS holds all of it's metadata in the `components.js` file - we can reference this file to check if a language exists or not.
* If there are multiple language blocks of the same language on the page, we don't want to asynchronously call that file twice - loading it once is enough.
* Because JavaScript is asynchronous, we want to make sure all needed language files are loaded before we try and highlight the page.

To do this, I created an array of Promises, and waited for all of them to resolve. Once resolved, I call the main highlightAll function to rerender the page:

```javascript
var baseComponentPath = '/bower_components/prism/components/',
    lang = /\blang(?:uage)?-(?!\*)(\w+)\b/i,
    elements = document.querySelectorAll('code[class*="language-"], [class*="language-"] code, code[class*="lang-"], [class*="lang-"] code'),
    language,
    promises = [],
    loaded = [];

// Find and load all necessary languages
for (var i = 0, element; element = elements[i++];) {
    language = lang.exec(element.className)[1];
    if (language in components.languages && !(language in Prism.languages) && loaded.indexOf(language) < 0) {
        var request = $.ajax(baseComponentPath + 'prism-' + language + '.min.js', {
            method: 'GET',
            dataType: 'script',
            cache: true
        });
        promises.push(request);
        loaded.push(language);
    }
}

// Rehighlight the page with all of the new languages which have been loaded
$.when.apply(null, promises).done(function () {
    Prism.highlightAll();
});
```

I'm selecting all `code` / `pre` elements using the same selector that PrismJS does in it's core file, and then for each language found, I'm checking that it exists in the components file and we haven't already loaded it.

Im using jQuery for AJAX here, but it can easily be switched out for a native `XMLHTTPRequest` call.

Now we have PrismJS dynamically loading our languages, only when needed.


#### 4. Include Plugins to allow more functionality
Including plugins follows a similar pattern to above. First shim the plugin, then load the plugin asynchronously, and allow Prism to highlight your elements:

```javascript
requirejs.config({
    baseUrl: '/bundles/anujnair/js',
    shim: {
        prism: {
            exports: "Prism"
        },
        'prism-line-numbers': {
            deps: [
                'prism'
            ]
        },
        'prism-components': {
            exports: "components"
        }
    },
    paths: {
        prism: '/bower_components/prism/prism',
        'prism-line-numbers': '/bower_components/prism/plugins/line-numbers/prism-line-numbers',
        'prism-components': '/bower_components/prism/components'
    }
});
```

```javascript
define([
    'prism',
    'prism-components'
], function (Prism, components) {
    "use strict";

    // On demand loading of Prism languages
    // -------------------------------------------------------------------------
    var baseComponentPath = '/bower_components/prism/components/',
        lang = /\blang(?:uage)?-(?!\*)(\w+)\b/i,
        elements = document.querySelectorAll('code[class*="language-"], [class*="language-"] code, code[class*="lang-"], [class*="lang-"] code'),
        language,
        promises = [],
        loaded = [];

    // Find and load all necessary languages
    for (var i = 0, element; element = elements[i++];) {
        language = lang.exec(element.className)[1];
        if (language in components.languages && !(language in Prism.languages) && loaded.indexOf(language) < 0) {
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
    if (elements.length > 0) {
        promises.push(
            $.ajax('/bower_components/prism/plugins/line-numbers/prism-line-numbers.min.js', {
                method: 'GET',
                dataType: 'script',
                cache: true
            })
        );
    }

    // Rehighlight the page with all of the new languages which have been loaded
    $.when.apply(null, promises).done(function () {
        Prism.highlightAll();
    });
});
```

As an added extra, you could add the plugins you want to load in the HTML like so `<code lang="javascript" data-plugins="line-numbers"></code>` and then parse the plugins you want to dynamically load from there, checking if they exist in the components file first.


#### 4. Careful about the 'Gotchas'!

**Load the Language files *before* the plugin files!**

When I initially created this module, I was loading the plugin files first, however with some languages, because of the way they are parsed, some of the plugins were being overwritten. To overcome this, make sure plugins are always loaded after languages.


#### 5. And that's all!

Although this solution isn't perfect, it's a start on how to use PrismJS with RequireJS - I'm open to all ideas on how to improve this setup, so please let me know your thoughts in the comments below!
