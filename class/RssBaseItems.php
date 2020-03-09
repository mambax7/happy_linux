<?php

namespace XoopsModules\Happy_linux;

// $Id: rss_base_object.php,v 1.6 2008/01/31 14:07:05 ohwada Exp $

// 2008-01-30 K.OHWADA
// typo: create_item_singlel
// happy_linux_rss_basic -> happy_linux_rss_base_basic

// 2007-10-10 K.OHWADA
// set_is_japanese()

// 2007-09-20 K.OHWADA
// PHP5.2
// Assigning the return value of new by reference is deprecated
// Declaration of happy_linux_rss_base_basic::get() should be compatible with that of happy_linux_basic::get()

// 2007-08-01 K.OHWADA
// w3cdtf.php

// 2007-06-01 K.OHWADA
// divid from rss_object.php
// move get_unixtime_rfc822 from rss_utility.php

// 2007-05-12 K.OHWADA
// this is new file
// porting form rssc_xml_object.php

//=========================================================
// Happy Linux Framework Module
// this file contains 3 classes
//   happy_linux_rss_base
//   happy_linux_rss_base_basic
//   happy_linux_rss_base_items
// 2007-05-12 K.OHWADA
//=========================================================

include_once XOOPS_ROOT_PATH . '/modules/happy_linux/include/w3cdtf.php';

//=========================================================
// class rss_base_items
//=========================================================
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
    public function &create_item_single()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new Happy_linux\RssBaseBasic();

        return $obj;
    }

    // --- class end ---
}
