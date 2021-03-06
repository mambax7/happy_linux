<?php

namespace XoopsModules\Happylinux;

// $Id: file.php,v 1.5 2007/06/17 03:19:52 ohwada Exp $

// 2007-06-10 K.OHWADA
// divid to dir.php
// file pointer

// 2006-10-01 K.OHWADA
// this is new file

//=========================================================
// Happy Linux Framework Module
// 2006-10-01 K.OHWADA
//=========================================================

//=========================================================
// class file
//=========================================================
//---------------------------------------------------------
// this class operate under XOOPS_ROOT_PATH
// this class has one resource handle
//---------------------------------------------------------

/**
 * Class File
 * @package XoopsModules\Happylinux
 */
class File extends Error
{
    public $_fp          = null;
    public $_file_name   = null;
    public $_file_mode   = null;
    public $_flag_write  = false;
    public $_CHMOD_MODE  = 0666;
    public $_DATE_FORMAT = 'Y-m-d H:i:s';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \XoopsModules\Happylinux\File|static
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // file pointer
    //---------------------------------------------------------
    /**
     * @param null $filename
     * @param null $mode
     * @return bool
     */
    public function fopen($filename = null, $mode = null)
    {
        $this->_fp = null;

        if (empty($filename)) {
            $filename = $this->_file_name;
        }

        if (!$this->check_filename($filename)) {
            return false;
        }

        $xoops_filename = XOOPS_ROOT_PATH . '/' . $filename;

        if (empty($mode)) {
            if ($this->_file_mode) {
                $mode = $this->_file_mode;
            } else {
                $mode = 'r';
            }
        }

        switch ($mode) {
            case 'w':
            case 'a':
                if (!$this->_flag_write) {
                    $this->_set_errors('flag write is not set');

                    return false;   // NG
                }
                break;
            case 'x':
                if (!$this->_flag_write) {
                    $this->_set_errors('flag write is not set');

                    return false;   // NG
                }
                if (file_exists($xoops_filename)) {
                    $this->_set_errors('file already exists: ' . $xoops_filename);

                    return false;   // NG
                }
                break;
            case 'r':
            default:
                if (!is_readable($xoops_filename)) {
                    $this->_set_errors('file is not readable: ' . $xoops_filename);

                    return false;   // NG
                }
                break;
        }

        $fp = fopen($xoops_filename, $mode);
        if (!$fp) {
            $this->_set_errors('cannot open file: ' . $xoops_filename);

            return false;   // NG
        }

        $this->_fp        = $fp;
        $this->_file_name = $filename;

        return true;
    }

    /**
     * @return bool
     */
    public function fclose()
    {
        if ($this->_fp) {
            $ret = fclose($this->_fp);
            if (!$ret) {
                $this->_set_errors('cannot close file: ' . $this->_file_name);

                return false;   // NG
            }
        }

        return true;
    }

    /**
     * @param bool $flag_chmod
     */
    public function fclose_chmod($flag_chmod = false)
    {
        $this->fclose();
        if ($flag_chmod) {
            $this->chmod_if_owner();
        }
    }

    /**
     * @param $data
     * @return bool
     */
    public function fwrite($data)
    {
        if ($this->_flag_write && $this->_fp) {
            $ret = fwrite($this->_fp, $data);
            if (!$ret) {
                $this->_set_errors('cannot write file: ' . $data);

                return false;   // NG
            }
        }

        return true;
    }

    /**
     * @param      $data
     * @param bool $flag_date
     * @param bool $flag_nl
     * @return bool
     */
    public function fwrite_with_date($data, $flag_date = false, $flag_nl = false)
    {
        if ($flag_date) {
            $data = $this->date() . ' ' . $data;
        }
        if ($flag_nl) {
            $data = $data . "\n";
        }

        return $this->fwrite($data);
    }

    /**
     * @return false|string
     */
    public function fread()
    {
        $xoops_filename = XOOPS_ROOT_PATH . '/' . $this->_file_name;
        $ret            = fread($this->_fp, filesize($xoops_filename));

        return $ret;
    }

