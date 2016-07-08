<?php
// $Id: rss_viewer.php,v 1.4 2008/01/31 14:07:05 ohwada Exp $

// 2008-01-20 K.OHWADA
// set_mode_content_script()
// Assigning the return value of new by reference is deprecated

// 2007-10-10 K.OHWADA
// set_is_japanese()

// 2007-06-01 K.OHWADA
// porting from happy_linux_rss_viewer.php

//=========================================================
// Happy Linux Framework Module
// 2007-06-01 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_rss_viewer
//=========================================================
class happy_linux_rss_viewer extends happy_linux_error
{
    // parameter
    public $_flag_title_html   = false;
    public $_flag_content_html = false;
    public $_flag_highlight    = false;
    public $_max_summary       = 250;
    public $_max_title         = -1; // unlimited
    public $_max_content       = -1; // unlimited
    public $_keyword_array     = null;
    public $_is_japanese       = false;

    public $_mode_content_script     = 2; // remove
    public $_mode_content_style      = 2;
    public $_mode_content_link       = 2;
    public $_mode_content_comment    = 2;
    public $_mode_content_cdata      = 2;
    public $_mode_content_onmouse    = 1;  // replace
    public $_mode_content_attr_style = 1;
    public $_mode_content_javascript = 1;
    public $_flag_content_tags       = false;
    public $_content_tags            = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_rss_viewer();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // create object
    //---------------------------------------------------------
    public function &create()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new happy_linux_rss_view();
        return $obj;
    }

    public function &create_channel()
    {
        $obj = new happy_linux_rss_view_channel();
        return $obj;
    }

    public function &create_image()
    {
        $obj = new happy_linux_rss_view_image();
        return $obj;
    }

    public function &create_textinput()
    {
        $obj = new happy_linux_rss_view_textinput();
        return $obj;
    }

    public function &create_items()
    {
        $obj = new happy_linux_rss_view_items();
        return $obj;
    }

    public function &create_item_single()
    {
        $obj = new happy_linux_rss_view_item_single();
        return $obj;
    }

    //---------------------------------------------------------
    // view sanitize
    //---------------------------------------------------------
    public function &view_format_sanitize(&$arr, $flag_sanitize = true)
    {
        $obj =& $this->create();
        $obj->set_vars($arr);
        $obj->view_format();
        if ($flag_sanitize) {
            $this->_set_sanitize_obj($obj);
            $obj->view_sanitize();
        }
        $data = $obj->get_vars();
        return $data;
    }

    public function &view_format(&$arr)
    {
        $obj =& $this->create();
        $obj->set_vars($arr);
        $obj->view_format();
        $data = $obj->get_vars();
        return $data;
    }

    public function &view_sanitize(&$arr)
    {
        $obj =& $this->create();
        $obj->set_vars($arr);
        $this->_set_sanitize_obj($obj);
        $obj->view_sanitize();
        $data = $obj->get_vars();
        return $data;
    }

    public function &view_format_sanitize_items(&$items, $flag_sanitize = true)
    {
        $feeds = array();
        if (is_array($items) && (count($items) > 0)) {
            foreach ($items as $item) {
                $feeds[] =& $this->view_format_sanitize_single_item($items, $flag_sanitize);
            }
        }
        return $feeds;
    }

    public function &view_format_sanitize_single_item(&$item, $flag_sanitize = true)
    {
        $obj =& $this->create();
        $obj->set_single_item($item);
        $obj->view_format_single_item();
        if ($flag_sanitize) {
            $this->_set_sanitize_obj($obj);
            $obj->view_sanitize_single_item();
        }
        $feed =& $obj->get_single_item();
        return $feed;
    }

    public function _set_sanitize_obj(&$obj)
    {
        $obj->set_title_html($this->_flag_title_html);
        $obj->set_content_html($this->_flag_content_html);
        $obj->set_flag_highlight($this->_flag_highlight);
        $obj->set_max_title($this->_max_title);
        $obj->set_max_content($this->_max_content);
        $obj->set_max_summary($this->_max_summary);
        $obj->set_keyword_array($this->_keyword_array);
        $obj->set_is_japanese($this->_is_japanese);

        $obj->set_mode_content_script($this->_mode_content_script);
        $obj->set_mode_content_style($this->_mode_content_style);
        $obj->set_mode_content_link($this->_mode_content_link);
        $obj->set_mode_content_comment($this->_mode_content_comment);
        $obj->set_mode_content_cdata($this->_mode_content_cdata);
        $obj->set_mode_content_onmouse($this->_mode_content_onmouse);
        $obj->set_mode_content_attr_style($this->_mode_content_attr_style);
        $obj->set_mode_content_javascript($this->_mode_content_javascript);
        $obj->set_flag_content_tags($this->_flag_content_tags);
        $obj->set_content_tags($this->_content_tags);
    }

    //---------------------------------------------------------
    // sanitize property
    //---------------------------------------------------------
    public function set_title_html($value)
    {
        $this->_flag_title_html = (bool)$value;
    }

    public function set_content_html($value)
    {
        $this->_flag_content_html = (bool)$value;
    }

    public function set_highlight($value)
    {
        $this->_flag_highlight = (bool)$value;
    }

    public function set_max_title($value)
    {
        $this->_max_title = (int)$value;
    }

    public function set_max_summary($value)
    {
        $this->_max_summary = (int)$value;
    }

    public function set_max_content($value)
    {
        $this->_max_content = (int)$value;
    }

    public function set_is_japanese($value)
    {
        $this->_is_japanese = (bool)$value;
    }

    public function set_keyword_array($arr)
    {
        if (is_array($arr) && count($arr)) {
            $this->_keyword_array = $arr;
        }
    }

    public function set_mode_content_script($value)
    {
        $this->_mode_content_script = (int)$value;
    }

    public function set_mode_content_style($value)
    {
        $this->_mode_content_style = (int)$value;
    }

    public function set_mode_content_link($value)
    {
        $this->_mode_content_link = (int)$value;
    }

    public function set_mode_content_comment($value)
    {
        $this->_mode_content_comment = (int)$value;
    }

    public function set_mode_content_cdata($value)
    {
        $this->_mode_content_cdata = (int)$value;
    }

    public function set_mode_content_onmouse($value)
    {
        $this->_mode_content_onmouse = (int)$value;
    }

    public function set_mode_content_attr_style($value)
    {
        $this->_mode_content_attr_style = (int)$value;
    }

    public function set_mode_content_javascript($value)
    {
        $this->_mode_content_javascript = (int)$value;
    }

    public function set_flag_content_tags($value)
    {
        $this->_flag_content_tags = (bool)$value;
    }

    public function set_content_tags($value)
    {
        $this->_content_tags = $value;
    }

    // --- class end ---
}
