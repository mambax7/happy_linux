<?php
// $Id: mail_template.php,v 1.1 2007/09/15 06:47:26 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2007-09-01 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_mail_template
// referrence: kernel/notification.php
//=========================================================
class happy_linux_mail_template
{
    public $_DIRNAME;

    public $_tags = array();

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname = null)
    {
        if ($dirname) {
            $this->_DIRNAME = $dirname;
        } elseif (is_object($xoopsModule)) {
            $this->_DIRNAME = $xoopsModule->dirname();
        }
    }

    public static function getInstance($dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_mail_template($dirname);
        }
        return $instance;
    }

    //-------------------------------------------------------------------
    // get_dir_mail_template
    // REQ 3028: send apoval email to anonymous user
    // move from submit_form.php
    //-------------------------------------------------------------------
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
    public function replace_tags_by_template($file)
    {
        return $this->replace_tags($this->read_template($file));
    }

    public function read_template($file)
    {
        $dir = $this->get_dir_mail_template($file);
        if ($dir) {
            return $this->read_file($dir . $file);
        }
        return false;
    }

    public function read_file($file)
    {
        $fp = fopen($file, 'r');
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

    public function merge_tags($tags)
    {
        if (is_array($tags)) {
            $this->_tags = array_merge($this->_tags, $tags);
        }
    }

    public function assign($tag, $value = null)
    {
        if (is_array($tag)) {
            foreach ($tag as $k => $v) {
                $this->assign($k, $v);
            }
        } else {
            if (!empty($tag) && isset($value)) {
                $tag               = strtoupper(trim($tag));
                $this->_tags[$tag] = $value;
            }
        }
    }

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
    public function get_module_path()
    {
        return XOOPS_ROOT_PATH . '/modules/' . $this->_DIRNAME . '/';
    }

    public function get_module_url()
    {
        return XOOPS_URL . '/modules/' . $this->_DIRNAME . '/';
    }

    public function get_unsubscribe_url()
    {
        return XOOPS_URL . '/notifications.php';
    }

    public function get_xoops_siteurl()
    {
        return XOOPS_URL . '/';
    }

    public function get_xoops_sitename()
    {
        global $xoopsConfig;
        return $xoopsConfig['sitename'];
    }

    public function get_xoops_adminmail()
    {
        global $xoopsConfig;
        return $xoopsConfig['adminmail'];
    }

    public function get_xoops_language()
    {
        global $xoopsConfig;
        return $xoopsConfig['language'];
    }

    public function get_xoops_module_name($format = 'n')
    {
        $name           = false;
        $module_handler = xoops_getHandler('module');
        $module         = $module_handler->getByDirname($this->_DIRNAME);
        if (is_object($module)) {
            $name = $module->getVar('name', $format);
        }
        return $name;
    }

    // --- class end ---
}
