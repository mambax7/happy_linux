<?php

namespace XoopsModules\Happylinux;

// $Id: highlight.php,v 1.7 2007/11/16 11:59:37 ohwada Exp $

// 2007-11-11 K.OHWADA
// omit empty in keyword array

// 2007-07-16 K.OHWADA
// BUG 4647: keyword "abc" match "abcc"

// 2006-11-20 K.OHWADA
// for happy_search
// small change build_highlight_keyword_array()

// 2006-10-14 K.OHWADA
// BUG: double highlight

// 2006-09-20 K.OHWADA
// this is new file

//=========================================================
// Happy Linux Framework Module
// 2006-09-01 K.OHWADA
// ͭ����������
//=========================================================

//---------------------------------------------------------
// original: keyhighlighter
// https://www.phpclasses.org/browse/package/1792.html
//
// porting from smartsection <https://smartfactory.ca>
// https://smartfactory.ca/modules/newbb/viewtopic.php?topic_id=1211
//---------------------------------------------------------

//=========================================================
// class highlight
//=========================================================

/**
 * Class Highlight
 * @package XoopsModules\Happylinux
 */
class Highlight
{
    // keyword
    public $_pattern_array;
    public $_replace_callback = 'happylinux_highlighter_by_style';

    // background-color: light yellow
    public $_style = 'font-weight: bolder; background-color: #ffff80; ';
    public $_class = 'happylinux_highlight';

    public $_flag_trim            = true;
    public $_flag_sanitize        = true;
    public $_flag_remove_not_word = false;

    // same language match contorl code
    // ex) BIG-5 GB2312 �� C05C B2CD ͷ B943 904A
    public $_flag_remove_control_code = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // function
    //---------------------------------------------------------
    /**
     * @param      $str
     * @param      $keywords
     * @param bool $flag_singlewords
     * @return string
     */
    public function build_highlight_keywords($str, $keywords, $flag_singlewords = true)
    {
        if ($keywords) {
            $keywords = $this->_sanitize_keyword($keywords);

            $arr = [];
            if ($flag_singlewords) {
                $keyword_array = explode(' ', $keywords);
                foreach ($keyword_array as $keyword) {
                    $arr[] = '/(?' . '>' . preg_quote($keyword) . '+)/si';
                }
            } else {
                $arr[] = '/(?' . '>' . preg_quote($keywords) . '+)/si';
            }

            $this->_pattern_array = $arr;

            $str = $this->_replace_content($str);
        }

        return $str;
    }

    /**
     * @param $str
     * @param $keyword_array
     * @return string
     */
    public function build_highlight_keyword_array($str, $keyword_array)
    {
        $ret = $str;

        if (is_array($keyword_array) && count($keyword_array)) {
            $arr = [];

            foreach ($keyword_array as $k) {
                $keyword = $this->_sanitize_keyword($k);

                // not empty
                if ($keyword) {
                    // BUG 4647: keyword "abc" match "abcc"
                    $arr[] = '/(?' . '>' . preg_quote($keyword, '/') . ')/si';
                }
            }

            if (count($arr)) {
                $this->_pattern_array = &$arr;
                $ret                  = $this->_replace_content($str);
            }
        }

        return $ret;
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public function _sanitize_keyword($str)
    {
        if ($this->_flag_trim) {
            $str = trim($str);
        }

        if ($this->_flag_remove_control_code) {
            $str = preg_replace('/[\x00-\x1F|\x7F]/', '', $str);
        }

        if ($this->_flag_remove_not_word) {
            $str = preg_replace('/[^\w ]/si', '', $str);
        }

        if ($this->_flag_sanitize) {
            $str = htmlspecialchars($str, ENT_QUOTES);
        }

        return $str;
    }

    /**
     * @param $str
     * @return string
     */
    public function _replace_content($str)
    {
        $str = '>' . $str . '<';
        $str = preg_replace_callback("/(\>(((?" . ">[^><]+)|(?R))*)\<)/is", [&$this, '_replace_with_callback'], $str);
        $str = mb_substr($str, 1, -1);

        return $str;
    }

    /**
     * @param $matches
     * @return bool|mixed|string|string[]|null
     */
    public function _replace_with_callback($matches)
    {
        $replacement = '<span class="' . $this->_class . '">\\0</span>';
        $result      = false;

        if (is_array($matches) && isset($matches[0])) {
            $result = $matches[0];

            foreach ($this->_pattern_array as $pattern) {
                if (null !== $this->_replace_callback) {
                    $result = preg_replace_callback($pattern, $this->_replace_callback, $result);
                } else {
                    $result = preg_replace($pattern, $replacement, $result);
                }
            }
        }

        return $result;
    }

    //---------------------------------------------------------
    // set parameter
    //---------------------------------------------------------
    /**
     * @param $val
     */
    public function set_replace_callback($val)
    {
        $this->_replace_callback = $val;
    }

    /**
     * @param $val
     */
    public function set_flag_sanitize($val)
    {
        $this->_flag_sanitize = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_flag_trim($val)
    {
        $this->_flag_trim = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_flag_remove_control_code($val)
    {
        $this->_flag_remove_control_code = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_flag_remove_not_word($val)
    {
        $this->_flag_remove_not_word = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_style($val)
    {
        $this->_style = $val;
    }

    /**
     * @param $val
     */
    public function set_class($val)
    {
        $this->_class = $val;
    }

    /**
     * @return string
     */
    public function get_style()
    {
        return $this->_style;
    }

    /**
     * @return string
     */
    public function get_class()
    {
        return $this->_class;
    }

    // --- class end ---
}

//=========================================================
// function
//=========================================================
//---------------------------------------------------------
// porting from smartsection <https://smartfactory.ca>
//---------------------------------------------------------
/**
 * @param $matches
 * @return bool|string
 */
function happylinux_highlighter($matches)
{
    // background-color: light yellow
    $STYLE = 'font-weight: bolder; background-color: #ffff80; ';
    $ret   = false;
    if (is_array($matches) && isset($matches[0])) {
        $ret = '<span style="' . $STYLE . '">' . $matches[0] . '</span>';
    }

    return $ret;
}

/**
 * @param $matches
 * @return bool|string
 */
function happylinux_highlighter_by_style($matches)
{
    $highlight = Highlight::getInstance();
    $style     = $highlight->get_style();
    $ret       = false;
    if (is_array($matches) && isset($matches[0])) {
        $ret = '<span style="' . $style . '">' . $matches[0] . '</span>';
    }

    return $ret;
}

/**
 * @param $matches
 * @return bool|string
 */
function happylinux_highlighter_by_class($matches)
{
    $highlight = Highlight::getInstance();
    $class     = $highlight->get_class();
    $ret       = false;
    if (is_array($matches) && isset($matches[0])) {
        $ret = '<span class="' . $class . '">' . $matches[0] . '</span>';
    }

    return $ret;
}
