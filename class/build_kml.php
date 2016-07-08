<?php
// $Id: build_kml.php,v 1.1 2008/02/26 15:35:42 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// this file include 4 classes
//   happy_linux_kml_document_object
//   happy_linux_kml_folder_object
//   happy_linux_kml_placemarks_object
//   happy_linux_build_kml
// 2008-02-17 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_kml_document_object
//=========================================================
class happy_linux_kml_document_object extends happy_linux_xml_single_object
{

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
        $this->set_tpl_key('document');
    }

    //---------------------------------------------------------
    // build
    //---------------------------------------------------------
    public function _build(&$item)
    {
        if (isset($item['tag_use'])) {
            $item['tag_use'] = (bool)$item['tag_use'];
        }
        if (isset($item['open_use'])) {
            $item['open_use'] = (bool)$item['open_use'];
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

//=========================================================
// class happy_linux_kml_folder_object
//=========================================================
class happy_linux_kml_folder_object extends happy_linux_xml_single_object
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
    public function _build(&$item)
    {
        if (isset($item['tag_use'])) {
            $item['tag_use'] = (bool)$item['tag_use'];
        }
        if (isset($item['open_use'])) {
            $item['open_use'] = (bool)$item['open_use'];
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

//=========================================================
// class happy_linux_kml_placemarks_object
//=========================================================
class happy_linux_kml_placemarks_object extends happy_linux_xml_iterate_object
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

//=========================================================
// class happy_linux_build_kml
//=========================================================
class happy_linux_build_kml extends happy_linux_build_xml
{
    public $_CONTENT_TYPE_KML    = 'Content-Type: application/vnd.google-earth.kml+xml';
    public $_CONTENT_DISPOSITION = 'Content-Disposition: attachment; filename=%s';
    public $_FILENAME_KML        = 'happy_linux.kml';

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

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_build_kml();
        }
        return $instance;
    }

    //=========================================================
    // public
    //=========================================================
    public function build_kml()
    {
        happy_linux_http_output('pass');
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
    public function set_dirname($val)
    {
        $this->_DIRNAME = $val;
    }

    public function set_document_tag_use($val)
    {
        $this->_DOCUMENT_TAG_USE = (bool)$val;
    }

    public function set_document_open_use($val)
    {
        $this->_DOCUMENT_OPEN_USE = (bool)$val;
    }

    public function set_document_open($val)
    {
        $this->_DOCUMENT_OPEN = (int)$val;
    }

    public function set_document_name($val)
    {
        $this->_DOCUMENT_NAME = $val;
    }

    public function set_document_description($val)
    {
        $this->_DOCUMENT_DESCRIPTION = $val;
    }

    public function set_folder_tag_use($val)
    {
        $this->_FOLDER_TAG_USE = (bool)$val;
    }

    public function set_folder_open_use($val)
    {
        $this->_FOLDER_OPEN_USE = (bool)$val;
    }

    public function set_folder_open($val)
    {
        $this->_FOLDER_OPEN = (int)$val;
    }

    public function set_folder_name($val)
    {
        $this->_FOLDER_NAME = $val;
    }

    public function set_folder_description($val)
    {
        $this->_FOLDER_DESCRIPTION = $val;
    }

    public function set_page($val)
    {
        $this->_page = (int)$val;
    }

    public function build_document_name()
    {
        return $this->_build_name($this->_DOCUMENT_NAME_TPL);
    }

    public function build_folder_name()
    {
        return $this->_build_name($this->_FOLDER_NAME_TPL);
    }

    //--------------------------------------------------------
    // private
    //--------------------------------------------------------
    public function _get_document_param()
    {
        $arr = array(
            'tag_use'     => $this->_DOCUMENT_TAG_USE,
            'open_use'    => $this->_DOCUMENT_OPEN_USE,
            'name'        => $this->_DOCUMENT_NAME,
            'description' => $this->_DOCUMENT_DESCRIPTION,
            'open'        => $this->_DOCUMENT_OPEN,
        );

        return $arr;
    }

    public function _get_folder_param()
    {
        $arr = array(
            'tag_use'     => $this->_FOLDER_TAG_USE,
            'open_use'    => $this->_FOLDER_OPEN_USE,
            'name'        => $this->_FOLDER_NAME,
            'description' => $this->_FOLDER_DESCRIPTION,
            'open'        => $this->_FOLDER_OPEN,
        );

        return $arr;
    }

    public function _build_name($str)
    {
        $str = str_replace('{SITE_NAME}', $this->get_xoops_sitename(), $str);
        if ($this->_DIRNAME) {
            $str = str_replace('{MODULE_NAME}', $this->get_xoops_module_name($this->_DIRNAME), $str);
        }
        if (!is_null($this->_page)) {
            $str = str_replace('{PAGE}', $this->_page, $str);
        }
        return $str;
    }

    //=========================================================
    // override for caller
    //=========================================================
    public function init_obj()
    {
        $this->_obj_document   = new happy_linux_kml_document_object();
        $this->_obj_folder     = new happy_linux_kml_folder_object();
        $this->_obj_placemarks = new happy_linux_kml_placemarks_object();
    }

    public function set_placemarks($val)
    {
        $this->_obj_placemarks->set_vars($val);
    }

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

        $tpl = new XoopsTpl();

        $this->_obj_document->assign($tpl);
        $this->_obj_folder->assign($tpl);
        $this->_obj_placemarks->append_iterate($tpl);

        return $tpl->fetch($template);
    }

    // --- class end ---
}
