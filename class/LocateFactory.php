<?php

namespace XoopsModules\Happylinux;

// $Id: locate.php,v 1.8 2007/11/15 11:08:43 ohwada Exp $

// 2007-11-11 K.OHWADA
// set_config_country_conty_code()
// get_happylinux_url()
// add format in get_var()

// 2007-09-20 K.OHWADA
// PHP5.2: Assigning the return value of new by reference is deprecated:

// 2007-07-28 K.OHWADA
// get_us_ping_servers()

// 2006-12-17 K.OHWADA
// BUG 4417: singleton done not work correctly
// add get_local_instance() get_language_country_code() etc

// 2006-10-05 K.OHWADA
// this is new file
// porting form weblinks_language_base.php

//=========================================================
// Happy Linux Framework Module
// 2006-10-01 K.OHWADA
//=========================================================

//=========================================================
// class LocateFactory
//=========================================================

/**
 * Class LocateFactory
 * @package XoopsModules\Happylinux
 */
class LocateFactory extends LocateBase
{
    public $_DIRNAME = null;

    //  var $_configHandler = null;

    public $_config_country_code = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param null $locate
     * @return bool|mixed|\XoopsModules\Happylinux\LocateBase
     */
    public static function getInstance($locate = null)
    {
        static $instance;
        if (!isset($instance)) {
            // Assigning the return value of new by reference is deprecated
            $factory = new self();

            $instance = &$factory->get_local_instance($locate);
        }

        return $instance;
    }

    // BUG: singleton done not work correctly

    /**
     * @param null $locate
     * @return bool|mixed|\XoopsModules\Happylinux\LocateBase
     */
    public function &get_local_instance($locate = null)
    {
        if (empty($locate)) {
            $locate = $this->get_language_country_code();
        }
        $local = &$this->get_instance($locate);

        return $local;
    }

    //---------------------------------------------------------
    // get_instance
    //---------------------------------------------------------
    /**
     * @param null   $country_code
     * @param string $prefix
     * @param string $dirname
     * @return bool|mixed|\XoopsModules\Happylinux\LocateBase
     */
    public function &get_instance($country_code = null, $prefix = 'happylinux', $dirname = 'happylinux')
    {
        if ($country_code) {
            $instance = &$this->get_instance_by_country_code($country_code, $prefix, $dirname);
            if ($instance) {
                $this->_country_code = $country_code;

                return $instance;
            }
        }

        $instance = &$this->get_instance_by_country_code($this->get_default_contry_code(), $prefix, $dirname);
        if ($instance) {
            return $instance;
        }

        // Assigning the return value of new by reference is deprecated
        $instance = new LocateBase();

        return $instance;
    }

    /**
     * @param        $country_code
     * @param string $prefix
     * @param string $dirname
     * @return bool|mixed
     */
    public function &get_instance_by_country_code($country_code, $prefix = 'happylinux', $dirname = 'happylinux')
    {
        $instance = false;
        $file     = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/locate/' . $country_code . '/local.php';
        $class    = $prefix . '_locate_' . $country_code;

        // BUG: singleton done not work correctly
        // if include file alreary
        if (class_exists($class)) {
            // Assigning the return value of new by reference is deprecated
            $instance = new $class();
        } // if NOT include file yet
        elseif (file_exists($file)) {
            require_once $file;
            if (class_exists($class)) {
                // Assigning the return value of new by reference is deprecated
                $instance = new $class();
            }
        }

        return $instance;
    }

    //---------------------------------------------------------
    // set & get parameter
    //---------------------------------------------------------
    //function set_config_handler( &$handler )
    //{
    //  $this->_configHandler =& $handler;
    //}

    /**
     * @param $dirname
     */
    public function set_dirname($dirname)
    {
        $this->_DIRNAME = $dirname;
    }

    /**
     * @return null
     */
    public function get_dirname()
    {
        return $this->_DIRNAME;
    }

    //---------------------------------------------------------
    // set & get country code
    //---------------------------------------------------------
    /**
     * @param $val
     */
    public function set_config_country_code($val)
    {
        $this->_config_country_code = $val;
    }

    /**
     * @return null
     */
    public function get_config_country_code()
    {
        return $this->_config_country_code;
    }

    //---------------------------------------------------------
    // happylinux_language_factory
    //---------------------------------------------------------
    /**
     * @return mixed
     */
    public function get_language_country_code()
    {
        $factory = LanguageFactory::getInstance();
        $code    = $factory->get_country_code();

        return $code;
    }

    // --- class end ---
}

//=========================================================
// function
//=========================================================
/**
 * @param string $format
 * @return mixed
 */
function get_happylinux_url($format = 's')
{
    $locate = LocateFactory::getInstance();

    return $locate->get_var('happylinux_url', $format);
}
