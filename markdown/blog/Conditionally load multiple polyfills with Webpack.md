# Conditionally load multiple Polyfills using Webpack, Promises and Code Splitting

For my latest react project, I wanted to be able to polyfill certain features which are missing in older browsers, yet not include them in my final code bundle if the browser the user is using already supports that feature.

This was a great problem for webpack's code splitting feature to solve!

After a quick google, I found a [post](http://ianobermiller.com/blog/2015/06/01/conditionally-load-intl-polyfill-webpack/) from Ian Obermiller which set me on the correct path, however the post didn't expand on how to load multiple polyfills conditionally.

For example:
* Safari is missing the Intl api (at the time of writing) - I wanted to be able to polyfill this.
* Slightly older versions of Firefox did include the Intl api, but are missing the newer JS Symbol data type. I should only include the Symbol polyfill in this case.

#### Proposal

By wrapping each browser feature check in a promise, requiring the polyfill if needed, and then waiting for all promises to resolve before initializing my main app, I would be able to get the functionality I desire.

#### Implementation

**1. Wrap your React app in a function so it can be called after all promises have resolved:**

```js
import React from 'react';
import App from './App';

function initialize() {
  React.render(<App />, document.body);
}
```

**2. Create an array of different features you'd like to test for**

```js
const availablePolyfills = [
  {
    test: () => !global.fetch,
    load: () => {
      return new Promise(resolve => {
        require.ensure([], () => {
          resolve({
            fetch: require('whatwg-fetch')
          });
        }, 'polyfills-fetch');
      });
    }
  },
  {
    test: () => !Object.assign,
    load: () => {
      return new Promise(resolve => {
        require.ensure([], () => {
          resolve({
            'object-assign': require('core-js/fn/object/assign')
          });
        }, 'polyfills-obj-assign');
      });
    }
  },
  {
    test: () => !global.Symbol,
    load: () => {
      return new Promise(resolve => {
        require.ensure([], () => {
          resolve({
            symbol: require('core-js/fn/symbol'),
            'symbol-iterator': require('core-js/fn/symbol/iterator')
          });
        }, 'polyfills-symbol');
      });
    }
  }
];
```

There are a few things going on here:

* Each block has a tet function and a load function
* We'll be using the test function to see if we need to download the polyfill to the browser
* Within the load function, we're returning a Promise - We'll use this to wait for all promises to resolve before loading our main app.
* Within each promise, we have a `require.ensure` block - as WebPack analyzes our code, this will tell it to split this specific code block into it's own chunk. That way it won't be included in our main app, and we can download it conditionally.
* Finally we resolve the promise with an object of requires - the key doesn't really matter. You will have to `npm install` any polyfills you might want to use. Here, I'm using `whatwg-fetch` and `core-js`.

**3. Test the browser for the necessary features, and wait for all promises to resolve**

```js
import Promise from 'bluebird';

export default function loadPolyfills(initialize) {
  if (availablePolyfills.some(polyfill => polyfill.test())) {
    let polyfillFns = [];

    availablePolyfills.forEach(polyfill => {
      if (polyfill.test()) {
        polyfillFns.push(polyfill.load());
      }
    });

    Promise.all(polyfillFns).then(() => initialize());
  } else {
    // load without polyfills
    initialize();
  }
};
```

Here, I am testing that at least one polyfill needs to be loaded. If it does, I push the promise to an array, and by using `Promise.all` from Bluebird, I wait for all promises in the array to resolve, before calling my initialize function (which loads the react app!)

And that's it! On compiling my code through webpack, I get my desired code splits:
```js
               Asset       Size  Chunks             Chunk Names
 polyfills-symbol.js    14.3 kB       3  [emitted]  polyfills-symbol
  polyfills-fetch.js    12.8 kB       2  [emitted]  polyfills-fetch
  polyfills-assign.js   12.8 kB       4  [emitted]  polyfills-obj-assign
              app.js     353 kB       1  [emitted]  app
polyfills-intl-en.js    54.3 kB       5  [emitted]  polyfills-intl-en
```

and my polyfills are only loaded when needed, and before I run my main app.

Let me know if you've found a better way!