    /**
     * @return array
     */
    public function &fgets_array()
    {
        $arr = [];
        while (!feof($this->_fp)) {
            $arr[] = fgets($this->_fp);
        }

        return $arr;
    }

    /**
     * @param $filename
     * @return bool
     */
    public function check_filename($filename)
    {
        // check directory travers
        if (preg_match("|\.\./|", $filename)) {
            $this->_set_errors('illegal file name: ' . $filename);

            return false;
        }

        return true;
    }

    /**
     * @param $val
     */
    public function set_file_name($val)
    {
        $this->_file_name = $val;
    }

    /**
     * @param $val
     */
    public function set_file_mode($val)
    {
        $this->_file_mode = $val;
    }

    /**
     * @param $val
     */
    public function set_flag_write($val)
    {
        $this->_flag_write = (bool)$val;
    }

    //---------------------------------------------------------
    // file name
    //---------------------------------------------------------
    /**
     * @param null $filenam
     * @return array|bool|false
     */
    public function &read($filenam = null)
    {
        $ret = $this->file($filename);

        return $ret;
    }

    /**
     * @param null $filenam
     * @return array|bool|false
     */
    public function file($filenam = null)
    {
        if (empty($filename)) {
            $filename = $this->_file_name;
        }
        if (!$this->check_filename($filename)) {
            return false;
        }
        $xoops_filename = XOOPS_ROOT_PATH . '/' . $filename;
        $ret            = file($xoops_filename);

        return $ret;
    }

    // give permission to orignal user and apache

    /**
     * @param null $filename
     * @param null $mode
     * @param bool $flag_error
     * @return bool
     */
    public function chmod_if_owner($filename = null, $mode = null, $flag_error = true)
    {
        if (getmyuid() == $this->fileowner($filename, $flag_error)) {
            return $this->chmod($filename, $mode);
        }

        if ($flag_error) {
            $this->_set_errors('you are not owner: ' . $filename);

            return false;   // NG
        }

        return true;    // no action
    }

    /**
     * @param null $filename
     * @param bool $flag_error
     * @return bool|false|int
     */
    public function fileowner($filename = null, $flag_error = true)
    {
        if (empty($filename)) {
            $filename = $this->_file_name;
        }

        $xoops_filename = XOOPS_ROOT_PATH . '/' . $filename;
        $uid            = fileowner($xoops_filename);
        if (!$uid) {
            $this->_set_errors('cannot get fileowner: ' . $xoops_filename);

            return false;   // NG
        }

        return $uid;
    }

    /**
     * @param null $filename
     * @param null $mode
     * @return bool
     */
    public function chmod($filename = null, $mode = null)
    {
        if (empty($filename)) {
            $filename = $this->_file_name;
        }

        if (empty($mode)) {
            $mode = $this->_CHMOD_MODE;
        }

        $xoops_filename = XOOPS_ROOT_PATH . '/' . $filename;
        $ret            = chmod($xoops_filename, $mode);
        if (!$ret) {
            $this->_set_errors('cannot chmod file: ' . $xoops_filename);

            return false;   // NG
        }

        return true;
    }

    /**
     * @param null $filename
     * @return bool
     */
    public function unlink($filename = null)
    {
        if (empty($filename)) {
            $filename = $this->_file_name;
        }

        $xoops_filename = XOOPS_ROOT_PATH . '/' . $filename;
        $ret            = unlink($xoops_filename);
        if (!$ret) {
            $this->_set_errors('cannot unlink file: ' . $xoops_filename);

            return false;   // NG
        }

        return true;
    }

    /**
     * @param $val
     */
    public function set_chmod_mode($val)
    {
        $this->_CHMOD_MODE = (int)$val;
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------
    /**
     * @param null $format
     * @param null $timestamp
     * @return false|string
     */
    public function date($format = null, $timestamp = null)
    {
        if (empty($format)) {
            $format = $this->_DATE_FORMAT;
        }
        if (empty($timestamp)) {
            $timestamp = time();
        }

        return date($format, $timestamp);
    }

    /**
     * @param $val
     */
    public function set_date_format($val)
    {
        $this->_DATE_FORMAT = (int)$val;
    }

    //----- class end -----
}
