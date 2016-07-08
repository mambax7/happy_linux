<?php
// $Id: form_lib.php,v 1.6 2009/01/05 17:50:39 ohwada Exp $

// 2008-12-20 K.OHWADA
// $extra in print_lib_box_upgrade_config()

// 2007-09-01 K.OHWADA
// build_confirm_form()

// 2007-06-01 K.OHWADA
// build_lib_user_link_uname_by_uid()

// 2007-05-12 K.OHWADA
// print_lib_box_init_config()

// 2006-09-15 K.OHWADA
// this is new file

//=========================================================
// Happy Linux Framework Module
// 2006-09-10 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_form_lib
//=========================================================
class happy_linux_form_lib extends happy_linux_form
{
    public $_system;

    public $_LIB_BOX_DIV_CLASS        = 'happy_linux_box_class';
    public $_LIB_BOX_SPAN_TITLE_CLASS = 'happy_linux_title_class';

    public $_LIB_BOX_DIV_STYLE        = 'background-color: #dde1de; border: 1px solid #808080; margin: 5px; padding: 10px 10px 5px 10px; width: 90%; ';
    public $_LIB_BOX_SPAN_TITLE_STYLE = 'font-size: 120%; font-weight: bold; color: #000000; ';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $this->_system = happy_linux_system::getInstance();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_form_lib();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // next box
    // caller: rssc/admin/table_manage.php
    //---------------------------------------------------------
    public function build_lib_box_limit_offset($title, $desc, $limit = 0, $offset = 0, $op_value = 'save', $submit_value = 'save', $action = '')
    {
        $val  = $this->build_lib_button_limit_offset($limit, $offset, $op_value, $submit_value, $action = '');
        $text = $this->build_lib_box_style($title, $desc, $val);
        return $text;
    }

    public function build_lib_button_limit_offset($limit = 0, $offset = 0, $op_value = 'save', $submit_value = 'save', $action = '')
    {
        $form_name   = '';
        $action      = '';
        $submit_name = 'submit';

        $arr = array(
            'op'     => $op_value,
            'limit'  => $limit,
            'offset' => $offset,
        );

        $text = $this->build_lib_button_hidden_array($arr, $form_name, $action, $submit_name, $submit_value);
        return $text;
    }

    //---------------------------------------------------------
    // box
    // caller: rssc/admin/admin_config_class.php
    //---------------------------------------------------------
    public function build_lib_box_button_style($title, $desc, $op_value = 'save', $submit_value = 'save', $action = '')
    {
        $val  = $this->build_lib_button($op_value, $submit_value, $action);
        $text = $this->build_lib_box_style($title, $desc, $val);
        return $text;
    }

    public function build_lib_box_button_class($title, $desc, $op_value = 'save', $submit_value = 'save', $action = '')
    {
        $val  = $this->build_lib_button($op_value, $submit_value, $action);
        $text = $this->build_lib_box_class($title, $desc, $val);
        return $text;
    }

    public function build_lib_box_style($title, $desc, $value, $style_div = '', $style_span = '')
    {
        if (empty($style_div)) {
            $style_div = $this->_LIB_BOX_DIV_STYLE;
        }

        if (empty($style_span)) {
            $style_span = $this->_LIB_BOX_SPAN_TITLE_STYLE;
        }

        $text = '<div style="' . $style_div . '">' . "\n";

        if ($title) {
            $text .= '<span style="' . $style_span . '">';
            $text .= $title;
            $text .= "</span><br /><br />\n";
        }

        if ($desc) {
            $text .= $desc . "<br /><br />\n";
        }

        $text .= $value;
        $text .= "</div><br />\n";
        return $text;
    }

    public function build_lib_box_class($title, $desc, $value, $class_div = '', $class_span = '')
    {
        if (empty($class_div)) {
            $class_div = $this->_LIB_BOX_DIV_CLASS;
        }

        if (empty($class_span)) {
            $class_span = $this->_LIB_BOX_SPAN_TITLE_CLASS;
        }

        $text = '<div class="' . $class_div . '">' . "\n";

        if ($title) {
            $text .= '<span class="' . $class_span . '">';
            $text .= $title;
            $text .= "</span><br /><br />\n";
        }

        if ($desc) {
            $text .= $desc . "<br /><br />\n";
        }

        $text .= $value;
        $text .= "</div><br />\n";
        return $text;
    }

    //---------------------------------------------------------
    // button
    //---------------------------------------------------------
    public function build_lib_button($op_value = 'save', $submit_value = 'save', $action = '')
    {
        $form_name   = $this->build_form_name_rand();
        $op_name     = $this->_OP_NAME;     // 'op'
        $submit_name = $this->_BUTTON_SUBMIT_NAME;  // 'submit'

        $arr = array(
            $op_name => $op_value,
        );

        $text = $this->build_lib_button_hidden_array($arr, $form_name, $action, $submit_name, $submit_value);
        return $text;
    }

    public function build_lib_button_hidden_array($hidden_array, $form_name = '', $action = '', $submit_name = '', $submit_value = '', $cancel_name = '', $cancel_value = '', $location_name = '',
                                                  $location_value = '', $location_url = ''
    )
    {
        if (empty($form_name)) {
            $form_name = $this->build_form_name_rand();
        }

        if (empty($action)) {
            $action = xoops_getenv('PHP_SELF');
        }

        $text = $this->build_form_begin($form_name, $action);
        $text .= $this->build_token();

        if (is_array($hidden_array) && count($hidden_array)) {
            foreach ($hidden_array as $k => $v) {
                $text .= $this->build_html_input_hidden($k, $v);
            }
        }

        if ($submit_value) {
            $text .= '  ' . $this->build_form_button_submit($submit_name, $submit_value);
        }

        if ($cancel_value) {
            $text .= '  ' . $this->build_form_button_cancel($cancel_name, $cancel_value);
        }

        if ($location_url) {
            $text .= '  ' . $this->build_form_button_location($location_name, $location_value, $location_url);
        }

        $text .= $this->build_form_end();
        return $text;
    }

