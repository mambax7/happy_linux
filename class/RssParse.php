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
// class rss_parse
//=========================================================

/**
 * Class RssParse
 * @package XoopsModules\Happylinux
 */
class RssParse extends RssBase
{
    // cached data
    public $_converted_data;

    // encoding
    public $_local_encoding = _CHARSET;
    public $_xml_encoding   = 'utf-8';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // set & get vars
    //---------------------------------------------------------
    /**
     * @param $obj
     */
    public function set_vars_from_parse($obj)
    {
        $control = [
            'feed_type'       => $obj->feed_type,
            'feed_version'    => $obj->feed_version,
            'source_encoding' => $obj->source_encoding,
            'encoding'        => $obj->encoding,
        ];

        $this->set_control($control);
        $this->set_channel($obj->channel);
        $this->set_image($obj->image);
        $this->set_textinput($obj->textinput);
        $this->set_items($obj->items);
    }

    /**
     * @return bool
     */
    public function get_source_encoding()
    {
        $arr = $this->get_control();

        $ret = false;
        if (isset($arr['source_encoding'])) {
            $ret = &$arr['source_encoding'];
        }

        return $ret;
    }

    /**
     * @return bool
     */
    public function &get_converted_data()
    {
        $ret = false;
        if (isset($this->_converted_data)) {
            $ret = &$this->_converted_data;
        }

        return $ret;
    }

    //---------------------------------------------------------
    // convert from parse to local
    //---------------------------------------------------------
    public function convert_to_local()
    {
        $to   = $this->_local_encoding;
        $from = $this->_xml_encoding;

        // BUG: sometime cannot parse
        if (isset($this->_channel_obj) && is_object($this->_channel_obj)) {
            $this->_channel_obj->convert($to, $from);
        }

        if (isset($this->_items_obj) && is_object($this->_items_obj)) {
            $this->_items_obj->convert($to, $from);
        }

        if (isset($this->_image_obj) && is_object($this->_image_obj)) {
            $this->_image_obj->convert($to, $from);
        }

        if (isset($this->_textinput_obj) && is_object($this->_textinput_obj)) {
            $this->_textinput_obj->convert($to, $from);
        }

        $this->_converted_data = &$this->get_vars();
    }

    //---------------------------------------------------------
    // build_for_store
    //---------------------------------------------------------
    public function build_for_store()
    {
        // BUG: sometime cannot parse
        if (isset($this->_items_obj) && is_object($this->_items_obj)) {
            $site_title = $this->get_channel_by_key('title');
            $site_link  = $this->get_channel_by_key('link');
            $this->_items_obj->build($site_title, $site_link, $this->_control_obj);
        }
    }

    //---------------------------------------------------------
    // set and get property
    //---------------------------------------------------------
    /**
     * @param $value
     */
    public function set_xml_encoding($value)
    {
        $this->_xml_encoding = $value;
    }

    /**
     * @param $value
     */
    public function set_local_encoding($value)
    {
        $this->_local_encoding = $value;
    }

    //---------------------------------------------------------
    // create
    // overload this function
    //---------------------------------------------------------
    /**
     * @return \XoopsModules\Happylinux\RssBaseBasic|\XoopsModules\Happylinux\RssParseChannel
     */
    public function &create_channel()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new RssParseChannel();

        return $obj;
    }

    /**
     * @return \XoopsModules\Happylinux\RssBaseBasic|\XoopsModules\Happylinux\RssParseImage
     */
    public function &create_image()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new RssParseImage();

        return $obj;
    }

    /**
     * @return \XoopsModules\Happylinux\RssBaseBasic|\XoopsModules\Happylinux\RssParseTextinput
     */
    public function &create_textinput()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new RssParseTextinput();

        return $obj;
    }

    /**
     * @return \XoopsModules\Happylinux\RssBaseBasic|\XoopsModules\Happylinux\RssParseItems
     */
    public function &create_items()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new RssParseItems();

        return $obj;
    }

    /**
     * @return \XoopsModules\Happylinux\RssParseItemSingle|\XoopsModules\Happylinux\RssBaseBasic
     */
    public function &create_single_item()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new RssParseItemSingle();

        return $obj;
    }

    // --- class end ---
}
