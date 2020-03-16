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
// class RssViewItems
//=========================================================

/**
 * Class RssViewItems
 * @package XoopsModules\Happylinux
 */
class RssViewItems extends RssBaseItems
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // format
    //---------------------------------------------------------
    public function format_from_parse()
    {
        foreach ($this->_item_objs as $i => $obj) {
            $this->_item_objs[$i]->format_from_parse();
        }
    }

    public function format_from_db()
    {
        foreach ($this->_item_objs as $i => $obj) {
            $this->_item_objs[$i]->format_from_db();
        }
    }

    //---------------------------------------------------------
    // for rssc_headline xoopsheadline
    //---------------------------------------------------------
    /**
     * @param string $format_date
     */
    public function format_for_rss($format_date = 'l')
    {
        foreach ($this->_item_objs as $i => $obj) {
            $this->_item_objs[$i]->format_for_rss($format_date);
        }
    }

    //---------------------------------------------------------
    // sanitize
    //---------------------------------------------------------
    /**
     * @param $param
     */
    public function sanitize(&$param)
    {
        foreach ($this->_item_objs as $i => $obj) {
            $this->_item_objs[$i]->sanitize($param);
        }
    }

    //---------------------------------------------------------
    // create
    // overload this function
    //---------------------------------------------------------
    /**
     * @return \XoopsModules\Happylinux\RssBaseBasic|\XoopsModules\Happylinux\RssViewItemSingle
     */
    public function &create_item_single()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new RssViewItemSingle();

        return $obj;
    }

    // --- class end ---
}
