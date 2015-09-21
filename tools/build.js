({
    //- paths are relative to this app.build.js file
    appDir: "../src/AnujRNair/AnujNairBundle/Resources/public/js",
    baseUrl: ".",
    //- this is the directory that the new files will be. it will be created if it doesn't exist
    dir: "../web/bundles/anujnair/js",
    paths: {
        jquery: '../web/bower_components/jquery/dist/jquery.min',
        bootstrap: '..//bower_components/bootstrap/dist/js/bootstrap.min',
        prism: '..//bower_components/prism/prism',
        'prism-line-numbers': '..//bower_components/prism/plugins/line-numbers/prism-line-numbers',
        'prism-components': '..//bower_components/prism/components',
        ga: '//www.google-analytics.com/analytics'
    },
    modules: [
        {
            name: "main"
        }
    ],
    fileExclusionRegExp: /\.git/
})