<?php
// $Id: dir.php,v 1.3 2007/11/14 11:30:03 ohwada Exp $

// 2007-11-11 K.OHWADA
// get_dirs_in_dir()

// 2007-09-20 K.OHWADA
// check_open_basedir()

// 2007-06-10 K.OHWADA
// divid from file.php

// 2006-10-01 K.OHWADA
// this is new file

//=========================================================
// Happy Linux Framework Module
// 2006-10-01 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_dir
//=========================================================
//---------------------------------------------------------
// this class operate under XOOPS_ROOT_PATH
// this class has one resource handle
//---------------------------------------------------------

class happy_linux_dir extends happy_linux_error
{
    public $_dh       = null;
    public $_dir_name = null;

    // tmpolary directory
    public $_FILE_PRELOAD    = 'modules/happy_liunx/preload/dir.php';
    public $_DIR_HAPPY_CACHE = 'modules/happy_linux/cache';
    public $_DIR_UNIX_TMP    = '/tmp';

    public $_exist_preload_tmp = false;
    public $_preload_tmp       = null;
    public $_dir_work          = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $this->_preload_file();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_dir();
        }
        return $instance;
    }

    public function _preload_file()
    {
        if (file_exists(XOOPS_ROOT_PATH . '/' . $this->_FILE_PRELOAD)) {
            include_once XOOPS_ROOT_PATH . '/' . $this->_FILE_PRELOAD;
        }

        if (defined('HAPPY_LINUX_DIR_TMP')) {
            $this->_exist_preload_tmp = true;
            $this->_preload_tmp       = HAPPY_LINUX_DIR_TMP;
        }
    }

    //---------------------------------------------------------
    // basic function
    //---------------------------------------------------------
    public function opendir($dirname = null)
    {
        $this->_dh = null;

        if (empty($dirname)) {
            $dirname = $this->_dir_name;
        }

        if (!$this->check_dirname($dirname)) {
            return false;
        }

        $xoops_dir = XOOPS_ROOT_PATH . '/' . $dirname;
        $xoops_dir = $this->add_slash_to_tail($xoops_dir);

        if (!is_dir($xoops_dir)) {
            $this->_set_errors('not directory: ' . $xoops_dir);
            return false;
        }

        $dh = opendir($xoops_dir);
        if (!$dh) {
            $this->_set_errors('cannot open directory: ' . $xoops_dir);
            return false;
        }

        $this->_dh       =& $dh;
        $this->_dir_name = $dirname;
        return true;
    }

    public function closedir()
    {
        if ($this->_dh) {
            $ret = closedir($this->_dh);
            if (!$ret) {
                $this->_set_errors('cannot close directory: ' . $this->_dir_name);
                return false;   // NG
            }
        }
        return true;
    }

    public function &readdir_array()
    {
        $arr = array();
        while (false !== ($file = readdir($this->_dh))) {
            $arr[] = $file;
        }
        return $arr;
    }

    public function readdir()
    {
        return readdir($this->_dh);
    }

    public function check_dirname($dirname)
    {
        // check directory travers
        if (preg_match("|\.\./|", $dirname)) {
            $this->_set_errors('illegal directory name: ' . $dirname);
            return false;
        }
        return true;
    }

    public function set_dir_name($val)
    {
        $this->_dir_name = $val;
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------
    public function &get_all_files_attr_in_dir($dirname, $flag_sort = false, $id_as_key = false)
    {
        $arr   = array();
        $false = false;

        $dirname = $this->strip_slash_from_tail($dirname);

        $ret = $this->opendir($dirname);
        if (!$ret) {
            return $false;
        }

        foreach ($this->readdir_array() as $file) {
            $xoops_file = XOOPS_ROOT_PATH . '/' . $dirname . '/' . $file;

            $temp = array(
                'name'          => $file,
                'path'          => $xoops_file,
                'is_dir'        => is_dir($xoops_file),
                'is_file'       => is_file($xoops_file),
                'is_link'       => is_link($xoops_file),
                'is_readable'   => is_readable($xoops_file),
                'is_writable'   => is_writable($xoops_file),
                'is_executable' => is_executable($xoops_file),
                'filetype'      => filetype($xoops_file),
                'stat'          => stat($xoops_file),
            );

            if ($id_as_key) {
                $arr[$file] = $temp;
            } else {
                $arr[] = $temp;
            }
        }

        $this->closedir();

        if ($flag_sort) {
            asort($arr);
            reset($arr);
        }

        return $arr;
    }

    public function &get_dirs_in_dir($dirname, $flag_dir = false, $flag_sort = false, $id_as_key = false)
    {
        $arr   = array();
        $false = false;

        $dirname = $this->strip_slash_from_tail($dirname);

        $ret = $this->opendir($dirname);
        if (!$ret) {
            return $false;
        }

        foreach ($this->readdir_array() as $file) {
            $xoops_file = XOOPS_ROOT_PATH . '/' . $dirname . '/' . $file;

            if (is_dir($xoops_file)) {
                $file_out = $file;
                if ($flag_dir) {
                    $file_out = $dirname . '/' . $file;
                }
                if ($id_as_key) {
                    $arr[$file] = $file_out;
                } else {
                    $arr[] = $file_out;
                }
            }
        }

        $this->closedir();

        if ($flag_sort) {
            asort($arr);
            reset($arr);
        }

        return $arr;
    }

    public function &get_files_in_dir($dirname, $ext = null, $flag_dir = false, $flag_sort = false, $id_as_key = false)
    {
        $arr   = array();
        $false = false;

        $dirname = $this->strip_slash_from_tail($dirname);

        $ret = $this->opendir($dirname);
        if (!$ret) {
            return $false;
        }

        $pattern = "/\." . preg_quote($ext) . "$/";

        foreach ($this->readdir_array() as $file) {
            $xoops_file = XOOPS_ROOT_PATH . '/' . $dirname . '/' . $file;

            if (!is_dir($xoops_file) && is_file($xoops_file)) {
                if (($ext && preg_match($pattern, $file)) || ($ext === '')) {
                    $file_out = $file;
                    if ($flag_dir) {
                        $file_out = $dirname . '/' . $file;
                    }
                    if ($id_as_key) {
                        $arr[$file] = $file_out;
                    } else {
                        $arr[] = $file_out;
                    }
                }
            }
        }

        $this->closedir();

        if ($flag_sort) {
            asort($arr);
            reset($arr);
        }

        return $arr;
    }

    public function add_slash_to_tail($dir)
    {
        if (substr($dir, -1, 1) != '/') {
            $dir .= '/';
        }
        return $dir;
    }

    public function strip_slash_from_tail($dir)
    {
        if (substr($dir, -1, 1) == '/') {
            $dir = substr($dir, 0, -1);
        }
        return $dir;
    }

    //---------------------------------------------------------
    // open_basedir
    //---------------------------------------------------------
    public function get_init_dir_work()
    {
        // already set
        if ($this->_dir_work) {
            return $this->_dir_work;
        }

        // init
        return $this->init_dir_work();
    }

    public function init_dir_work()
    {
        $dir_work = null;
        $dir_tmp  = null;

        // if preload
        // admin can set null, and use 'modules/happy_linux/cache/'
        if ($this->_exist_preload_tmp) {
            $dir_tmp = $this->_preload_tmp;
        }

        // if dir_temp
        // Warning : is_dir() open_basedir restriction
        elseif ($this->check_open_basedir($this->_DIR_UNIX_TMP)) {
            $dir_tmp = $this->_DIR_UNIX_TMP;
        }

        // is_writable
        if (is_dir($dir_tmp) && is_writable($dir_tmp)) {
            $dir_work = $dir_tmp;
        }

        // default
        if (empty($dir_work)) {
            $dir_work = XOOPS_ROOT_PATH . '/' . $this->_DIR_HAPPY_CACHE;
        }

        $this->_dir_work = $dir_work;
        return $dir_work;
    }

    public function check_open_basedir($dir)
    {
        $flag_allow = false;

        $dir = trim($dir);
        if (empty($dir)) {
            return false;
        }

        $arr =& $this->get_open_basedir(true);

        // allow all directies if not set open_basedir
        if (!$arr) {
            return true;
        }

        // check directies if set open_basedir
        if (is_array($arr) && count($arr)) {
            $dir = $this->add_slash_to_tail($dir);

            foreach ($arr as $temp) {
                // $dir '/tmp/var' match open_basedir '/tmp'
                // '/tmp' unmatch '/var/tmp'
                $pat = '/^' . preg_quote($temp, '/') . '/';
                if (preg_match($pat, $dir)) {
                    $flag_allow = true;
                    break;
                }
            }
        }

        return $flag_allow;
    }

    public function &get_open_basedir($flag_slash = false)
    {
        $null = null;
        $arr2 = array();

        $open_basedir = ini_get('open_basedir');
        if (empty($open_basedir)) {
            return $null;
        }

        $arr1 = explode(':', $open_basedir);

        if (is_array($arr1) && count($arr1)) {
            foreach ($arr1 as $temp) {
                $temp = trim($temp);
                if ($temp) {
                    if ($flag_slash) {
                        $temp = $this->add_slash_to_tail($temp);
                    }
                    $arr2[] = $temp;
                }
            }
        }

        if (is_array($arr2) && count($arr2)) {
            return $arr2;
        }

        return $null;
    }

    //----- class end -----
}
