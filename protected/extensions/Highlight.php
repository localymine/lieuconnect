<?php

/**
 * Perform a simple text replace
 * This should be used when the string does not contain HTML
 * (off by default)
 */
define('STR_HIGHLIGHT_SIMPLE', 1);

/**
 * Only match whole words in the string
 * (off by default)
 */
define('STR_HIGHLIGHT_WHOLEWD', 2);

/**
 * Case sensitive matching
 * (off by default)
 */
define('STR_HIGHLIGHT_CASESENS', 4);

/**
 * Overwrite links if matched
 * This should be used when the replacement string is a link
 * (off by default)
 */
define('STR_HIGHLIGHT_STRIPLINKS', 8);

/**
 * Highlight a string in text without corrupting HTML tags
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     3.1.1
 * @link        http://aidanlister.com/2004/04/highlighting-a-search-string-in-html-text/
 * @param       string          $text           Haystack - The text to search
 * @param       array|string    $needle         Needle - The string to highlight
 * @param       bool            $options        Bitwise set of options
 * @param       array           $highlight      Replacement string
 * @return      Text with needle highlighted
 */
class Highlight {

    public static function str_highlight($text, $needle, $options = null, $highlight = null) {
        // Default highlighting
        if ($highlight === null) {
            $highlight = '<strong>\1</strong>';
        }

        // Select pattern to use
        if ($options & STR_HIGHLIGHT_SIMPLE) {
            $pattern = '#(%s)#';
            $sl_pattern = '#(%s)#';
        } else {
            $pattern = '#(?!<.*?)(%s)(?![^<>]*?>)#';
            $sl_pattern = '#<a\s(?:.*?)>(%s)</a>#';
        }

        // Case sensitivity
        if (!($options & STR_HIGHLIGHT_CASESENS)) {
            $pattern .= 'i';
            $sl_pattern .= 'i';
        }

        $needle = (array) $needle;
        foreach ($needle as $needle_s) {
            $needle_s = preg_quote($needle_s);

            // Escape needle with optional whole word check
            if ($options & STR_HIGHLIGHT_WHOLEWD) {
                $needle_s = '\b' . $needle_s . '\b';
            }

            // Strip links
            if ($options & STR_HIGHLIGHT_STRIPLINKS) {
                $sl_regex = sprintf($sl_pattern, $needle_s);
                $text = preg_replace($sl_regex, '\1', $text);
            }

            $regex = sprintf($pattern, $needle_s);
            $text = preg_replace($regex, $highlight, $text);
        }

        return $text;
    }

    public static function str_highlight_accents($text, $needle, $options = null, $highlight = null) {
        $original_text = $text;
        $original_needle = $needle;

        // Normalize both text and needle to get rid of the accents e.g. "être humain' becomes "etre humain'
//        $text = self::strip_accents($text);
//        $needle = self::strip_accents($needle);

        $text = Slug::to_alphabet($text);
        $needle = Slug::to_alphabet($needle);

        // Default highlighting
        if ($highlight === null) {
            $highlight = '<strong>\1</strong>';
        }

        // Select pattern to use
        if ($options & STR_HIGHLIGHT_SIMPLE) {
            $pattern = '#(%s)#';
            $sl_pattern = '#(%s)#';
        } else {
            $pattern = '#(?!<.*?)(%s)(?![^]*?>)#';
            $sl_pattern = '#(%s)#';
        }

        // Case sensitivity
        if (!($options & STR_HIGHLIGHT_CASESENS)) {
            $pattern .= 'i';
            $sl_pattern .= 'i';
        }

        $needle = (array) $needle;
        foreach ($needle as $needle_s) {
            $needle_s = preg_quote($needle_s);

            // Escape needle with optional whole word check
            if ($options & STR_HIGHLIGHT_WHOLEWD) {
                $needle_s = '\b' . $needle_s . '\b';
            }

            // Strip links
            if ($options & STR_HIGHLIGHT_STRIPLINKS) {
                $sl_regex = sprintf($sl_pattern, $needle_s);
                $text = preg_replace($sl_regex, '\1', $text);
            }

            $regex = sprintf($pattern, $needle_s);
            $text = preg_replace($regex, $highlight, $text);
        }

        $opening_tag = substr($highlight, 0, strpos($highlight, '\\1'));
        $closing_tag = substr($highlight, strpos($highlight, '\\1') + 2);

        // Now the text contains the highlighted version of the text but without accents
        // We need to set the same highlighting but in the original text with accents

        $currPos = 0;
        $count = 0;
        $len_open = strlen($opening_tag);
        $len_close = strlen($closing_tag);
        $original_length = mb_strlen($original_text);
        while (true) {
            $start_pos = mb_strpos($text, $opening_tag, $currPos);
            if ($start_pos === FALSE)
                break;
            $stop_pos = mb_strpos($text, $closing_tag, $start_pos + 1);

            if ($stop_pos === FALSE)
                $stop_pos = mb_strlen($text);
            $original_text = mb_substr($original_text, 0, $start_pos) . $opening_tag . mb_substr($original_text, $start_pos, $stop_pos - $start_pos - $len_open) . $closing_tag . mb_substr($original_text, $stop_pos - $len_open);

            $currPos = $stop_pos;

            $count++;
        }

        // This can be useful for debugging
//        echo 'length should be ' . ($original_length + $count * ($len_open + $len_close)) . '==' . mb_strlen($text) . ' and is ' . mb_strlen($original_text) . '';
        return $original_text;
    }

//    The function strip_accents is the following (sources are referenced in the comment)

