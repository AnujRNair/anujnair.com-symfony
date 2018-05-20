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
  "settings": {
    "import/resolver": {
      "webpack": {
        "config": {
          "resolve": {
            "alias": Object.assign({}, {
              '@anujnair': path.resolve(__dirname, 'src', 'AnujRNair', 'AnujNairBundle', 'Resources', 'public')
            }),
            "extensions": ['.js', '.json', '.jsx', '.svg', '.scss', '.jpg', '.png'],
          }
        }
      }
    }
  },
};
