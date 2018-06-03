const path = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');

const configBase = require('./config.base');

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
      pathinfo: true
    },

    // disable webpack defaults
    mode: 'development',

    // no source maps
    devtool: false,

    // dev server config
    devServer: {
      // We don't need this enabled because Apache proxies requests to
      // webpack-dev-server, which makes those requests gated behind Uberproxy
      //
      // See https://github.com/webpack/webpack-dev-server/releases/tag/v2.4.3
      // for more info about the host check security enhancement
      disableHostCheck: true,

      // Specified here so the header will get blindly returned by the underlying
      // Express server in the response. Useful for making sure webpack-dev-server
      // is actually proxying requests properly.
      headers: {
        'Access-Control-Allow-Origin': '*',
        'Cache-Control': 'no-cache',
        'X-Webpack-Dev-Server': 'true'
      },

      // host and port info
      host: '127.0.0.1',
      port: 3010,

      // Seems to be ignored as of webpack-dev-server 2.5.1
      // I suspect this is an issue with webpack-dev-middleware which was
      // introduced here: https://github.com/webpack/webpack-dev-middleware/commit/23a75095bda747d24f8b902a8114dc8034303871#diff-bbfe1200f066d4b0611fd44a7368c0d4R38
      publicPath: 'http://127.0.0.1:3010/assets/'
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
                cacheDirectory: path.resolve(cacheDirectory, 'babel-loader')
              }
            }
          ]
        },

        // all less and css should be inlined into the head of the document after running through the normal loaders
        {
          test: /\.(scss|css)$/,
          use: [
            {
              loader: 'style-loader'
            },
            {
              loader: 'css-loader'
            },
            {
              loader: 'postcss-loader'
            },
            {
              loader: 'sass-loader',
              options: {
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
                  )
                ]
              }
            }
          ]
        }
      ]
    },

    plugins: [
      // don't watch node modules as they should never change
      new webpack.WatchIgnorePlugin([
        path.resolve(__dirname, '..', 'node_modules')
      ])
    ]
  },
  configBase
);