    function strip_accents($string) {

        // http://patisserie.keensoftware.com/en/pages/gerer-les-accents-dans-les-recherches-textes
        // https://github.com/cakephp/cakephp/blob/master/lib/Cake/Utility/Inflector.php
        /**
         * Default map of accented and special characters to ASCII characters
         *
         * @var array
         */
        $map = array(
            '/æ|ǽ/' => 'ae',
            '/ü/' => 'u',
            '/Ä/' => 'A',
            '/Ü/' => 'U',
            '/Ö/' => 'O',
            '/À|Á|Â|Ã|Å|Ǻ|Ā|Ă|Ą|Ǎ/' => 'A',
            '/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ä|ª/' => 'a',
            '/Ç|Ć|Ĉ|Ċ|Č/' => 'C',
            '/ç|ć|ĉ|ċ|č/' => 'c',
            '/Ð|Ď|Đ/' => 'D',
            '/ð|ď|đ/' => 'd',
            '/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě/' => 'E',
            '/è|é|ê|ë|ē|ĕ|ė|ę|ě/' => 'e',
            '/Ĝ|Ğ|Ġ|Ģ/' => 'G',
            '/ĝ|ğ|ġ|ģ/' => 'g',
            '/Ĥ|Ħ/' => 'H',
            '/ĥ|ħ/' => 'h',
            '/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ/' => 'I',
            '/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı/' => 'i',
            '/Ĵ/' => 'J',
            '/ĵ/' => 'j',
            '/Ķ/' => 'K',
            '/ķ/' => 'k',
            '/Ĺ|Ļ|Ľ|Ŀ|Ł/' => 'L',
            '/ĺ|ļ|ľ|ŀ|ł/' => 'l',
            '/Ñ|Ń|Ņ|Ň/' => 'N',
            '/ñ|ń|ņ|ň|ŉ/' => 'n',
            '/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ/' => 'O',
            '/ò|ó|ô|õ|ö|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º/' => 'o',
            '/Ŕ|Ŗ|Ř/' => 'R',
            '/ŕ|ŗ|ř/' => 'r',
            '/Ś|Ŝ|Ş|Ș|Š/' => 'S',
            '/ś|ŝ|ş|ș|š|ſ/' => 's',
            '/Ţ|Ț|Ť|Ŧ/' => 'T',
            '/ţ|ț|ť|ŧ/' => 't',
            '/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ/' => 'U',
            '/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ|ü/' => 'u',
            '/Ý|Ÿ|Ŷ/' => 'Y',
            '/ý|ÿ|ŷ/' => 'y',
            '/Ŵ/' => 'W',
            '/ŵ/' => 'w',
            '/Ź|Ż|Ž/' => 'Z',
            '/ź|ż|ž/' => 'z',
            '/Æ|Ǽ/' => 'AE',
            '/ß/' => 'ss',
            '/Ĳ/' => 'IJ',
            '/ĳ/' => 'ij',
            '/Œ/' => 'OE',
            '/ƒ/' => 'f',
            // vietnamese
            '/á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ/' => 'a',
            '/đ/' => 'd',
            '/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/' => 'e',
            '/í|ì|ỉ|ĩ|ị/' => 'i',
            '/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/' => 'o',
            '/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/' => 'u',
            '/ý|ỳ|ỷ|ỹ|ỵ/' => 'y',
            '/Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ/' => 'A',
            '/Đ/' => 'D',
            '/É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ/' => 'E',
            '/Í|Ì|Ỉ|Ĩ|Ị/' => 'I',
            '/Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ/' => 'O',
            '/Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự/' => 'U',
            '/Ý|Ỳ|Ỷ|Ỹ|Ỵ/' => 'Y',
        );
        return preg_replace(array_keys($map), array_values($map), $string);
    }

