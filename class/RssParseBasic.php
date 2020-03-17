<?php

namespace XoopsModules\Happylinux;

// $Id: rss_parse_object.php,v 1.3 2012/03/17 16:08:32 ohwada Exp $

// 2012-03-01 K.OHWADA
// <geo:lat>lat</geo:lat>

// 2010-11-07 K.OHWADA
// BUG: NOT parse https://maps.google.co.jp/maps/

// 2009-02-20 K.OHWADA
// _build_geo() _build_media_content()

// 2008-01-30 K.OHWADA
// typo: create_item_singlel

// 2007-09-20 K.OHWADA
// PHP5.2
// Assigning the return value of new by reference is deprecated
// Declaration of RssParseChannel::convert() should be compatible with that of RssParseBasic::convert()
// Declaration of RssParseItemSingle::build() should be compatible with that of RssParseBasic::build() in happylinux\class\rss_parse_object.php
// Non-static method Happylinux\ConvertEncoding::getInstance() should not be called statically, assuming $this from incompatible context

// 2007-08-01 K.OHWADA
// strip_control_array()

// 2007-06-01 K.OHWADA
// divid from rss_object.php

// 2007-05-12 K.OHWADA
// this is new file
// porting form rssc_xml_object.php

//=========================================================
// Happy Linux Framework Module
// this file contains 7 classes
//   happylinux_rss_parse
//   RssParseBasic
//   RssParseChannel
//   RssParseImage
//   RssParseTextinput
//   RssParseItems
//   RssParseItemSingle
// 2007-05-12 K.OHWADA
//=========================================================

//=========================================================
// class RssParseBasic
//=========================================================

/**
 * Class RssParseBasic
 * @package XoopsModules\Happylinux
 */
class RssParseBasic extends RssBaseBasic
{
    // class
    public $_convert;

    // control
    public $_feed_type = null;
    public $_feed_version;
    public $_source_encoding;
    public $_encoding;
    public $_local_encoding;

    public $_REPLACE_CHAR = ' ';   // space

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        // class
        // Non-static method Happylinux\ConvertEncoding::getInstance() should not be called statically, assuming $this from incompatible context
        $this->_convert = new ConvertEncoding();
    }

    //---------------------------------------------------------
    // convert parse to local
    //---------------------------------------------------------
    // Declaration of RssParseChannel::convert() should be compatible with that of RssParseBasic::convert()
    /**
     * @param $to
     * @param $from
     */
    public function convert($to, $from)
    {
        // no action
    }

    /**
     * @param $arr1
     * @param $to
     * @param $from
     * @return array|bool|string|string[]|null
     */
    public function &_convert_block(&$arr1, $to, $from)
    {
        $arr2 = &$this->_convert->convert_array($arr1, $to, $from);
        if ($this->_strings->check_in_encoding_array($to)) {
            $arr2 = $this->_strings->replace_control_array($arr2, $this->_REPLACE_CHAR);
        }

        return $arr2;
    }

    /**
     * @param $str
     * @param $to
     * @param $from
     * @return string|string[]|null
     */
    public function _convert_strings($str, $to, $from)
    {
        $str = $this->_convert->convert($str, $to, $from);
        if ($this->_strings->check_in_encoding_array($to)) {
            $str = $this->_strings->replace_control($str, $this->_REPLACE_CHAR);
        }

        return $str;
    }

    //---------------------------------------------------------
    // build
    //---------------------------------------------------------
    // Declaration of RssParseItemSingle::build() should be compatible with that of RssParseBasic::build() in happylinux\class\rss_parse_object.php
    /**
     * @param $site_title
     * @param $site_link
     * @param $obj
     */
    public function build($site_title, $site_link, $obj)
    {
        // no action
    }

    public function format()
    {
        // no action
    }

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
    // control
    //---------------------------------------------------------
    /**
     * @param $obj
     */
    public function set_control_obj($obj)
    {
        if (is_object($obj)) {
            $this->_feed_type       = $obj->get('feed_type');
            $this->_feed_version    = $obj->get('feed_version');
            $this->_source_encoding = $obj->get('source_encoding');
            $this->_encoding        = $obj->get('encoding');
        }
    }

    /**
     * @return bool
     */
    public function is_rss()
    {
        if (HAPPYLINUX_MAGPIE_RSS == $this->_feed_type) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function is_atom()
    {
        if (HAPPYLINUX_MAGPIE_ATOM == $this->_feed_type) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // set parameter
    //---------------------------------------------------------
    /**
     * @param $val
     */
    public function set_local_encoding($val)
    {
        $this->_local_encoding = $val;
    }

    // --- class end ---
}
