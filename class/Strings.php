<?php

namespace XoopsModules\Happylinux;

// $Id: strings.php,v 1.31 2008/02/26 15:30:05 ohwada Exp $

// 2008-02-17 K.OHWADA
// add_str_to_tail()

// 2008-01-20 K.OHWADA
// htmlout.php

// 2008-01-10 K.OHWADA
// is_module_admin_error_reporting_debug_print_backtrace()

// 2007-12-22 K.OHWADA
// BUG: not show  smile icon

// 2007-11-24 K.OHWADA
// analyze_script_type()

// 2007-10-10 K.OHWADA
// add_space_after_punctuation()
// set_empty_if_only_space()

// 2007-09-01 K.OHWADA
// comment for RFC2822

// 2007-08-01 K.OHWADA
// happylinux/include/sanitize.php
// bool_to_str()
// strip_control_array()

// 2007-07-14 K.OHWADA
// sanitize_var_export()
// check_email_format()

// 2007-05-12 K.OHWADA
// replace_html_amp_space_code_to_space()

// 2007-03-01 K.OHWADA
// add parse_tail_figure()

// 2006-12-10 K.OHWADA
// add split_time_ymd()
// add build_unique_array_without()
// add strip_slashes_array_gpc()

// 2006-11-18 K.OHWADA
// BUG 4378: dont strip slashes in conf_value when magic_quotes_gpc is on
// add init_strings() prepare_array()
// for happy_search
// add implode_array()

// 2006-11-08 K.OHWADA
// add html_specialchars() undo_html_specialchars()

// 2006-10-14 K.OHWADA
// add utf8_urlencode_from_array()

// 2006-10-01 K.OHWADA
// add replace_return_to_space() convert_str_to_crlf()
// add prepare_text()
// add $_flag_remove_control_code
// change build_summary() shorten_text()

// 2006-07-10 K.OHWADA
// this is new file
// porting from rssc_strings.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
// —L•üŽ©‰“•û—ˆ
//=========================================================

require_once XOOPS_ROOT_PATH . '/modules/happylinux/include/sanitize.php';
require_once XOOPS_ROOT_PATH . '/modules/happylinux/include/multibyte.php';
require_once XOOPS_ROOT_PATH . '/modules/happylinux/include/htmlout.php';

//=========================================================
// class strings
//=========================================================

/**
 * Class Strings
 * @package XoopsModules\Happylinux
 */
class Strings
{
    public $_MAX_DEPTH = 10;

    // local variable
    public $_max_summary  = 100;
    public $_time_start   = 0;
    public $_time_current = 0;
    public $_is_japanese  = false;
    public $_source_encoding;
    // same language match contorl code
    // ex) BIG-5 GB2312 Ž` C05C B2CD —V B943 904A
    public $_flag_remove_control_code = false;

    // allow to remove control code
    public $_ENCODING_ARRAY = ['iso-8859-1', 'utf-8', 'euc-jp'];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->init_strings();
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
    // init
    //---------------------------------------------------------
    public function init_strings()
    {
        if ($this->check_in_encoding_array(_CHARSET)) {
            $this->set_flag_remove_control_code(true);
        }
    }

    /**
     * @param $encoding
     * @return bool
     */
    public function check_in_encoding_array($encoding)
    {
        if (in_array(mb_strtolower($encoding), $this->_ENCODING_ARRAY)) {
            return true;
        }

        return false;
    }

    //=========================================================
    // for object->setVar()
    //=========================================================
    /**
     * @param      $str
     * @param bool $not_gpc
     * @return string|string[]|null
     */
    public function prepare_text($str, $not_gpc = false)
    {
        if ($this->_flag_remove_control_code) {
            $str = $this->strip_control($str);
            $str = $this->strip_tab($str);
            $str = $this->strip_return($str);
        }
        $str = $this->strip_slashes_gpc_flag($str, $not_gpc);
        $str = $this->replace_javascript($str);

        return $str;
    }

    /**
     * @param      $str
     * @param bool $not_gpc
     * @return string|string[]|null
     */
    public function prepare_textarea($str, $not_gpc = false)
    {
        if ($this->_flag_remove_control_code) {
            $str = $this->strip_control($str);
        }
        $str = $this->strip_slashes_gpc_flag($str, $not_gpc);
        $str = $this->replace_javascript($str);

        return $str;
    }

