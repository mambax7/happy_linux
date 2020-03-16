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
// class KmlDocumentObject
//=========================================================

//=========================================================
// class KmlFolderObject
//=========================================================

/**
 * Class KmlFolderObject
 * @package XoopsModules\Happylinux
 */
class KmlFolderObject extends XmlSingleObject
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
        $this->set_tpl_key('folder');
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
        if (isset($item['tag_use'])) {
            $item['tag_use'] = (bool)$item['tag_use'];
        }
        if (isset($item['open_use'])) {
            $item['open_use'] = $item['open_use'];
        }
        if (isset($item['name'])) {
            $item['name'] = $this->xml_text($item['name']);
        }
        if (isset($item['description'])) {
            $item['description'] = $this->xml_text($item['description']);
        }
        if (isset($item['open'])) {
            $item['open'] = (int)$item['open'];
        }

        return $item;
    }

    // --- class end ---
}
