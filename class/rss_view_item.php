<?php
// $Id: rss_view_item.php,v 1.1 2008/01/30 08:33:13 ohwada Exp $

// 2008-01-20 K.OHWADA
// divid from rss_view_object.php

//=========================================================
// Happy Linux Framework Module
// 2007-05-12 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_rss_view_item
//=========================================================
class happy_linux_rss_view_item
{
    public $_strings;
    public $_highlight;

    public $_params = [];

    // default
    public $_MAX_SUMMARY  = 250;
    public $_MAX_TITLE    = -1;  // unlimited
    public $_MAX_CONTENT  = -1;  // unlimited
    public $_KEYWORDS     = null;
    public $_TITLE_HTML   = false;
    public $_CONTENT_HTML = true;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_strings   = happy_linux_strings::getInstance();
        $this->_highlight = happy_linux_highlight::getInstance();

        $this->_highlight->set_replace_callback('happy_linux_highlighter_by_class');
        $this->_highlight->set_class('rssc_highlight');
    }

    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // public
    //---------------------------------------------------------
    public function set_param(&$param)
    {
        $this->_params = &$param;

        $is_japanese = $this->_get_param_by_key('is_japanese');
        $this->_strings->set_is_japanese($is_japanese);
    }

    public function sanitize_content($orig)
    {
        $script     = $this->_get_param_by_key('mode_content_script');
        $style      = $this->_get_param_by_key('mode_content_style');
        $link       = $this->_get_param_by_key('mode_content_link');
        $comment    = $this->_get_param_by_key('mode_content_comment');
        $cdata      = $this->_get_param_by_key('mode_content_cdata');
        $onmouse    = $this->_get_param_by_key('mode_content_onmouse');
        $attr_style = $this->_get_param_by_key('mode_content_attr_style');
        $javascript = $this->_get_param_by_key('mode_content_javascript');
        $flag_tags  = $this->_get_param_by_key('flag_content_tags');
        $tags       = $this->_get_param_by_key('content_tags');
        $highlight  = $this->_get_param_by_key('flag_highlight');
        $html       = $this->_get_param_by_key('flag_content_html', $this->_CONTENT_HTML);
        $max        = $this->_get_param_by_key('max_content', $this->_MAX_CONTENT);
        $keywords   = $this->_get_param_by_key('keyword_array', $this->_KEYWORDS);

        $str = $orig;

        $str = happy_linux_str_add_space_after_tag($str);

        if (2 == $script) {
            $str = happy_linux_html_remove_script($str);
            $str = happy_linux_html_replace_script($str);
        } elseif (1 == $script) {
            $str = happy_linux_html_replace_script($str);
        }

        if (2 == $style) {
            $str = happy_linux_html_remove_style($str);
            $str = happy_linux_html_replace_style($str);
        } elseif (1 == $style) {
            $str = happy_linux_html_replace_style($str);
        }

        if (2 == $link) {
            $str = happy_linux_html_remove_link($str);
            $str = happy_linux_html_replace_link($str);
        } elseif (1 == $link) {
            $str = happy_linux_html_replace_link($str);
        }

        if (2 == $comment) {
            $str = happy_linux_html_remove_comment($str);
            $str = happy_linux_html_replace_comment($str);
        } elseif (1 == $comment) {
            $str = happy_linux_html_replace_comment($str);
        }

        if (2 == $cdata) {
            $str = happy_linux_html_remove_cdata($str);
            $str = happy_linux_html_replace_cdata($str);
        } elseif (1 == $cdata) {
            $str = happy_linux_html_replace_cdata($str);
        }

        if ($flag_tags) {
            if ($tags) {
                $str = strip_tags($str, $tags);
            } else {
                $str = strip_tags($str);
            }
        }

        if (2 == $onmouse) {
            $str = happy_linux_html_remove_onmouse($str);
            $str = happy_linux_html_replace_onmouse($str);
        } elseif (1 == $onmouse) {
            $str = happy_linux_html_replace_onmouse($str);
        }

        if (2 == $attr_style) {
            $str = happy_linux_html_remove_attr_style($str);
            $str = happy_linux_html_remove_attr_class($str);
            $str = happy_linux_html_remove_attr_id($str);
            $str = happy_linux_html_replace_attr_style($str);
            $str = happy_linux_html_replace_attr_class($str);
            $str = happy_linux_html_replace_attr_id($str);
        } elseif (1 == $attr_style) {
            $str = happy_linux_html_replace_attr_style($str);
            $str = happy_linux_html_replace_attr_class($str);
            $str = happy_linux_html_replace_attr_id($str);
        }

        if (2 == $javascript) {
            $str = happy_linux_html_remove_javascript_colon($str);
            $str = happy_linux_html_remove_javascript($str);
            $str = happy_linux_html_remove_vbscript_colon($str);
            $str = happy_linux_html_remove_about_colon($str);
            $str = happy_linux_html_replace_javascript_colon($str);
            $str = happy_linux_html_replace_javascript($str);
            $str = happy_linux_html_replace_vbscript_colon($str);
            $str = happy_linux_html_replace_about_colon($str);
        } elseif (1 == $javascript) {
            $str = happy_linux_html_replace_javascript_colon($str);
            $str = happy_linux_html_replace_javascript($str);
            $str = happy_linux_html_replace_vbscript_colon($str);
            $str = happy_linux_html_replace_about_colon($str);
        }

        if (!$this->_check_html_allow($str, $html, $max)) {
            $str = $this->_strings->build_summary($orig, $max, null, 's');
        }

        if ($highlight) {
            $str = $this->_highlight->build_highlight_keyword_array($str, $keywords);
        }

        return $str;
    }

    public function sanitize_summary($str)
    {
        $max       = $this->_get_param_by_key('max_summary', $this->_MAX_SUMMARY);
        $keywords  = $this->_get_param_by_key('keyword_array', $this->_KEYWORDS);
        $highlight = $this->_get_param_by_key('flag_highlight');

        $str = $this->_strings->build_summary($str, $max, $keywords, 's');

        if ($highlight) {
            $str = $this->_highlight->build_highlight_keyword_array($str, $keywords);
        }

        return $str;
    }

    public function sanitize_title($str)
    {
        $html = $this->_get_param_by_key('flag_title_html', $this->_TITLE_HTML);
        $max  = $this->_get_param_by_key('max_title', $this->_MAX_TITLE);

        if (!$this->_check_html_allow($str, $html, $max)) {
            $str = $this->_strings->build_summary($str, $max, null, 's');
        }

        return $str;
    }

    //---------------------------------------------------------
    // private
    //---------------------------------------------------------
    public function _check_html_allow($str, $html, $max)
    {
        if ($html && (($max < 0) || (mb_strlen($str) <= $max))) {
            return true;
        }

        return false;
    }

    public function _get_param_by_key($key, $default = 0)
    {
        $val = isset($this->_params[$key]) ? $this->_params[$key] : $default;

        return $val;
    }

    // --- class end ---
}
