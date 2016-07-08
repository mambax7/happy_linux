<?php
// $Id: form.php,v 1.20 2008/02/28 13:12:48 ohwada Exp $

// 2008-02-24 K.OHWADA
// change build_edit_url_with_visit()

// 2007-11-01 K.OHWADA
// get_token_pair()

// 2007-08-01 K.OHWADA
// &nbsp => &nbsp;

// 2007-07-16 K.OHWADA
// build_form_table_textarea()

// 2007-06-23 K.OHWADA
// build_obj_table_radio_yesno() build_form_table_by_array()
// build_form_class_even_odd()

// 2007-05-12 K.OHWADA
// print_xoops_token_error()
// change build_form_checkbox_yesno()

// 2007-02-20 K.OHWADA
// change build_form_dhtml_textarea()

// 2006-12-10 K.OHWADA
// add build_form_select_time()

// 2006-10-05 K.OHWADA
// add get_form_radio_yesno_options()
// change build_gticket_token()

// 2006-09-20 K.OHWADA
// divid to happy_linux_form happy_linux_form_lib
// use XoopsGTicket
// add get_obj_var() etc
// add set_action() set_op_value()
// add build_form_button_submit()

// 2006-07-10 K.OHWADA
// this is new file
// porting from rssc_form.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

define('HAPPY_LINUX_MODE_ADD', 0);
define('HAPPY_LINUX_MODE_MOD', 1);
define('HAPPY_LINUX_MODE_ADD_PREVIEW', 2);
define('HAPPY_LINUX_MODE_MOD_PREVIEW', 3);
define('HAPPY_LINUX_MODE_DEL_PREVIEW', 4);

//=========================================================
// class happy_linux_form
//=========================================================
class happy_linux_form extends happy_linux_html
{
    // language
    public $_LANG_YES        = _YES;
    public $_LANG_NO         = _NO;
    public $_LANG_FORM_TITLE = _HAPPY_LINUX_FORM_TITLE;
    public $_LANG_VISIT      = _HAPPY_LINUX_FORM_VISIT;

    public $_LANG_BUTTON_SUBMIT_VALUE   = _HAPPY_LINUX_FORM_SUBMIT;
    public $_LANG_BUTTON_CANCEL_VALUE   = _HAPPY_LINUX_FORM_CANCEL;
    public $_LANG_BUTTON_LOCATION_VALUE = _HAPPY_LINUX_FORM_LOCATION;
    public $_LANG_BUTTON_CLOSE_VALUE    = _CLOSE;

    // color: red;  background-color: lightyellow;  border: gray
    public $_STYLE_ERROR = 'color: #ff0000; background-color: #ffffe0; border: #808080 1px dotted; padding: 3px 3px 3px 3px;';

    // rewritable contant
    public $_FORM_NAME_DEFAULT = 'form';
    public $_TOKEN_NAME        = 'form';
    public $_FORM_METHOD       = 'post';

    public $_BUTTON_SUBMIT_NAME   = 'submit';
    public $_BUTTON_CANCEL_NAME   = 'cancel';
    public $_BUTTON_LOCATION_NAME = 'location';
    public $_BUTTON_CLOSE_NAME    = 'close';

    public $_FORM_NAME = null;
    public $_ACTION    = null;

    // for childlen class, not use here
    public $_TEXT_SIZE = 50;
    public $_TEXT_MAX  = 255;
    public $_URL_SIZE  = 70;
    public $_URL_MAX   = 255;

    public $_SELECT_TIME_YEAR_BEFORE = 8;
    public $_SELECT_TIME_YEAR_AFTER  = 1;

    // variable
    public $_SIZE      = 50;
    public $_MAXLENGTH = 255;
    public $_ROWS      = 5;
    public $_COLS      = 50;

    public $_table_title_class = 'head';
    public $_table_ele_class   = 'odd';

    public $_obj;
    public $_datas = array();

    public $_font_caption_title     = "<span style='font-weight:bold;'>";
    public $_font_caption_desc      = "<span style='font-weight:normal;'>";
    public $_font_caption_title_end = '</span>';
    public $_font_caption_desc_end  = '</span>';

