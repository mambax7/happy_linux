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
// class RssView
//=========================================================

/**
 * Class RssView
 * @package XoopsModules\Happylinux
 */
class RssView extends RssBase
{
    // set param
    public $_flag_title_html   = false;
    public $_flag_content_html = false;
    public $_max_summary       = 250;
    public $_max_title         = -1;   // unlimited
    public $_max_content       = -1;   // unlimited
    public $_flag_highlight    = false;
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

    //---------------------------------------------------------
    // view format
    //---------------------------------------------------------
    public function view_format()
    {
        if (isset($this->_channel_obj) && is_object($this->_channel_obj)) {
            $this->_channel_obj->format();
        }

        if (isset($this->_items_obj) && is_object($this->_items_obj)) {
            $this->_items_obj->format_from_parse();
        }
    }

    public function view_format_items()
    {
        // BUG: Fatal error: Call to a member function on a non-object
        if (isset($this->_items_obj) && is_object($this->_items_obj)) {
            $this->_items_obj->format_from_db();
        }
    }

    public function view_format_single_item()
    {
        if (isset($this->_single_item_obj) && is_object($this->_single_item_obj)) {
            $this->_single_item_obj->format_from_db();
        }
    }

    //---------------------------------------------------------
    // view sanitize
    //---------------------------------------------------------
    public function view_sanitize()
    {
        if (isset($this->_channel_obj) && is_object($this->_channel_obj)) {
            $this->_channel_obj->sanitize();
        }

        if (isset($this->_items_obj) && is_object($this->_items_obj)) {
            $this->_items_obj->sanitize($this->_get_sanitize_param());
        }

        if (isset($this->_image_obj) && is_object($this->_image_obj)) {
            $this->_image_obj->sanitize();
        }

        if (isset($this->_textinput_obj) && is_object($this->_textinput_obj)) {
            $this->_textinput_obj->sanitize();
        }
    }

    public function view_sanitize_items()
    {
        if (isset($this->_items_obj) && is_object($this->_items_obj)) {
            $this->_items_obj->sanitize($this->_get_sanitize_param());
        }
    }

    public function view_sanitize_single_item()
    {
        if (isset($this->_single_item_obj) && is_object($this->_single_item_obj)) {
            $this->_single_item_obj->sanitize($this->_get_sanitize_param());
        }
    }

    /**
     * @return array
     */
    public function &_get_sanitize_param()
    {
        $arr = [
            'flag_title_html'   => $this->_flag_title_html,
            'max_title'         => $this->_max_title,
            'flag_content_html' => $this->_flag_content_html,
            'max_content'       => $this->_max_content,
            'max_summary'       => $this->_max_summary,
            'keyword_array'     => $this->_keyword_array,
            'flag_highlight'    => $this->_flag_highlight,
            'is_japanese'       => $this->_is_japanese,

            'mode_content_script'     => $this->_mode_content_script,
            'mode_content_style'      => $this->_mode_content_style,
            'mode_content_link'       => $this->_mode_content_link,
            'mode_content_comment'    => $this->_mode_content_comment,
            'mode_content_cdata'      => $this->_mode_content_cdata,
            'mode_content_onmouse'    => $this->_mode_content_onmouse,
            'mode_content_attr_style' => $this->_mode_content_attr_style,
            'mode_content_javascript' => $this->_mode_content_javascript,
            'flag_content_tags'       => $this->_flag_content_tags,
            'content_tags'            => $this->_content_tags,
        ];

        return $arr;
    }

    //---------------------------------------------------------
    // set and get property
    //---------------------------------------------------------
    /**
     * @param $value
     */
    public function set_title_html($value)
    {
        $this->_flag_title_html = (bool)$value;
    }

    /**
     * @param $value
     */
    public function set_content_html($value)
    {
        $this->_flag_content_html = (bool)$value;
    }

    /**
     * @param $value
     */
    public function set_max_title($value)
    {
        $this->_max_title = (int)$value;
    }

    /**
     * @param $value
     */
    public function set_max_summary($value)
    {
        $this->_max_summary = (int)$value;
    }

    /**
     * @param $value
     */
    public function set_max_content($value)
    {
        $this->_max_content = (int)$value;
    }

    /**
     * @param $value
     */
    public function set_is_japanese($value)
    {
        $this->_is_japanese = (bool)$value;
    }

    /**
     * @param $value
     */
    public function set_flag_highlight($value)
    {
        $this->_flag_highlight = (bool)$value;
    }

    /**
     * @param $value
     */
    public function set_keyword_array($value)
    {
        if (is_array($value)) {
            $this->_keyword_array = $value;
        }
    }

    /**
     * @param $value
     */
    public function set_mode_content_script($value)
    {
        $this->_mode_content_script = (int)$value;
    }

    /**
     * @param $value
     */
    public function set_mode_content_style($value)
    {
        $this->_mode_content_style = (int)$value;
    }

    /**
     * @param $value
     */
    public function set_mode_content_link($value)
    {
        $this->_mode_content_link = (int)$value;
    }

    /**
     * @param $value
     */
    public function set_mode_content_comment($value)
    {
        $this->_mode_content_comment = (int)$value;
    }

    /**
     * @param $value
     */
    public function set_mode_content_cdata($value)
    {
        $this->_mode_content_cdata = (int)$value;
    }

    /**
     * @param $value
     */
    public function set_mode_content_onmouse($value)
    {
        $this->_mode_content_onmouse = (int)$value;
    }

    /**
     * @param $value
     */
    public function set_mode_content_attr_style($value)
    {
        $this->_mode_content_attr_style = (int)$value;
    }

    /**
     * @param $value
     */
    public function set_mode_content_javascript($value)
    {
        $this->_mode_content_javascript = (int)$value;
    }

    /**
     * @param $value
     */
    public function set_flag_content_tags($value)
    {
        $this->_flag_content_tags = (bool)$value;
    }

    /**
     * @param $value
     */
    public function set_content_tags($value)
    {
        $this->_content_tags = $value;
    }

    //---------------------------------------------------------
    // create
    // overload this function
    //---------------------------------------------------------
    /**
     * @return \XoopsModules\Happylinux\RssBaseBasic|\XoopsModules\Happylinux\RssViewChannel
     */
    public function &create_channel()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new RssViewChannel();

        return $obj;
    }

    /**
     * @return \XoopsModules\Happylinux\RssBaseBasic|\XoopsModules\Happylinux\RssViewImage
     */
    public function &create_image()
    {
        $obj = new RssViewImage();

        return $obj;
    }

    /**
     * @return \XoopsModules\Happylinux\RssBaseBasic|\XoopsModules\Happylinux\RssViewTextinput
     */
    public function &create_textinput()
    {
        $obj = new RssViewTextinput();

        return $obj;
    }

    /**
     * @return \XoopsModules\Happylinux\RssBaseBasic|\XoopsModules\Happylinux\RssViewItems
     */
    public function &create_items()
    {
        $obj = new RssViewItems();

        return $obj;
    }

    /**
     * @return \XoopsModules\Happylinux\RssBaseBasic|\XoopsModules\Happylinux\RssViewItemSingle
     */
    public function &create_single_item()
    {
        $obj = new RssViewItemSingle();

        return $obj;
    }

    // --- class end ---
}
