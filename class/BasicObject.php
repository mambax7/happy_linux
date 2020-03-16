<?php

namespace XoopsModules\Happylinux;

// $Id: basic_object.php,v 1.3 2012/04/10 02:29:32 ohwada Exp $

// 2012-04-02 K.OHWADA
// debug_print_backtrace()

// 2007-06-01 K.OHWADA
// divid from basicHandler

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

//=========================================================
// class basic
//=========================================================

/**
 * Class BasicObject
 * @package XoopsModules\Happylinux
 */
class BasicObject extends Strings
{
    public $_vars = [];

    public $_DEBUG = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // set & get
    //---------------------------------------------------------
    public function clear_vars()
    {
        $this->_vars = [];
    }

    /**
     * @param $key
     * @param $value
     */
    public function setVar($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * @param $key
     * @param $value
     */
    public function setVarArray($key, $value)
    {
        $this->set($key, serialize($value));
    }

    /**
     * @param        $key
     * @param string $format
     * @return array|bool|mixed|string|string[]|null
     */
    public function getVar($key, $format = 'n')
    {
        $val = $this->get($key);
        if (!is_array($val)) {
            $val = $this->sanitize_format_text($val, $format);
        }

        return $val;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function &getVarArray($key)
    {
        $val = unserialize($this->get($key));

        return $val;
    }

    /**
     * @param string $format
     * @return array
     */
    public function &getVarAll($format = 'n')
    {
        $ret = [];
        foreach ($this->_vars as $k => $v) {
            $ret[$k] = $this->getVar($k, $format);
        }

        return $ret;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->_vars[$key] = $value;
    }

    /**
     * @param $key
     * @return bool|mixed
     */
    public function &get($key)
    {
        $ret = false;
        if (isset($this->_vars[$key])) {
            $ret = &$this->_vars[$key];
        }

        if ($this->_DEBUG) {
            echo "basic_object.php get(): $key <br>\n";
            debug_print_backtrace();
        }

        return $ret;
    }

    /**
     * @param $values
     */
    public function set_vars($values)
    {
        $this->_vars = $values;
    }

    /**
     * @return array|bool
     */
    public function &get_vars()
    {
        $ret = false;
        if (isset($this->_vars)) {
            $ret = &$this->_vars;
        }

        return $ret;
    }

    /**
     * @param $values
     */
    public function merge_vars($values)
    {
        $this->_vars = array_merge($this->_vars, $values);
    }

    /**
     * @param $key
     * @return bool
     */
    public function is_set($key)
    {
        if (isset($this->_vars[$key])) {
            return true;
        }

        return false;
    }

    /**
     * @param $key
     * @return bool
     */
    public function is_array($key)
    {
        if (isset($this->_vars[$key]) && is_array($this->_vars[$key])) {
            return true;
        }

        return false;
    }
}

// --- class end ---
