<?php

namespace XoopsModules\Happylinux;

// $Id: rss_parse_object.php,v 1.3 2012/03/17 16:08:32 ohwada Exp $

// 2012-03-01 K.OHWADA
// <geo:lat>lat</geo:lat>

// 2010-11-07 K.OHWADA
// BUG: NOT parse https://maps.google.co.jp/maps/

// 2009-02-20 K.OHWADA
// _build_geo() _build_media_content()

// 2008-01-30 K.OHWADA
// typo: create_item_singlel

// 2007-09-20 K.OHWADA
// PHP5.2
// Assigning the return value of new by reference is deprecated
// Declaration of RssParseChannel::convert() should be compatible with that of RssParseBasic::convert()
// Declaration of RssParseItemSingle::build() should be compatible with that of RssParseBasic::build() in happylinux\class\rss_parse_object.php
// Non-static method Happylinux\ConvertEncoding::getInstance() should not be called statically, assuming $this from incompatible context

// 2007-08-01 K.OHWADA
// strip_control_array()

// 2007-06-01 K.OHWADA
// divid from rss_object.php

// 2007-05-12 K.OHWADA
// this is new file
// porting form rssc_xml_object.php

//=========================================================
// Happy Linux Framework Module
// this file contains 7 classes
//   happylinux_rss_parse
//   RssParseBasic
//   RssParseChannel
//   RssParseImage
//   RssParseTextinput
//   RssParseItems
//   RssParseItemSingle
// 2007-05-12 K.OHWADA
//=========================================================

//=========================================================
// class RssParseTextinput
//=========================================================

/**
 * Class RssParseTextinput
 * @package XoopsModules\Happylinux
 */
class RssParseTextinput extends RssParseBasic
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // convert parse to local
    //---------------------------------------------------------
    /**
     * @param $to
     * @param $from
     */
    public function convert($to, $from)
    {
        $arr = &$this->_convert_block($this->get_vars(), $to, $from);
        $this->set_vars($arr);
    }

    // --- class end ---
}