    /**
     * @param      $str
     * @param bool $not_gpc
     * @return string
     */
    public function prepare_url($str, $not_gpc = false)
    {
        if ($this->_flag_remove_control_code) {
            $str = $this->strip_control($str);
            $str = $this->strip_tab($str);
            $str = $this->strip_return($str);
        }
        $str = $this->strip_slashes_gpc_flag($str, $not_gpc);
        $str = $this->deny_javascript($str);
        $str = $this->deny_http_only($str);
        $str = $this->allow_http($str);

        return $str;
    }

    /**
     * @param      $arr
     * @param bool $not_gpc
     * @return array|bool|string|string[]|null
     */
    public function &prepare_array($arr, $not_gpc = false)
    {
        $str = $this->_prepare_array_recursive(0, $arr, $not_gpc);

        return $str;
    }

    /**
     * @param      $num
     * @param      $arr_in
     * @param bool $not_gpc
     * @return array|bool|string|string[]|null
     */
    public function _prepare_array_recursive($num, $arr_in, $not_gpc = false)
    {
        ++$num;
        if ($num > $this->_MAX_DEPTH) {
            return false;
        }

        if (is_array($arr_in)) {
            $arr_out = [];
            reset($arr_in);

            foreach ($arr_in as $k => $v) {
                if (is_array($v)) {
                    $arr_out[$k] = $this->_prepare_array_recursive($num, $v, $not_gpc);
                } else {
                    $arr_out[$k] = $this->prepare_text($v, $not_gpc);
                }
            }

            return $arr_out;
        }

        $ret = $this->prepare_text($arr_in, $not_gpc);

        return $ret;
    }

    //=========================================================
    // sanitize strings
    //=========================================================
    // --------------------------------------------------------
    // Convert special characters to HTML entities
    //   &  => &amp;
    //   <  => &lt;
    //   >  => &gt;
    //   "  => &quot;
    //   '  => &#039;
    //
    // $flag_amp
    //   true:  normal: & => &amp;
    //   false: undo htmlentities
    //          ex ) &auml; => &amp;auml; => &auml;
    // --------------------------------------------------------
    /**
     * @param      $str
     * @param bool $flag_amp
     * @return array|string|string[]|null
     */
    public function html_specialchars($str, $flag_amp = true)
    {
        if (is_array($str)) {
            //      print_r($str);
            return $str;
        }

        $str = htmlspecialchars($str, ENT_QUOTES);

        if (!$flag_amp) {
            $str = happylinux_undo_html_entity_name($str);
            $str = happylinux_undo_html_entity_numeric($str);
        }

        return $str;
    }

    // --------------------------------------------------------
    // Invert special characters from HTML entities
    //   &amp;   =>  &
    //   &lt;    =>  <
    //   &gt;    =>  >
    //   &quot;  =>  "
    //   &#39;   =>  '
    //   &#039;  =>  '
    //   &apos;  =>  ' (xml format)
    // --------------------------------------------------------
    /**
     * @param $str
     * @return string
     */
    public function undo_html_specialchars($str)
    {
        return happylinux_undo_htmlspecialchars($str);
    }

    // --------------------------------------------------------
    // sanitize and convert htmlspecialchars for text
    // --------------------------------------------------------
    /**
     * @param      $str
     * @param bool $flag_java
     * @param bool $flag_amp
     * @return string|string[]|null
     */
    public function sanitize_text($str, $flag_java = true, $flag_amp = false)
    {
        if ($this->_flag_remove_control_code) {
            $str = $this->strip_control_all($str);
        }
        if ($flag_java) {
            $str = $this->replace_javascript($str);
        }
        $str = htmlspecialchars($str, ENT_QUOTES);
        if (!$flag_amp) {
            $str = happylinux_undo_html_entity_name($str);
            $str = happylinux_undo_html_entity_numeric($str);
        }

        return $str;
    }

    // --------------------------------------------------------
    // sanitize and convert htmlspecialchars for textarea
    // --------------------------------------------------------
    /**
     * @param      $str
     * @param bool $flag_java
     * @param bool $flag_amp
     * @return string|string[]|null
     */
    public function sanitize_textarea($str, $flag_java = true, $flag_amp = false)
    {
        if ($this->_flag_remove_control_code) {
            $str = $this->strip_control($str);
        }
        if ($flag_java) {
            $str = $this->replace_javascript($str);
        }
        $str = htmlspecialchars($str, ENT_QUOTES);
        if (!$flag_amp) {
            $str = happylinux_undo_html_entity_name($str);
            $str = happylinux_undo_html_entity_numeric($str);
        }

        return $str;
    }

