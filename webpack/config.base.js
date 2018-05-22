const path = require('path');

module.exports = {
  // this gives the parallel runner a proper name to use in the output
  name: 'anujnair',

  // the entrypoints we want to build assets for
  /* eslint-disable */
  entry: {
    'blog-index': path.resolve('src', 'AnujRNair', 'AnujNairBundle', 'Resources', 'public', 'js', 'entrypoints', 'blog-index'),
    'blog-post': path.resolve('src', 'AnujRNair', 'AnujNairBundle', 'Resources', 'public', 'js', 'entrypoints', 'blog-post'),
    'blog-tag': path.resolve('src', 'AnujRNair', 'AnujNairBundle', 'Resources', 'public', 'js', 'entrypoints', 'blog-tag')
  },
  /* eslint-enable */

  // output configuration
  output: {
    // where to output assets on disk
    path: path.resolve('assets')
  },

  // so we can run webpack from any inner directory, and all of the paths always resolve correctly: https://webpack.js.org/configuration/entry-context/#context
  context: path.resolve(__dirname, '..'),

  // fail out on the first error instead of tolerating it: https://webpack.js.org/configuration/other-options/#bail
  bail: true,

  resolve: {
    // We don't use npm link or yarn link so we can speed up compilation: https://webpack.js.org/guides/build-performance/#resolving
    symlinks: false,

    // what files webpack recognizes
    extensions: ['.js', '.json', '.jsx', '.svg', '.scss', '.jpg', '.png'],

    // set aliases for development ease
    alias: Object.assign(
      {},
      {
        lodash: 'lodash-es',
        normalize: 'normalize.css/normalize.css',
        '@anujnair': path.resolve(__dirname, '..', 'src', 'AnujRNair', 'AnujNairBundle', 'Resources', 'public')
      }
    ),

    // set extra top level directories where files can be found. Careful adding too much to this as it can slow things down
    modules: ['node_modules']
  },

  module: {
    // missing modules becomes an error: https://webpack.js.org/configuration/module/#module-contexts
    strictExportPresence: true,

    rules: [
      // load font files normally through the browser
      {
        test: /\.(woff|woff2|eot|ttf|otf)$/,
        use: ['file-loader']
      },

      // load images through url, try inlining
      {
        test: /\.(png|jpg)/,
        use: [
          {
            loader: 'url-loader',
            options: {
              limit: 8192
            }
          }
        ]
      }
    ]
  }
};
