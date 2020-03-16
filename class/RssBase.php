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
// class RssBase
//=========================================================

/**
 * Class RssBase
 * @package XoopsModules\Happylinux
 */
class RssBase
{
    // object
    public $_control_obj     = null;
    public $_channel_obj     = null;
    public $_image_obj       = null;
    public $_textinput_obj   = null;
    public $_items_obj       = null;
    public $_single_item_obj = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    //---------------------------------------------------------
    // set & get vars
    //---------------------------------------------------------
    /**
     * @param $arr
     */
    public function set_vars($arr)
    {
        if (isset($arr['control'])) {
            $this->set_control($arr['control']);
        }

        if (isset($arr['channel'])) {
            $this->set_channel($arr['channel']);
        }

        if (isset($arr['image'])) {
            $this->set_image($arr['image']);
        }

        if (isset($arr['textinput'])) {
            $this->set_textinput($arr['textinput']);
        }

        if (isset($arr['items'])) {
            $this->set_items($arr['items']);
        }
    }

    /**
     * @param $arr
     */
    public function set_control($arr)
    {
        if (is_array($arr) && (count($arr) > 0)) {
            $this->_control_obj = &$this->create_control();
            $this->_control_obj->set_vars($arr);
        }
    }

    /**
     * @param $arr
     */
    public function set_channel($arr)
    {
        if (is_array($arr) && (count($arr) > 0)) {
            $this->_channel_obj = &$this->create_channel();
            $this->_channel_obj->set_vars($arr);
        }
    }

    /**
     * @param $arr
     */
    public function set_image($arr)
    {
        if (is_array($arr) && (count($arr) > 0)) {
            $this->_image_obj = &$this->create_image();
            $this->_image_obj->set_vars($arr);
        }
    }

    /**
     * @param $arr
     */
    public function set_textinput($arr)
    {
        if (is_array($arr) && (count($arr) > 0)) {
            $this->_textinput_obj = &$this->create_textinput();
            $this->_textinput_obj->set_vars($arr);
        }
    }

    /**
     * @param $items
     */
    public function set_items($items)
    {
        if (is_array($items) && (count($items) > 0)) {
            $this->_items_obj = &$this->create_items();
            $this->_items_obj->set_vars($items);
        }
    }

    /**
     * @param $arr
     */
    public function set_single_item($arr)
    {
        if (is_array($arr) && (count($arr) > 0)) {
            $this->_single_item_obj = &$this->create_single_item();
            $this->_single_item_obj->set_vars($arr);
        }
    }

    /**
     * @return array
     */
    public function &get_vars()
    {
        $arr = [
            'control'   => $this->get_control(),
            'channel'   => $this->get_channel(),
            'image'     => $this->get_image(),
            'textinput' => $this->get_textinput(),
            'items'     => $this->get_items(),
        ];

        return $arr;
    }

    /**
     * @return bool
     */
    public function &get_control()
    {
        $ret = false;
        if (isset($this->_control_obj) && is_object($this->_control_obj)) {
            $ret = &$this->_control_obj->get_vars();
        }

        return $ret;
    }

    /**
     * @return bool
     */
    public function &get_channel()
    {
        $ret = false;
        if (isset($this->_channel_obj) && is_object($this->_channel_obj)) {
            $ret = &$this->_channel_obj->get_vars();
        }

        return $ret;
    }

    /**
     * @return bool
     */
    public function &get_image()
    {
        $ret = false;
        if (isset($this->_image_obj) && is_object($this->_image_obj)) {
            $ret = &$this->_image_obj->get_vars();
        }

        return $ret;
    }

    /**
     * @return bool
     */
    public function &get_textinput()
    {
        $ret = false;
        if (isset($this->_textinput_obj) && is_object($this->_textinput_obj)) {
            $ret = &$this->_textinput_obj->get_vars();
        }

        return $ret;
    }

    /**
     * @return bool
     */
    public function &get_items()
    {
        $ret = false;
        if (isset($this->_items_obj) && is_object($this->_items_obj)) {
            $ret = &$this->_items_obj->get_vars();
        }

        return $ret;
    }

    /**
     * @return bool
     */
    public function &get_single_item()
    {
        $ret = false;
        if (isset($this->_single_item_obj) && is_object($this->_single_item_obj)) {
            $ret = &$this->_single_item_obj->get_vars();
        }

        return $ret;
    }

    /**
     * @param $key
     * @return bool|mixed
     */
    public function &get_channel_by_key($key)
    {
        $arr = $this->get_channel();

        $ret = false;
        if (isset($arr[$key])) {
            $ret = &$arr[$key];
        }

        return $ret;
    }

    //---------------------------------------------------------
    // create
    // overload this function
    //---------------------------------------------------------
    /**
     * @return \XoopsModules\Happylinux\BasicObject
     */
    public function &create_control()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new BasicObject();

        return $obj;
    }

    /**
     * @return \XoopsModules\Happylinux\RssBaseBasic
     */
    public function &create_channel()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new RssBaseBasic();

        return $obj;
    }

    /**
     * @return \XoopsModules\Happylinux\RssBaseBasic
     */
    public function &create_image()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new RssBaseBasic();

        return $obj;
    }

    /**
     * @return \XoopsModules\Happylinux\RssBaseBasic
     */
    public function &create_textinput()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new RssBaseBasic();

        return $obj;
    }

    /**
     * @return \XoopsModules\Happylinux\RssBaseBasic
     */
    public function &create_items()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new RssBaseBasic();

        return $obj;
    }

    /**
     * @return \XoopsModules\Happylinux\RssBaseBasic
     */
    public function &create_single_item()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new RssBaseBasic();

        return $obj;
    }

    // --- class end ---
}
