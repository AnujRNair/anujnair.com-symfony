# Symfony2 Assetic with LESS generating empty files on Unix

Yesterday, I tried running the following command using the symfony console to dump my LESS files into a static CSS file, for my site:

`php app/console assetic:dump --env=prod --no-debug`

On doing so, when I visited my site, no CSS styling had been applied, and when checking the generated file in the `/web/bundles/` folder, it was empty.

I'd [recently had issues installing and running composer](http://anujnair.com/blog/2-issues-with-globally-installing-composer-on-ubuntu-under-root) on my fresh installation of Ubuntu, so I assumed it would be a similar issue, namely around the `php.ini` setting `open_basedir`.

When viewing the assetic library, specifically in the `\Assetic\Filter\LessFilter.php` file, I was seeing that Assetic first creates a temporary file in your system temp directory, compiles everything into there, and then outputs it to the file you have specified.

The relevant code doing this:

```php
// LessFilter.php
public function filterLoad(AssetInterface $asset)
{
    $pb = $this->createProcessBuilder();

    $pb->add($this->nodeBin)->add($input = FilesystemUtils::createTemporaryFile('less'));
    file_put_contents($input, sprintf($format,
        json_encode($asset->getContent()),
        json_encode(array_merge($parserOptions, $this->treeOptions))
    ));

    $proc = $pb->getProcess();
    $code = $proc->run();
    unlink($input);

    //...
}

// FilesystemUtils.php
public static function createTemporaryFile($prefix)
{
    return tempnam(self::getTemporaryDirectory(), 'assetic_'.$prefix);
}

public static function getTemporaryDirectory()
{
    return realpath(sys_get_temp_dir());
}
```

My `sys_get_temp_dir()` is set to `/tmp/`, however, PHP doesn't have permission to write there because of my `open_basedir` settings!

#### The solution

By adding `/tmp/` to my `open_basedir` in `php.ini`, assetic was able to run successfully and dump the contents of my LESS files into my desired output folder.
