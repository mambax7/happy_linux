<?php
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
// class happy_linux_rss_base
//=========================================================
class happy_linux_rss_base
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

    public function set_control($arr)
    {
        if (is_array($arr) && (count($arr) > 0)) {
            $this->_control_obj =& $this->create_control();
            $this->_control_obj->set_vars($arr);
        }
    }

    public function set_channel($arr)
    {
        if (is_array($arr) && (count($arr) > 0)) {
            $this->_channel_obj =& $this->create_channel();
            $this->_channel_obj->set_vars($arr);
        }
    }

    public function set_image($arr)
    {
        if (is_array($arr) && (count($arr) > 0)) {
            $this->_image_obj =& $this->create_image();
            $this->_image_obj->set_vars($arr);
        }
    }

    public function set_textinput($arr)
    {
        if (is_array($arr) && (count($arr) > 0)) {
            $this->_textinput_obj =& $this->create_textinput();
            $this->_textinput_obj->set_vars($arr);
        }
    }

    public function set_items($items)
    {
        if (is_array($items) && (count($items) > 0)) {
            $this->_items_obj =& $this->create_items();
            $this->_items_obj->set_vars($items);
        }
    }

    public function set_single_item($arr)
    {
        if (is_array($arr) && (count($arr) > 0)) {
            $this->_single_item_obj =& $this->create_single_item();
            $this->_single_item_obj->set_vars($arr);
        }
    }

    public function &get_vars()
    {
        $arr = array(
            'control'   => $this->get_control(),
            'channel'   => $this->get_channel(),
            'image'     => $this->get_image(),
            'textinput' => $this->get_textinput(),
            'items'     => $this->get_items(),
        );
        return $arr;
    }

    public function &get_control()
    {
        $ret = false;
        if (isset($this->_control_obj) && is_object($this->_control_obj)) {
            $ret =& $this->_control_obj->get_vars();
        }
        return $ret;
    }

    public function &get_channel()
    {
        $ret = false;
        if (isset($this->_channel_obj) && is_object($this->_channel_obj)) {
            $ret =& $this->_channel_obj->get_vars();
        }
        return $ret;
    }

    public function &get_image()
    {
        $ret = false;
        if (isset($this->_image_obj) && is_object($this->_image_obj)) {
            $ret =& $this->_image_obj->get_vars();
        }
        return $ret;
    }

    public function &get_textinput()
    {
        $ret = false;
        if (isset($this->_textinput_obj) && is_object($this->_textinput_obj)) {
            $ret =& $this->_textinput_obj->get_vars();
        }
        return $ret;
    }

    public function &get_items()
    {
        $ret = false;
        if (isset($this->_items_obj) && is_object($this->_items_obj)) {
            $ret =& $this->_items_obj->get_vars();
        }
        return $ret;
    }

    public function &get_single_item()
    {
        $ret = false;
        if (isset($this->_single_item_obj) && is_object($this->_single_item_obj)) {
            $ret =& $this->_single_item_obj->get_vars();
        }
        return $ret;
    }

    public function &get_channel_by_key($key)
    {
        $arr = $this->get_channel();

        $ret = false;
        if (isset($arr[$key])) {
            $ret =& $arr[$key];
        }
        return $ret;
    }

    //---------------------------------------------------------
    // create
    // overload this function
    //---------------------------------------------------------
    public function &create_control()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new happy_linux_basic();
        return $obj;
    }

    public function &create_channel()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new happy_linux_rss_base_basic();
        return $obj;
    }

    public function &create_image()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new happy_linux_rss_base_basic();
        return $obj;
    }

    public function &create_textinput()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new happy_linux_rss_base_basic();
        return $obj;
    }

    public function &create_items()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new happy_linux_rss_base_basic();
        return $obj;
    }

    public function &create_single_item()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new happy_linux_rss_base_basic();
        return $obj;
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_rss_base_basic
//=========================================================
class happy_linux_rss_base_basic extends happy_linux_basic
{
    // class
    public $_strings;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        // class
        $this->_strings = happy_linux_strings::getInstance();
    }

    //--------------------------------------------------------
    // set parameter
    //--------------------------------------------------------
    public function set_is_japanese($val)
    {
        $this->_strings->set_is_japanese($val);
    }

    //---------------------------------------------------------
    // is set & array
    //---------------------------------------------------------
    // Declaration of happy_linux_rss_base_basic::get() should be compatible with that of happy_linux_basic::get()
    public function get_rss_var($key1, $key2 = false)
    {
        $ret = false;
        if (isset($this->_vars[$key1])) {
            if ($key2) {
                if (isset($this->_vars[$key1][$key2])) {
                    $ret = $this->_vars[$key1][$key2];
                }
            } else {
                $ret = $this->_vars[$key1];
            }
        }
        return $ret;
    }

    public function is_set($key1, $key2 = false)
    {
        if (isset($this->_vars[$key1])) {
            if ($key2) {
                if (is_array($this->_vars[$key1]) && isset($this->_vars[$key1][$key2])) {
                    return true;
                }
            } else {
                return true;
            }
        }
        return false;
    }

    //-------------------------------------------------
    // non restrict feed_type
    // caller build, format_from_parse, format_from_db
    //-------------------------------------------------
    public function _build_summary_for_format()
    {
        $val = '';

        // ATOM summary
        if ($this->is_set('summary')) {
            $val = $this->get_rss_var('summary');
        } // RSS, RDF fulltext
        elseif ($this->_use_fulltext && $this->is_set('fulltext')) {
            $val = $this->get_rss_var('fulltext');
        } // RSS, RDF description
        elseif ($this->is_set('description')) {
            $val = $this->get_rss_var('description');
        } elseif ($this->is_set('dc', 'description')) {
            $val = $this->get_rss_var('dc', 'description');
        } // RSS, RDF content
        elseif ($this->is_set('content', 'encoded')) {
            $val = $this->get_rss_var('content', 'encoded');
        } // ATOM content
        elseif ($this->is_set('content') && $this->get_rss_var('content')) {
            $val = $this->get_rss_var('content');
        }

        $val = $this->_strings->strip_tags_for_text($val);

        return $val;
    }

    // some RSS have twe or more enclosure tag
    // set first one
    public function _get_enclosure_list()
    {
        $url    = null;
        $type   = null;
        $length = null;

        if ($this->is_set('enclosure')) {
            $enc = $this->get_rss_var('enclosure');

            if (isset($enc[0]['url'])) {
                $url = $enc[0]['url'];
            }
            if (isset($enc[0]['type'])) {
                $type = $enc[0]['type'];
            }
            if (isset($enc[0]['length'])) {
                $length = (int)$enc[0]['length'];
            }
        }

        return array($url, $type, $length);
    }

    //--------------------------------------------------------
    // get unixtime from RFC822
    //--------------------------------------------------------
    public function get_unixtime_rfc822($datetime)
    {
        $unixtime = strtotime($datetime);

        // maybe undefined time zone
        if ($unixtime == -1) {

            // delete time zone
            $datetime = preg_replace("/ [a-zA-Z]{3,}$/", '', $datetime);
            $unixtime = strtotime($datetime);
        }

        // give up
        $unixtime = (int)$unixtime;
        if ($unixtime < 0) {
            $unixtime = 0;
        }

        return $unixtime;
    }

    // -------------------------------------------------------------------------
    // get unixtime from W3C DTF (dc:date)
    // -------------------------------------------------------------------------
    public function get_unixtime_w3cdtf($datetime)
    {
        return happy_linux_w3cdtf_to_unixtime($datetime);
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_rss_base_items
//=========================================================
class happy_linux_rss_base_items
{
    public $_item_objs = array();

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
        $this->_item_objs = array();
        foreach ($items as $v) {
            // typo
            $item_obj =& $this->create_item_single();

            $item_obj->set_vars($v);
            $this->_item_objs[] = $item_obj;
        }
    }

    public function &get_vars()
    {
        $arr = array();
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
        $obj = new happy_linux_rss_base_basic();
        return $obj;
    }

    // --- class end ---
}
