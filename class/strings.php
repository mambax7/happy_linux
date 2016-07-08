<?php
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
// happy_linux/include/sanitize.php
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

include_once XOOPS_ROOT_PATH . '/modules/happy_linux/include/sanitize.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/include/multibyte.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/include/htmlout.php';

//=========================================================
// class happy_linux_strings
//=========================================================
class happy_linux_strings
{
    public $_MAX_DEPTH = 10;

    // local variable
    public $_max_summary  = 100;
    public $_time_start   = 0;
    public $_time_current = 0;
    public $_is_japanese  = false;

    // same language match contorl code
    // ex) BIG-5 GB2312 Ž` C05C B2CD —V B943 904A
    public $_flag_remove_control_code = false;

    // allow to remove control code
    public $_ENCODING_ARRAY = array('iso-8859-1', 'utf-8', 'euc-jp');

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->init_strings();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_strings();
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

    public function check_in_encoding_array($encoding)
    {
        if (in_array(strtolower($encoding), $this->_ENCODING_ARRAY)) {
            return true;
        }
        return false;
    }

    //=========================================================
    // for object->setVar()
    //=========================================================
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

    public function prepare_textarea($str, $not_gpc = false)
    {
        if ($this->_flag_remove_control_code) {
            $str = $this->strip_control($str);
        }
        $str = $this->strip_slashes_gpc_flag($str, $not_gpc);
        $str = $this->replace_javascript($str);
        return $str;
    }

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

    public function &prepare_array($arr, $not_gpc = false)
    {
        $str = $this->_prepare_array_recursive(0, $arr, $not_gpc);
        return $str;
    }

    public function _prepare_array_recursive($num, $arr_in, $not_gpc = false)
    {
        ++$num;
        if ($num > $this->_MAX_DEPTH) {
            return false;
        }

        if (is_array($arr_in)) {
            $arr_out = array();
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
    public function html_specialchars($str, $flag_amp = true)
    {
        if (is_array($str)) {
            //      print_r($str);
            return $str;
        }

        $str = htmlspecialchars($str, ENT_QUOTES);

        if (!$flag_amp) {
            $str = happy_linux_undo_html_entity_name($str);
            $str = happy_linux_undo_html_entity_numeric($str);
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
    public function undo_html_specialchars($str)
    {
        return happy_linux_undo_htmlspecialchars($str);
    }

    // --------------------------------------------------------
    // sanitize and convert htmlspecialchars for text
    // --------------------------------------------------------
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
            $str = happy_linux_undo_html_entity_name($str);
            $str = happy_linux_undo_html_entity_numeric($str);
        }
        return $str;
    }

    // --------------------------------------------------------
    // sanitize and convert htmlspecialchars for textarea
    // --------------------------------------------------------
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
            $str = happy_linux_undo_html_entity_name($str);
            $str = happy_linux_undo_html_entity_numeric($str);
        }
        return $str;
    }

