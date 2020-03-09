<?php

namespace XoopsModules\Happy_linux;

// $Id: build_xml.php,v 1.1 2008/02/26 15:35:42 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// this file include 4 classes
//   happy_linux_xml_base
//   happy_linux_xml_single_object
//   happy_linux_xml_iterate_object
//   happy_linux_build_xml
// 2008-02-17 K.OHWADA
//=========================================================

//=========================================================
// class xml_single_object
//=========================================================
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

    public function set_vars($val)
    {
        if (is_array($val) && count($val)) {
            $this->_vars = $val;
        }
    }

    public function get_vars()
    {
        return $this->_vars;
    }

    public function set($key, $val)
    {
        $this->_vars[$key] = $val;
    }

    public function get($key)
    {
        $ret = false;
        if (isset($this->_vars[$key])) {
            $ret = &$this->_vars[$key];
        }

        return $ret;
    }

    public function set_tpl_key($val)
    {
        $this->_TPL_KEY = $val;
    }

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

    public function _build($arr)
    {
        return $this->_build_text($arr);
    }

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
    public function assign($tpl)
    {
        $this->_assign($tpl, $this->_TPL_KEY, $this->get_vars());
    }

    public function append($tpl)
    {
        $this->_append($tpl, $this->_TPL_KEY, $this->get_vars());
    }

    public function _assign($tpl, $key, $val)
    {
        $tpl->assign($key, $val);
    }

    public function _append($tpl, $key, $val)
    {
        $tpl->append($key, $val);
    }

    // --- class end ---
}
