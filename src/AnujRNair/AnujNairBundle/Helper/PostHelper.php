<?php

namespace AnujRNair\AnujNairBundle\Helper;

/**
 * Class PostHelper
 * @package AnujRNair\AnujNairBundle\Helper
 */
class PostHelper
{

    /**
     * Parse BB Code into full html
     * @param string $string the string to parse for BB code
     * @return string the string with BB code converted to html
     */
    public static function parseBBCode($string)
    {
        /*
         * 0 => [code lang="php"]nl2br[/code]
         * 1 => ' or "
         * 2 => language [php|js|sql]
         * 3 => code
         * 4 => [inline|full] via detection of new line
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

    /**
     * Safely shorten a string to a specific length, which includes HTML
     * @param string $string the html string to safe shorten
     * @param int $length the approximate length to shorten to
     * @param string $truncationIndicator how to denote text has been shortened
     * @return string the shortened html string
     */
    public static function safeShorten($string, $length = 500, $truncationIndicator = '...')
    {
        if (strlen($string) < $length) {
            // String is already short enough, we can return it as is
            return $string;
        }

        // Detect the encoding, and set up the regex as needed
        $encoding = mb_detect_encoding($string);
        $encoding = (in_array($encoding, [
            'UTF-8',
            'ISO-8859-1'
        ]) ? $encoding : 'ISO-8859-1');
        $pregUtf8Modifier = ($encoding == 'UTF-8' ? 'u' : '');
        $regexAll = '#(</?\w+(?:(?:\s+\w+(?:\s*=\s*(?:".*?"|\'.*?\'|[^\'">\s]+))?)+\s*|\s*)/?>)#i' . $pregUtf8Modifier;
        $regexOpen = '#</?\w+(?:(?:\s+\w+(?:\s*=\s*(?:".*?"|\'.*?\'|[^\'">\s]+))?)+\s*|\s*)/?>#i' . $pregUtf8Modifier;
        $regexClose = '#<(/?\w+)(?:(?:\s+\w+(?:\s*=\s*(?:".*?"|\'.*?\'|[^\'">\s]+))?)+\s*|\s*)/?>#i' . $pregUtf8Modifier;

        // Initial check for html. If there isn't any, return a subset of the string
        preg_match_all($regexAll, $string, $matches);
        if (empty($matches[1])) {
            return mb_substr($string, 0, $length, $encoding) . $truncationIndicator;
        }

        $iSplit = preg_split($regexOpen, $string);
        // Create an array of the shortened string
        $curCount = 0;
        $iSplitShortened = [];
        foreach ($iSplit as $i => $val) {
            $val = html_entity_decode($val, 0, $encoding);
            if ($curCount + mb_strlen($val, $encoding) >= $length) {
                $iSplitShortened[$i] = htmlentities(mb_substr($val, 0, ($length - $curCount), $encoding) . $truncationIndicator, 0, $encoding);
                break;
            } else {
                $iSplitShortened[$i] = htmlentities($val, 0, $encoding);
                $curCount += mb_strlen($val, $encoding);
            }
        }

        // Convert array into a shortened string with html added in
        $iHtml = '';
        foreach ($iSplitShortened as $i => $txt) {
            if (isset($matches[1][$i - 1])) {
                $iHtml .= $matches[1][$i - 1] . $txt;
            } else {
                $iHtml .= $txt;
            }
        }

        // Match open tags in the shortened string
        $selfClosedTags = ['area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input',
                           'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'];
        preg_match_all($regexClose, $iHtml, $m);

        // Count number of open tags in the shortened string
        $tags = [];
        $length = 0;
        foreach ($m[1] as $v) {
            if (in_array($v, $selfClosedTags)) {
                continue;
            }
            if ($v[0] != '/') {
                $tags[] = $v;
                $length++;
            } else {
                for ($i = $length - 1; $i >= 0; $i--) {
                    if ($tags[$i] === mb_substr($v, 1, mb_strlen($v, $encoding), $encoding)) {
                        unset($tags[$i]);
                        $length--;
                        $tags = array_values($tags);
                        break;
                    }
                }
            }
        }

        // Reverse the tags and add them to the html - this should close any open tags in the correct order
        if (count($tags) > 0) {
            $tags = array_reverse($tags);
            foreach ($tags as $tag) {
                $iHtml .= '</' . $tag . '>';
            }
        }
        return $iHtml;
    }

}
