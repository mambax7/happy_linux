<?php

namespace XoopsModules\Happylinux;

// $Id: rss_view_object.php,v 1.2 2010/11/07 19:26:32 ohwada Exp $

// 2010-11-07 K.OHWADA
// _substitute_link()

// 2008-01-20 K.OHWADA
// divid to rss_view_object.php
// Assigning the return value of new by reference is deprecated

// 2007-10-10 K.OHWADA
// set_is_japanese()
// BUG: preg_match() expects parameter 2 to be string, array given in w3cdtf.php
// get() -> get_rss_var()

// 2007-08-01 K.OHWADA
// BUG: not sanitize site_url

// 2007-06-01 K.OHWADA
// RssViewItemSingle()

// 2007-05-12 K.OHWADA
// this is new file
// porting form rssc_xml_object.php

//=========================================================
// Happy Linux Framework Module
// this file contains 7 classes
//   RssView
//   happylinux_rss_view_basic
//   RssViewChannel
//   RssViewImage
//   RssViewTextinput
//   RssViewItems
//   RssViewItemSingle
// 2007-05-12 K.OHWADA
//=========================================================

//=========================================================
// class rss_view_basic
//=========================================================

/**
 * Class RssViewBasic
 * @package XoopsModules\Happylinux
 */
class RssViewBasic extends RssBaseBasic
{
    // constant
    public $_TITLE_SUBSTITUTE = '---';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // build
    //---------------------------------------------------------
    /**
     * @param $key
     * @param $time_unix
     */
    public function _set_unixtime($key, $time_unix)
    {
        if ($time_unix) {
            $this->set($key, $time_unix);
            $this->set($key . '_long', formatTimestamp($time_unix, 'l'));
            $this->set($key . '_short', formatTimestamp($time_unix, 's'));
            $this->set($key . '_mysql', formatTimestamp($time_unix, 'mysql'));
        }
    }

    //---------------------------------------------------------
    // substitute_title
    // some feed have no title
    // subsutute by ---
    //---------------------------------------------------------
    /**
     * @param $key
     * @return bool|mixed|string
     */
    public function _substitute_title($key)
    {
        if ($this->is_set($key)) {
            $val = $this->get_rss_var($key);
            if ($this->_strings->check_valid($val)) {
                return $val;
            }
        }

        return $this->_TITLE_SUBSTITUTE;
    }

    //---------------------------------------------------------
    // substitute_link
    // some feed have no link
    // subsutute by null
    //---------------------------------------------------------
    /**
     * @param $key
     * @return bool|mixed|null
     */
    public function _substitute_link($key)
    {
        if ($this->is_set($key)) {
            $val = $this->get_rss_var($key);
            if ($this->_check_http_start($val)) {
                return $val;
            }
        }

        return null;
    }

    /**
     * @param $str
     * @return bool
     */
    public function _check_http_start($str)
    {
        if (preg_match('|^https?://|', $str)) {
            return true;    // include HTTP
        }

        return false;
    }

    //---------------------------------------------------------
    // sanitize
    //---------------------------------------------------------
    public function sanitize()
    {
        // no action
    }

    /**
     * @param $text
     * @param $flag
     * @param $max
     * @return string|string[]|null
     */
    public function _sanitize_allow_html($text, $flag, $max)
    {
        // allow HTML tag & under max content
        if ($flag) {
            if (($max < 0) || (mb_strlen($text) <= $max)) {
                $ret = $this->_strings->replace_javascript($text);

                if (!$this->_strings->check_javascript($ret)) {
                    return $ret;
                }
            }
        }

        $ret = $this->_sanitize_summary($text, $max);

        return $ret;
    }

    //---------------------------------------------------------
    // use string class
    //---------------------------------------------------------
    /**
     * @param $arr
     * @return array|bool|string|string[]|null
     */
    public function &_sanitize_block($arr)
    {
        $ret = &$this->_strings->sanitize_array_text($arr);

        return $ret;
    }

    /**
     * @param $text
     * @return string
     */
    public function _sanitize_html_url($text)
    {
        $ret = $this->_strings->sanitize_url($text);

        return $ret;
    }

    /**
     * @param $text
     * @return string|string[]|null
     */
    public function _sanitize_html_text($text)
    {
        $ret = $this->_strings->sanitize_text($text);

        return $ret;
    }

    /**
     * @param      $text
     * @param      $max
     * @param null $keyword_array
     * @return string|string[]|null
     */
    public function _sanitize_summary($text, $max, $keyword_array = null)
    {
        $ret = $this->_strings->build_summary($text, $max, $keyword_array, 's');

        return $ret;
    }

    // --- class end ---
}
