<?php
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
// happy_linux_rss_view_item_single()

// 2007-05-12 K.OHWADA
// this is new file
// porting form rssc_xml_object.php

//=========================================================
// Happy Linux Framework Module
// this file contains 7 classes
//   happy_linux_rss_view
//   happy_linux_rss_view_basic
//   happy_linux_rss_view_channel
//   happy_linux_rss_view_image
//   happy_linux_rss_view_textinput
//   happy_linux_rss_view_items
//   happy_linux_rss_view_item_single
// 2007-05-12 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_rss_view
//=========================================================
class happy_linux_rss_view extends happy_linux_rss_base
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

    public function &_get_sanitize_param()
    {
        $arr = array(
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
        );

        return $arr;
    }

    //---------------------------------------------------------
    // set and get property
    //---------------------------------------------------------
    public function set_title_html($value)
    {
        $this->_flag_title_html = (bool)$value;
    }

    public function set_content_html($value)
    {
        $this->_flag_content_html = (bool)$value;
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

    public function set_flag_highlight($value)
    {
        $this->_flag_highlight = (bool)$value;
    }

    public function set_keyword_array($value)
    {
        if (is_array($value)) {
            $this->_keyword_array = $value;
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

    //---------------------------------------------------------
    // create
    // overload this function
    //---------------------------------------------------------
    public function &create_channel()
    {
        // Assigning the return value of new by reference is deprecated
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

    public function &create_single_item()
    {
        $obj = new happy_linux_rss_view_item_single();
        return $obj;
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_rss_view_basic
//=========================================================
class happy_linux_rss_view_basic extends happy_linux_rss_base_basic
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

    public function _sanitize_allow_html($text, $flag, $max)
    {
        // allow HTML tag & under max content
        if ($flag) {
            if (($max < 0) || (strlen($text) <= $max)) {
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
    public function &_sanitize_block($arr)
    {
        $ret =& $this->_strings->sanitize_array_text($arr);
        return $ret;
    }

    public function _sanitize_html_url($text)
    {
        $ret = $this->_strings->sanitize_url($text);
        return $ret;
    }

    public function _sanitize_html_text($text)
    {
        $ret = $this->_strings->sanitize_text($text);
        return $ret;
    }

    public function _sanitize_summary($text, $max, $keyword_array = null)
    {
        $ret = $this->_strings->build_summary($text, $max, $keyword_array, 's');
        return $ret;
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_rss_view_channel
//=========================================================
class happy_linux_rss_view_channel extends happy_linux_rss_view_basic
{
    // RSS
    public $DATE_RFC822_LIST = array('pubdate', 'lastbuilddate');

    public $DATE_W3C_LIST = array(
        // ATOM 1.0
        'published',
        'updated',
        // ATOM 0.3
        'modified',
        'issued',
        'created'
    );

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
    public function format()
    {
        $this->set('title', $this->_substitute_title('title'));

        // RFC882
        foreach ($this->DATE_RFC822_LIST as $key) {
            if ($this->get_rss_var($key)) {
                $time_unix = $this->get_unixtime_rfc822($this->get_rss_var($key));
                $this->_set_unixtime($key, $time_unix);
            }
        }

        // W3C format
        foreach ($this->DATE_W3C_LIST as $key) {
            if ($this->get_rss_var($key)) {
                $time_unix = $this->get_unixtime_w3cdtf($this->get_rss_var($key));
                $this->_set_unixtime($key, $time_unix);
            }
        }

        // dc:date
        if ($this->is_set('dc', 'date')) {
            // BUG: preg_match() expects parameter 2 to be string, array given in w3cdtf.php
            $time_unix = $this->get_unixtime_w3cdtf($this->get_rss_var('dc', 'date'));
            $this->_set_unixtime($key, $time_unix);
        }
    }

    //---------------------------------------------------------
    // for rssc_headline xoopsheadline
    // $format_date: l=long, r=rfc822
    //---------------------------------------------------------
    public function format_for_rss($format_date = 'l')
    {
        $date_unix = 0;

        // ATOM 1.0
        if ($this->is_set('updated')) {
            $date_unix = $this->get_unixtime_w3cdtf($this->get_rss_var('updated'));
        } // ATOM 0.3
        elseif ($this->is_set('modified')) {
            $date_unix = $this->get_unixtime_w3cdtf($this->get_rss_var('modified'));
        } // DC
        elseif ($this->is_set('dc', 'date')) {
            $date_unix = $this->get_unixtime_w3cdtf($this->get_rss_var('dc', 'date'));
        }

        if ($date_unix) {
            $date_long   = formatTimestamp($date_unix, 'l');
            $date_rfc822 = date('r', $date_unix);
        }

        if ($this->is_set('pubdate')) {
            $pubdate_unix = $this->get_unixtime_rfc822($this->get_rss_var('pubdate'));
            $pubdate_long = formatTimestamp($pubdate_unix, 'l');

            if ($format_date == 'l') {
                $this->set('pubdate', $pubdate_long);
            }
        } elseif ($date_unix) {
            if ($format_date == 'l') {
                $this->set('pubdate', $date_long);
            } else {
                $this->set('pubdate', $date_rfc822);
            }
        }

        if ($this->is_set('lastbuilddate')) {
            $lastbuilddate_unix = $this->get_unixtime_rfc822($this->get_rss_var('lastbuilddate'));
            $lastbuilddate_long = formatTimestamp($lastbuilddate_unix, 'l');

            if ($format_date == 'l') {
                $this->set('lastbuilddate', $lastbuilddate_long);
            }
        } elseif ($date_unix) {
            if ($format_date == 'l') {
                $this->set('lastbuilddate', $date_long);
            } else {
                $this->set('lastbuilddate', $date_rfc822);
            }
        }

        if (!$this->is_set('webmaster')) {
            // ATOM
            if ($this->is_set('author_email')) {
                $this->set('webmaster', $this->get_rss_var('author_email'));
            } elseif ($this->is_set('author_name')) {
                $this->set('webmaster', $this->get_rss_var('author_name'));
            } // DC
            elseif ($this->is_set('dc', 'creator')) {
                $this->set('webmaster', $this->get_rss_var('dc', 'creator'));
            } elseif ($this->is_set('dc', 'publisher')) {
                $this->set('webmaster', $this->get_rss_var('dc', 'publisher'));
            }
        }

        if (!$this->is_set('copyright')) {
            // ATOM
            if ($this->is_set('rights')) {
                $this->set('copyright', $this->get_rss_var('rights'));
            } // DC
            elseif ($this->is_set('dc', 'rights')) {
                $this->set('copyright', $this->get_rss_var('dc', 'rights'));
            }
        }

        if (!$this->is_set('category')) {
            // DC
            if ($this->is_set('dc', 'subject')) {
                $this->set('category', $this->get_rss_var('dc', 'subject'));
            }
        }

        if (!$this->is_set('language')) {
            // DC
            if ($this->is_set('dc', 'language')) {
                $this->set('language', $this->get_rss_var('dc', 'language'));
            }
        }
    }

    //---------------------------------------------------------
    // view sanitize
    //---------------------------------------------------------
    public function sanitize()
    {
        $arr = array();

        foreach ($this->get_vars() as $k => $v) {
            switch ($k) {
                case 'link':
                case 'link_self':
                case 'author_uri':
                case 'author_url':
                case 'contributor_uri':
                case 'contributor_url':
                    $val = $this->_sanitize_html_url($v);
                    break;

                default:
                    $val = $this->_sanitize_block($v);
                    break;
            }

            $arr[$k] = $val;
        }

        $this->set_vars($arr);
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_rss_view_image
//=========================================================
class happy_linux_rss_view_image extends happy_linux_rss_view_basic
{

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // sanitize
    //---------------------------------------------------------
    public function sanitize()
    {
        $arr = array();

        foreach ($this->get_vars() as $k => $v) {
            switch ($k) {
                case 'link':
                case 'url':
                    $val = $this->_sanitize_html_url($v);
                    break;

                case 'width':
                case 'height':
                    $val = (int)$v;
                    break;

                default:
                    $val = $this->_sanitize_html_text($v);
                    break;
            }

            $arr[$k] = $val;
        }

        if (count($arr) > 0) {
            $arr['show'] = 1;
        }

        $this->set_vars($arr);
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_rss_view_textinput
//=========================================================
class happy_linux_rss_view_textinput extends happy_linux_rss_view_basic
{

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // sanitize
    //---------------------------------------------------------
    public function sanitize()
    {
        $arr = array();

        foreach ($this->get_vars() as $k => $v) {
            switch ($k) {
                case 'link':
                    $val = $this->_sanitize_html_url($v);
                    break;

                default:
                    $val = $this->_sanitize_html_text($v);
                    break;
            }

            $arr[$k] = $val;
        }

        if (count($arr) > 0) {
            $arr['show'] = 1;
        }

        $this->set_vars($arr);
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_rss_view_items
//=========================================================
class happy_linux_rss_view_items extends happy_linux_rss_base_items
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
    public function format_for_rss($format_date = 'l')
    {
        foreach ($this->_item_objs as $i => $obj) {
            $this->_item_objs[$i]->format_for_rss($format_date);
        }
    }

    //---------------------------------------------------------
    // sanitize
    //---------------------------------------------------------
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
    public function &create_item_single()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new happy_linux_rss_view_item_single();
        return $obj;
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_rss_view_item_single
//=========================================================
class happy_linux_rss_view_item_single extends happy_linux_rss_view_basic
{
    // RSS
    public $DATE_RFC822_LIST = array('pubdate');

    public $DATE_W3C_LIST = array(
        // ATOM 1.0
        'published',
        'updated',
        // ATOM 0.3
        'modified',
        'issued',
        'created'
    );

    public $_view_item = null;

    // nonstandard fulltext tag
    public $_use_fulltext = true;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $this->_view_item = happy_linux_rss_view_item::getInstance();
    }

    //-------------------------------------------------
    // non restrict feed_type
    // caller build, format_from_parse, format_from_db
    //-------------------------------------------------
    public function _build_content_for_format()
    {
        $val = false;

        // ATOM content
        if ($this->is_set('atom_content') && $this->get_rss_var('atom_content')) {
            $val = $this->get_rss_var('atom_content');
        } // RSS, RDF content
        elseif ($this->is_set('content', 'encoded')) {
            $val = $this->get_rss_var('content', 'encoded');
        } // ATOM content
        elseif ($this->is_set('content') && $this->get_rss_var('content')) {
            $val = $this->get_rss_var('content');
        } // RSS, RDF fulltext
        elseif ($this->_use_fulltext && $this->is_set('fulltext')) {
            $val = $this->get_rss_var('fulltext');
        } // RSS, RDF description
        elseif ($this->is_set('description')) {
            $val = $this->get_rss_var('description');
        } elseif ($this->is_set('dc', 'description')) {
            $val = $this->get_rss_var('dc', 'description');
        } // ATOM summary
        elseif ($this->is_set('summary')) {
            $val = $this->get_rss_var('summary');
        }

        return $val;
    }

    public function _build_id_for_format()
    {
        $val = '';

        if ($this->is_set('id')) {
            $val = $this->get_rss_var('id');
        } elseif ($this->is_set('entry_id')) {
            $val = $this->get_rss_var('entry_id');
        }

        return $val;
    }

    //---------------------------------------------------------
    // view format
    //---------------------------------------------------------
    public function format_from_parse()
    {
        $this->set('site_title', $this->_substitute_title('site_title'));
        $this->set('title', $this->_substitute_title('title'));
        $this->set('content', $this->_build_content_for_format());
        $this->set('summary', $this->_build_summary_for_format());
        $this->set('id', $this->_build_id_for_format());
        $this->_format_enclosure();

        // RFC882
        foreach ($this->DATE_RFC822_LIST as $key) {
            if ($this->get_rss_var($key)) {
                $time_unix = $this->get_unixtime_rfc822($this->get_rss_var($key));
                $this->_set_unixtime($key, $time_unix);
            }
        }

        // W3C format
        foreach ($this->DATE_W3C_LIST as $key) {
            if ($this->get_rss_var($key)) {
                $time_unix = $this->get_unixtime_w3cdtf($this->get_rss_var($key));
                $this->_set_unixtime($key, $time_unix);
            }
        }

        // dc:date
        if ($this->is_set('dc', 'date')) {
            $time_unix = $this->get_unixtime_w3cdtf($this->get_rss_var('dc', 'date'));
            $this->_set_unixtime($key, $time_unix);
        }

        // updated_long
        if (!$this->is_set('updated_long') && $this->is_set('updated_unix')) {
            $time_unix = $this->get_rss_var('updated_unix');
            $this->_set_unixtime('updated', $time_unix);
        }
    }

    public function format_from_db()
    {
        $this->set('site_title', $this->_substitute_title('site_title'));
        $this->set('title', $this->_substitute_title('title'));
        $this->set('site_link', $this->_substitute_link('site_link'));
        $this->set('link', $this->_substitute_link('link'));
        $this->set('content', $this->_build_content_for_format());
        $this->set('summary', $this->_build_summary_for_format());
        $this->_format_guid_url();
        $this->_set_unixtime('updated', $this->get_rss_var('updated_unix'));
        $this->_set_unixtime('published', $this->get_rss_var('published_unix'));
    }

    // some feed have non URL formated guid
    // http://news.google.com/news?ned=us&output=rss
    // tag:news.google.com,2005:cluster=421c2ca3
    public function _format_guid_url()
    {
        $val = $this->get_rss_var('guid');
        $val = $this->_strings->allow_http($val);
        $val = $this->_strings->deny_http_only($val);
        $this->set('guid_url', $val);
    }

    public function _format_enclosure()
    {
        list($enc_url, $enc_type, $enc_length) = $this->_get_enclosure_list();
        $this->set('enclosure_url', $enc_url);
        $this->set('enclosure_type', $enc_type);
        $this->set('enclosure_length', $enc_length);
    }

    //---------------------------------------------------------
    // for rssc_headline xoopsheadline
    // $format_date: l=long, r=rfc822
    //---------------------------------------------------------
    public function format_for_rss($format_date = 'l')
    {
        if ($this->is_set('pubdate')) {
            if ($format_date == 'l') {
                $pubdate_unix = $this->get_unixtime_rfc822($this->get_rss_var('pubdate'));
                $pubdate_long = formatTimestamp($pubdate, 'l');
                $this->set('pubdate', $pubdate_long);
            }
        } elseif ($this->is_set('published_unix')) {
            $published_unix = $this->get_rss_var('published_unix');

            if ($format_date == 'l') {
                $published_long = formatTimestamp($published_unix, 'l');
                $this->set('pubdate', $published_long);
            } else {
                $published_rfc822 = date('r', $published_unix);
                $this->set('pubdate', $published_rfc822);
            }
        }

        if (!$this->is_set('description') && $this->is_set('content')) {
            $this->set('description', $this->get_rss_var('content'));
        }
    }

    //---------------------------------------------------------
    // view sanitize
    //---------------------------------------------------------
    public function sanitize(&$param)
    {
        $this->_view_item->set_param($param);

        $this->set_is_japanese($param['is_japanese']);

        $arr = array();

        foreach ($this->get_vars() as $k => $v) {
            switch ($k) {
                case 'link':
                case 'author_uri':
                case 'author_url':
                case 'contributor_uri':
                case 'contributor_url':
                case 'enclosure_url':
                case 'guid_url':

                    // BUG: not sanitize site_url
                case 'site_url':    // for RSSC

                    $val = $this->_sanitize_html_url($v);
                    break;

                case 'title':
                    $val = $this->_view_item->sanitize_title($v);
                    break;

                case 'content':
                    $v1 = $v;
                    if (is_array($v) && isset($v['encoded'])) {
                        $v1 = $v['encoded'];
                    }
                    $val = $this->_view_item->sanitize_content($v1);
                    break;

                case 'summary':
                    $val = $this->_view_item->sanitize_summary($v);
                    break;

                case 'raws':
                case 'item_orig':
                    $val = '';
                    break;

                default:
                    //          echo "$k |$v| <br>\n";
                    $val = $this->_sanitize_block($v);
                    break;
            }

            $arr[$k] = $val;
        }

        $this->set_vars($arr);
    }

    // --- class end ---
}
