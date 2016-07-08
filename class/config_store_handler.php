<?php
// $Id: config_store_handler.php,v 1.2 2012/03/17 13:09:23 ohwada Exp $

// 2012-03-01 K.OHWADA
// BUG: happy_linux_form_lib -> happy_linux_form_lib

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
// porting from weblinks_config_store_handler

//================================================================
// Happy Linux Framework Module
// this file contain 2 class
//   happy_linux_config_form
//   happy_linux_config_store_handler
// 2006-07-10 K.OHWADA
//================================================================

//=========================================================
// class happy_linux_config_form
//=========================================================
class happy_linux_config_form extends happy_linux_form_lib
{
    // constant
    public $_LANG_FORM_ITEM = 'Item';

    public $_FORM_NAME_CONFIG = 'config';
    public $_CC_MARK          = '<span style="color:#0000ff;">#</span>';

    // set by childen class
    public $_config_define_handler;

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

        // BUG: happy_linux_form_lib -> happy_linux_form_lib
        parent::__construct();

        $this->set_form_name($this->_FORM_NAME_CONFIG);

        $this->_config_define_handler = happy_linux_config_define_handler::getInstance();
        $this->_post                  = happy_linux_post::getInstance();

        if (defined('_HAPPY_LINUX_FORM_ITEM')) {
            $this->_LANG_FORM_ITEM = _HAPPY_LINUX_FORM_ITEM;
        }
        if (defined('_HAPPY_LINUX_UPDATE')) {
            $this->set_submit_value(_HAPPY_LINUX_UPDATE);
        }
    }

    public function set_config_handler($name, $dirname, $prefix)
    {
        $this->_config_define_handler->set_config_handler($name, $dirname, $prefix);
    }

    public function set_config_define(&$class)
    {
        $this->_config_define_handler->set_config_define($class);
    }

    //---------------------------------------------------------
    // main function
    //---------------------------------------------------------
    public function get_post_get_op()
    {
        $op = $this->_post->get_post_get('op');
        return $op;
    }

    //---------------------------------------------------------
    // show config
    //---------------------------------------------------------
    public function show_by_catid($catid, $title = '')
    {
        $config_arr =& $this->get_by_catid($catid);

        $form_name = $this->_FORM_NAME_CONFIG . '_' . $catid;
        $this->set_form_name($form_name);

        if ($title) {
            $this->set_form_title($title);
        }

        $this->show($config_arr, $catid);
    }

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
            $ele .= $this->build_conf_hidden($id);

            echo $this->build_form_table_line($title, $ele);
        }

        $button = $this->build_form_submit();

        if ($this->_button_extend) {
            $button .= $this->_button_extend;
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
    public function build_conf_caption($config)
    {
        $cap = $this->build_conf_caption_ccflag($config['title'], $config['description'], $config['cc_flag']);
        return $cap;
    }

    public function build_conf_caption_ccflag($title, $desc, $cc_flag = false)
    {
        $cap = $this->build_form_caption($title, $desc);
        if ($cc_flag) {
            $cap = $this->_CC_MARK . ' ' . $cap;
        }
        return $cap;
    }

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
                $ele = $this->build_html_input_radio_select($name, $value, $options, '<br />');
                break;

            case 'radio_nl_non':
                $ele = $this->build_html_input_radio_select($name, $value, $options, '<br />', false);
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
    public function build_conf_textarea($name, $value, $valuetype)
    {
        if ($valuetype == 'array') {
            if ($value != '') {
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

    public function build_conf_text_image($name, $value, $size = 50, $maxlength = 255, $width = 0, $height = 0, $border = 0, $alt = 'image')
    {
        $text = $this->build_html_input_text($name, $value, $size);
        $text .= "<br /><br />\n";
        $text .= $this->build_html_img_tag($value, $width, $height, $border, $alt);
        return $text;
    }

    public function build_conf_label_image($name, $value, $width = 0, $height = 0, $border = 0, $alt = 'image')
    {
        $text = $value;
        $text .= "<br /><br />\n";
        $text .= $this->build_html_img_tag($value, $width, $height, $border, $alt);
        return $text;
    }

    public function build_conf_textbox($name, $value, $size = 50, $maxlength = 255)
    {
        $value = $this->sanitize_text($value);
        $text  = $this->build_html_input_text($name, $value, $size);
        return $text;
    }

    public function build_conf_label($value)
    {
        $text = $this->sanitize_text($value);
        return $text;
    }

    public function build_conf_select($name, $value, $options, $none = 0, $none_name = '---', $none_value = '')
    {
        $text = $this->build_html_select($name, $value, $options, $none, $none_name, $none_value);
        return $text;
    }

    public function build_conf_select_multi($name, $value, $options, $none = 0, $none_name = '---', $none_value = '')
    {
        $text = $this->build_html_select_multiple($name, $value, $options, $none, $none_name, $none_value);
        return $text;
    }

    public function build_conf_radio_select($name, $value, $options, $del = '')
    {
        $text = $this->build_html_input_radio_select($name, $value, $options, $del);
        return $text;
    }

    public function build_conf_radio_yesno($name, $value)
    {
        $text = $this->build_form_radio_yesno($name, $value);
        return $text;
    }

    public function build_conf_checkbox_yesno($name, $value)
    {
        $text = $this->build_form_radio_yesno($name, $value);
        return $text;
    }

    public function build_conf_hidden($id)
    {
        $text = $this->build_html_input_hidden('conf_ids[]', $id);
        return $text;
    }

    //---------------------------------------------------------
    // make config by name
    //---------------------------------------------------------
    public function build_conf_title_by_name($name)
    {
        if (empty($name)) {
            return '&nbsp;';
        }

        $title = $this->get_by_name($name, 'title');
        return $title;
    }

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

    public function build_conf_radio_select_by_name($name)
    {
        if (empty($name)) {
            return '&nbsp;';
        }

        $id      = $this->get_by_name($name, 'conf_id');
        $value   = $this->get_by_name($name, 'value');
        $options = $this->get_by_name($name, 'options');

        $text = $this->build_html_input_radio_select($name, $value, $options, '<br />');
        $text .= $this->build_conf_hidden($id);
        return $text;
    }

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
    public function build_conf_table_begin($form_name)
    {
        $this->_conf_table_line_count = 0;

        $text = $this->build_form_begin($form_name);
        $text .= $this->build_token();
        $text .= $this->build_html_input_hidden('op', 'save');
        $text .= '<table class="outer" width="100%">' . "\n";
        return $text;
    }

    public function build_conf_table_end($num)
    {
        $text = '<tr class="foot"><td></td><td colspan="' . $num . '">';
        $text .= $this->build_form_submit();
        $text .= "</tr>\n";
        $text .= "</table>\n";
        $text .= $this->build_form_end();
        $text .= "<br />\n";
        return $text;
    }

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

    public function build_config_table_even_odd()
    {
        if ($this->_conf_table_line_count % 2 == 0) {
            $class = 'even';
        } else {
            $class = 'odd';
        }

        $this->_conf_table_line_count++;

        $text = '<tr class="' . $class . '">';
        return $text;
    }

    public function build_conf_table_col_space($num)
    {
        $arr = array();
        for ($i = 0; $i < $num; ++$i) {
            $arr[] = '&nbsp';
        }
        return $arr;
    }

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
    // config_define_handler
    //---------------------------------------------------------
    public function load()
    {
        $this->_config_define_handler->load();
    }

    public function get_by_confid($id, $key)
    {
        $val = $this->_config_define_handler->get_by_itemid($id, $key);
        return $val;
    }

    public function get_by_name($name, $key)
    {
        $val = $this->_config_define_handler->get_by_name($name, $key);
        return $val;
    }

    public function get_value_by_name($name)
    {
        $val = $this->_config_define_handler->get_by_name($name, 'value');
        return $val;
    }

    public function &get_by_catid($catid)
    {
        $arr =& $this->_config_define_handler->get_caches_by_catid($catid);
        return $arr;
    }

    //---------------------------------------------------------
    // set parameter
    //---------------------------------------------------------
    public function set_form_name_config($value)
    {
        $this->_FORM_NAME_CONFIG = $value;
    }

    public function set_button_extend($value)
    {
        $this->_button_extend = $value;
    }

    public function set_submit_button_extend($name, $value)
    {
        $button = $this->build_form_submit($name, $value);
        $this->set_button_extend($button);
    }

    //---------------------------------------------------------
    // override
    //---------------------------------------------------------
    public function build_conf_extra_func($config)
    {
        $name    = $config['name'];
        $value_s = $this->sanitize_text($config['value']);

        return $this->build_html_input_text($name, $value_s);
    }

    // --- class end ---
}

//================================================================
// class happy_linux_config_store_handler
//================================================================
class happy_linux_config_store_handler extends happy_linux_error
{
    // set by chieldren class
    public $_handler;
    public $_define;

    // class
    public $_post;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $this->_post = happy_linux_post::getInstance();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_config_store_handler();
        }

        return $instance;
    }

    public function set_handler($name, $dirname, $prefix)
    {
        $this->_handler = happy_linux_get_handler($name, $dirname, $prefix);
    }

    public function set_define(&$class)
    {
        $this->_define =& $class;
    }

    //---------------------------------------------------------
    // POST param
    //---------------------------------------------------------
    public function get_post_form_catid()
    {
        return $this->_post->get_post_int('form_catid');
    }

    public function check_post_form_catid($catid)
    {
        if ($catid == $this->get_post_form_catid()) {
            return true;
        }
        return false;
    }

    //---------------------------------------------------------
    // load config
    //---------------------------------------------------------
    public function load()
    {
        $this->_handler->load();
    }

    //---------------------------------------------------------
    // check config
    //---------------------------------------------------------
    public function compare_to_define()
    {
        $this->_clear_errors();

        $define_arr = $this->_define->get_define();

        // list from Define
        foreach ($define_arr as $id => $def) {
            $name      = $def['name'];
            $valuetype = $def['valuetype'];

            $count1 = $this->_handler->get_count_by_key_value('conf_id', (int)$id);
            $count2 = $this->_handler->get_count_by_key_value('conf_name', $name);

            if (($count1 == 0) || ($count2 == 0)) {
                $this->_set_errors("$id : $name : no record");
            } elseif (($count1 > 1) || ($count2 > 1)) {
                $this->_set_errors("$id : $name : too many record");
            } else {
                $obj            =& $this->_handler->get_by_confid($id);
                $conf_valuetype = $obj->get('conf_valuetype');
                if ($valuetype != $conf_valuetype) {
                    $msg = "$id : $name : unmatch valuetype : $valuetype != $conf_valuetype";
                    $this->_set_errors($msg);
                }
            }
        }

        return $this->returnExistError();
    }

    //---------------------------------------------------------
    // init config
    //---------------------------------------------------------
    public function init()
    {
        $this->_clear_errors();

        $define_arr = $this->_define->get_define();

        // list from Define
        foreach ($define_arr as $id => $def) {
            $name      = $def['name'];
            $valuetype = $def['valuetype'];
            $value     = $def['default'];

            $obj =& $this->_handler->create();
            $obj->set('conf_id', $id);
            $obj->set('conf_name', $name);
            $obj->set('conf_valuetype', $valuetype);
            $obj->setConfValueForInput($value);

            $ret = $this->_handler->insert($obj);
            if (!$ret) {
                $this->_set_errors($this->_handler->getErrors());
            }

            unset($obj);
        }

        return $this->returnExistError();
    }

    public function check_init()
    {
        $num = $this->_handler->getCount();

        // no record
        if ($num == 0) {
            return false;
        }
        return true;
    }

    //---------------------------------------------------------
    // upgrade config
    //---------------------------------------------------------
    public function upgrade()
    {
        $this->_clear_errors();

        $define_arr = $this->_define->get_define();

        // list from Define
        foreach ($define_arr as $id => $def) {
            $obj =& $this->_handler->get_by_confid($id);
            if (is_object($obj)) {
                continue;
            }

            // insert, when not in MySQL
            $name      = $def['name'];
            $valuetype = $def['valuetype'];
            $value     = $def['default'];

            $obj =& $this->_handler->create();
            $obj->set('conf_id', $id);
            $obj->set('conf_name', $name);
            $obj->set('conf_valuetype', $valuetype);
            $obj->setConfValueForInput($value);

            $ret = $this->_handler->insert($obj);
            if (!$ret) {
                $this->_set_errors($this->_handler->getErrors());
            }

            unset($obj);
        }

        return $this->returnExistError();
    }

    public function check_upgrade()
    {
        return false;
    }

    public function check_exist_by_name($name)
    {
        $arr = $this->_handler->get_cache_by_name($name);
        if (is_array($arr) && count($arr)) {
            return true;
        }
        return false;
    }

    //---------------------------------------------------------
    // save config
    //---------------------------------------------------------
    public function save()
    {
        $this->_clear_errors();

        $confid_arr = $this->_post->get_post('conf_ids');
        $count      = count($confid_arr);

        if (!is_array($confid_arr) || ($count == 0)) {
            return true;
        }   // no actuion

        // list from POST
        for ($i = 0; $i < $count; ++$i) {
            $id = $confid_arr[$i];

            $obj =& $this->_handler->get_by_confid($id);
            if (!is_object($obj)) {
                continue;
            }

            $name        = $obj->get('conf_name');
            $val_current = $obj->get('conf_value');
            $value       = $this->_post->get_post($name);

            // BUG 4378: dont strip slashes in conf_value when magic_quotes_gpc is on
            $flag_update = false;
            if (is_array($value)) {
                $flag_update = true;
            } elseif ($value != $val_current) {
                $flag_update = true;
            } else {
                $value_gpc = $this->_post->strip_slashes_gpc($value);
                if ($value_gpc != $val_current) {
                    $flag_update = true;
                }
            }

            // update
            if ($flag_update) {
                $obj->setConfValueForInput($value);

                $ret = $this->_handler->update($obj);
                if (!$ret) {
                    $this->_set_errors($this->_handler->getErrors());
                }
            }

            unset($obj);
        }

        return $this->returnExistError();
    }

    //---------------------------------------------------------
    // renew config by country code
    //---------------------------------------------------------
    public function renew_by_country_code()
    {
        $this->_clear_errors();

        $define_arr = $this->_define->get_define();

        // list from Define
        foreach ($define_arr as $id => $def) {
            $name      = $def['name'];
            $valuetype = $def['valuetype'];

            if (isset($def['cc_flag']) && $def['cc_flag']) {
                $flag  = $def['cc_flag'];
                $value = $def['cc_value'];
            } else {
                continue;
            }

            $obj =& $this->_handler->get_by_confid($id);
            if (!is_object($obj)) {
                continue;
            }

            $obj->set('conf_id', $id);
            $obj->set('conf_name', $name);
            $obj->set('conf_valuetype', $valuetype);
            $obj->setConfValueForInput($value);

            $ret = $this->_handler->update($obj);
            if (!$ret) {
                $this->_set_errors($this->_handler->getErrors());
            }

            unset($obj);
        }

        return $this->returnExistError();
    }

    //---------------------------------------------------------
    // config_handler
    //---------------------------------------------------------
    public function create_table()
    {
        $ret = $this->_handler->create_table();
        if (!$ret) {
            $this->_set_errors($this->_handler->getErrors());
        }
        return $ret;
    }

    public function clean_table()
    {
        $ret = $this->_handler->clean_table($this->_handler->get_magic_word());
        if (!$ret) {
            $this->_set_errors($this->_handler->getErrors());
        }
        return $ret;
    }

    public function compare_to_scheme()
    {
        $ret = $this->_handler->compare_to_scheme();
        if (!$ret) {
            $this->_set_errors($this->_handler->getErrors());
        }
        return $ret;
    }

    public function update_by_name($name, $value)
    {
        $ret = $this->_handler->update_by_name($name, $value);
        if (!$ret) {
            $this->_set_errors($this->_handler->getErrors());
        }
        return $ret;
    }

    public function existsTable()
    {
        return $this->_handler->existsTable();
    }

    public function getCount()
    {
        return $this->_handler->getCount();
    }

    // --- class end ---
}
