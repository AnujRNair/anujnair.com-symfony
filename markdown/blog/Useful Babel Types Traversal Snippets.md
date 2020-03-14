# Useful `@babel/types` Traversal Snippets

Listed below are some useful traversal snippets that I've created when working with
`@babel/parser`, `@babel/traverse` and `@babel/generator`. Hopefully some of these
examples will come in useful for you when you're creating your own codemods:

## Generating a Dynamic Import, with a `webpackChunkName` Comment

I wanted to add a property to an object, which had a function to generate a dynamic
import (🤯)

Go from this:

```javascript
obj.func({});
```

to this:

```javascript
obj.func({
    render: () => import(/* webpackChunkName: "chunk-name" */ './file/path')
});
```

I did so by writing the following transformation:

```javascript
const t = require('@babel/types');

{
    CallExpression: ({ node }) => {
        // ignore anything which is not on my `func` method
        if (
            !node.callee ||
            !node.callee.property ||
            node.callee.property.name !== 'func'
        ) {
            return;
        }

        node.arguments[0].properties.push(
            t.objectProperty(
                t.identifier('render'),
                t.arrowFunctionExpression(
                    [],
                    t.callExpression(t.import(), [
                        t.addComment(
                            t.stringLiteral('./file/path'),
                            'leading',
                            `webpackChunkName: 'chunk-name'`
                        ),
                    ])
                )
            )
        );
    },
}
```

Be careful not to use double quotes in there as they will be escaped.
