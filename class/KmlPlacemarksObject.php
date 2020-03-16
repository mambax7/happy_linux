<?php

namespace XoopsModules\Happylinux;

// $Id: build_kml.php,v 1.1 2008/02/26 15:35:42 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// this file include 4 classes
//   KmlDocumentObject
//   KmlFolderObject
//   KmlPlacemarksObject
//   happylinux_build_kml
// 2008-02-17 K.OHWADA
//=========================================================

//=========================================================
// class KmlPlacemarksObject
//=========================================================

/**
 * Class KmlPlacemarksObject
 * @package XoopsModules\Happylinux
 */
class KmlPlacemarksObject extends XmlIterateObject
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
        $this->set_tpl_key('placemarks');
    }

    //---------------------------------------------------------
    // build
    //---------------------------------------------------------
    /**
     * @param $item
     * @return array
     */
    public function _build(&$item)
    {
        if (isset($item['name'])) {
            $item['name'] = $this->xml_text($item['name']);
        }
        if (isset($item['description'])) {
            $item['description'] = $this->xml_cdata($item['description']);
        }
        if (isset($item['latitude'])) {
            $item['latitude'] = (float)$item['latitude'];
        }
        if (isset($item['longitude'])) {
            $item['longitude'] = (float)$item['longitude'];
        }

        return $item;
    }

    // --- class end ---
}
