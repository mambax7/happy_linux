<?php

namespace XoopsModules\Happylinux;

// $Id: rss_base_object.php,v 1.6 2008/01/31 14:07:05 ohwada Exp $

// 2008-01-30 K.OHWADA
// typo: create_item_singlel
// happylinux_rss_basic -> happylinux_rss_base_basic

// 2007-10-10 K.OHWADA
// set_is_japanese()

// 2007-09-20 K.OHWADA
// PHP5.2
// Assigning the return value of new by reference is deprecated
// Declaration of happylinux_rss_base_basic::get() should be compatible with that of happylinux_basic::get()

// 2007-08-01 K.OHWADA
// w3cdtf.php

// 2007-06-01 K.OHWADA
// divid from rss_object.php
// move get_unixtime_rfc822 from RssUtility.php

// 2007-05-12 K.OHWADA
// this is new file
// porting form rssc_xml_object.php

//=========================================================
// Happy Linux Framework Module
// this file contains 3 classes
//   happylinux_rss_base
//   happylinux_rss_base_basic
//   happylinux_rss_base_items
// 2007-05-12 K.OHWADA
//=========================================================

require_once XOOPS_ROOT_PATH . '/modules/happylinux/include/w3cdtf.php';

//=========================================================
// class rss_base_items
//=========================================================

/**
 * Class RssBaseItems
 * @package XoopsModules\Happylinux
 */
class RssBaseItems
{
    public $_item_objs = [];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy();
    }

    //---------------------------------------------------------
    // set & get var
    //---------------------------------------------------------
    /**
     * @param $items
     */
    public function set_vars($items)
    {
        $this->_item_objs = [];
        foreach ($items as $v) {
            // typo
            $item_obj = &$this->create_item_single();

            $item_obj->set_vars($v);
            $this->_item_objs[] = $item_obj;
        }
    }

    /**
     * @return array
     */
    public function &get_vars()
    {
        $arr = [];
        foreach ($this->_item_objs as $item_obj) {
            $arr[] = $item_obj->get_vars();
        }

        return $arr;
    }

    //---------------------------------------------------------
    // create
    // overload this function
    //---------------------------------------------------------
    // typo: create_item_singlel
    /**
     * @return \XoopsModules\Happylinux\RssBaseBasic
     */
    public function &create_item_single()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new RssBaseBasic();

        return $obj;
    }

    // --- class end ---
}
