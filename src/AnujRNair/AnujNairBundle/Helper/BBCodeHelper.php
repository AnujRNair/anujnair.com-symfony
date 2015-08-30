<?php

namespace AnujRNair\AnujNairBundle\Helper;

class BBCodeHelper
{

    public static function parseBBCode($string)
    {
        /*
         * 0 => [code lang="php"]nl2br[/code]
         * 1 => ' or "
         * 2 => language [php|js|sql]
         * 3 => code
         * 4 => [inline|full]
         */
        $bbCodeRegex = '/\[code lang(?:uage)?=([\"\'])(.+?)(?:\1)\](.+?)\[\/code\]([\r\n]+)?/ims';
        preg_match_all($bbCodeRegex, $string, $fullMatches);

        $bbFind = [
            '/\[b\](.*?)\[\/b\]/is',
            '/\[i\](.*?)\[\/i\]/is',
            '/\[u\](.*?)\[\/u\]/is',
            '/(?:\r\n)?\[subheader\](.*?)\[\/subheader\](?:[\r\n]+)?/is',
            '/\[url\=([\"\'])(.*?)\1\](.*?)\[\/url\]/is',
            '/\[img(?:[\s]+)?(?:width=([\'\"])(\d+)\1)?(?:[\s]+)?(?:height=([\'\"])(\d+)\3)?\](.*?)\[\/img\]/is',
            '/\[list\][\r\n]+(.*?)\[\/list\]/is',
            '/\[\*\](.*?)[\r\n]+/i',
            '/\[>](.*?)\[<\][\r\n]+/is',
            '/[\r\n]+\[code/',      #Needed to remove leading line breaks :(
            '/\[\/code\][\r\n]+/'   #Needed to remove trailing line breaks :(
        ];
        $bbReplace = [
            '<strong>$1</strong>',
            '<em>$1</em>',
            '<u>$1</u>',
            '<h4>$1</h4>',
            '<a href="$2">$3</a>',
            '<img src="$5" style="max-width: ${2}px; max-height:${4}px;" />',
            '<ul>$1</ul>',
            '<li>$1</li>',
            '<blockquote>$1</blockquote>',
            '[code',
            '[/code]'
        ];

        $string = htmlspecialchars($string, ENT_NOQUOTES | ENT_HTML5);
        $converted = preg_replace("/[\r]/", '<br>', preg_replace($bbFind, $bbReplace, $string));

        // Match and convert code blocks
        for ($i = 0; $i < count($fullMatches[0]); $i++) {
            $opening = (strlen($fullMatches[4][$i]) > 0
                ? '<pre class="line-numbers"><code class="language-' . $fullMatches[2][$i] . '">'
                : '<code class="language-' . $fullMatches[2][$i] . '">'
            );
            $closing = (strlen($fullMatches[4][$i]) > 0
                ? '</code></pre>'
                : '</code>'
            );
            $converted = preg_replace($bbCodeRegex, $opening . htmlspecialchars($fullMatches[3][$i]) . $closing, $converted, 1);
        }

        return $converted;
    }

}
