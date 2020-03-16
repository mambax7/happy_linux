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
// class build_kml
//=========================================================

/**
 * Class build_kml
 * @package XoopsModules\Happylinux
 */
class KmlBuild extends XmlBuild
{
    public $_CONTENT_TYPE_KML    = 'Content-Type: application/vnd.google-earth.kml+xml';
    public $_CONTENT_DISPOSITION = 'Content-Disposition: attachment; filename=%s';
    public $_FILENAME_KML        = 'happylinux.kml';

    public $_DIRNAME = null;

    public $_DOCUMENT_TAG_USE     = false;
    public $_DOCUMENT_OPEN_USE    = false;
    public $_DOCUMENT_OPEN        = '1';
    public $_DOCUMENT_NAME        = 'happy linux';
    public $_DOCUMENT_DESCRIPTION = null;

    public $_FOLDER_TAG_USE     = false;
    public $_FOLDER_OPEN_USE    = false;
    public $_FOLDER_OPEN        = '1';
    public $_FOLDER_NAME        = 'happy linux';
    public $_FOLDER_DESCRIPTION = null;

    public $_DOCUMENT_NAME_TPL = '{SITE_NAME} - {MODULE_NAME}';
    public $_FOLDER_NAME_TPL   = 'page {PAGE}';

    public $_page = null;

    // object
    public $_obj_document   = null;
    public $_obj_folder     = null;
    public $_obj_placemarks = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
        $this->set_view_title('Google KML');
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    //=========================================================
    // public
    //=========================================================
    public function build_kml()
    {
        happylinux_http_output('pass');
        header($this->_CONTENT_TYPE_KML);
        header(sprintf($this->_CONTENT_DISPOSITION, $this->_FILENAME_KML));

        echo $this->_build_template($this->_get_template());
    }

    public function view_kml()
    {
        $this->view_xml();
    }

    //--------------------------------------------------------
    // set param
    //--------------------------------------------------------
    /**
     * @param $val
     */
    public function set_dirname($val)
    {
        $this->_DIRNAME = $val;
    }

    /**
     * @param $val
     */
    public function set_document_tag_use($val)
    {
        $this->_DOCUMENT_TAG_USE = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_document_open_use($val)
    {
        $this->_DOCUMENT_OPEN_USE = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_document_open($val)
    {
        $this->_DOCUMENT_OPEN = (int)$val;
    }

    /**
     * @param $val
     */
    public function set_document_name($val)
    {
        $this->_DOCUMENT_NAME = $val;
    }

    /**
     * @param $val
     */
    public function set_document_description($val)
    {
        $this->_DOCUMENT_DESCRIPTION = $val;
    }

    /**
     * @param $val
     */
    public function set_folder_tag_use($val)
    {
        $this->_FOLDER_TAG_USE = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_folder_open_use($val)
    {
        $this->_FOLDER_OPEN_USE = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_folder_open($val)
    {
        $this->_FOLDER_OPEN = (int)$val;
    }

    /**
     * @param $val
     */
    public function set_folder_name($val)
    {
        $this->_FOLDER_NAME = $val;
    }

    /**
     * @param $val
     */
    public function set_folder_description($val)
    {
        $this->_FOLDER_DESCRIPTION = $val;
    }

    /**
     * @param $val
     */
    public function set_page($val)
    {
        $this->_page = (int)$val;
    }

    /**
     * @return string|string[]
     */
    public function build_document_name()
    {
        return $this->_build_name($this->_DOCUMENT_NAME_TPL);
    }

    /**
     * @return string|string[]
     */
    public function build_folder_name()
    {
        return $this->_build_name($this->_FOLDER_NAME_TPL);
    }

    //--------------------------------------------------------
    // private
    //--------------------------------------------------------
    /**
     * @return array
     */
    public function _get_document_param()
    {
        $arr = [
            'tag_use'     => $this->_DOCUMENT_TAG_USE,
            'open_use'    => $this->_DOCUMENT_OPEN_USE,
            'name'        => $this->_DOCUMENT_NAME,
            'description' => $this->_DOCUMENT_DESCRIPTION,
            'open'        => $this->_DOCUMENT_OPEN,
        ];

        return $arr;
    }

    /**
     * @return array
     */
    public function _get_folder_param()
    {
        $arr = [
            'tag_use'     => $this->_FOLDER_TAG_USE,
            'open_use'    => $this->_FOLDER_OPEN_USE,
            'name'        => $this->_FOLDER_NAME,
            'description' => $this->_FOLDER_DESCRIPTION,
            'open'        => $this->_FOLDER_OPEN,
        ];

        return $arr;
    }

    /**
     * @param $str
     * @return string|string[]
     */
    public function _build_name($str)
    {
        $str = str_replace('{SITE_NAME}', $this->get_xoops_sitename(), $str);
        if ($this->_DIRNAME) {
            $str = str_replace('{MODULE_NAME}', $this->get_xoops_module_name($this->_DIRNAME), $str);
        }
        if (null !== $this->_page) {
            $str = str_replace('{PAGE}', $this->_page, $str);
        }

        return $str;
    }

    //=========================================================
    // override for caller
    //=========================================================
    public function init_obj()
    {
        $this->_obj_document   = new KmlDocumentObject();
        $this->_obj_folder     = new KmlFolderObject();
        $this->_obj_placemarks = new KmlPlacemarksObject();
    }

    /**
     * @param $val
     */
    public function set_placemarks($val)
    {
        $this->_obj_placemarks->set_vars($val);
    }

    /**
     * @param $template
     * @return mixed
     */
    public function _build_template($template)
    {
        $this->_obj_document->set_vars($this->_get_document_param());
        $this->_obj_document->build();
        $this->_obj_document->to_utf8();

        $this->_obj_folder->set_vars($this->_get_folder_param());
        $this->_obj_folder->build();
        $this->_obj_folder->to_utf8();

        $this->_obj_placemarks->build_iterate();
        $this->_obj_placemarks->to_utf8_iterate();

        $tpl = new \XoopsTpl();

        $this->_obj_document->assign($tpl);
        $this->_obj_folder->assign($tpl);
        $this->_obj_placemarks->append_iterate($tpl);

        return $tpl->fetch($template);
    }

    // --- class end ---
}
