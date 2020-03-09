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
// class XmlIterateObject
//=========================================================
class XmlIterateObject extends XmlSingleObject
{
    public $_TPL_KEY = 'iterate';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // build
    //---------------------------------------------------------
    public function build_iterate()
    {
        $vars = $this->get_vars();
        if (is_array($vars) && count($vars)) {
            $arr = [];
            foreach ($vars as $var) {
                $arr[] = $this->_build($var);
            }
            $this->set_vars($arr);
        }
    }

    //---------------------------------------------------------
    // utf8
    //---------------------------------------------------------
    public function to_utf8_iterate()
    {
        $vars = $this->get_vars();
        if (is_array($vars) && count($vars)) {
            $arr = [];
            foreach ($vars as $var) {
                $arr[] = $this->_to_utf8($var);
            }
            $this->set_vars($arr);
        }
    }

    //---------------------------------------------------------
    // append
    //---------------------------------------------------------
    public function append_iterate($tpl)
    {
        $tpl_key = $this->get_tpl_key();
        $vars    = $this->get_vars();

        if (is_array($vars) && count($vars)) {
            foreach ($vars as $var) {
                $this->_append($tpl, $tpl_key, $var);
            }
        }
    }

    // --- class end ---
}
