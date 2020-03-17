<?php

namespace XoopsModules\Happylinux;

// $Id: ConfigDefineHandler.php,v 1.5 2007/11/15 11:08:43 ohwada Exp $

// 2007-11-11 K.OHWADA
// divid to config_define_base.php

// 2006-10-01 K.OHWADA
// add cc_flag

// 2006-07-08 K.OHWADA
// this is new file
// porting from weblinks_config_defineHandler

//================================================================
// Happy Linux Framework Module
// 2006-07-08 K.OHWADA
//================================================================

//require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/config_define_base.php';

//=========================================================
// class ConfigDefineHandler
//=========================================================

/**
 * Class ConfigDefineHandler
 * @package XoopsModules\Happylinux
 */
class ConfigDefineHandler
{
    public $_configHandler;
    public $_config_define;

    // cache
    public $_cached_by_confid = [];
    public $_cached_by_name   = [];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @param      $name
     * @param      $dirname
     * @param      $prefix
     * @param null|\Xmf\Module\Helper\ $helper
     */
    public function set_config_handler($name, $dirname, $prefix, $helper = null)
    {
        if (null === $helper) {
            $helperType = '\XoopsModules' . '\\' . ucfirst($dirname) . '\Helper';
            $helper     = $helperType::getInstance();
        }
        $this->_configHandler = $helper->getHandler(ucfirst($name));
    }

    /**
     * @param $class
     */
    public function set_config_define(&$class)
    {
        $this->_config_define = &$class;
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
    // load
    //---------------------------------------------------------
    /**
     * @return array
     */
    public function load()
    {
        $this->_configHandler->load();
        $country_code = $this->_configHandler->get_cache_by_name_key('country_code', 'conf_value');
        $this->_config_define->set_config_country_code($country_code);
        $def_arr = &$this->_config_define->load();

        $this->_cached_by_confid = [];
        $this->_cached_by_name   = [];

        foreach ($def_arr as $id => $def) {
            $name      = $this->_config_define->get_cache_by_confid_key($id, 'name');
            $catid     = $this->_config_define->get_cache_by_confid_key($id, 'catid');
            $title     = $this->_config_define->get_cache_by_confid_key($id, 'title');
            $desc      = $this->_config_define->get_cache_by_confid_key($id, 'description');
            $formtype  = $this->_config_define->get_cache_by_confid_key($id, 'formtype');
            $valuetype = $this->_config_define->get_cache_by_confid_key($id, 'valuetype');
            $default   = $this->_config_define->get_cache_by_confid_key($id, 'default');
            $opt       = $this->_config_define->get_cache_by_confid_key($id, 'options');
            $cc_flag   = $this->_config_define->get_cache_by_confid_key($id, 'cc_flag');
            $cc_value  = $this->_config_define->get_cache_by_confid_key($id, 'cc_value');

            $value = $this->_configHandler->get_cache_by_confid_key($id, 'value_output');

            $title = $this->conv_constant($title);
            $desc  = $this->conv_constant($desc);

            $arr = [
                'conf_id'     => $id,
                'catid'       => $catid,
                'name'        => $name,
                'title'       => $title,
                'description' => $desc,
                'formtype'    => $formtype,
                'valuetype'   => $valuetype,
                'value'       => $value,
                'options'     => $opt,
                'cc_flag'     => $cc_flag,
                'cc_value'    => $cc_value,
            ];

            $this->_cached_by_confid[$id] = $arr;
            $this->_cached_by_name[$name] = $arr;
        }

        return $this->_cached_by_confid;
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function conv_by_key($key, $value)
    {
        $text = $value;

        switch ($key) {
            case 'title':
            case 'description':
                if (defined($value)) {
                    $text = constant($value);
                }
                break;
            default:
                break;
        }

        return $text;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function conv_constant($value)
    {
        $text = $value;

        if (defined($value)) {
            $text = constant($value);
        }

        return $text;
    }

    //---------------------------------------------------------
    // load
    //---------------------------------------------------------
    /**
     * @param $id
     * @param $key
     * @return bool|mixed
     */
    public function get_by_confid($id, $key)
    {
        if (isset($this->_cached_by_confid[$id][$key])) {
            $value = $this->_cached_by_confid[$id][$key];
            $value = $this->conv_by_key($key, $value);

            return $value;
        }

        return false;
    }

    /**
     * @param $name
     * @param $key
     * @return bool|mixed
     */
    public function get_by_name($name, $key)
    {
        if (isset($this->_cached_by_name[$name][$key])) {
            $value = $this->_cached_by_name[$name][$key];
            $value = $this->conv_by_key($key, $value);

            return $value;
        }

        return false;
    }

    /**
     * @param $catid
     * @return array|bool
     */
    public function get_caches_by_catid($catid)
    {
        $catid = (int)$catid;

        if ($catid <= 0) {
            return false;
        }

        $arr = [];

        foreach ($this->_cached_by_confid as $id => $conf) {
            if ($catid == $conf['catid']) {
                $arr[$id] = $conf;
            }
        }

        return $arr;
    }

    // --- class end ---
}
