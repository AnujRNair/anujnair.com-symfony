# ERROR in file.js from UglifyJs SyntaxError: Unexpected token: name (xxxxx) [file.js:line,column]

Recently whilst upgrading from Webpack to Webpack2, I ran into this error which took a while to debug.

I was seeing the following error output:
```bash
ERROR in file.js from UglifyJs
SyntaxError: Unexpected token: name (DateRange) [file.js:line,column]
```

I went into my webpack config and turned minification off so that I could see the file which was being generated:


```js
plugins: [
    ...
    new webpack.LoaderOptionsPlugin({
      minimize: false,
      debug: false
    }),
    new webpack.optimize.UglifyJsPlugin({
    	...
	})
]
```

On rerunning Webpack, I was able to see the line as to which my error was occurring:

```js
//-----------------------------------------------------------------------------
// Date Ranges
//-----------------------------------------------------------------------------

export class DateRange {		// <-- error was here for me
  constructor(start, end) {
    let s = start;
    let e = end;
```

UglifyJS needs all code to be transpiled down from ES6 to ES5 to work, but I was already doing that - I am using `babel-loader`, and I had the `es2015` preset already installed in my `.babelrc` file.

#### The solution

Turns out I had excluded `node_modules` in the babel loader step, and so the moment date range code, which is written in ES6, was not being transpiled to ES5, hence UglifyJS was having issues running over it.

By including specific `node_module` directories, I was also able to have babel run over some node modules which were written in ES6:
```js
{
     test: /\.jsx?$/,
     exclude: /node_modules/,
     include: [
     	/node_modules\/moment-range/
     ]
     use: ['babel-loader']
}
```

Done!
