<?php

// $Id: config_define_handler.php,v 1.1 2010/11/07 14:59:21 ohwada Exp $

// 2007-11-11 K.OHWADA
// divid to config_define_base.php

// 2006-10-01 K.OHWADA
// add cc_flag

// 2006-07-08 K.OHWADA
// this is new file
// porting from weblinks_config_define_handler

//================================================================
// Happy Linux Framework Module
// 2006-07-08 K.OHWADA
//================================================================

include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/config_define_base.php';

//=========================================================
// class happy_linux_config_define_handler
//=========================================================

/**
 * Class happy_linux_config_define_handler
 */
class happy_linux_config_define_handler
{
    public $_config_handler;
    public $_config_define;

    // cache
    public $_cached_by_confid = [];
    public $_cached_by_name = [];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @param $name
     * @param $dirname
     * @param $prefix
     */
    public function set_config_handler($name, $dirname, $prefix)
    {
        $this->_config_handler = happy_linux_get_handler($name, $dirname, $prefix);
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
        $this->_config_handler->load();
        $country_code = $this->_config_handler->get_cache_by_name_key('country_code', 'conf_value');
        $this->_config_define->set_config_country_code($country_code);
        $def_arr = &$this->_config_define->load();

        $this->_cached_by_confid = [];
        $this->_cached_by_name = [];

        foreach ($def_arr as $id => $def) {
            $name = $this->_config_define->get_cache_by_confid_key($id, 'name');
            $catid = $this->_config_define->get_cache_by_confid_key($id, 'catid');
            $title = $this->_config_define->get_cache_by_confid_key($id, 'title');
            $desc = $this->_config_define->get_cache_by_confid_key($id, 'description');
            $formtype = $this->_config_define->get_cache_by_confid_key($id, 'formtype');
            $valuetype = $this->_config_define->get_cache_by_confid_key($id, 'valuetype');
            $default = $this->_config_define->get_cache_by_confid_key($id, 'default');
            $opt = $this->_config_define->get_cache_by_confid_key($id, 'options');
            $cc_flag = $this->_config_define->get_cache_by_confid_key($id, 'cc_flag');
            $cc_value = $this->_config_define->get_cache_by_confid_key($id, 'cc_value');

            $value = $this->_config_handler->get_cache_by_confid_key($id, 'value_output');

            $title = $this->conv_constant($title);
            $desc = $this->conv_constant($desc);

            $arr = [
                'conf_id' => $id,
                'catid' => $catid,
                'name' => $name,
                'title' => $title,
                'description' => $desc,
                'formtype' => $formtype,
                'valuetype' => $valuetype,
                'value' => $value,
                'options' => $opt,
                'cc_flag' => $cc_flag,
                'cc_value' => $cc_value,
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