    //---------------------------------------------------------
    // sanitize and convert htmlspecialchars for url
    //---------------------------------------------------------
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
            $str = happy_linux_undo_htmlspecialchars($str);
        }
        $str = htmlspecialchars($str, ENT_QUOTES);
        return $str;
    }

    //---------------------------------------------------------
    // strip control code
    //---------------------------------------------------------
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
    public function strip_control($str)
    {
        return $this->replace_control($str, '');
    }

    public function replace_control($str, $replace)
    {
        return happy_linux_str_replace_control_code($str, $replace);
    }

    // strip TAB code
    public function strip_tab($str)
    {
        return happy_linux_str_replace_tab_code($str, '');
    }

    // strip LF, CR code
    public function strip_return($str)
    {
        return happy_linux_str_replace_return_code($str, '');
    }

    public function replace_return_to_space($str)
    {
        return happy_linux_str_replace_return_code($str, ' ');
    }

    public function &strip_control_array($arr_in)
    {
        $ret = $this->_replace_control_array_recursive(0, $arr_in, '');
        return $ret;
    }

    public function &replace_control_array($arr_in, $replace)
    {
        $ret = $this->_replace_control_array_recursive(0, $arr_in, $replace);
        return $ret;
    }

    public function _replace_control_array_recursive($num, $arr_in, $replace)
    {
        ++$num;
        if ($num > $this->_MAX_DEPTH) {
            return false;
        }

        if (is_array($arr_in)) {
            $arr_out = array();
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
    public function strip_space_code($str)
    {
        return happy_linux_str_replace_space_code($str, '');
    }

    public function strip_html_space_code($str)
    {
        return happy_linux_str_replace_html_space_code($str, '');
    }

    public function replace_html_amp_space_code_to_space($str)
    {
        return preg_replace('/&amp;nbsp;/i', ' ', $str);
    }

    public function replace_space_code_to_single_space($str)
    {
        return happy_linux_str_replace_html_space_code($str, ' ');
    }

    public function replace_html_space_code_to_space($str)
    {
        return happy_linux_str_replace_continuous_space_code($str, ' ');
    }

    //--------------------------------------------------------
    // javascript
    //--------------------------------------------------------
    public function replace_javascript($str)
    {
        $str = happy_linux_html_replace_javascript($str);
        $str = happy_linux_html_replace_javascript_colon($str);
        $str = happy_linux_html_replace_vbscript_colon($str);
        $str = happy_linux_html_replace_about_colon($str);
        return $str;
    }

    // deny_javascript
    public function deny_javascript($str)
    {
        if ($this->check_javascript($str)) {
            return '';
        }
        return $str;
    }

    // Checks if Javascript are included in string
    public function check_javascript($str)
    {
        $str = $this->strip_control_all($str);

        if (happy_linux_html_check_javascript($str)) {
            return true;    // include JavaScript
        }
        if (happy_linux_html_check_javascript_colon($str)) {
            return true;    // include JavaScript
        }
        if (happy_linux_html_check_vbscript_colon($str)) {
            return true;    // include vbscript
        }
        if (happy_linux_html_check_about_colon($str)) {
            return true;    // include about
        }
        return false;
    }

    //--------------------------------------------------------
    // sanitize array
    //--------------------------------------------------------
    public function &sanitize_array_text($arr_in)
    {
        $ret = $this->_sanitize_array_text_recursive(0, $arr_in);
        return $ret;
    }

    public function _sanitize_array_text_recursive($num, $arr_in)
    {
        ++$num;
        if ($num > $this->_MAX_DEPTH) {
            return false;
        }

        if (is_array($arr_in)) {
            $arr_out = array();
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
    public function sanitize_format($value, $format = 's')
    {
        $ret = $this->sanitize_format_text($value, $format);
        return $ret;
    }

    public function sanitize_format_text($value, $format = 's')
    {
        switch (strtolower($format)) {
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

    public function sanitize_format_textarea($value, $format = 's')
    {
        switch (strtolower($format)) {
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

    public function sanitize_format_url($value, $format = 's')
    {
        switch (strtolower($format)) {
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
    public function strip_slashes_gpc_flag($str, $not_gpc = false)
    {
        if (!$not_gpc) {
            $str = $this->strip_slashes_gpc($str);
        }
        return $str;
    }

    public function strip_slashes_gpc($str)
    {
        if (get_magic_quotes_gpc() && !is_array($str)) {
            $str = stripslashes($str);
        }
        return $str;
    }

    public function &add_slashes_gpc($str)
    {
        if (!get_magic_quotes_gpc()) {
            $str = addslashes($str);
        }
        return $str;
    }

    public function &strip_slashes_array_gpc(&$arr_in)
    {
        $arr_out =& $arr_in;
        if (get_magic_quotes_gpc()) {
            $arr_out =& $this->strip_slashes_array($arr_in);
        }
        return $arr_out;
    }

    public function &strip_slashes_array(&$arr_in)
    {
        $arr_out = array();
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
    public function deny_http_only($str)
    {
        if ($this->check_http_only($str)) {
            return '';
        }
        return $str;
    }

    public function allow_http($str)
    {
        if ($this->check_http_start($str)) {
            return $str;
        }
        return '';
    }

    // Checks if string is started from HTTP
    public function check_http_start($str)
    {
        if (preg_match('|^https?://|', $str)) {
            return true;    // include HTTP
        }
        return false;
    }

    // Checks if string is HTTP only
    public function check_http_only($str)
    {
        if (($str == 'http://') || ($str == 'https://')) {
            return true;    // http only
        }
        return false;
    }

    public function check_http_fill($str)
    {
        if (($str != '') && ($str != 'http://') && ($str != 'https://')) {
            return true;
        }
        return false;
    }

    //--------------------------------------------------------
    // check mail string
    // porting from xoopsmailer.php _checkValidEmail()
    // this is not fully based on RFC2822
    // RFC2822 Internet Message Format
    // http://www.faqs.org/rfcs/rfc2822.html
    //--------------------------------------------------------
    public function check_email_format($str)
    {
        if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i", $str)) {
            return true;
        }
        return false;
    }

    //=========================================================
    // convert strings
    //=========================================================
    // --------------------------------------------------------
    // summary
    // --------------------------------------------------------
    public function build_summary($text, $max, $keyword_array = null, $format = 'n')
    {
        $text = $this->strip_tags_for_text($text);
        $text = $this->shorten_text($text, $max, $keyword_array);
        $text = $this->sanitize_format($text, $format);
        return $text;
    }

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

    public function strip_script_tag($text)
    {
        return happy_linux_html_remove_script($text);
    }

    public function strip_style_tag($text)
    {
        return happy_linux_html_remove_style($text);
    }

    public function add_space_after_tag($text)
    {
        return happy_linux_str_add_space_after_tag($text);
    }

    public function add_space_after_punctuation($text)
    {
        // BUG: not show smile icon
        //  $text = happy_linux_str_add_space_after_punctuation($text);

        if ($this->_is_japanese) {
            $text = happy_linux_str_add_space_after_punctuation_ja($text);
        }
        return $text;
    }

    public function strip_space($str)
    {
        if ($this->_is_japanese) {
            $str = $this->convert_space_zen_to_han($str);
        }
        $str = $this->replace_html_space_code_to_space($str);
        $str = $this->replace_space_code_to_single_space($str);
        return $str;
    }

    public function set_empty_if_only_space($str)
    {
        return happy_linux_str_set_empty_if_only_space($str);
    }

    //--------------------------------------------------------
    // split_nl
    //--------------------------------------------------------
    public function split_nl($text)
    {
        $text = $this->convert_nl($text);
        $text = explode("\n", $text);
        return $text;
    }

    //--------------------------------------------------------
    // convert_nl
    //--------------------------------------------------------
    public function convert_nl($text)
    {
        $text = str_replace("\r\n", "\n", $text);
        $text = str_replace("\n\r", "\n", $text);
        $text = str_replace("\r", "\n", $text);
        return $text;
    }

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
    public function check_valid($str)
    {
        // remove control and space
        if ($this->_flag_remove_control_code) {
            $str = $this->strip_control_all($str);
        }

        $str = $this->strip_space($str);

        if (strlen($str) > 0) {
            return true;
        }

        return false;
    }

    // --------------------------------------------------------
    // utility for array
    // --------------------------------------------------------
    public function &merge_unique_array($arr1, $arr2)
    {
        $arr = false;
        if (is_array($arr1) && is_array($arr2)) {
            $arr = array_merge($arr1, $arr2);
            $arr = array_unique($arr);
        }
        return $arr;
    }

    public function implode_array($glue, $arr)
    {
        $val = false;
        if (is_array($arr) && count($arr)) {
            $val = implode($glue, $arr);
        }
        return $val;
    }

    public function urlencode_from_array($arr, $glue = ' ')
    {
        $val = false;
        if (is_array($arr) && count($arr)) {
            $val = implode($glue, $arr);
            $val = urlencode($val);
        }
        return $val;
    }

    public function &build_unique_array_without($arr_in, $without = 0)
    {
        $arr_out = array();
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

    public function sanitize_var_export(&$arr, $flag_sanitize = true)
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
    public function add_str_to_tail($str, $add)
    {
        if ($add && (substr(trim($str), -1, 1) != $add)) {
            $str .= $add;
        }
        return $str;
    }

    // --------------------------------------------------------
    // convert bool, int to strings
    // --------------------------------------------------------
    public function bool_to_str($bool)
    {
        $str = 'false';
        if ($bool) {
            $str = 'true';
        }
        return $str;
    }

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
    public function convert_array_key_to_string($arr)
    {
        if ((count($arr) == 0) || !is_array($arr)) {
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

    public function convert_string_to_array_key($str, $format = 'n')
    {
        $array = array();

        $line_arr = $this->convert_string_to_array($str, "\n");

        if (count($line_arr) == 0) {
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

    public function &convert_string_to_array($str, $pattern = '&')
    {
        $arr = array();

        if ($str === '') {
            return $arr;
        }

        $str_arr = explode($pattern, $str);

        $i = 0;
        foreach ($str_arr as $value) {
            $value = trim($value);

            if ($value == '') {
                continue;
            }

            $arr[++$i] = $value;
        }

        return $arr;
    }

    //-------------------------------------------------------------------
    // parse strings
    //-------------------------------------------------------------------
    public function parse_tail_figure($name)
    {
        // take out the figure of the tail
        preg_match('/^(\D+)(\d*)$/', $name, $regs);
        $ext = $regs[2] === '' ? '' : (int)$regs[2];
        return $ext;
    }

    //-------------------------------------------------------------------
    // time utility
    //-------------------------------------------------------------------
    public function &split_time_ymd($time, $y = 'Y', $m = 'n', $d = 'd', $h = 'H', $i = 'i', $s = 's')
    {
        $year  = date($y, $time);
        $month = date($m, $time);
        $day   = date($d, $time);
        $hour  = date($h, $time);
        $min   = date($i, $time);
        $sec   = date($s, $time);

        $arr = array($year, $month, $day, $hour, $min, $sec);
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

    public function add_script_by_type($script, $add, $type)
    {
        $ret = $script;
        if ($type == 0) {
            $ret = $script . '?' . $add;
        } elseif ($type == 1) {
            $ret = $script . $add;
        } elseif ($type == 2) {
            $ret = $script . '&' . $add;
        }
        return $ret;
    }

    //--------------------------------------------------------
    // set parameter
    //--------------------------------------------------------
    public function set_max_summary($value)
    {
        $this->_max_summary = (int)$value;
    }

    public function set_flag_remove_control_code($val)
    {
        $this->_flag_remove_control_code = (bool)$val;
    }

    public function set_is_japanese($val)
    {
        $this->_is_japanese = (bool)$val;
    }

    //========================================================
    // multibyte function
    //========================================================
    public function shorten_text($text, $max, $keyword_array = null)
    {
        // nothing, if zero
        if ($max == 0) {
            return '';
        }

        // unlimited, if minus
        if ($max < 0) {
            return $text;
        }

        // less than
        if (strlen($text) <= $max) {
            return $text;
        }

        if (is_array($keyword_array)) {
            $text = happy_linux_build_search_context($text, $keyword_array, $max);
        } else {
            $text = happy_linux_strcut($text, 0, $max) . ' ...';;
        }

        return $text;
    }

    public function convert_space_zen_to_han($str)
    {
        return happy_linux_convert_kana($str, 's');
    }

    public function utf8_urlencode_from_array($arr, $glue = ' ')
    {
        $val = false;
        if (is_array($arr) && count($arr)) {
            $val = implode($glue, $arr);
            $val = happy_linux_convert_to_utf8($val);
            $val = urlencode($val);
        }
        return $val;
    }

    //========================================================
    // measure time
    // this function is deprecated
    // recommend happy_linux_time
    //========================================================
    public function start_measure_time()
    {
        $this->_time_start   = $this->get_microtime();
        $this->_time_current = $this->_time_start;
    }

    public function get_measure_time()
    {
        $time = $this->get_microtime() - $this->_time_start;
        return $time;
    }

    public function get_microtime()
    {
        list($usec, $sec) = explode(' ', microtime());
        $time = (float)$sec + (float)$usec;
        return $time;
    }

    //---------------------------------------------------------
    // xoops param
    //---------------------------------------------------------
    public function is_module_admin_error_reporting_debug_print_backtrace()
    {
        if ($this->is_module_admin_error_reporting() && $this->exist_debug_print_backtrace()) {
            return true;
        }
        return false;
    }

    public function exist_debug_print_backtrace()
    {
        if (function_exists('debug_print_backtrace')) {
            return true;
        }
        return false;
    }

    public function is_module_admin_error_reporting()
    {
        if ($this->is_module_admin() && error_reporting()) {
            return true;
        }
        return false;
    }

    public function is_module_admin()
    {
        global $xoopsUser, $xoopsModule;
        if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
            return true;;
        }
        return false;
    }

    // --- class end ---
}