    public $_line_count = 0;

    // token
    public $_DEBUG_CHECK_TOKEN = true;
    public $_SEL_TOKEN_CLASS   = 'gticket';
    public $_token_error       = null;
    public $_cached_token      = null;

    // for form_lib page_form
    public $_OP_NAME  = 'op';
    public $_op_value = null;

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
            $instance = new happy_linux_form();
        }
        return $instance;
    }

    //================================================================
    // build form element
    //================================================================
    //---------------------------------------------------------
    // build table form
    //---------------------------------------------------------
    public function build_form_table_begin($width = '100%', $height = '', $cellpadding = 1, $cellspacing = 1, $class = 'outer')
    {
        $text = $this->build_html_table_tag_begin($width, $height, $cellpadding, $cellspacing, $class);
        return $text;
    }

    public function build_form_table_end()
    {
        $text = $this->build_html_table_tag_end();
        return $text;
    }

    public function build_form_table_title($title = '', $th_colspan = 2, $th_class = '')
    {
        $th_align   = '';
        $th_valign  = '';
        $th_rowspan = '';

        if (empty($title)) {
            $title = $this->_LANG_FORM_TITLE;
        }

        $text = $this->build_html_tr_tag_begin('left', 'top');
        $text .= $this->build_html_th_tag_begin($th_align, $th_valign, $th_colspan, $th_rowspan, $th_class);
        $text .= $title;
        $text .= $this->build_html_th_tag_end();
        $text .= $this->build_html_tr_tag_end();
        return $text;
    }

    public function build_form_table_line($title = '', $ele = '', $title_class = 'head', $ele_class = 'odd')
    {
        if (empty($title)) {
            $title = '&nbsp;';
        }

        if (empty($ele)) {
            $ele = '&nbsp;';
        }

        $text = $this->build_html_tr_tag_begin('left', 'top');
        $text .= $this->build_form_td_class($title, $title_class);
        $text .= $this->build_form_td_class($ele, $ele_class);
        $text .= $this->build_html_tr_tag_end();
        return $text;
    }

    public function build_form_td_class($value, $class = '')
    {
        $text = $this->build_html_td_tag_class($class);
        $text .= $this->substute_blank($value);
        $text .= $this->build_html_td_tag_end();
        return $text;
    }

    //---------------------------------------------------------
    // build table form
    //---------------------------------------------------------
    public function build_form_table_text($cap, $name, $value, $size = 50, $maxlength = 255)
    {
        $ele  = $this->build_html_input_text($name, $value, $size, $maxlength);
        $text = $this->build_form_table_line($cap, $ele, $this->_table_title_class, $this->_table_ele_class);
        return $text;
    }

    public function build_form_table_textarea($cap, $name, $value, $rows = '10', $cols = '60')
    {
        $ele  = $this->build_html_textarea($name, $value, $rows, $cols);
        $text = $this->build_form_table_line($cap, $ele, $this->_table_title_class, $this->_table_ele_class);
        return $text;
    }

    public function build_form_table_radio_select($cap, $name, $value, $options, $del = '')
    {
        $ele  = $this->build_html_input_radio_select($name, $value, $options, $del = '');
        $text = $this->build_form_table_line($cap, $ele, $this->_table_title_class, $this->_table_ele_class);
        return $text;
    }

    public function build_form_table_submit($cap, $name, $value)
    {
        $ele  = $this->build_html_input_submit($name, $value);
        $text = $this->build_form_table_line($cap, $ele, $this->_table_title_class, $this->_table_title_class);
        return $text;
    }

    public function build_form_table_by_array(&$arr, $flag_sanitize = true)
    {
        $text = $this->build_form_table_begin();

        if (is_array($arr)) {
            foreach ($arr as $k1 => $v1) {
                $k1_s = $this->sanitize_text_by_flag($k1, $flag_sanitize);
                if (is_array($v1)) {
                    $text .= $this->build_form_table_title($k1_s);
                    foreach ($v1 as $k2 => $v2) {
                        $k2_s = $this->sanitize_text_by_flag($k2, $flag_sanitize);
                        $v2_s = $this->sanitize_var_export($v2, $flag_sanitize);
                        $text .= $this->build_form_table_line($k2_s, $v2_s);
                    }
                } else {
                    $v1_s = $this->sanitize_text_by_flag($v1, $flag_sanitize);
                    $text .= $this->build_form_table_line($k1_s, $v1_s);
                }
            }
        } else {
            $arr_s = $this->sanitize_text_by_flag($arr, $flag_sanitize);
            $text .= $this->build_form_table_title($arr_s);
        }

        $text .= $this->build_form_table_end();
        return $text;
    }

    public function build_form_clear_line_count()
    {
        $this->_line_count = 0;
    }

    public function build_form_class_even_odd()
    {
        if ($this->_line_count % 2 == 0) {
            $class = 'even';
        } else {
            $class = 'odd';
        }

        $this->_line_count++;
        return $class;
    }

    //---------------------------------------------------------
    // build form
    //---------------------------------------------------------
    public function build_form_begin($name = '', $action = '', $method = 'post', $enctype = '', $extra = '')
    {
        if (empty($name)) {
            if ($this->_FORM_NAME) {
                $name = $this->_FORM_NAME;
            } else {
                $name = $this->build_form_name_rand();
            }
        }

        // restore form name
        $this->_FORM_NAME = $name;

        if (empty($action)) {
            if ($this->_ACTION) {
                $action = $this->_ACTION;
            } else {
                $action = xoops_getenv('PHP_SELF');
            }
        }

        // sanitize
        $action = $this->sanitize_url($action);

        $text = $this->build_html_form_tag_begin($name, $action, $enctype, $method, $extra);
        return $text;
    }

    public function build_form_end()
    {
        $text = $this->build_html_form_tag_end();
        return $text;
    }

    public function build_form_name_rand()
    {
        $name = $this->_FORM_NAME_DEFAULT . '_' . rand();
        return $name;
    }

    //---------------------------------------------------------
    // complex form
    //---------------------------------------------------------
    public function build_form_radio_yesno($name, $value)
    {
        $value = (int)$value;

        if ($value != 0) {
            $value = 1;
        }

        $options = $this->get_form_radio_yesno_options();
        $text    = $this->build_html_input_radio_select($name, $value, $options);
        return $text;
    }

    public function &get_form_radio_yesno_options()
    {
        $arr = array(
            $this->_LANG_YES => 1,
            $this->_LANG_NO  => 0
        );
        return $arr;
    }

    public function build_form_checkbox_yesno($name, $value)
    {
        // check, if yes
        $checked = $this->build_html_checked($value, 1);
        $text    = $this->build_html_input_checkbox($name, 1, $checked);
        return $text;
    }

    public function build_html_error_with_style($val)
    {
        if ($val) {
            return $this->build_html_div_tag_with_style($val, $this->_STYLE_ERROR);
        }
        return $val;
    }

    public function build_form_select_time($name, $time = 0, $format = '%y - %m - %d  %h : %i')
    {
        $time = (int)$time;

        if (empty($time)) {
            $time = time();
        }

        list($year, $month, $day, $hour, $min, $sec) = $this->split_time_ymd($time);

        $this_year  = date('Y');
        $year_start = $this_year - $this->_SELECT_TIME_YEAR_BEFORE;
        $year_end   = $this_year + $this->_SELECT_TIME_YEAR_AFTER;

        $year_opt = array($year_start => $year_start);
        for ($i = ($year_start + 1); $i <= $year_end; ++$i) {
            $year_opt[] = $i;
        }

        $month_opt = array(1 => 1);
        for ($i = 2; $i <= 12; ++$i) {
            $month_opt[] = $i;
        }

        $day_opt = array(1 => 1);
        for ($i = 2; $i <= 31; ++$i) {
            $day_opt[] = $i;
        }

        $hour_opt = array();
        for ($i = 0; $i <= 23; ++$i) {
            $hour_opt[] = $i;
        }

        $sixty_opt = array();
        for ($i = 0; $i < 60; ++$i) {
            $sixty_opt[] = $i;
        }

        $year_sel  = $this->build_html_select($name . '_year', $year, $year_opt);
        $month_sel = $this->build_html_select($name . '_month', $month, $month_opt);
        $day_sel   = $this->build_html_select($name . '_day', $day, $day_opt);
        $hour_sel  = $this->build_html_select($name . '_hour', $hour, $hour_opt);
        $min_sel   = $this->build_html_select($name . '_min', $min, $sixty_opt);
        $sec_sel   = $this->build_html_select($name . '_sec', $sec, $sixty_opt);

        $format = preg_replace('/%y/i', $year_sel, $format);
        $format = preg_replace('/%m/i', $month_sel, $format);
        $format = preg_replace('/%d/i', $day_sel, $format);
        $format = preg_replace('/%h/i', $hour_sel, $format);
        $format = preg_replace('/%i/i', $min_sel, $format);
        $format = preg_replace('/%s/i', $sec_sel, $format);

        return $format;
    }

    public function get_unixtime_form_select_time_from_post(&$post, $name)
    {
        $year  = 0;
        $month = 0;
        $day   = 0;
        $hour  = 0;
        $min   = 0;
        $sec   = 0;

        if (isset($post[$name . '_year'])) {
            $year = (int)$post[$name . '_year'];
        }

        if (isset($post[$name . '_month'])) {
            $month = (int)$post[$name . '_month'];
        }

        if (isset($post[$name . '_day'])) {
            $day = (int)$post[$name . '_day'];
        }

        if (isset($post[$name . '_hour'])) {
            $hour = (int)$post[$name . '_hour'];
        }

        if (isset($post[$name . '_min'])) {
            $min = (int)$post[$name . '_min'];
        }

        if (isset($post[$name . '_sec'])) {
            $sec = (int)$post[$name . '_sec'];
        }

        $time = mktime($hour, $min, $sec, $month, $day, $year);
        return $time;
    }

    public function get_unixtime_form_select_time_with_flag_from_post(&$post, $name, $default = 0)
    {
        $name_flag = $name . '_flag';

        $val = $default;
        if (isset($post[$name_flag]) && $post[$name_flag]) {
            $val = $this->get_unixtime_form_select_time_from_post($post, $name);
        }
        return (int)$val;
    }

    public function build_form_dhtml_textarea($name, $value, $rows = 5, $cols = 50, $hiddentext = 'xoopsHiddenText')
    {
        $ele  = new XoopsFormDhtmlTextArea('', $name, $value, $rows, $cols, $hiddentext);
        $text = $ele->render();
        return $text;
    }

    //---------------------------------------------------------
    // button
    //---------------------------------------------------------
    public function build_form_submit($name = '', $value = '', $extra = '')
    {
        $text = $this->build_form_button_submit($name, $value, $extra);
        return $text;
    }

    public function build_form_button_submit($name = '', $value = '', $extra = '')
    {
        if (empty($name)) {
            $name = $this->_BUTTON_SUBMIT_NAME; // 'submit'
        }

        if (empty($value)) {
            $value = $this->_LANG_BUTTON_SUBMIT_VALUE;
        }

        $text = $this->build_html_input_submit($name, $value, $extra = '');
        return $text;
    }

    public function build_form_button_cancel($name = '', $value = '')
    {
        if (empty($name)) {
            $name = $this->_BUTTON_CANCEL_NAME; // 'cancel'
        }

        if (empty($value)) {
            $value = $this->_LANG_BUTTON_CANCEL_VALUE;
        }

        $text = $this->build_html_input_button_cancel($name, $value);
        return $text;
    }

    public function build_form_button_location($name, $value, $url)
    {
        if (empty($name)) {
            $name = $this->_BUTTON_LOCATION_NAME;   // 'location'
        }

        if (empty($value)) {
            $value = $this->_LANG_BUTTON_LOCATION_VALUE;
        }

        $text = $this->build_html_input_button_location($name, $value, $url);
        return $text;
    }

    public function build_form_button_close($name, $value)
    {
        if (empty($name)) {
            $name = $this->_BUTTON_CLOSE_NAME;  // 'close'
        }
        if (empty($value)) {
            $value = $this->_LANG_BUTTON_CLOSE_VALUE;
        }
        $text = $this->build_html_input_button_close($name, $value);
        return $text;
    }

    public function build_form_button_close_style($name = '', $value = '', $style = 'text-align:center;')
    {
        $text = $this->build_form_button_close($name, $value);
        if ($style) {
            $text = $this->build_html_div_tag_with_style($text, $style);
        }
        return $text;
    }

    //---------------------------------------------------------
    // substute
    //---------------------------------------------------------
    public function substute_blank($value, $default = '&nbsp;')
    {
        if ($value == '') {
            $value = $default;
        }
        return $value;
    }

    public function substute_http($value, $default = 'http://')
    {
        if ($value == '') {
            $value = $default;
        }
        return $value;
    }


    //================================================================
    // build object element
    //================================================================
    public function set_obj(&$obj)
    {
        $this->_obj = $obj;
    }

    public function get_obj_var($key, $format = 's')
    {
        $val = $this->_obj->getVar($key, $format);
        return $val;
    }

    public function build_obj_table_label($caption, $key)
    {
        $value = $this->_obj->getVar($key, 's');
        $text  = $this->build_form_table_line($caption, $value);
        return $text;
    }

    public function build_obj_table_text($caption, $key, $size = '', $maxlength = '')
    {
        $ele  = $this->build_obj_text($key, $size, $maxlength);
        $text = $this->build_form_table_line($caption, $ele);
        return $text;
    }

    public function build_obj_table_textarea($caption, $key, $rows = '', $cols = '')
    {
        $ele  = $this->build_obj_textarea($key, $rows, $cols);
        $text = $this->build_form_table_line($caption, $ele);
        return $text;
    }

    public function build_obj_table_radio_yesno($caption, $key)
    {
        $ele  = $this->build_obj_radio_yesno($key);
        $text = $this->build_form_table_line($caption, $ele);
        return $text;
    }

    public function build_obj_text($key, $size = '', $maxlength = '')
    {
        if (empty($size)) {
            $size = $this->_SIZE;
        }
        if (empty($maxlength)) {
            $maxlength = $this->_MAXLENGTH;
        }
        $value = $this->_obj->getVar($key, 's');
        $text  = $this->build_html_input_text($key, $value, $size, $maxlength);
        return $text;
    }

    public function build_obj_textarea($key, $rows = '', $cols = '')
    {
        if (empty($rows)) {
            $rows = $this->_ROWS;
        }
        if (empty($cols)) {
            $cols = $this->_COLS;
        }
        $value = $this->_obj->getVar($key, 's');
        $text  = $this->build_html_textarea($key, $value, $rows, $cols);
        return $text;
    }

    public function build_obj_radio_yesno($key)
    {
        $value = $this->_obj->getVar($key, 's');
        $text  = $this->build_form_radio_yesno($key, $value);
        return $text;
    }

    //================================================================
    // build edit element
    //================================================================
    public function build_edit_textarea_urllist($name, $urllist)
    {
        $url_s = $this->build_edit_url($urllist);
        $text  = $this->build_html_textarea($name, $url_s);
        return $text;
    }

    public function build_edit_url_with_visit($name, $url, $size = 70, $maxlength = 255, $extra = '', $del = '')
    {
        $url_s = $this->build_edit_url($url);

        if ($maxlength > 0) {
            $text = $this->build_html_input_text($name, $url_s, $size, $maxlength, $extra);
        } else {
            $text = $this->build_html_input_text_without_maxlenghth($name, $url_s, $size, $extra);
        }

        if ($url && ($url != 'http://') && ($url != 'https://')) {
            $text .= ' ';
            $text .= $del;
            $text .= $this->build_edit_visit($url);
        }

        return $text;
    }

    public function build_edit_url($url, $default = 'http://')
    {
        if ($url) {
            $text = $url;
        } else {
            $text = $default;
        }

        $text = $this->sanitize_url($text, true, false);
        return $text;
    }

    public function build_edit_visit($url, $target = '_blank')
    {
        $ret = $this->build_html_a_href_name($url, $this->get_edit_visit_mark(), $target);
        return $ret;
    }

    public function get_edit_visit_mark()
    {
        $mark = ' [' . $this->_LANG_VISIT . '] ';
        return $mark;
    }

    //================================================================
    // build data element
    //================================================================
    //---------------------------------------------------------
    // build data form
    //---------------------------------------------------------
    public function build_data_table_label_hidden($caption, $key)
    {
        $value = $this->get_data_value($key);
        $ele   = $value . ' ';
        $ele .= $this->build_html_input_hidden($key, $value);
        $text = $this->build_form_table_line($caption, $ele);
        return $text;
    }

    public function build_data_table_label($caption, $key)
    {
        $ele  = $this->get_data_value($key);
        $text = $this->build_form_table_line($caption, $ele);
        return $text;
    }

    public function build_data_table_text($caption, $key)
    {
        $ele  = $this->build_data_text($key);
        $text = $this->build_form_table_line($caption, $ele);
        return $text;
    }

    public function build_data_table_textarea($caption, $key)
    {
        $ele = $this->build_data_textarea($key);
        return $this->build_form_table_line($caption, $ele);
        return $text;
    }

    public function build_data_table_radio_yesno($caption, $key)
    {
        $ele  = $this->build_data_radio_yesno($key);
        $text = $this->build_form_table_line($caption, $ele);
        return $text;
    }

    public function build_data_text($key)
    {
        $value = $this->get_data_value($key);
        $text  = $this->build_html_input_text($key, $value, $this->_SIZE, $this->_MAXLENGTH);
        return $text;
    }

    public function build_data_textarea($key)
    {
        $value = $this->get_data_value($key);
        $text  = $this->build_html_textarea($key, $value, $this->_ROWS, $this->_COLS);
        return $text;
    }

    public function build_data_radio_yesno($key)
    {
        $value = $this->get_data_value($key);
        $text  = $this->build_form_radio_yesno($key, $value, $this->_ROWS, $this->_COLS);
        return $text;
    }

    public function build_data_short($key, $max = 100)
    {
        $value = '';

        if ($this->isset_data_value($key)) {
            $value = $this->get_data_value($key);

            if ($max) {
                $value = $this->shorten_text($value, $max);
            }

            $value = wordwrap($value);
            $value = '<pre>' . $value . '</pre>' . "\n";
        }

        return $value;
    }

    public function get_data_value($key)
    {
        $val = false;
        if (isset($this->_datas[$key])) {
            $val = $this->_datas[$key];
        }
        return $val;
    }

    public function isset_data_value($key)
    {
        if (isset($this->_datas[$key])) {
            return true;
        }
        return false;
    }

    //---------------------------------------------------------
    // show for manage class
    //---------------------------------------------------------
    public function _show_add(&$obj, $extra = null)
    {
        $this->_show($obj, $extra, HAPPY_LINUX_MODE_ADD);
    }

    public function _show_mod(&$obj, $extra = null)
    {
        $this->_show($obj, $extra, HAPPY_LINUX_MODE_MOD);
    }

    public function _show_add_preview(&$obj, $extra = null)
    {
        $this->_show($obj, $extra, HAPPY_LINUX_MODE_ADD_PREVIEW);
    }

    public function _show_mod_preview(&$obj, $extra = null)
    {
        $this->_show($obj, $extra, HAPPY_LINUX_MODE_MOD_PREVIEW);
    }

    public function _show_del_preview(&$obj, $extra = null)
    {
        $this->_show($obj, $extra, HAPPY_LINUX_MODE_DEL_PREVIEW);
    }

    public function _show($obj, $extra = null, $mode = 0)
    {
        // dummy
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------
    public function build_form_caption($title, $desc = '')
    {
        $text = '';

        if ($title) {
            if ($this->_font_caption_title) {
                $text .= $this->_font_caption_title . $title . $this->_font_caption_title_end;
            } else {
                $text .= $title;
            }
        }

        if ($desc) {
            if ($title) {
                $text .= "<br /><br />\n";
            }

            if ($this->_font_caption_desc) {
                $text .= $this->_font_caption_desc . $desc . $this->_font_caption_desc_end;
            } else {
                $text .= $desc;
            }
        }

        return $text;
    }


    //================================================================
    // build XOOPS element
    //================================================================
    //---------------------------------------------------------
    // XOOPS JavaScript
    //---------------------------------------------------------
    public function build_xoops_js_checkall($form_name)
    {
        $value   = '';
        $checked = '';

        $checkall       = $form_name . '_checkall';
        $xoops_checkall = "xoopsCheckAll('" . $form_name . "', '" . $checkall . "')";
        $extra          = ' onclick="' . $xoops_checkall . '" ';
        $text           = $this->build_html_input_checkbox($checkall, $value, $checked, $extra);
        return $text;
    }

    public function build_xoops_js_checkbox($form_name, $value)
    {
        $name = $form_name . '_id[]';
        $text = $this->build_html_input_checkbox($name, $value);
        return $text;
    }

    public function build_form_js_checkall()
    {
        $text = $this->build_xoops_js_checkall($this->_FORM_NAME);
        return $text;
    }

    public function build_form_js_checkbox($value)
    {
        $text = $this->build_xoops_js_checkbox($this->_FORM_NAME, $value);
        return $text;
    }

    //=========================================================
    // token class
    //=========================================================
    // return html format
    public function build_token()
    {
        switch ($this->_SEL_TOKEN_CLASS) {
            case 'xoops':
                return $this->build_xoops_token();

            case 'gticket':
            default:
                return $this->build_gticket_token();
        }
    }

    // return ( name, value )
    public function &get_token_pair()
    {
        return $this->get_token();
    }

    public function &get_token()
    {
        switch ($this->_SEL_TOKEN_CLASS) {
            case 'xoops':
                return $this->get_xoops_token();

            case 'gticket':
            default:
                return $this->get_gticket_token();
        }
        return null;    // dummy
    }

    public function check_token($allow_repost = false)
    {
        if ($this->_DEBUG_CHECK_TOKEN) {
            switch ($this->_SEL_TOKEN_CLASS) {
                case 'xoops':
                    return $this->check_xoops_token();

                case 'gticket':
                default:
                    return $this->check_gticket_token($allow_repost);
            }
        }
        return true;
    }

    //---------------------------------------------------------
    // XoopsMultiTokenHandler
    //---------------------------------------------------------
    public function build_xoops_token()
    {
        if (class_exists('XoopsMultiTokenHandler')) {
            list($token_name, $token_value) = $this->get_xoops_token($this->_TOKEN_NAME);
            return $this->build_html_input_hidden($token_name, $token_value);
        }
        return '';
    }

    // return ( name, value )
    public function &get_xoops_token()
    {
        if (class_exists('XoopsMultiTokenHandler')) {
            $token =& XoopsMultiTokenHandler::quickCreate($this->_TOKEN_NAME);
            $name  = $token->getTokenName();
            $value = $token->getTokenValue();
        } else {
            $name  = 'token';
            $value = 0;
        }
        $arr = array($name, $value);
        return $arr;
    }

    public function check_xoops_token()
    {
        if (class_exists('XoopsMultiTokenHandler')) {
            if (!XoopsMultiTokenHandler::quickValidate($this->_TOKEN_NAME)) {
                return false;
            }
        }

        // always true, if not exists class
        return true;
    }

    //---------------------------------------------------------
    // XoopsGTicket
    //---------------------------------------------------------
    public function build_gticket_token()
    {
        // get same token on one page, becuase max ticket is 10
        if ($this->_cached_token) {
            return $this->_cached_token;
        }

        global $xoopsGTicket;
        $text = '';
        if (is_object($xoopsGTicket)) {
            $salt                = $this->_FORM_NAME;
            $text                = $xoopsGTicket->getTicketHtml($salt) . "\n";
            $this->_cached_token = $text;
        }
        return $text;
    }

    // return ( name, value )
    public function &get_gticket_token()
    {
        global $xoopsGTicket;
        $name = 'XOOPS_G_TICKET';
        if (is_object($xoopsGTicket)) {
            $salt = $this->_FORM_NAME;
            $val  = $xoopsGTicket->issue($salt);
        } else {
            $val = 0;
        }
        $arr = array($name, $val);
        return $arr;
    }

    public function check_gticket_token($allow_repost = false)
    {
        global $xoopsGTicket;
        if (is_object($xoopsGTicket)) {
            if (!$xoopsGTicket->check(true, '', $allow_repost)) {
                $this->_token_error = $xoopsGTicket->getErrors();
                return false;
            }
        }
        return true;
    }

    //---------------------------------------------------------
    // set parameter
    //---------------------------------------------------------
    public function set_form_name($value)
    {
        $this->_FORM_NAME = $value;
    }

    public function set_action($value)
    {
        $this->_ACTION = $value;
    }

    public function set_token_name($value)
    {
        $this->_TOKEN_NAME = $value;
    }

    public function set_form_method($value)
    {
        $this->_FORM_METHOD = $value;
    }

    public function set_button_sumbit_name($value)
    {
        $this->_BUTTON_SUBMIT_NAME = $value;
    }

    public function set_button_cancel_name($value)
    {
        $this->_BUTTON_CANCEL_NAME = $value;
    }

    public function set_button_location_name($value)
    {
        $this->_BUTTON_LOCATION_NAME = $value;
    }

    public function set_submit_value($value)
    {
        $this->set_button_submit_value($value);
    }

    public function set_button_submit_value($value)
    {
        $this->_LANG_BUTTON_SUBMIT_VALUE = $value;
    }

    public function set_button_cancel_value($value)
    {
        $this->_LANG_BUTTON_CANCEL_VALUE = $value;
    }

    public function set_button_location_value($value)
    {
        $this->_LANG_BUTTON_LOCATION_VALUE = $value;
    }

    public function set_form_title($value)
    {
        $this->_LANG_FORM_TITLE = $value;
    }

    public function set_op_name($value)
    {
        $this->_OP_NAME = $value;
    }

    public function set_op_value($value)
    {
        $this->_op_value = $value;
    }

    public function set_title_class($value)
    {
        $this->_table_title_class = $value;
    }

    public function set_ele_class($value)
    {
        $this->_table_ele_class = $value;
    }

    public function set_size($value)
    {
        $this->_SIZE = (int)$value;
    }

    public function set_maxlength($value)
    {
        $this->_MAXLENGTH = (int)$value;
    }

    public function set_rows($value)
    {
        $this->_ROWS = (int)$value;
    }

    public function set_cols($value)
    {
        $this->_text_COLS = (int)$value;
    }

    public function set_datas($value)
    {
        $this->_datas = $value;
    }

    public function set_sel_token_class($value)
    {
        $this->_SEL_TOKEN_CLASS = $value;
    }

    public function set_debug_check_token($val)
    {
        $this->_DEBUG_CHECK_TOKEN = (bool)$val;
    }

    public function _set_style_error($val)
    {
        $this->_STYLE_ERROR = $val;
    }

    //---------------------------------------------------------
    // get parameter
    //---------------------------------------------------------
    public function get_form_name()
    {
        return $this->_FORM_NAME;
    }

    public function get_form_title()
    {
        return $this->_LANG_FORM_TITLE;
    }

    public function get_button_submit_value()
    {
        return $this->_LANG_BUTTON_SUBMIT_VALUE;
    }

    public function get_token_error($format = '')
    {
        if ($format) {
            return $this->build_html_error_with_style($this->_token_error);
        } else {
            return $this->_token_error;
        }
    }

    public function print_xoops_token_error()
    {
        xoops_error('Token Error');
        echo "<br />\n";
        echo $this->get_token_error(1);
        echo "<br />\n";
    }

    // --- class end ---
}
