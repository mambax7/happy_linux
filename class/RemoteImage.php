<?php

namespace XoopsModules\Happylinux;

// $Id: remote_image.php,v 1.8 2007/11/02 11:14:12 ohwada Exp $

// 2007-10-10 K.OHWADA
// not use happylinux_dir

// 2007-09-20 K.OHWADA
// Warning : is_dir() open_basedir restriction
// happylinux_dir

// 2007-05-12 K.OHWADA
// change happylinux_remote_image()
// use "/tmp" in UNIX

// 2006-11-19 K.OHWADA
// BUG 4379: Undefined property: _flag_allow_url_fopen
// change $_flag_allow_url_fopen to $_remote_mode

// 2006-09-10 K.OHWADA
// change return value

// 2006-07-10 K.OHWADA
// this is new file
// porting from weblinks_remote_image.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// define constant
//---------------------------------------------------------
define('HAPPYLINUX_REMOTE_CODE_NOT_WRITABLE', 21);

//=========================================================
// class  happylinux_remote_image
// requre happylinux_dir
//=========================================================

/**
 * Class RemoteImage
 * @package XoopsModules\Happylinux
 */
class RemoteImage extends RemoteFile
{
    public $_dir_work = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        // caller can change
        $this->_dir_work = XOOPS_ROOT_PATH . '/modules/happylinux/cache';
    }

    /**
     * @return \XoopsModules\Happylinux\RemoteFile|\XoopsModules\Happylinux\RemoteImage|static
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
    //---------------------------------------------------------
    // get_image_size
    // return is same as getimagesize()
    // array of width, height, type, attr
    //---------------------------------------------------------
    /**
     * @param $url
     * @return array|bool|false
     */
    public function &get_image_size($url)
    {
        $false = false;

        $this->_clear_errors();

        // add check https://
        if (empty($url) || ('https://' == $url) || ('https://' == $url)) {
            $this->_set_error_code(HAPPYLINUX_REMOTE_CODE_EMPTY_URL);
            $this->_set_errors('remote url is empty');

            return $false;
        }

        // BUG 4379: Undefined property: _flag_allow_url_fopen
        switch ($this->_remote_mode) {
            case 1:
                $size = &$this->_get_image_size_remote($url);
                break;
            case 0:
            default:
                $size = &$this->_get_image_size_local($url);
                break;
        }

        return $size;
    }

    //---------------------------------------------------------
    // set and get property
    //---------------------------------------------------------
    /**
     * @param $value
     */
    public function set_dir_work($value)
    {
        $this->_dir_work = $value;
    }

    /**
     * @return string|null
     */
    public function get_dir_work()
    {
        return $this->_dir_work;
    }

    //=========================================================
    // private
    //=========================================================
    /**
     * @param $url
     * @return array|false
     */
    public function &_get_image_size_local($url)
    {
        $size = getimagesize($url);

        return $size;
    }

    /**
     * @param $url
     * @return array|bool|false
     */
    public function &_get_image_size_remote($url)
    {
        $false = false;

        if (!is_writable($this->_dir_work)) {
            $this->_set_error_code(HAPPYLINUX_ERR_REMOTE_NOT_WRITABLE);
            $this->_set_errors('work directory is not writable : ' . $this->_dir_work);

            return $false;
        }

        $data = $this->_read_file_remote($url);
        if (!$data) {
            return $false;
        }

        $file = tempnam($this->_dir_work, 'image');

        if (!$this->write_file_local($file, $data)) {
            return $false;
        }

        $size = getimagesize($file);

        unlink($file);

        return $size;
    }

    // --- class end ---
}
