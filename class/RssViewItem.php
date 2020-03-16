<?php

namespace XoopsModules\Happylinux;

// $Id: RssViewItem.php,v 1.1 2008/01/30 08:33:13 ohwada Exp $

// 2008-01-20 K.OHWADA
// divid from rss_view_object.php

//=========================================================
// Happy Linux Framework Module
// 2007-05-12 K.OHWADA
//=========================================================

//=========================================================
// class RssViewItem
//=========================================================

/**
 * Class RssViewItem
 * @package XoopsModules\Happylinux
 */
class RssViewItem
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
        $this->_strings   = Strings::getInstance();
        $this->_highlight = Highlight::getInstance();

        $this->_highlight->set_replace_callback('happylinux_highlighter_by_class');
        $this->_highlight->set_class('rssc_highlight');
    }

    /**
     * @return static
     */
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
    /**
     * @param $param
     */
    public function set_param(&$param)
    {
        $this->_params = &$param;

        $is_japanese = $this->_get_param_by_key('is_japanese');
        $this->_strings->set_is_japanese($is_japanese);
    }

    /**
     * @param $orig
     * @return string|string[]|null
     */
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

        $str = happylinux_str_add_space_after_tag($str);

        if (2 == $script) {
            $str = happylinux_html_remove_script($str);
            $str = happylinux_html_replace_script($str);
        } elseif (1 == $script) {
            $str = happylinux_html_replace_script($str);
        }

        if (2 == $style) {
            $str = happylinux_html_remove_style($str);
            $str = happylinux_html_replace_style($str);
        } elseif (1 == $style) {
            $str = happylinux_html_replace_style($str);
        }

        if (2 == $link) {
            $str = happylinux_html_remove_link($str);
            $str = happylinux_html_replace_link($str);
        } elseif (1 == $link) {
            $str = happylinux_html_replace_link($str);
        }

        if (2 == $comment) {
            $str = happylinux_html_remove_comment($str);
            $str = happylinux_html_replace_comment($str);
        } elseif (1 == $comment) {
            $str = happylinux_html_replace_comment($str);
        }

        if (2 == $cdata) {
            $str = happylinux_html_remove_cdata($str);
            $str = happylinux_html_replace_cdata($str);
        } elseif (1 == $cdata) {
            $str = happylinux_html_replace_cdata($str);
        }

        if ($flag_tags) {
            if ($tags) {
                $str = strip_tags($str, $tags);
            } else {
                $str = strip_tags($str);
            }
        }

        if (2 == $onmouse) {
            $str = happylinux_html_remove_onmouse($str);
            $str = happylinux_html_replace_onmouse($str);
        } elseif (1 == $onmouse) {
            $str = happylinux_html_replace_onmouse($str);
        }

        if (2 == $attr_style) {
            $str = happylinux_html_remove_attr_style($str);
            $str = happylinux_html_remove_attr_class($str);
            $str = happylinux_html_remove_attr_id($str);
            $str = happylinux_html_replace_attr_style($str);
            $str = happylinux_html_replace_attr_class($str);
            $str = happylinux_html_replace_attr_id($str);
        } elseif (1 == $attr_style) {
            $str = happylinux_html_replace_attr_style($str);
            $str = happylinux_html_replace_attr_class($str);
            $str = happylinux_html_replace_attr_id($str);
        }

        if (2 == $javascript) {
            $str = happylinux_html_remove_javascript_colon($str);
            $str = happylinux_html_remove_javascript($str);
            $str = happylinux_html_remove_vbscript_colon($str);
            $str = happylinux_html_remove_about_colon($str);
            $str = happylinux_html_replace_javascript_colon($str);
            $str = happylinux_html_replace_javascript($str);
            $str = happylinux_html_replace_vbscript_colon($str);
            $str = happylinux_html_replace_about_colon($str);
        } elseif (1 == $javascript) {
            $str = happylinux_html_replace_javascript_colon($str);
            $str = happylinux_html_replace_javascript($str);
            $str = happylinux_html_replace_vbscript_colon($str);
            $str = happylinux_html_replace_about_colon($str);
        }

        if (!$this->_check_html_allow($str, $html, $max)) {
            $str = $this->_strings->build_summary($orig, $max, null, 's');
        }

        if ($highlight) {
            $str = $this->_highlight->build_highlight_keyword_array($str, $keywords);
        }

        return $str;
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
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

    /**
     * @param $str
     * @return string|string[]|null
     */
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
    /**
     * @param $str
     * @param $html
     * @param $max
     * @return bool
     */
    public function _check_html_allow($str, $html, $max)
    {
        if ($html && (($max < 0) || (mb_strlen($str) <= $max))) {
            return true;
        }

        return false;
    }

    /**
     * @param     $key
     * @param int $default
     * @return int|mixed
     */
    public function _get_param_by_key($key, $default = 0)
    {
        $val = isset($this->_params[$key]) ? $this->_params[$key] : $default;

        return $val;
    }

    // --- class end ---
}
