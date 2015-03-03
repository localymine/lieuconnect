<?php

class Common {

    public static function get_current_date() {
        return (string) date("Y-m-d H:i:s");
//        return (string) date("Y-m-d H:i:s", mktime(gmdate("H") - 4, gmdate("i"), gmdate("s"), gmdate("m"), gmdate("d"), gmdate("Y")));
    }

    public static function date_format($date, $format = 'Y-m-d H:i:s') {
        $date = new DateTime($date);
        return $date->format($format);
    }

    public static function register($file, $themeName = 'classic', $pos = CClientScript::POS_END) {
        $path = Yii::app()->baseUrl . '/themes/' . $themeName . '/';
        if (strpos($file, 'js') !== false)
            return Yii::app()->clientScript->registerScriptFile($path . 'js/' . $file, $pos);
        else if (strpos($file, 'css') !== false)
            return Yii::app()->clientScript->registerCssFile($path . 'css/' . $file);
    }

    public static function register_js($path, $pos = CClientScript::POS_END) {
        return Yii::app()->clientScript->registerScriptFile($path, $pos);
    }

    public static function register_css($path) {
        return Yii::app()->clientScript->registerCssFile($path);
    }

    public static function register_script($id = '', $script, $pos = CClientScript::POS_END) {
        return Yii::app()->clientScript->registerScript("$id-" . rand(), $script, $pos);
    }

    public static function getSex($sex) {
        $d_sex = array(-1 => 'NA', 0 => 'Female', 1 => 'Male');
        return $d_sex[$sex];
    }

    public static function pop_by_key($foo, array &$array) {
        foreach ($array as $key => $value) {
            if ($key == $foo) {
                unset($array[$key]);
            }
        }
    }

    public static function pop_by_value($foo, array &$array) {
        foreach ($array as $key => $value) {
            if ($value == $foo) {
                unset($array[$key]);
            }
        }
    }

    public static function truncate($text, $length = 100) {
        $length = abs((int) $length);
        if (strlen($text) > $length) {
            $text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1…', $text);
        }
        return($text);
    }

    public static function strip_truncate($text, $length = 100) {
        $text = strip_tags($text);
        $text = Common::truncate($text, $length);

        return $text;
    }

    /* add new line, strip tag, truncate */

    public static function strip_nl_truncate($text, $length = 100) {
        // ----- remove HTML TAGs -----
        $text = preg_replace('/<[^>]*>/', ' ', $text);
        //
        $text = preg_replace('/^(\<p\>(\&nbsp\;|(\s)*)\<\/p\>|\<br(\s\/)?\>)$/', '', $text);
        //
        // remove <p> <br> of the begining
        $pattern = '#^(<br\s*/?>|\s|&nbsp;)*(.+?)(<br\s*/?>|\s|&nbsp;)*$#i';
        $text = preg_replace($pattern, '$2', $text);
        //
        preg_replace("/<br\W*?\/>/", "\n", $text);
        $text = strip_tags($text);
        $text = Common::truncate($text, $length);
        $text = nl2br($text);

        return $text;
    }

    public static function clean_text($str) {
        $str = @strip_tags($str);
        $str = @stripslashes($str);
        //
        $invalid_characters = array("$", "%", "#", "<", ">", "|", "	", '"', '\'', ',', '.', '~', '!', '@', '^', '&', '*', '-');
        $str = str_replace($invalid_characters, "", $str);
        //
        $str = trim($str);
        //
        return $str;
    }

    function br2nl($string) {
        return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
    }

    public static function validateUploadedPicture(CUploadedFile $file, $maxWidth, $maxHeight, $minWidth, $minHeight, $mimeTypes) {

        $info = @getimagesize($file->getTempName());

        // remove unnecessary values from $info and make it more readable
        $info = array(
            'width' => $info[0],
            'height' => $info[1],
            'mime' => $info['mime'],
        );

        if ($info['width'] < $minWidth) {
            return self::MIN_WIDTH_ERROR;
        }
        if ($info['width'] > $maxWidth) {
            return self::MAX_WIDTH_ERROR;
        }
        if ($info['height'] < $minHeight) {
            return self::MIN_HEIGHT_ERROR;
        }
        if ($info['height'] > $maxHeight) {
            return self::MAX_HEIGHT_ERROR;
        }

        $mimeTypes = is_scalar($mimeTypes) ? array($mimeTypes) : $mimeTypes;
        if (!in_array($info['mime'], $mimeTypes)) {
            return self::MIME_TYPE_ERROR;
        }
        return self::PICTURE_VALID;
    }

