<?php

namespace XoopsModules\Happylinux;

// $Id: config_storeHandler.php,v 1.2 2012/03/17 13:09:23 ohwada Exp $

// 2012-03-01 K.OHWADA
// BUG: happylinux_form_lib -> happylinux_form_lib

// 2007-11-24 K.OHWADA
// compare_to_define()
// BUG : radio -> checkbox

// 2007-11-11 K.OHWADA
// build_conf_select_by_name()

// 2007-08-01 K.OHWADA
// get_value_by_name()

// 2007-06-23 K.OHWADA
// build_conf_hidden_by_name()

// 2007-05-12 K.OHWADA
// build_conf_extra_func()
// build_conf_title_by_name()
// check_post_form_catid()

// 2007-02-20 K.OHWADA
// clean_table()

// 2006-11-18 K.OHWADA
// BUG 4378: dont strip slashes in conf_value when magic_quotes_gpc is on

// 2006-11-08 K.OHWADA
// build config table

// 2006-10-05 K.OHWADA
// add renew_by_contry_code()

// 2006-09-20 K.OHWADA
// use XoopsGTicket
// add set_form_name_config()

// 2006-07-10 K.OHWADA
// this is new file
// porting from weblinks_config_storeHandler

//================================================================
// Happy Linux Framework Module
// this file contain 2 class
//   happylinux_config_form
//   happylinux_config_storeHandler
// 2006-07-10 K.OHWADA
//================================================================

//=========================================================
// class ConfigForm
//=========================================================

/**
 * Class ConfigForm
 * @package XoopsModules\Happylinux
 */
class ConfigForm extends FormLib
{
    // constant
    public $_LANG_FORM_ITEM = 'Item';

    public $_FORM_NAME_CONFIG = 'config';
    public $_CC_MARK          = '<span style="color:#0000ff;">#</span>';

    // set by childen class
    public $configDefineHandler;

    // class
    public $_post;

    // set parameter
    public $_button_extend = '';

    // local
    public $_conf_table_line_count = 0;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // BUG: happylinux_form_lib -> happylinux_form_lib
        parent::__construct();

        $this->set_form_name($this->_FORM_NAME_CONFIG);

        $this->configDefineHandler = ConfigDefineHandler::getInstance();
        $this->_post                  = Post::getInstance();

