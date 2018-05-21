const path = require('path');

module.exports = {
  extends: ['airbnb-base', 'prettier'],
  plugins: ['prettier'],
  parser: 'babel-eslint',
  env: {
    browser: true,
    node: false
  },
  rules: {
    'prettier/prettier': ['error', { singleQuote: true }],
    'import/no-extraneous-dependencies': [
      'off',
      {
        devDependencies: true
      }
    ]
  },
  globals: {
    document: true
  },
  settings: {
    'import/resolver': {
      webpack: {
        config: {
          resolve: {
            alias: {
              '@anujnair': path.resolve(
                __dirname,
                'src',
                'AnujRNair',
                'AnujNairBundle',
                'Resources',
                'public'
              )
            },
            extensions: [
              '.js',
              '.json',
              '.jsx',
              '.svg',
              '.scss',
              '.jpg',
              '.png'
            ]
          }
        }
      }
    }
  }
};
