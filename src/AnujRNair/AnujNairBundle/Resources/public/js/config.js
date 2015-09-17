/*globals requirejs */
requirejs.config({
    baseUrl: '/bundles/anujnair/js',
    shim: {
        bootstrap: {
            deps: [
                'jquery'
            ]
        },
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
        },
        ga: {
            exports: 'ga'
        }
    },
    paths: {
        jquery: '/bower_components/jquery/dist/jquery.min',
        bootstrap: '/bower_components/bootstrap/dist/js/bootstrap.min',
        prism: '/bower_components/prism/prism',
        'prism-line-numbers': '/bower_components/prism/plugins/line-numbers/prism-line-numbers',
        'prism-components': '/bower_components/prism/components',
        ga: '//www.google-analytics.com/analytics'
    }
});