    //---------------------------------------------------------
    // sanitize and convert htmlspecialchars for url
    //---------------------------------------------------------
    /**
     * @param      $str
     * @param bool $flag_java
     * @param bool $flag_deny
     * @param bool $flag_undo
     * @return string
     */
    public function sanitize_url($str, $flag_java = true, $flag_deny = true, $flag_undo = true)
    {
        if ($this->_flag_remove_control_code) {
            $str = $this->strip_control_all($str);
        }
        if ($flag_java) {
            $str = $this->replace_javascript($str);
        }
        if ($flag_deny) {
            $str = $this->deny_http_only($str);
        }
        if ($flag_undo) {
            $str = happylinux_undo_htmlspecialchars($str);
        }
        $str = htmlspecialchars($str, ENT_QUOTES);

        return $str;
    }

    //---------------------------------------------------------
    // strip control code
    //---------------------------------------------------------
    /**
     * @param $str
     * @return string|string[]|null
     */
    public function strip_control_all($str)
    {
        $str = $this->strip_control($str);
        $str = $this->strip_tab($str);
        $str = $this->strip_return($str);

        return $str;
    }

    //---------------------------------------------------------
    // strip control code except TAB, LF, CR
    // TAB \x09 \t
    // LF  \xOA \n
    // CR  \xOD \r
    //---------------------------------------------------------
    /**
     * @param $str
     * @return string|string[]|null
     */
    public function strip_control($str)
    {
        return $this->replace_control($str, '');
    }

    /**
     * @param $str
     * @param $replace
     * @return string|string[]|null
     */
    public function replace_control($str, $replace)
    {
        return happylinux_str_replace_control_code($str, $replace);
    }

    // strip TAB code

    /**
     * @param $str
     * @return string|string[]|null
     */
    public function strip_tab($str)
    {
        return happylinux_str_replace_tab_code($str, '');
    }

    // strip LF, CR code

