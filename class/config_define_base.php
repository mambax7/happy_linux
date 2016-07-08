<?php
// $Id: config_define_base.php,v 1.2 2008/01/10 11:32:57 ohwada Exp $

// 2008-01-10 K.OHWADA
// Notice [PHP]: Only variables should be assigned by reference

// 2007-11-11 K.OHWADA
// divid from config_define_handler.php
// set_config_country_conty_code()

//================================================================
// Happy Linux Framework Module
// 2006-07-08 K.OHWADA
//================================================================

//=========================================================
// class happy_linux_config_define_base
//=========================================================
class happy_linux_config_define_base
{
    // cache
    public $_cached = array();

    public $_config_country_code = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    //---------------------------------------------------------
    // load
    //---------------------------------------------------------
    // Notice [PHP]: Only variables should be assigned by reference
    public function &load()
    {
        $this->_cached = $this->get_define();
        return $this->_cached;
    }

    public function get_cache_by_confid_key($id, $key)
    {
        $ret = false;
        if (isset($this->_cached[$id][$key])) {
            $ret = $this->_cached[$id][$key];
        }
        return $ret;
    }

    //---------------------------------------------------------
    // country code
    //---------------------------------------------------------
    public function set_config_country_code($val)
    {
        $this->_config_country_code = $val;
    }

    public function get_config_country_code()
    {
        return $this->_config_country_code;
    }

    // --- class end ---
}
