const path = require('path');

module.exports = {
  parser: 'babel-eslint',
  extends: ['airbnb-base', 'plugin:react/all', 'prettier', 'prettier/react'],
  plugins: ['react', 'import', 'prettier'],
  env: {
    browser: true,
  },
  rules: {
    'prettier/prettier': ['error', { singleQuote: true }],
    'import/no-extraneous-dependencies': [
      'off',
      {
        devDependencies: true,
      },
    ],
    'react/jsx-max-depth': [2, { max: 3 }],
    'class-methods-use-this': 0,
    'max-classes-per-file': 0,
    'react/jsx-no-literals': 0,
    'react/destructuring-assignment': 0,
    'react/no-multi-comp': 0,
    'react/no-set-state': 0,
    'react/jsx-fragments': 0,
  },
  globals: {
    document: true,
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
              ),
            },
            extensions: [
              '.js',
              '.json',
              '.jsx',
              '.svg',
              '.scss',
              '.jpg',
              '.png',
            ],
          },
        },
      },
    },
    react: {
      version: 'detect',
    },
  },
};
