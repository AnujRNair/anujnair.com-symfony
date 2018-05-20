const path = require('path');

module.exports = {
  extends: ['airbnb-base', 'prettier'],
  plugins: ['prettier'],
  env: {
    es6: true,
    node: true
  },
  rules: {
    'prettier/prettier': ['error', { singleQuote: true }]
  },
  globals: {
    document: true
  },
  settings: {
    "import/resolver": {
      "webpack": {
        "config": path.resolve('webpack', 'config.dev.js')
      }
    }
  },
};
