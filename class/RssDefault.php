<?php

namespace XoopsModules\Happylinux;

// $Id: rss_default.php,v 1.1 2007/11/14 11:30:03 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2007-11-11 K.OHWADA
//=========================================================

//=========================================================
// class builder base
//=========================================================

/**
 * Class RssDefault
 * @package XoopsModules\Happylinux
 */
class RssDefault
{
    // https://www.rssboard.org/rss-specification#ltimagegtSubelementOfLtchannelgt
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

    //---------------------------------------------------------
    // site information
    //---------------------------------------------------------
    /**
     * @return string
     */
    public function get_default_site_url()
    {
        return XOOPS_URL . '/';
    }

    /**
     * @return mixed
     */
    public function get_default_site_name()
    {
        return $this->get_xoops_sitename();
    }

    /**
     * @return mixed
     */
    public function get_default_site_desc()
    {
        return $this->get_xoops_slogan();
    }

    /**
     * @return bool|mixed
     */
    public function get_default_site_tag()
    {
        return $this->parse_site_tag($this->get_default_site_url());
    }

    /**
     * @return string
     */
    public function get_default_site_link_self()
    {
        $val = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        return $val;
    }

    /**
     * @return mixed|string
     */
    public function get_default_site_author_name()
    {
        $name = $this->get_xoops_uname_from_id($this->_SITE_AUTHOR_NAME_UID);
        if (empty($name)) {
            $name = $this->_SITE_AUTHOR_NAME_DEFAULT;
        }

        return $name;
    }

    /**
     * @return mixed
     */
    public function get_default_site_author_email()
    {
        return $this->get_xoops_adminmail();
    }

    /**
     * @return string
     */
    public function get_default_site_author_uri()
    {
        return '';
    }

    /**
     * @param $url
     * @return bool|mixed
     */
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
    /**
     * @return string
     */
    public function get_default_site_image_logo()
    {
        return $this->_SITE_IMAGE_LOGO;
    }

    /**
     * @return int
     */
    public function get_site_image_width_max()
    {
        return $this->_SITE_IMAGE_WIDTH_MAX;
    }

    /**
     * @return int
     */
    public function get_site_image_height_max()
    {
        return $this->_SITE_IMAGE_HEIGHT_MAX;
    }

    /**
     * @param $logo
     * @return array
     */
    public function get_site_image_size($logo)
    {
        $url    = '';
        $width  = 0;
        $height = 0;

        if (empty($logo)) {
            return [$url, $width, $height];
        }

        $url  = XOOPS_URL . '/' . $logo;
        $path = XOOPS_ROOT_PATH . '/' . $logo;

        $size = getimagesize($path);  // PHP function
        if ($size) {
            $width  = (int)$size[0];
            $height = (int)$size[1];
        }

        return [$url, $width, $height];
    }

    /**
     * @param $width
     * @return int
     */
    public function check_site_image_width($width)
    {
        $ret = 0;
        if ($width > $this->_SITE_IMAGE_WIDTH_MAX) {
            $ret = 1;
        }

        return $ret;
    }

    /**
     * @param $height
     * @return int
     */
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
    public function get_xoops_slogan()
    {
        global $xoopsConfig;

        return $xoopsConfig['slogan'];
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
     * @param     $uid
     * @param int $usereal
     * @return mixed
     */
    public function get_xoops_uname_from_id($uid, $usereal = 0)
    {
        return \XoopsUser::getUnameFromId($uid, $usereal);
    }

    // --- class end ---
}
