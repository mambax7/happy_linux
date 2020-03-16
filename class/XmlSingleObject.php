<?php

namespace XoopsModules\Happylinux;

// $Id: build_xml.php,v 1.1 2008/02/26 15:35:42 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// this file include 4 classes
//   happylinux_xml_base
//   happylinux_xml_single_object
//   happylinux_xml_iterate_object
//   happylinux_build_xml
// 2008-02-17 K.OHWADA
//=========================================================

//=========================================================
// class xml_single_object
//=========================================================

/**
 * Class XmlSingleObject
 * @package XoopsModules\Happylinux
 */
class XmlSingleObject extends XmlBase
{
    public $_vars    = [];
    public $_TPL_KEY = 'single';

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
     * @param $val
     */
    public function set_vars($val)
    {
        if (is_array($val) && count($val)) {
            $this->_vars = $val;
        }
    }

    /**
     * @return array
     */
    public function get_vars()
    {
        return $this->_vars;
    }

    /**
     * @param $key
     * @param $val
     */
    public function set($key, $val)
    {
        $this->_vars[$key] = $val;
    }

    /**
     * @param $key
     * @return bool|mixed
     */
    public function get($key)
    {
        $ret = false;
        if (isset($this->_vars[$key])) {
            $ret = &$this->_vars[$key];
        }

        return $ret;
    }

    /**
     * @param $val
     */
    public function set_tpl_key($val)
    {
        $this->_TPL_KEY = $val;
    }

    /**
     * @return string
     */
    public function get_tpl_key()
    {
        return $this->_TPL_KEY;
    }

    //---------------------------------------------------------
    // build
    //---------------------------------------------------------
    public function build()
    {
        $arr  = [];
        $vars = $this->get_vars();
        if (is_array($vars) && count($vars)) {
            $this->set_vars($this->_build($vars));
        }
    }

    /**
     * @param $arr
     * @return array
     */
    public function _build($arr)
    {
        return $this->_build_text($arr);
    }

    /**
     * @param $arr
     * @return array
     */
    public function _build_text($arr)
    {
        $ret = [];
        foreach ($arr as $k => $v) {
            if (!is_array($v)) {
                $ret[$k] = $this->xml_text($v);
            }
        }

        return $ret;
    }

    //---------------------------------------------------------
    // utf8
    //---------------------------------------------------------
    public function to_utf8()
    {
        $arr  = [];
        $vars = $this->get_vars();
        if (is_array($vars) && count($vars)) {
            $this->set_vars($this->_to_utf8($vars));
        }
    }

    /**
     * @param $arr
     * @return array
     */
    public function _to_utf8($arr)
    {
        $ret = [];
        foreach ($arr as $k => $v) {
            if (!is_array($v)) {
                $ret[$k] = $this->xml_utf8($v);
            }
        }

        return $ret;
    }

    //---------------------------------------------------------
    // assign
    //---------------------------------------------------------
    /**
     * @param $tpl
     */
    public function assign($tpl)
    {
        $this->_assign($tpl, $this->_TPL_KEY, $this->get_vars());
    }

    /**
     * @param $tpl
     */
    public function append($tpl)
    {
        $this->_append($tpl, $this->_TPL_KEY, $this->get_vars());
    }

    /**
     * @param $tpl
     * @param $key
     * @param $val
     */
    public function _assign($tpl, $key, $val)
    {
        $tpl->assign($key, $val);
    }

    /**
     * @param $tpl
     * @param $key
     * @param $val
     */
    public function _append($tpl, $key, $val)
    {
        $tpl->append($key, $val);
    }

    // --- class end ---
}
