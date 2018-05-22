<?php

namespace AnujRNair\AnujNairBundle\Helper;

/**
 * Class URLHelper
 * @package AnujRNair\AnujNairBundle\Helper
 */
class URLHelper
{

    /**
     * @param $string
     * @return mixed|string
     */
    public static function getURLSafeString($string)
    {
        // Lower string
        $string = strtolower($string);
        // Convert special chars
        $string = iconv("UTF-8", "ASCII//TRANSLIT", $string);
        // Replace unwanted with blank
        $string = preg_replace("/[^a-z0-9_\-\s]/i", "", $string);
        // Replace multiple dash / space with single space
        $string = preg_replace("/[\s-]+/", " ", $string);
        // Replace space with dashes
        $string = preg_replace("/[\s_]/", "-", $string);
        // Return
        return $string;
    }

}
