<?php

namespace XoopsModules\Happylinux;

// $Id: mail_template.php,v 1.1 2010/11/07 14:59:21 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2007-09-01 K.OHWADA
//=========================================================

//=========================================================
// class mail_template
// referrence: kernel/notification.php
//=========================================================

/**
 * Class MailTemplate
 * @package XoopsModules\Happylinux
 */
class MailTemplate
{
    public $_DIRNAME;

    public $_tags = [];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    /**
     * MailTemplate constructor.
     * @param null $dirname
     */
    public function __construct($dirname = null)
    {
        if ($dirname) {
            $this->_DIRNAME = $dirname;
        } elseif (is_object($xoopsModule)) {
            $this->_DIRNAME = $xoopsModule->dirname();
        }
    }

    /**
     * @param null $dirname
     * @return static
     */
    public static function getInstance($dirname = null)
    {
        static $instance;
        if (null === $instance) {
            $instance = new static($dirname);
        }

        return $instance;
    }

    //-------------------------------------------------------------------
    // get_dir_mail_template
    // REQ 3028: send apoval email to anonymous user
    // move from submit_form.php
    //-------------------------------------------------------------------
    /**
     * @param $file
     * @return bool|string
     */
    public function get_dir_mail_template($file)
    {
        $DIR_LANG = $this->get_module_path() . 'language/';
        $dir_lang = $DIR_LANG . $this->get_xoops_language() . '/mail_template/';
        $dir_eng  = $DIR_LANG . 'english/mail_template/';

        if (file_exists($dir_lang . $file)) {
            return $dir_lang;
        } elseif (file_exists($dir_eng . $file)) {
            return $dir_eng;
        }

        return false;
    }

    //---------------------------------------------------------
    // read template file
    //---------------------------------------------------------
    /**
     * @param $file
     * @return string|string[]
     */
    public function replace_tags_by_template($file)
    {
        return $this->replace_tags($this->read_template($file));
    }

    /**
     * @param $file
     * @return bool|false|string
     */
    public function read_template($file)
    {
        $dir = $this->get_dir_mail_template($file);
        if ($dir) {
            return $this->read_file($dir . $file);
        }

        return false;
    }

    /**
     * @param $file
     * @return bool|false|string
     */
    public function read_file($file)
    {
        $fp = fopen($file, 'rb');
        if ($fp) {
            $ret = fread($fp, filesize($file));

            return $ret;
        }

        return false;
    }

    //---------------------------------------------------------
    // assign tags
    //---------------------------------------------------------
    public function init_tags()
    {
        $this->assign('X_SITEURL', $this->get_xoops_siteurl());
        $this->assign('X_SITENAME', $this->get_xoops_sitename());
        $this->assign('X_ADMINMAIL', $this->get_xoops_adminmail());
        $this->assign('X_MODULE', $this->get_xoops_module_name());
        $this->assign('X_MODULE_URL', $this->get_module_url());
        $this->assign('X_UNSUBSCRIBE_URL', $this->get_unsubscribe_url());
    }

    /**
     * @param $tags
     */
    public function merge_tags($tags)
    {
        if (is_array($tags)) {
            $this->_tags = array_merge($this->_tags, $tags);
        }
    }

    /**
     * @param      $tag
     * @param null $value
     */
    public function assign($tag, $value = null)
    {
        if (is_array($tag)) {
            foreach ($tag as $k => $v) {
                $this->assign($k, $v);
            }
        } else {
            if (!empty($tag) && isset($value)) {
                $tag               = mb_strtoupper(trim($tag));
                $this->_tags[$tag] = $value;
            }
        }
    }

    /**
     * @param $str
     * @return string|string[]
     */
    public function replace_tags($str)
    {
        foreach ($this->_tags as $k => $v) {
            $str = str_replace('{' . $k . '}', $v, $str);
        }

        return $str;
    }

    //---------------------------------------------------------
    // get system param
    //---------------------------------------------------------
    /**
     * @return string
     */
    public function get_module_path()
    {
        return XOOPS_ROOT_PATH . '/modules/' . $this->_DIRNAME . '/';
    }

    /**
     * @return string
     */
    public function get_module_url()
    {
        return XOOPS_URL . '/modules/' . $this->_DIRNAME . '/';
    }

    /**
     * @return string
     */
    public function get_unsubscribe_url()
    {
        return XOOPS_URL . '/notifications.php';
    }

    /**
     * @return string
     */
    public function get_xoops_siteurl()
    {
        return XOOPS_URL . '/';
    }

    /**
     * @return mixed
     */
    public function get_xoops_sitename()
    {
        global $xoopsConfig;

        return $xoopsConfig['sitename'];
    }

    /**
     * @return mixed
     */
    public function get_xoops_adminmail()
    {
        global $xoopsConfig;

        return $xoopsConfig['adminmail'];
    }

    /**
     * @return mixed
     */
    public function get_xoops_language()
    {
        global $xoopsConfig;

        return $xoopsConfig['language'];
    }

    /**
     * @param string $format
     * @return bool
     */
    public function get_xoops_module_name($format = 'n')
    {
        $name           = false;
        $moduleHandler = xoops_getHandler('module');
        $module         = $moduleHandler->getByDirname($this->_DIRNAME);
        if (is_object($module)) {
            $name = $module->getVar('name', $format);
        }

        return $name;
    }

    // --- class end ---
}