    public static function viewPicture($picture, $width = 0, $class = '', $alt = '', $type = 1) {
        switch ($type) {
            case 0:
                $picture = $picture;
                break;
            case 1: // base url, avatar
                $picture = UP_AVATAR . '/' . $picture;
                break;
            case 2: // uploads
                $picture = UP_DIR . '/' . $picture;
                break;
        }
        if ($width == 0)
            return CHtml::image($picture, $alt, array('class' => $class));
        else
            return CHtml::image($picture, $alt, array('width' => $width . 'px', 'class' => $class));
    }

    /**
     * Checks whether a string is valid json.
     *
     * @param string $string
     * @return boolean
     */
    function isJSON($string) {
        try {
            // try to decode string
            json_decode($string);
        } catch (ErrorException $e) {
            // exception has been caught which means argument wasn't a string and thus is definitely no json.
            return FALSE;
        }

        // check if error occured
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function print_r($foo, $f = FALSE) {
        echo '<pre style="background: #fff; color:#333;">';
        print_r($foo, $f);
        echo '</pre>';
    }

    public static function var_dump($foo) {
        echo '<pre style="background: #fff; color:#333;">';
        var_dump($foo);
        echo '</pre>';
    }

    public static function out($foo) {
        echo '<pre style="background: #fff; color:#333;">';
        echo $foo;
        echo '</pre>';
    }

    public static function make_keywords($keyword) {
        $keyword = stripslashes($keyword);
        $keyword = str_replace("<", "&lt;", $keyword);
        $keyword = str_replace(">", "&gt;", $keyword);
        if (function_exists('mb_strtolower')) {
            $t = mb_strtolower($keyword, 'UTF-8');
        } else {
            $t = strtolower($keyword);
        }
        $t = preg_split("/[\s,]+/", $t);
        return $t;
    }

    public static function get_amount($money) {
        $cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
        $onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);

        $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

        $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
        $removedThousendSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '', $stringWithCommaOrDot);

        return (float) str_replace(',', '.', $removedThousendSeparator);
    }

    /*
     * @method: getTimeDuration
     * @param: unix timestamp
     * @return: duration of minutes, hours, days, weeks, months, years
     */

    public static function get_time_duration($unixTime) {
        $period = '';
        $secsago = time() - strtotime($unixTime);

        if ($secsago < 60) {
            $period = $secsago == 1 ? '1 second' : $secsago . ' seconds';
        } else if ($secsago < 3600) {
            $period = round($secsago / 60);
            $period = $period == 1 ? '1 minute' : $period . ' minutes';
        } else if ($secsago < 86400) {
            $period = round($secsago / 3600);
            $period = $period == 1 ? '1 hour' : $period . ' hours';
        } else if ($secsago < 604800) {
            $period = round($secsago / 86400);
            $period = $period == 1 ? '1 day' : $period . ' days';
        } else if ($secsago < 2419200) {
            $period = round($secsago / 604800);
            $period = $period == 1 ? '1 week' : $period . ' weeks';
        } else if ($secsago < 29030400) {
            $period = round($secsago / 2419200);
            $period = $period == 1 ? '1 month' : $period . ' months';
        } else {
            $period = round($secsago / 29030400);
            $period = $period == 1 ? '1 year' : $period . ' years';
        }
        return $period;
    }

    public static function second_minute($seconds) {
        $minResult = floor($seconds / 60);
        if ($minResult < 10) {
            $minResult = 0 . $minResult;
        }
        $secResult = ($seconds / 60 - $minResult) * 60;
        if ($secResult < 10) {
            $secResult = 0 . round($secResult);
        } else {
            $secResult = round($secResult);
        }
        return $minResult . ":" . $secResult;
    }

    /**
     * @param $str
     * @param $params
     * @param $dic
     * @return string
     */
    public static function t($str = '', $dic = 'post', $params = array(), $language = null, $source = null) {
        return Yii::t($dic, $str, $params, $source, $language);
    }

    public static function get_years_from_current($nearly = 30) {
        $cur_year = date('Y');
        $year = $cur_year - $nearly;
        while ($year <= $cur_year) {
            $years[$year] = $year;
            $year++;
        }
        return $years;
    }

    public static function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function multiExplode($delims, $string, $special = '|||') {

        if (is_array($delims) == false) {
            $delims = array($delims);
        }

        if (empty($delims) == false) {
            foreach ($delims as $d) {
                $string = str_replace($d, $special, $string);
            }
        }

        return explode($special, $string);
    }

    public static function pairsKeyValue($delims, $delimiter1 = '=', $delimiter2 = ',') {
        
        if (is_array($delims)) {
            foreach ($delims as $pair) {
                list ($k, $v) = explode($delimiter1, $pair);
                $pairs[$k] = $v;
            }
        } else {
            foreach (explode($delimiter2, $delims) as $pair) {
                list ($k, $v) = explode($delimiter1, $pair);
                $pairs[$k] = $v;
            }
        }
        
        return $pairs;
    }

}
