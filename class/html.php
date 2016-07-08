<?php
// $Id: html.php,v 1.13 2008/02/28 13:12:48 ohwada Exp $

// 2008-02-24 K.OHWADA
// build_html_input_text_without_maxlenghth()

// 2007-09-10 K.OHWADA
// build_html_link_stylesheet()

// 2007-08-01 K.OHWADA
// base on W3C

// 2007-06-23 K.OHWADA
// build_html_input_button_close()
// build_html_input_checkbox_select_multi()
// change build_html_input_radio_select()

// 2007-05-12 K.OHWADA
// change build_html_menu_table()
// debug in build_html_input_radio_select()

// 2007-02-20 K.OHWADA
// build_html_comment()

// 2006-12-10 K.OHWADA
// small change build_html_option_tag_begin()

// 2006-09-20 K.OHWADA
// this is new file
// divid from happy_linux_form

//=========================================================
// Happy Linux Framework Module
// 2006-09-01 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_html
//=========================================================
class happy_linux_html extends happy_linux_strings
{
    public $_DEBUG_PRINT = false;

    // base on W3C
    public $_SELECTED = 'selected="selected"';
    public $_CHECKED  = 'checked="checked"';

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
            $instance = new happy_linux_html();
        }
        return $instance;
    }

    //================================================================
    // build HTML tag
    //================================================================
    //---------------------------------------------------------
    // form tag
    //---------------------------------------------------------
    public function build_html_form_tag_begin($name = '', $action = '', $enctype = '', $method = 'post', $extra = '')
    {
        $text = '<form ';

        if ($name) {
            $text .= 'name="' . $name . '" ';
            $text .= 'id="' . $name . '" ';
        }

        if ($action) {
            $text .= 'action="' . $action . '" ';
        }

        if ($enctype) {
            $text .= 'enctype="' . $enctype . '" ';
        }

        if ($method) {
            $text .= 'method="' . $method . '" ';
        }

        $text .= $extra . " >\n";
        return $text;
    }

    public function build_html_form_tag_end()
    {
        $text = "</form>\n";
        return $text;
    }

    //---------------------------------------------------------
    // input tag
    //---------------------------------------------------------
    public function build_html_input_text($name, $value, $size = 50, $maxlength = 255, $extra = '')
    {
        $text = '<input type="text" name="' . $name . '" id="' . $name . '" value="' . $value . '" size="' . $size . '" maxlength="' . $maxlength . '" ' . $extra . ' />' . "\n";
        return $text;
    }

    public function build_html_input_text_without_maxlenghth($name, $value, $size = 50, $extra = '')
    {
        $text = '<input type="text" name="' . $name . '" id="' . $name . '" value="' . $value . '" size="' . $size . '" ' . $extra . ' />' . "\n";
        return $text;
    }

    public function build_html_input_password($name, $value, $size = 50, $maxlength = 255, $extra = '')
    {
        $text = '<input type="password" name="' . $name . '" id="' . $name . '" value="' . $value . '" size="' . $size . '" maxlength="' . $maxlength . '" ' . $extra . ' />' . "\n";
        return $text;
    }

    public function build_html_input_file($name, $value, $extra = '')
    {
        $text = '<input type="file" name="' . $name . '" id="' . $name . '" value="' . $value . '" ' . $extra . ' />' . "\n";
        return $text;
    }

    public function build_html_input_hidden_max_file_size($value)
    {
        $text = '<input type="hidden" name="MAX_FILE_SIZE" value="' . $value . '" />' . "\n";
        return $text;
    }

    public function build_html_input_hidden($name, $value)
    {
        $text = '<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />' . "\n";
        return $text;
    }

    public function build_html_input_submit($name, $value, $extra = '')
    {
        $text = '<input type="submit" name="' . $name . '" id="' . $name . '" value="' . $value . '" ' . $extra . ' />' . "\n";
        return $text;
    }

    public function build_html_input_button($name, $value, $extra = '')
    {
        $text = '<input type="button" name="' . $name . '" id="' . $name . '" value="' . $value . '" ' . $extra . ' />' . "\n";
        return $text;
    }

    public function build_html_input_button_cancel($name, $value)
    {
        $extra = ' onclick="javascript:history.go(-1)" ';
        $text  = $this->build_html_input_button($name, $value, $extra);
        return $text;
    }

    public function build_html_input_button_close($name, $value)
    {
        $extra = ' onclick="javascript:window.close()" ';
        $text  = $this->build_html_input_button($name, $value, $extra);
        return $text;
    }

    public function build_html_input_button_location($name, $value, $url)
    {
        $location = "window.location='" . $url . "'";
        $extra    = ' onclick="' . $location . '" ';
        $text     = $this->build_html_input_button($name, $value, $extra);
        return $text;
    }

    //---------------------------------------------------------
    // input radio tag
    //---------------------------------------------------------
    public function build_html_input_radio_select($name, $value, $options, $del = '', $flag_sani = true)
    {
        if ($this->_DEBUG_PRINT) {
            echo "build_html_input_radio_select($name, $value, $options, $del) <br />";
            print_r($options);
            echo "<br />\n";
        }

        $text = '';

        foreach ($options as $opt_name => $opt_val) {
            $opt_val_show  = $opt_val;
            $opt_name_show = $opt_name;
            if ($flag_sani) {
                $opt_val_show  = $this->sanitize_text($opt_val);
                $opt_name_show = $this->sanitize_text($opt_name);
            }

            $checked = $this->build_html_checked($value, $opt_val);

            $text .= $this->build_html_input_radio($name, $opt_val_show, $checked);
            $text .= ' ';
            $text .= $opt_name_show;
            $text .= ' ';
            $text .= $del;
        }

        return $text;
    }

    // base on W3C: not use id
    public function build_html_input_radio($name, $value, $checked = '', $extra = '')
    {
        $text = '<input type="radio" name="' . $name . '" value="' . $value . '" ' . $checked . ' ' . $extra . ' />' . "\n";
        return $text;
    }

    public function build_html_checked($val1, $val2)
    {
        if (isset($val1) && ($val1 == $val2)) {
            return $this->_CHECKED;
        }
        return '';
    }

    public function build_html_checked_multi($val_arr1, $val2)
    {
        if (is_array($val_arr1)) {
            if (count($val_arr1) && in_array($val2, $val_arr1)) {
                return $this->_CHECKED;
            }
            return '';
        }

        return $this->build_html_checked($val_arr1, $val2);
    }

    //---------------------------------------------------------
    // input checkbox tag
    //---------------------------------------------------------
    public function build_html_input_checkbox_select($name, $value, $options, $del = '')
    {
        if ($this->_DEBUG_PRINT) {
            echo "build_html_input_checkbox_select($name, $value, $options, $del) <br />";
            print_r($options);
            echo "<br />\n";
        }

        $text = '';

        foreach ($options as $opt_name => $opt_val) {
            $checked       = $this->build_html_checked($value, $opt_val);
            $opt_val_show  = $this->sanitize_text($opt_val);
            $opt_name_show = $this->sanitize_text($opt_name);
            $text .= $this->build_html_input_checkbox($name, $opt_val_show, $checked);
            $text .= ' ';
            $text .= $opt_name_show;
            $text .= ' ';
            $text .= $del;
        }

        return $text;
    }

    public function build_html_input_checkbox_select_multi($name, $value_arr, $options, $del = '')
    {
        if ($this->_DEBUG_PRINT) {
            echo "build_html_input_checkbox_select_multi($name, $value, $options, $del) <br />";
            print_r($value_arr);
            print_r($options);
            echo "<br />\n";
        }

        $text = '';

        if (!is_array($value_arr)) {
            $value_arr = array($value_arr);
        }

        foreach ($options as $opt_name => $opt_val) {
            $checked       = $this->build_html_checked_multi($value_arr, $opt_val);
            $opt_val_show  = $this->sanitize_text($opt_val);
            $opt_name_show = $this->sanitize_text($opt_name);
            $text .= $this->build_html_input_checkbox($name, $opt_val_show, $checked);
            $text .= ' ';
            $text .= $opt_name_show;
            $text .= ' ';
            $text .= $del;
        }

        return $text;
    }

    public function build_html_input_checkbox($name, $value, $checked = '', $extra = '')
    {
        $text = '<input type="checkbox" name="' . $name . '" id="' . $name . '" value="' . $value . '" ' . $checked . ' ' . $extra . ' />' . "\n";
        return $text;
    }

    //---------------------------------------------------------
    // textarea tag
    //---------------------------------------------------------
    public function build_html_textarea($name, $value, $rows = 5, $cols = 50, $extra = '')
    {
        $text = $this->build_html_textarea_tag_begin($name, $rows, $cols);
        $text .= $value;
        $text .= $this->build_html_textarea_tag_end();
        return $text;
    }

    public function build_html_textarea_tag_begin($name, $rows = 5, $cols = 50, $extra = '')
    {
        $text = '<textarea name="' . $name . '" id="' . $name . '" rows="' . $rows . '" cols="' . $cols . '" ' . $extra . ' >' . "\n";
        return $text;
    }

    public function build_html_textarea_tag_end()
    {
        $text = "</textarea>\n";
        return $text;
    }

    //---------------------------------------------------------
    // select tag
    //---------------------------------------------------------
    public function build_html_select($name, $value, $options, $none = 0, $none_name = '---', $none_value = '')
    {
        $text = $this->build_html_select_tag_begin($name);

        if ($none) {
            $text .= $this->build_html_option($none_name, $none_value);
        }

        foreach ($options as $opt_name => $opt_val) {
            $text .= $this->build_html_option_selected($opt_name, $opt_val, array($value));
        }

        $text .= $this->build_html_select_tag_end();
        return $text;
    }

    public function build_html_select_multiple($name, $value_arr, $opt_arr, $size = 4, $none = 0, $none_name = '---', $none_value = '')
    {
        $text = $this->build_html_select_tag_begin($name, $size, true);

        if ($none) {
            $text .= $this->build_html_option($none_name, $none_value);
        }

        foreach ($opt_arr as $opt_name => $opt_val) {
            $text .= $this->build_html_option_selected($opt_name, $opt_val, $value_arr);
        }

        $text .= $this->build_html_select_tag_end();
        return $text;
    }

    public function build_html_select_tag_begin($name, $size = '', $flag_multiple = false, $extra = '')
    {
        $text = '<select ';

        if ($flag_multiple) {
            $text .= 'name="' . $name . '[]" ';
        } else {
            $text .= 'name="' . $name . '" ';
        }

        $text .= 'id="' . $name . '" ';

        if ($size) {
            $text .= 'size="' . $size . '" ';
        }

        if ($flag_multiple) {
            $text .= 'multiple ';
        }

        $text .= $extra . " >\n";
        return $text;
    }

    public function build_html_select_tag_end()
    {
        $text = "</select>\n";
        return $text;
    }

    //---------------------------------------------------------
    // option tag
    //---------------------------------------------------------
    public function build_html_option_selected($opt_name, $opt_val, $value_arr)
    {
        $flag = false;
        if (is_array($value_arr) && (count($value_arr) > 0)) {
            foreach ($value_arr as $value) {
                if ($value == $opt_val) {
                    $flag = true;
                }
            }
        }
        $text = $this->build_html_option($opt_name, $opt_val, $flag);
        return $text;
    }

    public function build_html_option($name, $value, $flag_selected = false)
    {
        $text = $this->build_html_option_tag_begin($value, $flag_selected);
        $text .= $name;
        $text .= $this->build_html_option_tag_end();
        return $text;
    }

    public function build_html_option_tag_begin($value, $flag_selected = false)
    {
        $selected = $this->build_html_selected_by_flag($flag_selected);

        $text = '<option value="' . $value . '" ' . $selected . ' > ';
        return $text;
    }

    public function build_html_option_tag_end()
    {
        $text = "</option>\n";
        return $text;
    }

    public function build_html_selected($val1, $val2)
    {
        if (isset($val1) && ($val1 == $val2)) {
            return $this->_SELECTED;
        }
        return '';
    }

    public function build_html_selected_by_flag($flag)
    {
        if ($flag) {
            return $this->_SELECTED;
        }
        return '';
    }

    //---------------------------------------------------------
    // table tag
    //---------------------------------------------------------
    public function build_html_table_tag_begin($width = '', $height = '', $cellpadding = '', $cellspacing = '', $class = '')
    {
        $text = '<table ';

        if ($width) {
            $text .= 'width="' . $width . '" ';
        }

        if ($height) {
            $text .= 'height="' . $height . '" ';
        }

        if ($cellpadding) {
            $text .= 'cellpadding="' . $cellpadding . '" ';
        }

        if ($cellspacing) {
            $text .= 'cellspacing="' . $cellspacing . '" ';
        }

        if ($class) {
            $text .= 'class="' . $class . '" ';
        }

        $text .= " >\n";
        return $text;
    }

    public function build_html_table_tag_class($class = '')
    {
        $text = '<table ';

        if ($class) {
            $text .= 'class="' . $class . '" ';
        }

        $text .= " >\n";
        return $text;
    }

    public function build_html_table_tag_end()
    {
        $text = "</table>\n";
        return $text;
    }

    //---------------------------------------------------------
    // tr tag
    //---------------------------------------------------------
    public function build_html_tr_tag_begin($align = '', $valign = '', $class = '')
    {
        $text = '<tr ';

        if ($align) {
            $text .= 'align="' . $align . '" ';
        }

        if ($valign) {
            $text .= 'valign="' . $valign . '" ';
        }

        if ($class) {
            $text .= 'class="' . $class . '" ';
        }

        $text .= " >\n";
        return $text;
    }

    public function build_html_tr_tag_class($class = '')
    {
        $text = '<tr ';

        if ($class) {
            $text .= 'class="' . $class . '" ';
        }

        $text .= " >\n";
        return $text;
    }

    public function build_html_tr_tag_end()
    {
        $text = "</tr>\n";
        return $text;
    }

    //---------------------------------------------------------
    // th tag
    //---------------------------------------------------------
    public function build_html_th_tag_begin($align = '', $valign = '', $colspan = '', $rowspan = '', $class = '')
    {
        $text = '<th ';

        if ($align) {
            $text .= 'align="' . $align . '" ';
        }

        if ($valign) {
            $text .= 'valign="' . $valign . '" ';
        }

        if ($colspan) {
            $text .= 'colspan="' . $colspan . '" ';
        }

        if ($rowspan) {
            $text .= 'rowspan="' . $rowspan . '" ';
        }

        if ($class) {
            $text .= 'class="' . $class . '" ';
        }

        $text .= " >\n";
        return $text;
    }

    public function build_html_th_tag_class($class = '')
    {
        $text = '<th ';

        if ($class) {
            $text .= 'class="' . $class . '" ';
        }

        $text .= " >\n";
        return $text;
    }

    public function build_html_th_tag_end()
    {
        $text = "</th>\n";
        return $text;
    }

    //---------------------------------------------------------
    // td tag
    //---------------------------------------------------------
    public function build_html_td_tag_begin($align = '', $valign = '', $colspan = '', $rowspan = '', $class = '')
    {
        $text = '<td ';

        if ($align) {
            $text .= 'align="' . $align . '" ';
        }

        if ($valign) {
            $text .= 'valign="' . $valign . '" ';
        }

        if ($colspan) {
            $text .= 'colspan="' . $colspan . '" ';
        }

        if ($rowspan) {
            $text .= 'rowspan="' . $rowspan . '" ';
        }

        if ($class) {
            $text .= 'class="' . $class . '" ';
        }

        $text .= " >\n";
        return $text;
    }

    public function build_html_td_tag_class($class = '')
    {
        $text = '<td ';

        if ($class) {
            $text .= 'class="' . $class . '" ';
        }

        $text .= " >\n";
        return $text;
    }

    public function build_html_td_tag_end()
    {
        $text = "</td>\n";
        return $text;
    }

    //---------------------------------------------------------
    // img tag
    //---------------------------------------------------------
    public function build_html_img_tag($src, $width = 0, $height = 0, $border = 0, $alt = 'image')
    {
        if (empty($src) || ($src == 'http://') || ($src == 'https://')) {
            return false;
        }

        // sanitize
        $width  = (int)$width;
        $height = (int)$height;
        $border = (int)$border;
        $src    = $this->sanitize_url($src);
        $alt    = $this->sanitize_text($alt);

        $text = '<img ';
        $text .= 'src="' . $src . '" ';

        if ($width) {
            $text .= 'width="' . $width . '" ';
        }

        if ($height) {
            $text .= 'height="' . $height . '" ';
        }

        $text .= 'border="' . $border . '" ';
        $text .= 'alt="' . $alt . '" ';
        $text .= " />\n";

        return $text;
    }

    //---------------------------------------------------------
    // a tag
    //---------------------------------------------------------
    public function build_html_a_tag_begin($href, $target = '')
    {
        $text = '<a ';
        $text .= 'href="' . $href . '" ';

        if ($target) {
            $text .= 'target="' . $target . '" ';
        }

        $text .= " >\n";
        return $text;
    }

    public function build_html_a_tag_end()
    {
        $text = "</a>\n";
        return $text;
    }

    public function build_html_a_href_name($url, $name = '', $target = '', $flag_name_sanitize = true)
    {
        // sanitize
        $url    = $this->sanitize_url($url);
        $target = $this->sanitize_text($target);

        if ($flag_name_sanitize) {
            $name = $this->sanitize_text($name);
        }

        if ($url && $name) {
            $text = $this->build_html_a_tag_begin($url, $target);
            $text .= $name;
            $text .= $this->build_html_a_tag_end();
        } elseif ($url) {
            $text = $this->build_html_a_tag_begin($url, $target);
            $text .= $url;
            $text .= $this->build_html_a_tag_end();
        } elseif ($name) {
            $text = $name;
        } else {
            $text = '';
        }

        return $text;
    }

    public function build_html_a_href_email($url, $name = '', $target = '', $flag_name_sanitize = true)
    {
        $mailto = 'mailto:' . $url;
        $text   = $this->build_html_a_href_name($mailto, $name, $target, $flag_name_sanitize);
        return $text;
    }

    //-------------------------------------------------------------------
    // span tag
    //-------------------------------------------------------------------
    public function build_html_span_tag_with_style($val, $style)
    {
        if ($style) {
            $text = '<span style="' . $style . '" >';
            $text .= $val;
            $text .= '</span>';
        } else {
            $text = $val;
        }
        return $text;
    }

    public function build_html_span_tag_with_class($val, $class)
    {
        if ($class) {
            $text = '<span class="' . $class . '" >';
            $text .= $val;
            $text .= '</span>';
        } else {
            $text = $val;
        }
        return $text;
    }

    //-------------------------------------------------------------------
    // div tag
    //-------------------------------------------------------------------
    public function build_html_div_tag_with_style($val, $style)
    {
        if ($style) {
            $text = '<div style="' . $style . '" >';
            $text .= $val;
            $text .= "</div>\n";
        } else {
            $text = $val;
        }
        return $text;
    }

    public function build_html_div_tag_with_class($val, $class)
    {
        if ($class) {
            $text = '<div class="' . $class . '" >';
            $text .= $val;
            $text .= "</div>\n";
        } else {
            $text = $val;
        }
        return $text;
    }

    //-------------------------------------------------------------------
    // style
    //-------------------------------------------------------------------
    public function build_html_span_style($color = '', $background_color = '', $font_weight = '')
    {
        $text = '';

        if ($color) {
            $text .= 'color: ' . $color . '; ';
        }

        if ($background_color) {
            $text .= 'background-color: ' . $background_color . '; ';
        }

        if ($font_weight) {
            $text .= 'font-weight: ' . $font_weight . '; ';
        }

        return $text;
    }

    //-------------------------------------------------------------------
    // highlight
    //-------------------------------------------------------------------
    public function build_html_red($value, $background_color = '', $font_weight = '')
    {
        $text = $this->build_html_highlight($value, '#ff0000', $background_color, $font_weight);
        return $text;
    }

    public function build_html_green($value, $background_color = '', $font_weight = '')
    {
        $text = $this->build_html_highlight($value, '#00ff00', $background_color, $font_weight);
        return $text;
    }

    public function build_html_blue($value, $background_color = '', $font_weight = '')
    {
        $text = $this->build_html_highlight($value, '#0000ff', $background_color, $font_weight);
        return $text;
    }

    public function build_html_highlight($value, $color = '#ff0000', $background_color = '', $font_weight = 'bold')
    {
        $style = $this->build_html_span_style($color, $background_color, $font_weight);
        $text  = $this->build_html_span_tag_with_style($value, $style);
        return $text;
    }

    public function build_html_highlight_number($num, $limit_under = 0, $color = '#ff0000', $background_color = '', $font_weight = 'bold')
    {
        if ($num > $limit_under) {
            $num = $this->build_html_highlight($num, $color, $background_color, $font_weight);
        }
        return $num;
    }

    //-------------------------------------------------------------------
    // stylesheet
    //-------------------------------------------------------------------
    public function build_html_link_stylesheet($url)
    {
        $text = '<link rel="stylesheet" type="text/css" href="' . $url . '" />' . "\n";
        return $text;
    }

    //-------------------------------------------------------------------
    // comment
    //-------------------------------------------------------------------
    public function build_html_comment($str)
    {
        $text = ' <!-- ' . $str . ' -->' . "\n";
        return $text;
    }

    //-------------------------------------------------------------------
    // bread_crumb
    //-------------------------------------------------------------------
    public function build_html_bread_crumb($paths)
    {
        $arr = array();
        foreach ($paths as $path) {
            if (isset($path['url']) && isset($path['name']) && $path['url'] && $path['name']) {
                $url   = $this->sanitize_url($path['url']);
                $name  = $this->sanitize_text($path['name']);
                $arr[] = '<a href="' . $url . '">' . $name . '</a>';
            } elseif (isset($path['name']) && $path['name']) {
                $name  = $this->sanitize_text($path['name']);
                $arr[] = '<b>' . $name . '</b>';
            }
        }

        $text = ' ';
        $text .= implode(' &gt;&gt; ', $arr);
        $text .= " <br />\n";

        return $text;
    }

    //-------------------------------------------------------------------
    // menu
    //-------------------------------------------------------------------
    public function build_html_menu_table($menu_arr, $MAX_COL = 5, $width = '', $outer = 'outer', $even = 'even', $odd = 'odd')
    {
        if (empty($width)) {
            $width = (int)(100 / $MAX_COL) - 1;
            if ($width <= 0) {
                $width = 1;
            }
            $width .= '%';
        }

        $col_count  = 0;
        $line_count = 0;
        $class      = $odd;

        $text = '<table class="' . $outer . '" cellpadding="4" cellspacing="1" >' . "\n";

        foreach ($menu_arr as $name => $url) {
            // column begin
            if ($col_count == 0) {
                if ($line_count % 2 == 0) {
                    $class = $odd;
                } else {
                    $class = $even;
                }

                $text .= '<tr>';
            }

            $class = ($class == $even) ? $odd : $even;
            $text .= '<td class="' . $class . '" width="' . $width . '" align="center" valign="bottom" >';

            if ($name && $url) {
                $text .= '<a href="' . $url . '"><b>' . $name . '</b></a>';
            } else {
                $text .= '&nbsp;';
            }

            $text .= "</td>\n";

            ++$col_count;

            // column end
            if ($col_count >= $MAX_COL) {
                $col_count = 0;
                ++$line_count;

                $text .= "</tr>\n";
            }
        }

        $col_count_2 = $col_count;

        if ($col_count_2 && ($col_count_2 < $MAX_COL)) {
            while ($col_count < $MAX_COL) {
                $class = ($class == $even) ? $odd : $even;
                $text .= '<td class="' . $class . '">&nbsp;</td>';
                ++$col_count;

                // column end
                if ($col_count >= $MAX_COL) {
                    $text .= "</tr>\n";
                }
            }
        }

        $text .= "</table><br />\n";
        return $text;
    }

    //================================================================
    // build XOOPS element
    //================================================================
    public function build_xoops_img_email_logo($width = 0, $height = 0, $border = 0, $alt = 'email')
    {
        $url  = XOOPS_URL . '/images/icons/email.gif';
        $text = $this->build_html_img_tag($url, $width, $height, $border, $alt);
        return $text;
    }

    public function build_xoops_img_www_logo($width = 0, $height = 0, $border = 0, $alt = 'www')
    {
        $url  = XOOPS_URL . '/images/icons/www.gif';
        $text = $this->build_html_img_tag($url, $width, $height, $border, $alt);
        return $text;
    }

    public function build_xoops_img_pm_logo($width = 0, $height = 0, $border = 0, $alt = 'pm')
    {
        $url  = XOOPS_URL . '/images/icons/pm.gif';
        $text = $this->build_html_img_tag($url, $width, $height, $border, $alt);
        return $text;
    }

    public function build_xoops_mailto_with_logo($email, $target = '')
    {
        if (empty($email)) {
            return '';
        }

        $img  = $this->build_xoops_img_email_logo();
        $text = $this->build_html_a_href_email($email, $img, $target, false);
        return $text;
    }

    public function build_xoops_url_with_logo($url, $target = '_blank')
    {
        if (empty($url)) {
            return '';
        }

        $img  = $this->build_xoops_img_www_logo();
        $text = $this->build_html_a_href_name($url, $img, $target, false);
        return $text;
    }

    public function build_xoops_pm_with_logo($uid, $target = '_blank')
    {
        $uid = (int)$uid;
        if ($uid < 1) {
            return '';
        }

        $img    = $this->build_xoops_img_pm_logo();
        $url_pm = XOOPS_URL . '/pmlite.php?send2=1&amp;to_userid=' . $uid;
        $url    = $this->build_xoops_openWithSelfMain($url_pm, 'pmlite', 450, 370);
        $text   = $this->build_html_a_href_name($url, $img, $target, false);
        return $text;
    }

    public function build_xoops_url_userinfo($uid, $uname)
    {
        $uid = (int)$uid;
        if ($uid < 1) {
            return '';
        }

        $userinfo_url = XOOPS_URL . '/userinfo.php?uid=' . $uid;
        $submitter    = $this->build_html_a_href_name($userinfo_url, $uname);
        return $submitter;
    }

    public function build_xoops_openWithSelfMain($url, $name, $width = 450, $height = 400)
    {
        $text = "javascript:openWithSelfMain('$url', '$name', $width, $height)";
        return $text;
    }

    // --- class end ---
}
