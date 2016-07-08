<?php
// $Id: object_validater.php,v 1.1 2006/12/22 15:02:34 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2006-12-10 K.OHWADA
//=========================================================
class happy_linux_object_validater extends happy_linux_object
{
    public $_system;
    public $_form;
    public $_post;

    // local
    public $_validater_value_arr   = array();
    public $_validater_allow_arr   = array();
    public $_validater_conf_arr    = null;
    public $_validater_name_prefix = null;

    public $_xoops_uid;
    public $_is_xoops_module_admin;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $this->_system = happy_linux_system::getInstance();
        $this->_form   = happy_linux_form::getInstance();
        $this->_post   = happy_linux_post::getInstance();

        $this->_xoops_uid             = $this->_system->get_uid();
        $this->_is_xoops_module_admin = $this->_system->is_module_admin();
    }

    //---------------------------------------------------------
    // set & get param
    //---------------------------------------------------------
    public function set_validater_name_prefix($val)
    {
        $this->_validater_name_prefix = $val;
    }

    public function set_validater_conf_array($val)
    {
        $this->_validater_conf_arr = $val;
    }

    public function get_xoops_uid()
    {
        return $this->_xoops_uid;
    }

    public function is_xoops_module_admin()
    {
        return $this->_is_xoops_module_admin;
    }

    //---------------------------------------------------------
    // set & get validater value
    //---------------------------------------------------------
    public function merge_validater_value_list($list)
    {
        foreach ($list as $k => $v) {
            $this->_validater_value_arr[$k] = $v;
        }
    }

    public function set_validater_value($key, $value)
    {
        $this->_validater_value_arr[$key] = $value;
    }

    public function set_validater_value_allow_by_array($arr)
    {
        $this->set_validater_value($arr[0], $arr[1]);
        $this->set_validater_allow($arr[0], $arr[2]);
    }

    public function set_validater_value_allow($key, $value, $allow)
    {
        $this->set_validater_value($key, $value);
        $this->set_validater_allow($key, $allow);
    }

    public function get_validater_value($key)
    {
        if (isset($this->_validater_value_arr[$key])) {
            return $this->_validater_value_arr[$key];
        }
        return false;
    }

    //---------------------------------------------------------
    // set & get validater allow
    //---------------------------------------------------------
    public function merge_validater_allow_list($list)
    {
        foreach ($list as $k => $v) {
            $this->_validater_allow_arr[$k] = (bool)$v;
        }
    }

    public function set_validater_allow($key, $value)
    {
        $this->_validater_allow_arr[$key] = (bool)$value;
    }

    public function set_validater_allow_true($key)
    {
        $this->_validater_allow_arr[$key] = true;
    }

    public function check_validater_allow($key)
    {
        if (isset($this->_validater_value_arr[$key]) && isset($this->_validater_allow_arr[$key]) && $this->_validater_allow_arr[$key]) {
            return true;
        }
        return false;
    }

    //---------------------------------------------------------
    // validate value form post
    //---------------------------------------------------------
    public function &validate_values_from_post(&$post, $not_gpc = false)
    {
        $arr = array();

        $flag_conf_arr = false;

        if (is_array($this->_validater_conf_arr) && count($this->_validater_conf_arr)) {
            $flag_conf_arr = true;
            $merged_arr    = array_merge($this->gets(), $post, $this->_validater_conf_arr);
        } else {
            $merged_arr = array_merge($this->gets(), $post);
        }

        $key_arr = array_unique(array_keys($merged_arr));

        $pattern = '';
        if ($this->_validater_name_prefix) {
            // ex) xxx_description -> description
            $pattern     = '/^' . preg_quote($this->_validater_name_prefix, '/') . '_/';
            $replacement = '';
        }

        foreach ($key_arr as $key) {
            $val             = null;
            $flag_conf_valid = false;

            // post value
            if (isset($post[$key])) {
                $val = $post[$key];
            }

            // name convert
            if ($pattern) {
                $key = preg_replace($pattern, $replacement, $key);
            }

            // value filter
            if ($flag_conf_arr) {
                list($val, $flag_conf_valid) = $this->validate_conf_value($post, $key, $val, $not_gpc);
            }

            if (!$flag_conf_valid) {
                $val = $this->validate_standard_value($key, $val, $not_gpc);
            }

            if ($val !== null) {
                $arr[$key] = $val;
            }
        }

        return $arr;
    }

    public function validate_conf_value(&$post, $key, $val, $not_gpc = false)
    {
        $flag_conf_valid = false;

        if (isset($this->_validater_conf_arr[$key]['data_type'])) {
            $data_type = $this->_validater_conf_arr[$key]['data_type'];
        } else {
            return array($val, $flag_conf_valid);
        }

        switch ($data_type) {
            case 'int_checkbox':
                $val             = $this->_post->get_int_from_post($post, $key, 0);
                $flag_conf_valid = true;
                break;

            case 'int_time_select':
                $val             = $this->get_int_time_select_from_post($post, $key);
                $flag_conf_valid = true;
                break;
        }

        return array($val, $flag_conf_valid);
    }

    public function validate_standard_value($key, $val, $not_gpc = false)
    {
        if (isset($this->_vars[$key]['data_type'])) {
            $data_type = $this->_vars[$key]['data_type'];
        } else {
            return $val;
        }

        switch ($data_type) {
            case XOBJ_DTYPE_BOOL:
                $val = (bool)$val;
                break;

            case XOBJ_DTYPE_INT:
                $val = (int)$val;
                break;

            case XOBJ_DTYPE_FLOAT:
                $val = (float)$val;
                break;

            case XOBJ_DTYPE_TXTBOX:
                // strip GPC slashes
                $val = $this->prepare_text($val, $not_gpc);
                break;

            case XOBJ_DTYPE_TXTAREA:
                // strip GPC slashes
                $val = $this->prepare_textarea($val, $not_gpc);
                break;

            case XOBJ_DTYPE_URL:
                // strip GPC slashes
                $val = $this->prepare_url($val, $not_gpc);
                break;
        }

        return $val;
    }

    //---------------------------------------------------------
    // set object vars
    // presuppose to execute validate_values_from_post()
    //---------------------------------------------------------
    public function set_object_with_validater($not_gpc = true)
    {
        foreach ($this->gets() as $k => $v) {
            if ($this->check_validater_allow($k)) {
                $this->setVar($k, $this->get_validater_value($k), $not_gpc);
            }
        }
    }

    //---------------------------------------------------------
    // get from POST
    //---------------------------------------------------------
    public function get_int_xoops_uid(&$post, $key)
    {
        return $this->_post->get_int_from_post($post, $key, $this->_xoops_uid);
    }

    public function get_int_time_select_from_post(&$post, $key, $default = 0)
    {
        return $this->_form->get_unixtime_form_select_time_with_flag_from_post($post, $key, $default);
    }

    //---------------------------------------------------------
    // get value & allow from POST
    //---------------------------------------------------------
    public function get_value_allow_type_int_with_flag_update_from_post(&$post, $key)
    {
        $value      = null;
        $key_update = $key . '_flag_update';
        $allow      = $this->get_allow_type_key_from_post($post, $key_update);

        if (isset($post[$key])) {
            $value = (int)$post[$key];
        }

        return array($key, $value, $allow);
    }

    public function get_value_allow_type_time_update_form_post(&$post, $key)
    {
        $key_update = $key . '_flag_update';
        $allow      = $this->get_allow_type_user_always_admin_with_key_form_post($post, $key_update);
        return array($key, time(), $allow);
    }

    public function get_allow_type_key_from_post(&$post, $key)
    {
        $allow = false;
        if (isset($post[$key]) && $post[$key]) {
            $allow = true;
        }
        return $allow;
    }

    public function get_allow_type_admin_with_key_form_post(&$post, $key)
    {
        $allow = false;

        // admin if key
        if ($this->_is_xoops_module_admin) {
            if (isset($post[$key]) && $post[$key]) {
                $allow = true;
            }
        }

        return $allow;
    }

    public function get_allow_type_user_always_admin_with_key_form_post(&$post, $key)
    {
        $allow = false;

        // admin if key
        if ($this->_is_xoops_module_admin) {
            if (isset($post[$key]) && $post[$key]) {
                $allow = true;
            }
        } // user always
        else {
            $allow = true;
        }

        return $allow;
    }

    // --- class end ---
}