        if (defined('_HAPPYLINUX_FORM_ITEM')) {
            $this->_LANG_FORM_ITEM = _HAPPYLINUX_FORM_ITEM;
        }
        if (defined('_HAPPYLINUX_UPDATE')) {
            $this->set_submit_value(_HAPPYLINUX_UPDATE);
        }
    }

    /**
     * @param $name
     * @param $dirname
     * @param $prefix
     */
    public function set_config_handler($name, $dirname, $prefix, $helper = null)
    {
        $this->configDefineHandler->set_config_handler($name, $dirname, $prefix, $helper);
    }

    /**
     * @param $class
     */
    public function set_config_define(&$class)
    {
        $this->configDefineHandler->set_config_define($class);
    }

    //---------------------------------------------------------
    // main function
    //---------------------------------------------------------
    /**
     * @return mixed|string
     */
    public function get_post_get_op()
    {
        $op = $this->_post->get_post_get('op');

        return $op;
    }

    //---------------------------------------------------------
    // show config
    //---------------------------------------------------------
    /**
     * @param        $catid
     * @param string $title
     */
    public function show_by_catid($catid, $title = '')
    {
        $config_arr = &$this->get_by_catid($catid);

        $form_name = $this->_FORM_NAME_CONFIG . '_' . $catid;
        $this->set_form_name($form_name);

        if ($title) {
            $this->set_form_title($title);
        }

        $this->show($config_arr, $catid);
    }

    /**
     * @param     $config_arr
     * @param int $catid
     */
    public function show($config_arr, $catid = 0)
    {
        if (!is_array($config_arr) || empty($config_arr)) {
            return;
        }

        // form start
        echo $this->build_form_begin();
        echo $this->build_token();
        echo $this->build_html_input_hidden('op', 'save');

        if ($catid) {
            echo $this->build_html_input_hidden('form_catid', $catid);
        }

        echo $this->build_form_table_begin();
        echo $this->build_form_table_title();

        // list from config array
        foreach ($config_arr as $id => $config) {
            $title = $this->build_conf_caption($config);
            $ele   = $this->build_conf_element($config);
            $ele   .= $this->build_conf_hidden($id);

            echo $this->build_form_table_line($title, $ele);
        }

        $button = $this->build_form_submit();

        if ($this->_button_extend) {
            $button               .= $this->_button_extend;
            $this->_button_extend = ''; // clear by myself
        }

        echo $this->build_form_table_line('', $button, 'foot', 'foot');
        echo $this->build_form_table_end();
        echo $this->build_form_end();
        // form end
    }

    //---------------------------------------------------------
    // make config element
    //---------------------------------------------------------
    /**
     * @param $config
     * @return string
     */
    public function build_conf_caption($config)
    {
        $cap = $this->build_conf_caption_ccflag($config['title'], $config['description'], $config['cc_flag']);

        return $cap;
    }

    /**
     * @param      $title
     * @param      $desc
     * @param bool $cc_flag
     * @return string
     */
    public function build_conf_caption_ccflag($title, $desc, $cc_flag = false)
    {
        $cap = $this->build_form_caption($title, $desc);
        if ($cc_flag) {
            $cap = $this->_CC_MARK . ' ' . $cap;
        }

        return $cap;
    }

    /**
     * @param $config
     * @return string|string[]|null
     */
    public function build_conf_element($config)
    {
        $formtype  = $config['formtype'];
        $valuetype = $config['valuetype'];
        $name      = $config['name'];
        $options   = $config['options'];
        $value     = $config['value'];
        $value_s   = $this->sanitize_text($value);

        switch ($formtype) {
            case 'textbox':
                $ele = $this->build_html_input_text($name, $value_s);
                break;
            case 'textarea':
                $ele = $this->build_conf_textarea($name, $value, $valuetype);
                break;
            case 'select':
                $ele = $this->build_html_select($name, $value, $options);
                break;
            case 'select_multi':
                $ele = $this->build_html_select_multiple($name, $value, $options);
                break;
            case 'radio':
            case 'radio_select':
                $ele = $this->build_html_input_radio_select($name, $value, $options);
                break;
            case 'radio_nl':
                $ele = $this->build_html_input_radio_select($name, $value, $options, '<br>');
                break;
            case 'radio_nl_non':
                $ele = $this->build_html_input_radio_select($name, $value, $options, '<br>', false);
                break;
            case 'yesno':
            case 'radio_yesno':
                $ele = $this->build_form_radio_yesno($name, $value);
                break;
            case 'yesno_check':
            case 'checkbox_yesno':
                // BUG : radio -> checkbox
                $ele = $this->build_form_checkbox_yesno($name, $value_s);
                break;
            case 'label':
                $ele = $value_s;
                break;
            case 'text_image':
                $ele = $this->build_conf_text_image($name, $value);
                break;
            case 'label_image':
                $ele = $this->build_conf_label_image($name, $value);
                break;
            //      case 'group':
            //          $ele = $this->build_conf_group( $config );
            //          break;

            //      case 'group_multi':
            //          $ele = $this->build_conf_group_multi( $config );
            //          break;

            //      case 'user':
            //          $ele = $this->build_conf_user( $config );
            //          break;

            //      case 'user_multi':
            //          $ele = $this->build_conf_user_multi( $config );
            //          break;

            //      case 'password':
            //          $ele = $this->build_conf_password( $config );
            //          break;

            default:
                // extra_xxx
                if (preg_match('/^extra_/', $formtype)) {
                    $ele = $this->build_conf_extra_func($config);
                } else {
                    $ele = $this->build_html_input_text($name, $value_s);
                }
                break;
        }

        return $ele;
    }

    //---------------------------------------------------------
    // make config form
    //---------------------------------------------------------
    /**
     * @param $name
     * @param $value
     * @param $valuetype
     * @return string
     */
    public function build_conf_textarea($name, $value, $valuetype)
    {
        if ('array' == $valuetype) {
            if ('' != $value) {
                $value_s = $this->sanitize_textarea(implode('|', $value));
                $text    = $this->build_html_textarea($name, $value_s, 5, 50);
            } else {
                $text = $this->build_html_textarea($name, '', 5, 50);
            }
        } else {
            $value_s = $this->sanitize_textarea($value);
            $text    = $this->build_html_textarea($name, $value_s, 5, 50);
        }

        return $text;
    }

    /**
     * @param        $name
     * @param        $value
     * @param int    $size
     * @param int    $maxlength
     * @param int    $width
     * @param int    $height
     * @param int    $border
     * @param string $alt
     * @return string
     */
    public function build_conf_text_image($name, $value, $size = 50, $maxlength = 255, $width = 0, $height = 0, $border = 0, $alt = 'image')
    {
        $text = $this->build_html_input_text($name, $value, $size);
        $text .= "<br><br>\n";
        $text .= $this->build_html_img_tag($value, $width, $height, $border, $alt);

        return $text;
    }

    /**
     * @param        $name
     * @param        $value
     * @param int    $width
     * @param int    $height
     * @param int    $border
     * @param string $alt
     * @return string
     */
    public function build_conf_label_image($name, $value, $width = 0, $height = 0, $border = 0, $alt = 'image')
    {
        $text = $value;
        $text .= "<br><br>\n";
        $text .= $this->build_html_img_tag($value, $width, $height, $border, $alt);

        return $text;
    }

    /**
     * @param     $name
     * @param     $value
     * @param int $size
     * @param int $maxlength
     * @return string
     */
    public function build_conf_textbox($name, $value, $size = 50, $maxlength = 255)
    {
        $value = $this->sanitize_text($value);
        $text  = $this->build_html_input_text($name, $value, $size);

        return $text;
    }

    /**
     * @param $value
     * @return string|string[]|null
     */
    public function build_conf_label($value)
    {
        $text = $this->sanitize_text($value);

        return $text;
    }

    /**
     * @param        $name
     * @param        $value
     * @param        $options
     * @param int    $none
     * @param string $none_name
     * @param string $none_value
     * @return string
     */
    public function build_conf_select($name, $value, $options, $none = 0, $none_name = '---', $none_value = '')
    {
        $text = $this->build_html_select($name, $value, $options, $none, $none_name, $none_value);

        return $text;
    }

    /**
     * @param        $name
     * @param        $value
     * @param        $options
     * @param int    $none
     * @param string $none_name
     * @param string $none_value
     * @return string
     */
    public function build_conf_select_multi($name, $value, $options, $none = 0, $none_name = '---', $none_value = '')
    {
        $text = $this->build_html_select_multiple($name, $value, $options, $none, $none_name, $none_value);

        return $text;
    }

    /**
     * @param        $name
     * @param        $value
     * @param        $options
     * @param string $del
     * @return string
     */
    public function build_conf_radio_select($name, $value, $options, $del = '')
    {
        $text = $this->build_html_input_radio_select($name, $value, $options, $del);

        return $text;
    }

    /**
     * @param $name
     * @param $value
     * @return string
     */
    public function build_conf_radio_yesno($name, $value)
    {
        $text = $this->build_form_radio_yesno($name, $value);

        return $text;
    }

    /**
     * @param $name
     * @param $value
     * @return string
     */
    public function build_conf_checkbox_yesno($name, $value)
    {
        $text = $this->build_form_radio_yesno($name, $value);

        return $text;
    }

    /**
     * @param $id
     * @return string
     */
    public function build_conf_hidden($id)
    {
        $text = $this->build_html_input_hidden('conf_ids[]', $id);

        return $text;
    }

    //---------------------------------------------------------
    // make config by name
    //---------------------------------------------------------
    /**
     * @param $name
     * @return mixed|string
     */
    public function build_conf_title_by_name($name)
    {
        if (empty($name)) {
            return '&nbsp;';
        }

        $title = $this->get_by_name($name, 'title');

        return $title;
    }

    /**
     * @param $name
     * @return string
     */
    public function build_conf_caption_by_name($name)
    {
        if (empty($name)) {
            return '&nbsp;';
        }

        $title   = $this->get_by_name($name, 'title');
        $desc    = $this->get_by_name($name, 'description');
        $cc_flag = $this->get_by_name($name, 'cc_flag');
        $cap     = $this->build_conf_caption_ccflag($title, $desc, $cc_flag);

        return $cap;
    }

    /**
     * @param     $name
     * @param int $size
     * @return string
     */
    public function build_conf_textbox_by_name($name, $size = 5)
    {
        if (empty($name)) {
            return '&nbsp;';
        }

        $id      = $this->get_by_name($name, 'conf_id');
        $value   = $this->get_by_name($name, 'value');
        $value_s = $this->sanitize_text($value);

        $text = $this->build_html_input_text($name, $value_s, $size);
        $text .= $this->build_conf_hidden($id);

        return $text;
    }

    /**
     * @param $name
     * @return string
     */
    public function build_conf_radio_yesno_by_name($name)
    {
        if (empty($name)) {
            return '&nbsp;';
        }

        $id      = $this->get_by_name($name, 'conf_id');
        $value   = $this->get_by_name($name, 'value');
        $value_s = $this->sanitize_text($value);

        $text = $this->build_form_radio_yesno($name, $value_s);
        $text .= $this->build_conf_hidden($id);

        return $text;
    }

    /**
     * @param $name
     * @return string
     */
    public function build_conf_checkbox_by_name($name)
    {
        if (empty($name)) {
            return '&nbsp;';
        }

        $id      = $this->get_by_name($name, 'conf_id');
        $value   = $this->get_by_name($name, 'value');
        $value_s = $this->sanitize_text($value);

        $text = $this->build_form_checkbox_yesno($name, $value_s);
        $text .= $this->build_conf_hidden($id);

        return $text;
    }

    /**
     * @param $name
     * @return string
     */
    public function build_conf_radio_select_by_name($name)
    {
        if (empty($name)) {
            return '&nbsp;';
        }

        $id      = $this->get_by_name($name, 'conf_id');
        $value   = $this->get_by_name($name, 'value');
        $options = $this->get_by_name($name, 'options');

        $text = $this->build_html_input_radio_select($name, $value, $options, '<br>');
        $text .= $this->build_conf_hidden($id);

        return $text;
    }

    /**
     * @param      $name
     * @param null $options
     * @return string
     */
    public function build_conf_select_by_name($name, $options = null)
    {
        if (empty($name)) {
            return '&nbsp;';
        }

        $id    = $this->get_by_name($name, 'conf_id');
        $value = $this->get_by_name($name, 'value');

        if (empty($options)) {
            $options = $this->get_by_name($name, 'options');
        }

        $text = $this->build_html_select($name, $value, $options);
        $text .= $this->build_conf_hidden($id);

        return $text;
    }

    /**
     * @param $name
     * @return string|void
     */
    public function build_conf_hidden_by_name($name)
    {
        if (empty($name)) {
            return;
        }

        $id   = $this->get_by_name($name, 'conf_id');
        $text = $this->build_conf_hidden($id);

        return $text;
    }

    //---------------------------------------------------------
    // build config table
    //---------------------------------------------------------
    /**
     * @param $form_name
     * @return string
     */
    public function build_conf_table_begin($form_name)
    {
        $this->_conf_table_line_count = 0;

        $text = $this->build_form_begin($form_name);
        $text .= $this->build_token();
        $text .= $this->build_html_input_hidden('op', 'save');
        $text .= '<table class="outer" width="100%">' . "\n";

        return $text;
    }

    /**
     * @param $num
     * @return string
     */
    public function build_conf_table_end($num)
    {
        $text = '<tr class="foot"><td></td><td colspan="' . $num . '">';
        $text .= $this->build_form_submit();
        $text .= "</tr>\n";
        $text .= "</table>\n";
        $text .= $this->build_form_end();
        $text .= "<br>\n";

        return $text;
    }

    /**
     * @param $num
     * @return string
     */
    public function build_conf_table_head($num)
    {
        $num_args = func_num_args();
        $args     = func_get_args();

        $arr = $this->build_conf_table_col_space($num);

        for ($i = 1; $i < $num_args; ++$i) {
            if ($args[$i]) {
                $arr[$i - 1] = $args[$i];
            }
        }

        $text = '<tr><th align="center">' . $this->_LANG_FORM_ITEM . '</th>';
        for ($i = 0; $i < $num; ++$i) {
            $text .= '<th align="center">' . $arr[$i] . '</th>';
        }
        $text .= "</tr>\n";

        return $text;
    }

    /**
     * @param $num
     * @return string
     */
    public function build_conf_table_textbox($num)
    {
        $num_args = func_num_args();
        $args     = func_get_args();

        $arr = $this->build_conf_table_col_space($num);

        for ($i = 1; $i < $num_args; ++$i) {
            if ($args[$i]) {
                $arr[$i - 1] = $this->build_conf_textbox_by_name($args[$i]);
            }
        }

        $title = $this->build_conf_table_title($num_args, $args);

        $text = $this->build_config_table_line($num, $title, $arr);

        return $text;
    }

    /**
     * @param $num
     * @return string
     */
    public function build_conf_table_yesno($num)
    {
        $num_args = func_num_args();
        $args     = func_get_args();

        $arr = $this->build_conf_table_col_space($num);

        for ($i = 1; $i < $num_args; ++$i) {
            if ($args[$i]) {
                $arr[$i - 1] = $this->build_conf_radio_yesno_by_name($args[$i]);
            }
        }

        $title = $this->build_conf_table_title($num_args, $args);

        $text = $this->build_config_table_line($num, $title, $arr);

        return $text;
    }

    /**
     * @param $num
     * @return string
     */
    public function build_conf_table_select($num)
    {
        $num_args = func_num_args();
        $args     = func_get_args();

        $arr = $this->build_conf_table_col_space($num);

        for ($i = 1; $i < $num_args; ++$i) {
            if ($args[$i]) {
                $arr[$i - 1] = $this->build_conf_radio_select_by_name($args[$i]);
            }
        }

        $title = $this->build_conf_table_title($num_args, $args);

        $text = $this->build_config_table_line($num, $title, $arr);

        return $text;
    }

    /**
     * @param $num
     * @param $title
     * @param $arr
     * @return string
     */
    public function build_config_table_line($num, $title, $arr)
    {
        $text = $this->build_config_table_even_odd();
        $text .= '<td align="left">' . $title . '</th>';
        for ($i = 0; $i < $num; ++$i) {
            $text .= '<td align="right">' . $arr[$i] . '</th>';
        }
        $text .= "</tr>\n";

        return $text;
    }

    /**
     * @return string
     */
    public function build_config_table_even_odd()
    {
        if (0 == $this->_conf_table_line_count % 2) {
            $class = 'even';
        } else {
            $class = 'odd';
        }

        $this->_conf_table_line_count++;

        $text = '<tr class="' . $class . '">';

        return $text;
    }

    /**
     * @param $num
     * @return array
     */
    public function build_conf_table_col_space($num)
    {
        $arr = [];
        for ($i = 0; $i < $num; ++$i) {
            $arr[] = '&nbsp';
        }

        return $arr;
    }

    /**
     * @param $num
     * @param $args
     * @return string
     */
    public function build_conf_table_title($num, $args)
    {
        $title = '';

        for ($i = 1; $i < $num; ++$i) {
            $name = $args[$i];
            if ($name && empty($title)) {
                $title = $this->build_conf_caption_by_name($name);
                break;
            }
        }

        return $title;
    }

    //---------------------------------------------------------
    // ConfigDefineHandler
    //---------------------------------------------------------
    public function load()
    {
        $this->configDefineHandler->load();
    }

    /**
     * @param $id
     * @param $key
     * @return mixed
     */
    public function get_by_confid($id, $key)
    {
        $val = $this->configDefineHandler->get_by_itemid($id, $key);

        return $val;
    }

    /**
     * @param $name
     * @param $key
     * @return mixed
     */
    public function get_by_name($name, $key)
    {
        $val = $this->configDefineHandler->get_by_name($name, $key);

        return $val;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get_value_by_name($name)
    {
        $val = $this->configDefineHandler->get_by_name($name, 'value');

        return $val;
    }

    /**
     * @param $catid
     * @return mixed
     */
    public function &get_by_catid($catid)
    {
        $arr = $this->configDefineHandler->get_caches_by_catid($catid);

        return $arr;
    }

    //---------------------------------------------------------
    // set parameter
    //---------------------------------------------------------
    /**
     * @param $value
     */
    public function set_form_name_config($value)
    {
        $this->_FORM_NAME_CONFIG = $value;
    }

    /**
     * @param $value
     */
    public function set_button_extend($value)
    {
        $this->_button_extend = $value;
    }

    /**
     * @param $name
     * @param $value
     */
    public function set_submit_button_extend($name, $value)
    {
        $button = $this->build_form_submit($name, $value);
        $this->set_button_extend($button);
    }

    //---------------------------------------------------------
    // override
    //---------------------------------------------------------
    /**
     * @param $config
     * @return string
     */
    public function build_conf_extra_func($config)
    {
        $name    = $config['name'];
        $value_s = $this->sanitize_text($config['value']);

        return $this->build_html_input_text($name, $value_s);
    }

    // --- class end ---
}
