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

//================================================================
// class ConfigFormHandler
//================================================================

/**
 * Class ConfigFormHandler
 * @package XoopsModules\Happylinux
 */
class ConfigFormHandler extends Error
{
    // set by chieldren class
    public $handler;
    public $_define;

    // class
    public $_post;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $this->_post = Post::getInstance();
    }

    /**
     * @return \XoopsModules\Happylinux\ConfigFormHandler|static
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * @param $name
     * @param $dirname
     * @param $prefix
     */
    public function set_handler($name, $dirname, $prefix, $helper = null)
    {
        //        $this->handler = \XoopsModules\Happylinux\Helper::getInstance()->getHandler($name, $dirname, $prefix);
        if (null === $helper) {
            $helperType = '\XoopsModules' . '\\' . ucfirst($dirname) . '\Helper';
            $helper     = $helperType::getInstance();
        }

        $this->handler = $helper->getHandler(ucfirst($name));
    }

    /**
     * @param $class
     */
    public function set_define(&$class)
    {
        $this->_define = &$class;
    }

    //---------------------------------------------------------
    // POST param
    //---------------------------------------------------------
    /**
     * @return int
     */
    public function get_post_form_catid()
    {
        return $this->_post->get_post_int('form_catid');
    }

    /**
     * @param $catid
     * @return bool
     */
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
        $this->handler->load();
    }

    //---------------------------------------------------------
    // check config
    //---------------------------------------------------------
    /**
     * @return bool
     */
    public function compare_to_define()
    {
        $this->_clear_errors();

        $define_arr = $this->_define->get_define();

        // list from Define
        foreach ($define_arr as $id => $def) {
            $name      = $def['name'];
            $valuetype = $def['valuetype'];

            $count1 = $this->handler->get_count_by_key_value('conf_id', (int)$id);
            $count2 = $this->handler->get_count_by_key_value('conf_name', $name);

            if ((0 == $count1) || (0 == $count2)) {
                $this->_set_errors("$id : $name : no record");
            } elseif (($count1 > 1) || ($count2 > 1)) {
                $this->_set_errors("$id : $name : too many record");
            } else {
                $obj            = &$this->handler->get_by_confid($id);
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
    /**
     * @return bool
     */
    public function init()
    {
        $this->_clear_errors();

        $define_arr = $this->_define->get_define();

        // list from Define
        foreach ($define_arr as $id => $def) {
            $name      = $def['name'];
            $valuetype = $def['valuetype'];
            $value     = $def['default'];

            $obj = &$this->handler->create();
            $obj->set('conf_id', $id);
            $obj->set('conf_name', $name);
            $obj->set('conf_valuetype', $valuetype);
            $obj->setConfValueForInput($value);

            $ret = $this->handler->insert($obj);
            if (!$ret) {
                $this->_set_errors($this->handler->getErrors());
            }

            unset($obj);
        }

        return $this->returnExistError();
    }

    /**
     * @return bool
     */
    public function check_init()
    {
        $num = $this->handler->getCount();

        // no record
        if (0 == $num) {
            return false;
        }

        return true;
    }

    //---------------------------------------------------------
    // upgrade config
    //---------------------------------------------------------
    /**
     * @return bool
     */
    public function upgrade()
    {
        $this->_clear_errors();

        $define_arr = $this->_define->get_define();

        // list from Define
        foreach ($define_arr as $id => $def) {
            $obj = &$this->handler->get_by_confid($id);
            if (is_object($obj)) {
                continue;
            }

            // insert, when not in MySQL
            $name      = $def['name'];
            $valuetype = $def['valuetype'];
            $value     = $def['default'];

            $obj = &$this->handler->create();
            $obj->set('conf_id', $id);
            $obj->set('conf_name', $name);
            $obj->set('conf_valuetype', $valuetype);
            $obj->setConfValueForInput($value);

            $ret = $this->handler->insert($obj);
            if (!$ret) {
                $this->_set_errors($this->handler->getErrors());
            }

            unset($obj);
        }

        return $this->returnExistError();
    }

    /**
     * @return bool
     */
    public function check_upgrade()
    {
        return false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function check_exist_by_name($name)
    {
        $arr = $this->handler->get_cache_by_name($name);
        if (is_array($arr) && count($arr)) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // save config
    //---------------------------------------------------------
    /**
     * @return bool
     */
    public function save()
    {
        $this->_clear_errors();

        $confid_arr = $this->_post->get_post('conf_ids');
        $count      = count($confid_arr);

        if (!is_array($confid_arr) || (0 == $count)) {
            return true;
        }   // no actuion

        // list from POST
        for ($i = 0; $i < $count; ++$i) {
            $id = $confid_arr[$i];

            $obj = &$this->handler->get_by_confid($id);
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

                $ret = $this->handler->update($obj);
                if (!$ret) {
                    $this->_set_errors($this->handler->getErrors());
                }
            }

            unset($obj);
        }

        return $this->returnExistError();
    }

    //---------------------------------------------------------
    // renew config by country code
    //---------------------------------------------------------
    /**
     * @return bool
     */
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

            $obj = &$this->handler->get_by_confid($id);
            if (!is_object($obj)) {
                continue;
            }

            $obj->set('conf_id', $id);
            $obj->set('conf_name', $name);
            $obj->set('conf_valuetype', $valuetype);
            $obj->setConfValueForInput($value);

            $ret = $this->handler->update($obj);
            if (!$ret) {
                $this->_set_errors($this->handler->getErrors());
            }

            unset($obj);
        }

        return $this->returnExistError();
    }

    //---------------------------------------------------------
    // configHandler
    //---------------------------------------------------------
    /**
     * @return mixed
     */
    public function create_table()
    {
        $ret = $this->handler->create_table();
        if (!$ret) {
            $this->_set_errors($this->handler->getErrors());
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function clean_table()
    {
        $ret = $this->handler->clean_table($this->handler->get_magic_word());
        if (!$ret) {
            $this->_set_errors($this->handler->getErrors());
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function compare_to_scheme()
    {
        $ret = $this->handler->compare_to_scheme();
        if (!$ret) {
            $this->_set_errors($this->handler->getErrors());
        }

        return $ret;
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function update_by_name($name, $value)
    {
        $ret = $this->handler->update_by_name($name, $value);
        if (!$ret) {
            $this->_set_errors($this->handler->getErrors());
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function existsTable()
    {
        return $this->handler->existsTable();
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->handler->getCount();
    }

    // --- class end ---
}
