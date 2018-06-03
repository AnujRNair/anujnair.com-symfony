const path = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');
const ManifestPlugin = require('webpack-manifest-plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const configBase = require('./config.base');

const cacheDirectory = path.resolve(__dirname, '..', 'node_modules', '.cache');

module.exports = merge(
  {
    // output configuration
    output: {
      // name of our main entry points
      filename: '[name].[chunkhash:7].min.js',

      // name of our chunked assets
      chunkFilename: '[name].[chunkhash:7].min.js',

      // how they will be accessed through the browser on the cdn
      publicPath: '/bundles/assets/'
    },

    // disable webpack defaults
    mode: 'none',

    // no source maps on production - this greatly increases build time: https://webpack.js.org/configuration/devtool/#devtool
    devtool: false,

    // chunk assets
    optimization: {
      splitChunks: {
        automaticNameDelimiter: '-',
        chunks: 'all',
        maxAsyncRequests: 3,
        maxInitialRequests: 3,
        minChunks: 1,
        minSize: 10000,
        name: true,
        cacheGroups: {
          default: false,
          vendors: {
            test: /[\\/]node_modules[\\/]/,
            name: 'vendors',
            chunks: 'all'
          },
          application: {
            minChunks: 2,
            name: 'application'
          }
        }
      }
    },

    module: {
      rules: [
        // run all js and jsx files through the babel loader, except node modules
        {
          test: /\.(j|t)sx?$/,
          exclude: /(node_modules)|(js\/libs\/)/,
          use: [
            {
              loader: 'thread-loader',
              options: {
                workers: 2
              }
            },
            {
              loader: 'babel-loader',
              options: {
                cacheDirectory: path.resolve(cacheDirectory, 'babel-loader')
              }
            }
          ]
        },

        // run css and scss through scss loader, post css (for vendor prefixes) and then css loader (with minification)
        {
          test: /\.(scss|css)$/,
          use: [
            {
              loader: 'cache-loader',
              options: {
                cacheDirectory: path.resolve(cacheDirectory, 'css')
              }
            },
            {
              loader: MiniCssExtractPlugin.loader
            },
            {
              loader: 'css-loader',
              options: {
                minimize: true
              }
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
          ],
          sideEffects: true
        }
      ]
    },

    plugins: [
      new webpack.DefinePlugin({
        'process.env': {
          NODE_ENV: JSON.stringify('production')
        }
      }),

      // to help with keeping module ids consistent and strengthen caching
      new webpack.HashedModuleIdsPlugin(),

      // This extracts css and less into a separate css file, one for each entry point
      new MiniCssExtractPlugin({
        filename: '[name].[contenthash:7].css',
        chunkFilename: '[name].[contenthash:7].css'
      }),

      // tree shake, minimize, compress
      new UglifyJsPlugin({
        sourceMap: false,
        cache: path.resolve(cacheDirectory, 'uglifyjs-js-plugin'),
        parallel: 2
      }),

      // create a manifest file of assets so php knows where to find files
      new ManifestPlugin({
        fileName: 'manifest.json'
      })
    ]
  },
  configBase
);