    /**
     * @param $str
     * @return string|string[]|null
     */
    public function strip_return($str)
    {
        return happylinux_str_replace_return_code($str, '');
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public function replace_return_to_space($str)
    {
        return happylinux_str_replace_return_code($str, ' ');
    }

    /**
     * @param $arr_in
     * @return array|bool|string|string[]|null
     */
    public function &strip_control_array($arr_in)
    {
        $ret = $this->_replace_control_array_recursive(0, $arr_in, '');

        return $ret;
    }

    /**
     * @param $arr_in
     * @param $replace
     * @return array|bool|string|string[]|null
     */
    public function &replace_control_array($arr_in, $replace)
    {
        $ret = $this->_replace_control_array_recursive(0, $arr_in, $replace);

        return $ret;
    }

    /**
     * @param $num
     * @param $arr_in
     * @param $replace
     * @return array|bool|string|string[]|null
     */
    public function _replace_control_array_recursive($num, $arr_in, $replace)
    {
        ++$num;
        if ($num > $this->_MAX_DEPTH) {
            return false;
        }

        if (is_array($arr_in)) {
            $arr_out = [];
            reset($arr_in);

            foreach ($arr_in as $k => $v) {
                if (is_array($v)) {
                    $arr_out[$k] = $this->_replace_control_array_recursive($num, $v, $replace);
                } else {
                    $arr_out[$k] = $this->replace_control($v, $replace);
                }
            }

            return $arr_out;
        }

        $ret = $this->replace_control($arr_in, $replace);

        return $ret;
    }

    //---------------------------------------------------------
    // strip space code
    // SPACE \x20
    //---------------------------------------------------------
    /**
     * @param $str
     * @return string|string[]|null
     */
    public function strip_space_code($str)
    {
        return happylinux_str_replace_space_code($str, '');
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public function strip_html_space_code($str)
    {
        return happylinux_str_replace_html_space_code($str, '');
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public function replace_html_amp_space_code_to_space($str)
    {
        return preg_replace('/&amp;nbsp;/i', ' ', $str);
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public function replace_space_code_to_single_space($str)
    {
        return happylinux_str_replace_html_space_code($str, ' ');
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public function replace_html_space_code_to_space($str)
    {
        return happylinux_str_replace_continuous_space_code($str, ' ');
    }

    //--------------------------------------------------------
    // javascript
    //--------------------------------------------------------
    /**
     * @param $str
     * @return string|string[]|null
     */
    public function replace_javascript($str)
    {
        $str = happylinux_html_replace_javascript($str);
        $str = happylinux_html_replace_javascript_colon($str);
        $str = happylinux_html_replace_vbscript_colon($str);
        $str = happylinux_html_replace_about_colon($str);

        return $str;
    }

    // deny_javascript

    /**
     * @param $str
     * @return string
     */
    public function deny_javascript($str)
    {
        if ($this->check_javascript($str)) {
            return '';
        }

        return $str;
    }

    // Checks if Javascript are included in string

    /**
     * @param $str
     * @return bool
     */
    public function check_javascript($str)
    {
        $str = $this->strip_control_all($str);

        if (happylinux_html_check_javascript($str)) {
            return true;    // include JavaScript
        }
        if (happylinux_html_check_javascript_colon($str)) {
            return true;    // include JavaScript
        }
        if (happylinux_html_check_vbscript_colon($str)) {
            return true;    // include vbscript
        }
        if (happylinux_html_check_about_colon($str)) {
            return true;    // include about
        }

        return false;
    }

    //--------------------------------------------------------
    // sanitize array
    //--------------------------------------------------------
    /**
     * @param $arr_in
     * @return array|bool|string|string[]|null
     */
    public function &sanitize_array_text($arr_in)
    {
        $ret = $this->_sanitize_array_text_recursive(0, $arr_in);

        return $ret;
    }

    /**
     * @param $num
     * @param $arr_in
     * @return array|bool|string|string[]|null
     */
    public function _sanitize_array_text_recursive($num, $arr_in)
    {
        ++$num;
        if ($num > $this->_MAX_DEPTH) {
            return false;
        }

        if (is_array($arr_in)) {
            $arr_out = [];
            reset($arr_in);

            foreach ($arr_in as $k => $v) {
                if (is_array($v)) {
                    $arr_out[$k] = $this->_sanitize_array_text_recursive($num, $v);
                } else {
                    $arr_out[$k] = $this->sanitize_text($v);
                }
            }

            return $arr_out;
        }

        $ret = $this->sanitize_text($arr_in);

        return $ret;
    }

    //--------------------------------------------------------
    // sanitize for XOOPS object
    //--------------------------------------------------------
    /**
     * @param        $value
     * @param string $format
     * @return string|string[]|null
     */
    public function sanitize_format($value, $format = 's')
    {
        $ret = $this->sanitize_format_text($value, $format);

        return $ret;
    }

    /**
     * @param        $value
     * @param string $format
     * @return string|string[]|null
     */
    public function sanitize_format_text($value, $format = 's')
    {
        switch (mb_strtolower($format)) {
            // strip GPC slashes when set by serVar()
            case 's':
            case 'show':
            case 'p':
            case 'preview':
            case 'f':
            case 'formpreview':
                $value = $this->sanitize_text($value);
                break;
            case 'e':
            case 'edit':
                $value = htmlspecialchars($value, ENT_QUOTES);
                break;
            case 'n':
            case 'none':
            default:
                break;
        }

        return $value;
    }

    /**
     * @param        $value
     * @param string $format
     * @return string|string[]|null
     */
    public function sanitize_format_textarea($value, $format = 's')
    {
        switch (mb_strtolower($format)) {
            // strip GPC slashes when set by serVar()
            case 's':
            case 'show':
            case 'p':
            case 'preview':
            case 'f':
            case 'formpreview':
                $value = $this->sanitize_textarea($value);
                break;
            case 'e':
            case 'edit':
                $value = htmlspecialchars($value, ENT_QUOTES);
                break;
            case 'n':
            case 'none':
            default:
                break;
        }

        return $value;
    }

    /**
     * @param        $value
     * @param string $format
     * @return string
     */
    public function sanitize_format_url($value, $format = 's')
    {
        switch (mb_strtolower($format)) {
            // strip GPC slashes when set by serVar();
            case 's':
            case 'show':
            case 'p':
            case 'preview':
            case 'f':
            case 'formpreview':
                $value = $this->sanitize_url($value);
                break;
            case 'e':
            case 'edit':
                $value = htmlspecialchars($value, ENT_QUOTES);
                break;
            case 'n':
            case 'none':
            default:
                break;
        }

        return $value;
    }

    /**
     * @param        $value
     * @param string $format
     * @param int    $max
     * @return string
     */
    public function sanitize_format_text_short($value, $format = 's', $max = 100)
    {
        if ($value) {
            if ($max) {
                $value = $this->shorten_text($value, $max);
            }

            $value = $this->sanitize_format_text($value, $format);
            $value = wordwrap($value);
            $value = '<pre>' . $value . '</pre>' . "\n";
        }

        return $value;
    }

    //--------------------------------------------------------
    // get_magic_quotes_gpc
    //--------------------------------------------------------
    /**
     * @param      $str
     * @param bool $not_gpc
     * @return array|string
     */
    public function strip_slashes_gpc_flag($str, $not_gpc = false)
    {
        if (!$not_gpc) {
            $str = $this->strip_slashes_gpc($str);
        }

        return $str;
    }

    /**
     * @param $str
     * @return array|string
     */
    public function strip_slashes_gpc($str)
    {
        if (@get_magic_quotes_gpc() && !is_array($str)) {
            $str = stripslashes($str);
        }

        return $str;
    }

    /**
     * @param $str
     * @return string
     */
    public function &add_slashes_gpc($str)
    {
        if (@!get_magic_quotes_gpc()) {
            $str = addslashes($str);
        }

        return $str;
    }

    /**
     * @param $arr_in
     * @return array
     */
    public function &strip_slashes_array_gpc(&$arr_in)
    {
        $arr_out = &$arr_in;
        if (@get_magic_quotes_gpc()) {
            $arr_out = &$this->strip_slashes_array($arr_in);
        }

        return $arr_out;
    }

    /**
     * @param $arr_in
     * @return array
     */
    public function &strip_slashes_array($arr_in)
    {
        $arr_out = [];
        foreach ($arr_in as $k => $v) {
            $val = $v;
            if (!is_array($v)) {
                $val = stripslashes($v);
            }
            $arr_out[$k] = $val;
        }

        return $arr_out;
    }

    //--------------------------------------------------------
    // check url string
    //--------------------------------------------------------
    /**
     * @param $str
     * @return string
     */
    public function deny_http_only($str)
    {
        if ($this->check_http_only($str)) {
            return '';
        }

        return $str;
    }

    /**
     * @param $str
     * @return string
     */
    public function allow_http($str)
    {
        if ($this->check_http_start($str)) {
            return $str;
        }

        return '';
    }

    // Checks if string is started from HTTP

    /**
     * @param $str
     * @return bool
     */
    public function check_http_start($str)
    {
        if (preg_match('|^https?://|', $str)) {
            return true;    // include HTTP
        }

        return false;
    }

    // Checks if string is HTTP only

    /**
     * @param $str
     * @return bool
     */
    public function check_http_only($str)
    {
        if (('http://' == $str) || ('https://' == $str)) {
            return true;    // http only
        }

        return false;
    }

    /**
     * @param $str
     * @return bool
     */
    public function check_http_fill($str)
    {
        if (('' != $str) && ('https://' != $str) && ('https://' != $str)) {
            return true;
        }

        return false;
    }

    //--------------------------------------------------------
    // check mail string
    // porting from xoopsmailer.php _checkValidEmail()
    // this is not fully based on RFC2822
    // RFC2822 Internet Message Format
    // https://www.faqs.org/rfcs/rfc2822.html
    //--------------------------------------------------------
    /**
     * @param $str
     * @return bool
     */
    public function check_email_format($str)
    {
        if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i", $str)) {
            return true;
        }

        return false;
    }


    //---------------------------------------------------------
    // substute
    //---------------------------------------------------------
    /**
     * @param        $value
     * @param string $default
     * @return string
     */
    public function substute_blank($value, $default = '&nbsp;')
    {
        if ('' == $value) {
            $value = $default;
        }

        return $value;
    }

    /**
     * @param        $value
     * @param string $default
     * @return string
     */
    public function substute_http($value, $default = 'https://')
    {
        if ('' == $value) {
            $value = $default;
        }

        return $value;
    }

    //=========================================================
    // convert strings
    //=========================================================
    // --------------------------------------------------------
    // summary
    // --------------------------------------------------------
    /**
     * @param        $text
     * @param        $max
     * @param null   $keyword_array
     * @param string $format
     * @return string|string[]|null
     */
    public function build_summary($text, $max, $keyword_array = null, $format = 'n')
    {
        $text = $this->strip_tags_for_text($text);
        $text = $this->shorten_text($text, $max, $keyword_array);
        $text = $this->sanitize_format($text, $format);

        return $text;
    }

    /**
     * @param $text
     * @return string
     */
    public function strip_tags_for_text($text)
    {
        if ($this->_flag_remove_control_code) {
            $text = $this->strip_control_all($text);
        }
        $text = $this->strip_script_tag($text);
        $text = $this->strip_style_tag($text);
        $text = $this->add_space_after_tag($text);
        $text = strip_tags($text);
        $text = $this->strip_space($text);
        $text = $this->set_empty_if_only_space($text);

        return $text;
    }

    /**
     * @param $text
     * @return string|string[]|null
     */
    public function strip_script_tag($text)
    {
        return happylinux_html_remove_script($text);
    }

    /**
     * @param $text
     * @return string|string[]|null
     */
    public function strip_style_tag($text)
    {
        return happylinux_html_remove_style($text);
    }

    /**
     * @param $text
     * @return string|string[]
     */
    public function add_space_after_tag($text)
    {
        return happylinux_str_add_space_after_tag($text);
    }

    /**
     * @param $text
     * @return false|string
     */
    public function add_space_after_punctuation($text)
    {
        // BUG: not show smile icon
        //  $text = happylinux_str_add_space_after_punctuation($text);

        if ($this->_is_japanese) {
            $text = happylinux_str_add_space_after_punctuation_ja($text);
        }

        return $text;
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public function strip_space($str)
    {
        if ($this->_is_japanese) {
            $str = $this->convert_space_zen_to_han($str);
        }
        $str = $this->replace_html_space_code_to_space($str);
        $str = $this->replace_space_code_to_single_space($str);

        return $str;
    }

    /**
     * @param $str
     * @return string
     */
    public function set_empty_if_only_space($str)
    {
        return happylinux_str_set_empty_if_only_space($str);
    }

    //--------------------------------------------------------
    // split_nl
    //--------------------------------------------------------
    /**
     * @param $text
     * @return false|string[]
     */
    public function split_nl($text)
    {
        $text = $this->convert_nl($text);
        $text = explode("\n", $text);

        return $text;
    }

    //--------------------------------------------------------
    // convert_nl
    //--------------------------------------------------------
    /**
     * @param $text
     * @return string|string[]
     */
    public function convert_nl($text)
    {
        $text = str_replace("\r\n", "\n", $text);
        $text = str_replace("\n\r", "\n", $text);
        $text = str_replace("\r", "\n", $text);

        return $text;
    }

    /**
     * @param $str
     * @return string|string[]
     */
    public function convert_str_to_crlf($str)
    {
        $str = str_replace('\r\n', "\r\n", $str);
        $str = str_replace('\r', "\r", $str);
        $str = str_replace('\n', "\n", $str);

        return $str;
    }

    //--------------------------------------------------------
    // check valid strings
    //--------------------------------------------------------
    /**
     * @param $str
     * @return bool
     */
    public function check_valid($str)
    {
        // remove control and space
        if ($this->_flag_remove_control_code) {
            $str = $this->strip_control_all($str);
        }

        $str = $this->strip_space($str);

        if (mb_strlen($str) > 0) {
            return true;
        }

        return false;
    }

    // --------------------------------------------------------
    // utility for array
    // --------------------------------------------------------
    /**
     * @param $arr1
     * @param $arr2
     * @return array|bool
     */
    public function &merge_unique_array($arr1, $arr2)
    {
        $arr = false;
        if (is_array($arr1) && is_array($arr2)) {
            $arr = array_merge($arr1, $arr2);
            $arr = array_unique($arr);
        }

        return $arr;
    }

    /**
     * @param $glue
     * @param $arr
     * @return bool|string
     */
    public function implode_array($glue, $arr)
    {
        $val = false;
        if (is_array($arr) && count($arr)) {
            $val = implode($glue, $arr);
        }

        return $val;
    }

    /**
     * @param        $arr
     * @param string $glue
     * @return bool|string
     */
    public function urlencode_from_array($arr, $glue = ' ')
    {
        $val = false;
        if (is_array($arr) && count($arr)) {
            $val = implode($glue, $arr);
            $val = urlencode($val);
        }

        return $val;
    }

    /**
     * @param     $arr_in
     * @param int $without
     * @return array
     */
    public function &build_unique_array_without($arr_in, $without = 0)
    {
        $arr_out = [];
        $without = (int)$without;

        if (is_array($arr_in)) {
            foreach ($arr_in as $int) {
                $int = (int)$int;
                if ($int != $without) {
                    $arr_out[] = $int;
                }
            }
        } else {
            $int = (int)$arr_in;
            if ($int != $without) {
                $arr_out[] = $int;
            }
        }

        $arr_out = array_unique($arr_out);

        return $arr_out;
    }

    /**
     * @param      $arr
     * @param bool $flag_sanitize
     * @return string|string[]|null
     */
    public function sanitize_var_export($arr, $flag_sanitize = true)
    {
        if (is_array($arr)) {
            $val = var_export($arr, true);
            if ($flag_sanitize) {
                $text = '<pre>';
                $text .= $this->sanitize_text($val);
                $text .= "</pre>\n";
            } else {
                $text = $val;
            }
        } else {
            $text = $this->sanitize_text_by_flag($arr, $flag_sanitize);
        }

        return $text;
    }

    /**
     * @param      $str
     * @param bool $flag_sanitize
     * @return string|string[]|null
     */
    public function sanitize_text_by_flag($str, $flag_sanitize = true)
    {
        if ($flag_sanitize) {
            $text = $this->sanitize_text($str);
        } else {
            $text = $str;
        }

        return $text;
    }

    //--------------------------------------------------------
    // add strings to tail
    //--------------------------------------------------------
    /**
     * @param $str
     * @param $add
     * @return string
     */
    public function add_str_to_tail($str, $add)
    {
        if ($add && (mb_substr(trim($str), -1, 1) != $add)) {
            $str .= $add;
        }

        return $str;
    }

    // --------------------------------------------------------
    // convert bool, int to strings
    // --------------------------------------------------------
    /**
     * @param $bool
     * @return string
     */
    public function bool_to_str($bool)
    {
        $str = 'false';
        if ($bool) {
            $str = 'true';
        }

        return $str;
    }

    /**
     * @param $int
     * @return bool
     */
    public function bool_val($int)
    {
        $bool = false;
        if ($int) {
            $bool = true;
        }

        return $bool;
    }

    // --------------------------------------------------------
    // onvert_array_key_to_string
    // array($key => $value)  ==> $key:$value
    // --------------------------------------------------------
    /**
     * @param $arr
     * @return string
     */
    public function convert_array_key_to_string($arr)
    {
        if ((0 == count($arr)) || !is_array($arr)) {
            return '';
        }

        $str = '';

        foreach ($arr as $key => $value) {
            // replace return code
            $value = preg_replace("/\n/", '\\n', $value);

            $str .= $key . ':' . $value . "\n";
        }

        return $str;
    }

    /**
     * @param        $str
     * @param string $format
     * @return array
     */
    public function convert_string_to_array_key($str, $format = 'n')
    {
        $array = [];

        $line_arr = $this->convert_string_to_array($str, "\n");

        if (0 == count($line_arr)) {
            return $array;
        }

        foreach ($line_arr as $line) {
            list($key, $value) = explode(':', $line, 2);

            // replace return code
            $value = preg_replace('/\\n/', "\n", $value);

            $array[$key] = $this->make_format($value, $format);
        }

        return $array;
    }

    //---------------------------------------------------------
    // convert array to string
    // array($a, $b, $c)  ==> $a & $b & $c
    //---------------------------------------------------------
    /**
     * @param        $arr
     * @param string $pattern
     * @return string
     */
    public function convert_array_to_string($arr, $pattern = '&')
    {
        if (is_array($arr)) {
            $str = $pattern;

            foreach ($arr as $value) {
                $str .= (int)$value . $pattern;
            }
        } elseif (is_int($arr)) {
            $str = $pattern . (int)$arr . $pattern;
        } else {
            $str = $arr;
        }

        return $str;
    }

    /**
     * @param        $str
     * @param string $pattern
     * @return array
     */
    public function &convert_string_to_array($str, $pattern = '&')
    {
        $arr = [];

        if ('' === $str) {
            return $arr;
        }

        $str_arr = explode($pattern, $str);

        $i = 0;
        foreach ($str_arr as $value) {
            $value = trim($value);

            if ('' == $value) {
                continue;
            }

            $arr[++$i] = $value;
        }

        return $arr;
    }

    //-------------------------------------------------------------------
    // parse strings
    //-------------------------------------------------------------------
    /**
     * @param $name
     * @return int|string
     */
    public function parse_tail_figure($name)
    {
        // take out the figure of the tail
        preg_match('/^(\D+)(\d*)$/', $name, $regs);
        $ext = '' === $regs[2] ? '' : (int)$regs[2];

        return $ext;
    }

    //-------------------------------------------------------------------
    // time utility
    //-------------------------------------------------------------------
    /**
     * @param        $time
     * @param string $y
     * @param string $m
     * @param string $d
     * @param string $h
     * @param string $i
     * @param string $s
     * @return array
     */
    public function &split_time_ymd($time, $y = 'Y', $m = 'n', $d = 'd', $h = 'H', $i = 'i', $s = 's')
    {
        $year  = date($y, $time);
        $month = date($m, $time);
        $day   = date($d, $time);
        $hour  = date($h, $time);
        $min   = date($i, $time);
        $sec   = date($s, $time);

        $arr = [$year, $month, $day, $hour, $min, $sec];

        return $arr;
    }

    //---------------------------------------------------------
    // analyze_script_type
    //
    // script:
    //   type 0: foo.php
    //   type 1: foo.php?
    //   type 2: foo.php?bar=abc
    //---------------------------------------------------------
    /**
     * @param $script
     * @return int
     */
    public function analyze_script_type($script)
    {
        $type = 0;  // foo.php

        // set script_type, if ? in script
        if (preg_match('/\?/', $script)) {
            $script_arr = explode('?', $script);
            if ($script_arr[1]) {
                $type = 2;  // foo.php?bar=abc
            } else {
                $type = 1;  // foo.php?
            }
        }

        return $type;
    }

    /**
     * @param $script
     * @param $add
     * @param $type
     * @return string
     */
    public function add_script_by_type($script, $add, $type)
    {
        $ret = $script;
        if (0 == $type) {
            $ret = $script . '?' . $add;
        } elseif (1 == $type) {
            $ret = $script . $add;
        } elseif (2 == $type) {
            $ret = $script . '&' . $add;
        }

        return $ret;
    }

    //--------------------------------------------------------
    // set parameter
    //--------------------------------------------------------
    /**
     * @param $value
     */
    public function set_max_summary($value)
    {
        $this->_max_summary = (int)$value;
    }

    /**
     * @param $val
     */
    public function set_flag_remove_control_code($val)
    {
        $this->_flag_remove_control_code = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_is_japanese($val)
    {
        $this->_is_japanese = (bool)$val;
    }

    //========================================================
    // multibyte function
    //========================================================
    /**
     * @param      $text
     * @param      $max
     * @param null $keyword_array
     * @return string
     */
    public function shorten_text($text, $max, $keyword_array = null)
    {
        // nothing, if zero
        if (0 == $max) {
            return '';
        }

        // unlimited, if minus
        if ($max < 0) {
            return $text;
        }

        // less than
        if (mb_strlen($text) <= $max) {
            return $text;
        }

        if (is_array($keyword_array)) {
            $text = happylinux_build_search_context($text, $keyword_array, $max);
        } else {
            $text = happylinux_strcut($text, 0, $max) . ' ...';
        }

        return $text;
    }

    /**
     * @param $str
     * @return string
     */
    public function convert_space_zen_to_han($str)
    {
        return happylinux_convert_kana($str, 's');
    }

    /**
     * @param        $arr
     * @param string $glue
     * @return bool|string
     */
    public function utf8_urlencode_from_array($arr, $glue = ' ')
    {
        $val = false;
        if (is_array($arr) && count($arr)) {
            $val = implode($glue, $arr);
            $val = happylinux_convert_to_utf8($val);
            $val = urlencode($val);
        }

        return $val;
    }

    //========================================================
    // measure time
    // this function is deprecated
    // recommend Time
    //========================================================
    public function start_measure_time()
    {
        $this->_time_start   = $this->get_microtime();
        $this->_time_current = $this->_time_start;
    }

    /**
     * @return float|int
     */
    public function get_measure_time()
    {
        $time = $this->get_microtime() - $this->_time_start;

        return $time;
    }

    /**
     * @return float
     */
    public function get_microtime()
    {
        list($usec, $sec) = explode(' ', microtime());
        $time = (float)$sec + (float)$usec;

        return $time;
    }

    //---------------------------------------------------------
    // xoops param
    //---------------------------------------------------------
    /**
     * @return bool
     */
    public function is_module_admin_error_reporting_debug_print_backtrace()
    {
        if ($this->is_module_admin_error_reporting() && $this->exist_debug_print_backtrace()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function exist_debug_print_backtrace()
    {
        if (function_exists('debug_print_backtrace')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function is_module_admin_error_reporting()
    {
        if ($this->is_module_admin() && error_reporting()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function is_module_admin()
    {
        global $xoopsUser, $xoopsModule;
        if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
            return true;
        }

        return false;
    }

    // --- class end ---
}
