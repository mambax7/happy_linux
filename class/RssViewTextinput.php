<?php

namespace XoopsModules\Happylinux;

// $Id: rss_view_object.php,v 1.2 2010/11/07 19:26:32 ohwada Exp $

// 2010-11-07 K.OHWADA
// _substitute_link()

// 2008-01-20 K.OHWADA
// divid to rss_view_object.php
// Assigning the return value of new by reference is deprecated

// 2007-10-10 K.OHWADA
// set_is_japanese()
// BUG: preg_match() expects parameter 2 to be string, array given in w3cdtf.php
// get() -> get_rss_var()

// 2007-08-01 K.OHWADA
// BUG: not sanitize site_url

// 2007-06-01 K.OHWADA
// RssViewItemSingle()

// 2007-05-12 K.OHWADA
// this is new file
// porting form rssc_xml_object.php

//=========================================================
// Happy Linux Framework Module
// this file contains 7 classes
//   RssView
//   happylinux_rss_view_basic
//   RssViewChannel
//   RssViewImage
//   RssViewTextinput
//   RssViewItems
//   RssViewItemSingle
// 2007-05-12 K.OHWADA
//=========================================================

//=========================================================
// class RssViewTextinput
//=========================================================

/**
 * Class RssViewTextinput
 * @package XoopsModules\Happylinux
 */
class RssViewTextinput extends RssViewBasic
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // sanitize
    //---------------------------------------------------------
    public function sanitize()
    {
        $arr = [];

        foreach ($this->get_vars() as $k => $v) {
            switch ($k) {
                case 'link':
                    $val = $this->_sanitize_html_url($v);
                    break;
                default:
                    $val = $this->_sanitize_html_text($v);
                    break;
            }

            $arr[$k] = $val;
        }

        if (count($arr) > 0) {
            $arr['show'] = 1;
        }

        $this->set_vars($arr);
    }

    // --- class end ---
}
