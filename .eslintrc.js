const path = require('path');

module.exports = {
  parser: 'babel-eslint',
  extends: ['airbnb-base', 'plugin:react/recommended', 'prettier', 'prettier/react'],
  plugins: ['react', 'import', 'prettier'],
  env: {
    browser: true
  },
  rules: {
    'prettier/prettier': ['error', { singleQuote: true }],
    'import/no-extraneous-dependencies': [
      'off',
      {
        devDependencies: true
      }
    ],
    'class-methods-use-this': 'off'
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
