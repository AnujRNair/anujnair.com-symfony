const path = require('path');
const webpack = require('webpack');
const { merge } = require('webpack-merge');

const configBase = require('./config.base.cjs');

const cacheDirectory = path.resolve(__dirname, '..', 'node_modules', '.cache');

module.exports = merge(
  {
    // output configuration
    output: {
      // name of our main entry points
      filename: '[name].bundle.js',

      // name of our chunked assets
      chunkFilename: '[name].bundle.js',

      // how they will be accessed through the browser locally
      publicPath: 'http://127.0.0.1:3010/assets/',

      // tell webpack to include comments in the generated code with info on the contained bundles
      pathinfo: true,
    },

    // disable webpack defaults
    mode: 'development',

    // no source maps
    devtool: false,

    // dev server config
    devServer: {
      // Specified here so the header will get blindly returned by the underlying
      // Express server in the response. Useful for making sure webpack-dev-server
      // is actually proxying requests properly.
      headers: {
        'Access-Control-Allow-Origin': '*',
        'Cache-Control': 'no-cache',
        'X-Webpack-Dev-Server': 'true',
      },

      // host and port info
      host: '127.0.0.1',
      port: 3010,

      static: ['assets'],
    },

    module: {
      rules: [
        // run all js and jsx files through the babel loader, except node modules
        {
          test: /\.jsx?$/,
          exclude: /(node_modules)/,
          use: [
            {
              loader: 'babel-loader',
              options: {
                cacheDirectory: path.resolve(cacheDirectory, 'babel-loader'),
              },
            },
          ],
        },

        // all less and css should be inlined into the head of the document after running through the normal loaders
        {
          test: /\.(scss|css)$/,
          use: [
            {
              loader: 'style-loader',
            },
            {
              loader: 'css-loader',
            },
            {
              loader: 'postcss-loader',
            },
            {
              loader: 'sass-loader',
              options: {
                sassOptions: {
                  includePaths: [
                    path.resolve(
                      __dirname,
                      '..',
                      'src',
                      'AnujRNair',
                      'AnujNairBundle',
                      'Resources',
                      'public',
                      'css'
                    ),
                  ],
                }
              },
            },
          ],
        },
      ],
    },

    plugins: [
      // don't watch node modules as they should never change
      new webpack.WatchIgnorePlugin({ paths: [
        path.resolve(__dirname, '..', 'node_modules'),
      ]}),
    ],
  },
  configBase
);