    //---------------------------------------------------------
    // print message
    //---------------------------------------------------------
    public function print_lib_box_init_config()
    {
        xoops_error(_HAPPY_LINUX_FORM_INIT_NOT);
        echo "<br />\n";
        echo $this->build_lib_box_button_style(_HAPPY_LINUX_FORM_INIT_EXEC, '', 'init', _HAPPY_LINUX_SAVE);
        echo "<br />\n";
    }

    public function print_lib_box_upgrade_config($ver, $extra = null)
    {
        $msg = sprintf(_HAPPY_LINUX_FORM_VERSION_NOT, $ver);
        if ($extra) {
            $msg .= "<br />\n" . $extra;
        }
        xoops_error($msg);
        echo "<br />\n";
        echo $this->build_lib_box_button_style(_HAPPY_LINUX_FORM_UPGRADE_EXEC, '', 'upgrade', _HAPPY_LINUX_SAVE);
        echo "<br />\n";
    }

    //---------------------------------------------------------
    // set parameter
    //---------------------------------------------------------
    public function set_lib_box_div_class($val)
    {
        $this->_LIB_BOX_DIV_CLASS = $val;
    }

    public function set_lib_box_span_title_class($val)
    {
        $this->_LIB_BOX_SPAN_TITLE_CLASS = $val;
    }

    public function set_lib_box_div_style($val)
    {
        $this->_LIB_BOX_DIV_STYLE = $val;
    }

    public function set_lib_box_span_title_style($val)
    {
        $this->_LIB_BOX_SPAN_TITLE_STYLE = $val;
    }

    //---------------------------------------------------------
    // user_link
    //---------------------------------------------------------
    public function build_lib_user_link_uname_by_uid($uid, $target = '_blank')
    {
        $uname      = $this->_system->get_uname_by_uid($uid);
        $link_uname = $uname;
        if ($uid != 0) {
            $url        = XOOPS_URL . '/userinfo.php?uid=' . $uid;
            $link_uname = $this->build_html_a_href_name($url, $uname, $target);
        }
        return $link_uname;
    }

    public function build_lib_user_link_email_by_uid($uid, $name = '', $target = '_blank')
    {
        $email      = $this->_system->get_email_by_uid($uid);
        $link_email = '';
        if (($uid != 0) && $email) {
            $link_email = $this->build_html_a_href_email($email, $name, $target);
        }
        return $link_email;
    }

    //---------------------------------------------------------
    // confirm
    //---------------------------------------------------------
    public function build_confirm_form(&$param)
    {
        $div_class     = isset($param['div_class']) ? $param['div_class'] : 'confirmMsg';
        $form_name     = isset($param['form_name']) ? $param['form_name'] : 'confirm_form';
        $action        = isset($param['action']) ? $param['action'] : null;
        $title         = isset($param['title']) ? $param['title'] : null;
        $hiddens       = isset($param['hiddens']) ? $param['hiddens'] : null;
        $flag_sanitize = isset($param['flag_sanitize']) ? $param['flag_sanitize'] : true;
        $flag_cancel   = isset($param['flag_cancel']) ? $param['flag_cancel'] : true;
        $submit_name   = isset($param['submit_name']) ? $param['submit_name'] : 'confirm_submit';
        $submit_value  = isset($param['submit_value']) ? $param['submit_value'] : _YES;
        $button_name   = isset($param['button_name']) ? $param['button_name'] : null;
        $button_value  = isset($param['button_value']) ? $param['button_value'] : null;
        $cancel_name   = isset($param['cancel_name']) ? $param['cancel_name'] : 'cancel';
        $cancel_value  = isset($param['cancel_value']) ? $param['cancel_value'] : _CANCEL;

        $text = '<div class="' . $div_class . '">' . "\n";

        if ($title) {
            $text .= '<h4>' . $title . "</h4>\n";
        }

        $text .= $this->build_form_begin($form_name, $action);
        $text .= $this->build_token();

        if (is_array($hiddens) && count($hiddens)) {
            foreach ($hiddens as $name => $value) {
                if (is_array($value)) {
                    foreach ($value as $caption => $val) {
                        if ($flag_sanitize) {
                            $val = $this->sanitize_text($val);
                        }
                        $text .= $this->build_html_input_radio($name, $val);
                        $text .= $caption;
                    }
                    $text .= "<br />\n";
                } else {
                    if ($flag_sanitize) {
                        $value = $this->sanitize_text($value);
                    }
                    $text .= $this->build_html_input_hidden($name, $value);
                }
            }
        }

        $text .= $this->build_html_input_submit($submit_name, $submit_value);
        if ($button_name) {
            $text .= $this->build_html_input_submit($button_name, $button_value);
        }
        if ($flag_cancel) {
            $text .= $this->build_html_input_button_cancel($cancel_name, $cancel_value);
        }

        $text .= $this->build_form_end();
        $text .= "<br />\n";
        $text .= "</div>\n";

        return $text;
    }

    // --- class end ---
}
