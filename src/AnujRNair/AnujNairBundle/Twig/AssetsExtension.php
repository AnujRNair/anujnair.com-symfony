<?php

namespace AnujRNair\AnujNairBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
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
    public function __construct($container)
    {
        $this->container = $container;

        if ($this->container->isScopeActive('request')) {
            $this->request = $this->container->get('request');
        }

        $path = realpath(__DIR__ . '/../../../../web/bundles/assets/manifest.json');
        if ($path === false) {
            throw new FileNotFoundException('Could not find webpack manifest file');
        }

        $this->manifest = json_decode(file_get_contents($path), true);
    }

    /**
     * Gets the webpack entry name from the route
     * @return string
     */
    protected function getWebpackEntryFromRoute()
    {
        $route = $this->request->get('_route');
        if ($route === null) {
            return 'error';
        }

        return implode('-', array_slice(explode('_', $route), -2, 2));
    }

    /**
     * Gets all assets needed for a specific webpack entry name
     * @param $name - the webpack entry name
     * @param $ext - css of js assets
     * @return array
     */
    protected function getManifestEntries($name, $ext)
    {
        $result = [];

        $assets = [
            'vendors.' . $ext,
            'application.' . $ext,
            $name . '.' . $ext
        ];

        foreach ($assets as $asset) {
            if (isset($this->manifest[$asset])) {
                $result[] = $this->manifest[$asset];
            }
        }

        return $result;
    }

    /**
     * Registers the webpack asset paths to twig
     * @return array
     */
    public function getGlobals()
    {
        $data = array();

        $data['asset_css'] = $this->getManifestEntries($this->getWebpackEntryFromRoute(), 'css');
        $data['asset_js'] = $this->getManifestEntries($this->getWebpackEntryFromRoute(), 'js');
        $data['error_css'] = $this->getManifestEntries('error', 'css');
        $data['error_js'] = $this->getManifestEntries('error', 'js');

        return $data;
    }

}
