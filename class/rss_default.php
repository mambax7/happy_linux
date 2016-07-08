<?php
// $Id: rss_default.php,v 1.1 2007/11/14 11:30:03 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2007-11-11 K.OHWADA
//=========================================================

//=========================================================
// class builder base
//=========================================================
class happy_linux_rss_default
{

    // http://www.rssboard.org/rss-specification#ltimagegtSubelementOfLtchannelgt
    public $_SITE_IMAGE_WIDTH_MAX      = 144;
    public $_SITE_IMAGE_WIDTH_DEFAULT  = 88;
    public $_SITE_IMAGE_HEIGHT_MAX     = 400;
    public $_SITE_IMAGE_HEIGHT_DEFAULT = 31;

    public $_SITE_AUTHOR_NAME_UID     = 1;
    public $_SITE_AUTHOR_NAME_DEFAULT = 'xoops';
    public $_SITE_IMAGE_LOGO          = 'images/logo.gif';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_rss_default();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // site information
    //---------------------------------------------------------
    public function get_default_site_url()
    {
        return XOOPS_URL . '/';
    }

    public function get_default_site_name()
    {
        return $this->get_xoops_sitename();
    }

    public function get_default_site_desc()
    {
        return $this->get_xoops_slogan();
    }

    public function get_default_site_tag()
    {
        return $this->parse_site_tag($this->get_default_site_url());
    }

    public function get_default_site_link_self()
    {
        $val = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        return $val;
    }

    public function get_default_site_author_name()
    {
        $name = $this->get_xoops_uname_from_id($this->_SITE_AUTHOR_NAME_UID);
        if (empty($name)) {
            $name = $this->_SITE_AUTHOR_NAME_DEFAULT;
        }
        return $name;
    }

    public function get_default_site_author_email()
    {
        return $this->get_xoops_adminmail();
    }

    public function get_default_site_author_uri()
    {
        return '';
    }

    public function parse_site_tag($url)
    {
        $parse = parse_url($url);
        if (isset($parse['host'])) {
            return $parse['host'];
        }
        return false;
    }

    //---------------------------------------------------------
    // site image
    //---------------------------------------------------------
    public function get_default_site_image_logo()
    {
        return $this->_SITE_IMAGE_LOGO;
    }

    public function get_site_image_width_max()
    {
        return $this->_SITE_IMAGE_WIDTH_MAX;
    }

    public function get_site_image_height_max()
    {
        return $this->_SITE_IMAGE_HEIGHT_MAX;
    }

    public function get_site_image_size($logo)
    {
        $url    = '';
        $width  = 0;
        $height = 0;

        if (empty($logo)) {
            return array($url, $width, $height);
        }

        $url  = XOOPS_URL . '/' . $logo;
        $path = XOOPS_ROOT_PATH . '/' . $logo;

        $size = getimagesize($path);  // PHP function
        if ($size) {
            $width  = (int)$size[0];
            $height = (int)$size[1];
        }

        return array($url, $width, $height);
    }

    public function check_site_image_width($width)
    {
        $ret = 0;
        if ($width > $this->_SITE_IMAGE_WIDTH_MAX) {
            $ret = 1;
        }
        return $ret;
    }

    public function check_site_image_height($height)
    {
        $ret = 0;
        if ($height > $this->_SITE_IMAGE_HEIGHT_MAX) {
            $ret = 1;
        }
        return $ret;
    }

    //---------------------------------------------------------
    // xoops param
    //---------------------------------------------------------
    public function get_xoops_sitename()
    {
        global $xoopsConfig;
        return $xoopsConfig['sitename'];
    }

    public function get_xoops_slogan()
    {
        global $xoopsConfig;
        return $xoopsConfig['slogan'];
    }

    public function get_xoops_adminmail()
    {
        global $xoopsConfig;
        return $xoopsConfig['adminmail'];
    }

    public function get_xoops_uname_from_id($uid, $usereal = 0)
    {
        return XoopsUser::getUnameFromId($uid, $usereal);
    }

    // --- class end ---
}
