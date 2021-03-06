<?php

namespace AnujRNair\AnujNairBundle\Helper;

/**
 * Class NavHelper
 * @package AnujRNair\AnujNairBundle\Helper
 */
class NavHelper
{

    /**
     * @var array
     */
    protected $sections = array();

    /**
     * @var string
     */
    protected $activeRoute = null;

    /**
     * @return array
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param string $route
     * @param string $name
     * @param string $icon
     * @return $this
     */
    public function add($route, $name, $icon)
    {
        $this->sections[] = [
            'route' => $route,
            'name' => $name,
            'icon' => $icon
        ];
        return $this;
    }

    /**
     * @param $route
     */
    public function setActive($route)
    {
        $this->activeRoute = $route;
    }

    /**
     * @param $section
     * @return bool
     */
    public function isActive($section)
    {
        return $this->activeRoute && $this->activeRoute == $section['route'];
    }


}