# Output filename not configured error from Webpack

I couldn't find an answer to this simple issue, so I thought I'd quickly post one myself.

Whilst creating a new ReactJS app, I wanted to use Webpack to build all of my modules into a single bundle.

I had included it in my project by running `npm install webpack --save`.

However, on running the `webpack` command, I was seeing the following output:

```bash
Output filename not configured.
```

#### The solution

Turns out it was simple. I had missed the **s** from my `module.exports` line:

```js
module.export = App;

//Should be
module.exports = App;
```

Adding the missing character solved the issue!
