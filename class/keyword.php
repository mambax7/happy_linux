<?php
// $Id: keyword.php,v 1.3 2008/02/05 00:42:12 ohwada Exp $

// 2008-02-03 K.OHWADA
// EUC-JP in google

//=========================================================
// Happy Linux Framework Module
// 2007-11-11 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_keyword
//=========================================================
class happy_linux_keyword
{

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_keyword();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // get keyword
    //---------------------------------------------------------
    public function &get_keyword_array_by_request()
    {
        // GET param
        $arr =& $this->get_keyword_array_from_get();
        if (is_array($arr) && count($arr)) {
            return $arr;
        }

        // server referer
        $arr =& $this->get_keyword_array_from_referer();
        return $arr;
    }

    //---------------------------------------------------------
    // get keyword from GET param
    //---------------------------------------------------------
    public function &get_keyword_array_from_get()
    {
        return $this->convert_str_to_array($this->get_keywords_from_get());
    }

    public function get_keywords_from_get()
    {
        return urldecode($this->get_text_from_get('keywords'));
    }

    public function get_query_from_get()
    {
        return $this->get_text_from_get('query');
    }

    public function get_text_from_get($key, $default = null)
    {
        $val = $default;
        if (isset($_GET[$key])) {
            $val = trim($this->strip_control($this->strip_slashes_gpc($_GET[$key])));
        }
        return $val;
    }

    //---------------------------------------------------------
    // get keyword from referer
    //---------------------------------------------------------
    public function &get_keyword_array_from_referer()
    {
        return $this->convert_str_to_array($this->get_keyword_from_referer());
    }

    public function get_keyword_from_referer()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer = $_SERVER['HTTP_REFERER'];
        } else {
            return false;
        }

        $parsed_url = parse_url($referer);

        if (isset($parsed_url['host'])) {
            $host = $parsed_url['host'];
        } else {
            return false;
        }

        if (isset($parsed_url['query'])) {
            $query = $parsed_url['query'];
        } else {
            return false;
        }

        parse_str($query, $parsed_str);

        if (preg_match('/google/', $host)) {
            $keyword = $this->_get_keyword_from_google($parsed_str);
        } elseif (preg_match('/yahoo/', $host)) {
            $keyword = $this->_get_keyword_from_yahoo($parsed_str);
        } else {
            return false;
        }

        return $keyword;
    }

    // http://www.google.com/search?hl=en&q=xoops&btnG=Google+Search
    // http://www.google.co.jp/search?hl=ja&q=%E3%81%AF%E3%81%A3%E3%81%B4%E3%81%83&lr=&btnG=Google+%E6%A4%9C%E7%B4%A2
    public function _get_keyword_from_google(&$arr)
    {
        if (isset($arr['q'])) {
            $q = $arr['q'];
        } elseif (isset($arr['as_q'])) {
            $q = $arr['as_q'];
        } else {
            return false;
        }

        // EUC-JP in google
        $ie = 'UTF-8';
        if (isset($arr['ie'])) {
            $ie = $arr['ie'];
        }

        $keyword = happy_linux_convert_encoding(urldecode($q), _CHARSET, $ie);
        return $keyword;
    }

    // http://search.yahoo.com/search?p=xoops&fr=yfp-t-501&toggle=1&cop=mss&ei=UTF-8
    // http://search.yahoo.co.jp/search?p=%A4%CF%A4%C3%A4%D4%A4%A3&x=14&y=13&fr=top_v2&tid=top_v2&ei=euc-jp&search.x=1
    public function _get_keyword_from_yahoo(&$arr)
    {
        if (isset($arr['p'])) {
            $p = $arr['p'];
        } else {
            return false;
        }

        $ei = 'UTF-8';
        if (isset($arr['ei'])) {
            $ei = $arr['ei'];
        }

        $keyword = happy_linux_convert_encoding(urldecode($p), _CHARSET, $ei);
        return $keyword;
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------
    public function urlencode_from_array(&$arr, $glue = ' ')
    {
        return urlencode($this->convert_array_to_str($arr, $glue));
    }

    public function &convert_str_to_array($str, $pattern = ' ')
    {
        $arr = null;

        if ($str === '') {
            return $arr;
        }

        $str_arr = explode($pattern, $str);

        foreach ($str_arr as $value) {
            $value = trim($value);
            if ($value != '') {
                $arr[] = $value;
            }
        }

        return $arr;
    }

    public function convert_array_to_str(&$arr, $glue = ' ')
    {
        $val = null;
        if (is_array($arr) && count($arr)) {
            $val = implode($glue, $arr);
        }
        return $val;
    }

    public function strip_slashes_gpc($str)
    {
        if (get_magic_quotes_gpc() && !is_array($str)) {
            $str = stripslashes($str);
        }
        return $str;
    }

    public function strip_control($str, $replace = '')
    {
        return happy_linux_str_replace_control_code($str, $replace);
    }

    // --- class end ---
}
