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

        $patternAndCallbacks = [
            '/\[b\](.*?)\[\/b\]/is' => function ($matches) {
                return "<strong>$matches[1]</strong>";
            },
            '/\[i\](.*?)\[\/i\]/is' => function ($matches) {
                return "<em>$matches[1]</em>";
            },
            '/\[u\](.*?)\[\/u\]/is' => function ($matches) {
                return "<u>$matches[1]</u>";
            },
            '/(?:\r\n)?\[subheader\](.*?)\[\/subheader\](?:[\r\n]+)?/i' => function ($matches) {
                return "<h4>$matches[1]</h4>";
            },
            '/\[url(?:\=([\"\'])(.*?)\1)?\](.*?)\[\/url\]/i' => function ($matches) {
                if (strlen($matches[2]) > 0) {
                    return "<a href='$matches[2]'>$matches[3]</a>";
                } else {
                    return "<a href='$matches[3]'>$matches[3]</a>";
                }
            },
            '/\[img(?:[\s]+)?(?:width=([\'\"])(\d+)\1)?(?:[\s]+)?(?:height=([\'\"])(\d+)\3)?\](.*?)\[\/img\]/i' => function ($matches) {
                if (strlen($matches[2]) > 0 && strlen($matches[4]) > 0) {
                    return "<img src='$matches[5]' style='max-width: $matches[2]px; max-height:$matches[4]px;' />";
                } elseif (strlen($matches[2]) > 0) {
                    return "<img src='$matches[5]' style='max-width: $matches[2]px;' />";
                } elseif (strlen($matches[4]) > 0) {
                    return "<img src='$matches[5]' style='max-height: $matches[4]px;' />";
                } else {
                    return "<img src='$matches[5]' />";
                }
            },
            '/\[list\][\r\n]+(.*?)\[\/list\]/is' => function ($matches) {
                return "<ul>$matches[1]</ul>";
            },
            '/\[\*\](.*?)[\r\n]+/i' => function ($matches) {
                return "<li>$matches[1]</li>";
            },
            '/\[quote\](.*?)\[\/quote\][\r\n]+/ims' => function ($matches) {
                return "<blockquote>$matches[1]</blockquote>";
            },
            '/[\r\n]+\[code/' => function () {
                return '[code';
            },
            '/\[\/code\][\r\n]+/' => function () {
                return '[/code]';
            }
        ];

        $string = htmlspecialchars($string, ENT_NOQUOTES | ENT_HTML5);
        $converted = preg_replace("/[\r]/", '<br>', self::preg_replace_callback_array($patternAndCallbacks, $string));

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

    /**
     * Implementation of the PHP7 preg_replace_callback_array function
     * @param array $patterns_and_callbacks
     * @param string $subject
     * @param int $limit
     * @param int $count
     * @return null|array|string
     */
    protected static function preg_replace_callback_array(array $patterns_and_callbacks, $subject, $limit = -1, &$count = null)
    {
        foreach ($patterns_and_callbacks as $pattern => $callback) {
            $subject = preg_replace_callback($pattern, $callback, $subject, $limit, $count);
            if ($subject === null) {
                return null;
            }
        }
        return $subject;
    }

}
