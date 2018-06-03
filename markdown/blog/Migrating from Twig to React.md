# Migrating from Twig to React in Symfony2

It's been a long time since I updated my site, so I thought I'd take the opportunity to upgrade the front end to use React, utilizing Webpack 4, Babel etc.

I didn't want to upgrade my backend, only make a few tweaks to allow supporting React development on the front end.

I faced a few hurdles in doing so, so I thought I'd write about them:

#### Prerendered Content

I decided that the layout of my site would stay in twig, but I would render all 'main' content using React, without prerendering it via `ReactDOMServer`.

[After doing some research on whether Search Engines could crawl and index JS only sites](https://medium.freecodecamp.org/seo-vs-react-is-it-neccessary-to-render-react-pages-in-the-backend-74ce5015c0c9), I decided that this was an acceptable path for me to take.

#### Getting the necessary props into React client side

Next step was to get the props from Symfony into React.

The way that Symfony and Twig interact is to return variables from PHP which the Twig Render Engine can then pick up and use. I didn't want to rewrite the frontend portion of my site to grab all necessary data from API calls, so I decided to JSON encode the Symfony variables and write them onto the page for React to the read.

This is an example of how it's done:
```php
public function indexAction(Request $request)
{
    $page = $request->get('page', 1);
    $noPerPage = min($request->get('noPerPage', 10), 100);
    
    // more data
    
    return [
        'json' => json_encode([
            'page' => (int)$page,
            'noPerPage' => (int)$noPerPage,
            ...
        ])
    ];
}
```

And then in my `layout.html.twig` file:
```html
{% if json is defined %}
    <script type="application/javascript">
        window.reactProps = {{ json|raw }};
    </script>
{% endif %}
```

#### JSON Encoding Symfony Entites doesn't work though

If you try JSON Encoding an entity which has been created via Symfony, it won't output what you expect, as all of the properties are usually private in the class instance.

To get around this, I has each entity implement the `JsonSerializable` class, which needs a `jsonSerialize` function defined.

This allowed me to define exactly what should happen when `json_encode` is called on an Entity:
```php
class Blog implements JsonSerializable
{
    private $id;
    private $name;
    ...
    
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            ...
        ];
    }
```

Now when `json_encode` is called, it returns the exact data I want it to.

This method does mean you can't use the symfony generators to create exactly what you want, but it was a necessary trade off I was willing to take.

#### Now we can create React Components in a Development Environment

Each entry point can now react the props which we've rendered into the layout, and start mounting the react components that we've created!
```js
import React from 'react';
import ReactDOM from 'react-dom';

import BlogIndex from '@anujnair/js/pages/blog-index';

const mainContainer = document.querySelector('.main-content');
ReactDOM.render(
  React.createElement(BlogIndex, window.reactProps),
  mainContainer
);
```

#### Creating assets for Production and having Twig reference those

I wanted to be able to take advantage of the browser cache and webpack's chunking to make sure that when I release updates, the end user only has to download the newely changed assets.

That meant fingerprinting the file names with their hashes (e.g. `blog.9034e90.min.js`) and then using [Webpack Manifest Plugin](https://www.npmjs.com/package/webpack-manifest-plugin) to map the original names back to the hashed file names.

To allow Twig access to this mapping, I created a new Twig Extension which would then output the necessary assets on the `layout.html.twig` page:

```php
<?php

namespace AnujRNair\AnujNairBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class AssetsExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var String[]
     */
    protected $manifest;

    /**
     * Constructor - ensure the manifest exists
     * @param ContainerInterface $container
     */
    public function __construct($container, $env)
    {
        $this->container = $container;
        $this->request = $this->container->get('request');
        $path = realpath(__DIR__ . '/../../../../web/bundles/assets/manifest.json');
        $this->manifest = json_decode(file_get_contents($path), true);
    }

    /**
     * Gets the webpack entry name from the route
     * @return string
     */
    protected function getWebpackEntryFromRoute()
    {
        $route = $this->request->get('_route');
        return implode('-', array_slice(explode('_', $route), -2, 2));
    }

    /**
     * Registers the webpack asset paths to twig
     * @return array
     */
    public function getGlobals()
    {
        $data = array();

        $data['asset_css'] = $this->manifest[$this->getWebpackEntryFromRoute() . '.css'];
        $data['asset_js'] = $this->manifest[$this->getWebpackEntryFromRoute() . '.js'];

        return $data;
    }
}
```

* We read the manifest into a variable on construction
* We convert the current route we're on into a webpack manifest entry name
* We grab the hashed version of the file from the manifest and register it as a twig global variable

In the twig layout, we then loop through these variables and output them onto the page:
```html
{% for css in asset_css %}
    <link type="text/css" rel="stylesheet" href="{{ css }}"/>
{% endfor %}
```

And that's it! These examples have been simplified slightly, but the full implementation can be foudn in my [GitHub Repository](https://github.com/AnujRNair/anujnair.com-symfony)

Thanks!
