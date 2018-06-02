# Symfony2 Web Debug Toolbar 500 Internal Server Error based on getTemplateClass

I came across a strange issue today whilst developing on symfony2. On a very bare installation of symfony, every time I refreshed the page, I came across the following error:

```
An error occurred while loading the web debug toolbar (500 internal server error)
```

I'd then have the option to view the profiler or cancel.
On viewing the profiler, I saw the following:

```
Error: Maximum function nesting level of '100' reached, aborting!
500 Internal Server Error - FatalErrorException
```

and the initial stack trace started as:

```
$class = substr($this->getTemplateClass($name), strlen($this->templateClassPrefix));
    return $this->getCache() . '/' . $class[0] . '/' . $class[1] . '/' . $class . '.php';
}
public function getTemplateClass($name, $index = null)
{
    return $this->templateClassPrefix.hash('sha256', $this->getLoader()->getCacheKey($name)) . (null === $index ? '' : '_' . $index);
}
```

After that, the same few lines were repeated over and over, until the maximum nesting level was hit:

```
at Twig_Environment->loadTemplate ()
in app/cache/dev/classes.php at line 4754

at Twig_Template->loadTemplate ()
in app/cache/dev/twig/9/1/91a16e803287d3661e6e45ff141c7445cf0fd1e6c0ab8ff1494ef7ebe3c9a481.php at line 11

at __TwigTemplate_91a16e803287d3661e6e45ff141c7445cf0fd1e6c0ab8ff1494ef7ebe3c9a481 ->__construct () 
in app/cache/dev/classes.php at line 3181
```

#### The problem

Loading up the cached Twig Template showed me that it seemed to be including **itself** at the top of the file, causing the infinite recursion!

#### The solution

Force composer to use a stable version of twig by adding the following to require section of composer.json:
`"twig/twig": "@stable"`

Update your composer dependencies, and the error should disappear!