    public static function simple($text, $keywords) {
        foreach ($keywords as $keyword) {
            $text = preg_replace('#' . $keyword . '#iu', '<strong class="usearch-highlight">$0</strong>', $text);
        }
        return $text;
    }

    public static function words($text, $keywords) {
        return preg_replace('/(' . implode('|', $keywords) . ')/i', '<strong class="usearch-highlight">$0</strong>', $text);
    }

    
    
    public static function doit($content, $keywords, $wordspan = 5) {
        
        $keywords = Common::make_keywords($keywords);
        
        $keywordsPattern = implode('|', array_map(function($val) {
                    return preg_quote($val, '/');
                }, $keywords));
        $matches = preg_split("/($keywordsPattern)/ui", $content, -1, PREG_SPLIT_DELIM_CAPTURE);
        for ($i = 0, $n = count($matches); $i < $n; ++$i) {
            if ($i % 2 == 0) {
                $words = preg_split('/(\s+)/u', $matches[$i], -1, PREG_SPLIT_DELIM_CAPTURE);
                if (count($words) > ($wordspan + 1) * 2) {
                    $matches[$i] = '…';
                    if ($i > 0) {
                        $matches[$i] = implode('', array_slice($words, 0, ($wordspan + 1) * 2)) . $matches[$i];
                    }
                    if ($i < $n - 1) {
                        $matches[$i] .= implode('', array_slice($words, -($wordspan + 1) * 2));
                    }
                }
            } else {
                $matches[$i] = '<strong class="highlight">' . $matches[$i] . '</strong>';
            }
        }
        if ($matches[0] == '…'){
            return $content;
        }
        return implode('', $matches);
    }

}

/*
Example
 * 
 * 
I've added support for highlighting with links with the STR_HIGHLIGHT_STRIPLINKS constant. This will simply remove the existing link if a match is found in the text, ostensibly to be replaced by the replacement link.
 * 
For example, STR_HIGHLIGHT_SIMPLE|STR_HIGHLIGHT_WHOLEWD is the same as 1|2 or 3.
 * 
 *  * 
 * 

// Simple Example
$string = 'This is a site about PHP and SQL';
$search = array('php', 'sql');
echo str_highlight($string, $search);
echo "\n";
  
// With HTML in the text
$string = 'Link to <a href="php">php</a>';
$search = 'php';
echo htmlspecialchars(str_highlight($string, $search));
echo "\n";
  
// Matching whole words only
$string = 'I like to eat bananas with my nana!';
$search = 'Nana';
echo str_highlight($string, $search, STR_HIGHLIGHT_SIMPLE|STR_HIGHLIGHT_WHOLEWD);
echo "\n";
  
// With custom highlighting
$string = 'With custom highlighting!';
$search = 'custom';
$highlight = '<span style="text-decoration: underline;">\1</span>';
echo str_highlight($string, $search, STR_HIGHLIGHT_SIMPLE, $highlight);
echo "\n";
  
// With links
$string = 'I am a <a href="http://www.php.net">link</a>';
$search = 'link';
$highlight = '<a href="http://www.google.com/">\1</a>';
echo htmlspecialchars(str_highlight($string, $search, STR_HIGHLIGHT_STRIPLINKS, $highlight));

 * 
 * 

This is a site about <strong>PHP</strong> and <strong>SQL</strong>
Link to <a href="/php/"><strong>php</strong></a>
I like to eat bananas with my <strong>nana</strong>!
With <span style="text-decoration: underline;">custom highlighting</span>!
I am a <a href="http://www.google.com/">link</a>

 *
 *  */