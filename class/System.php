<?php

namespace XoopsModules\Happylinux;

// $Id: system.php,v 1.14 2008/01/30 08:33:13 ohwada Exp $

// 2008-01-20 K.OHWADA
// get_template_vars()

// 2007-09-10 K.OHWADA
// get_module_list()

// 2007-07-01 K.OHWADA
// get_module_by_mid() in_array_language()

// 2007-05-12 K.OHWADA
// XC 2.1: is_active_legacy_module()
// get_module_objects() etc

// 2007-03-01 K.OHWADA
// small change get_user_groups()

// 2006-12-10 K.OHWADA
// add is_guest()

// 2006-11-18 K.OHWADA
// for happy_search
// add get_groupperm_mid_list() etc

// 2006-09-20 K.OHWADA
// change get_user_param() get_user_by_uid()
// add get_user_by_uname()

// 2006-07-10 K.OHWADA
// this is new file
// porting from rssc_system.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// TODO
// global $xoopsConfig, $xoopsUser, $xoopsModule
//---------------------------------------------------------

//=========================================================
// class system
//=========================================================

/**
 * Class System
 * @package XoopsModules\Happylinux
 */
class System
{
    public $_user_uid_list;
    public $_user_list;

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

    //--------------------------------------------------------
    // xoops constant
    //--------------------------------------------------------
    /**
     * @return string
     */
    public function get_siteurl()
    {
        $url = XOOPS_URL . '/';

        return $url;
    }

    /**
     * @return string
     */
    public function get_langcode()
    {
        return _LANGCODE;
    }

    /**
     * @return string
     */
    public function get_xoops_version()
    {
        return XOOPS_VERSION;
    }

    //--------------------------------------------------------
    // xoops config
    //--------------------------------------------------------
    /**
     * @return mixed
     */
    public function get_sitename()
    {
        global $xoopsConfig;

        return $xoopsConfig['sitename'];
    }

    /**
     * @return mixed
     */
    public function get_slogan()
    {
        global $xoopsConfig;

        return $xoopsConfig['slogan'];
    }

    /**
     * @return mixed
     */
    public function get_adminmail()
    {
        global $xoopsConfig;

        return $xoopsConfig['adminmail'];
    }

    /**
     * @return mixed
     */
    public function get_anonymous()
    {
        global $xoopsConfig;

        return $xoopsConfig['anonymous'];
    }

    /**
     * @return mixed
     */
    public function get_language()
    {
        global $xoopsConfig;

        return $xoopsConfig['language'];
    }

    /**
     * @param $lang
     * @return bool
     */
    public function is_language($lang)
    {
        if ($this->get_language() == $lang) {
            return true;
        }

        return false;
    }

    /**
     * @param $arr
     * @return bool
     */
    public function in_array_language($arr)
    {
        if (in_array($this->get_language(), $arr)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function is_japanese()
    {
        require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/lang_name_ja.php';

        return $this->in_array_language(happylinux_get_lang_name_ja());
    }

    //--------------------------------------------------------
    // xoops user
    //--------------------------------------------------------
    /**
     * @param $uid
     * @return bool
     */
    public function is_owner($uid)
    {
        $uid = (int)$uid;

        if ((0 != $uid) && ($uid == $this->get_uid())) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function get_uid()
    {
        $user = $this->get_user_param();

        return $user['uid'];
    }

    /**
     * @return mixed
     */
    public function get_uname()
    {
        $user = $this->get_user_param();

        return $user['uname'];
    }

    /**
     * @return mixed
     */
    public function get_email()
    {
        $user = $this->get_user_param();

        return $user['email'];
    }

    /**
     * @return array
     */
    public function &get_user_param()
    {
        global $xoopsUser;

        $uid      = 0;
        $uname    = '';
        $email    = '';
        $url      = '';
        $groups   = '';
        $isactive = false;

        if (is_object($xoopsUser)) {
            $uid      = $xoopsUser->getVar('uid');
            $uname    = $xoopsUser->getVar('uname');
            $email    = $xoopsUser->getVar('email');
            $url      = $xoopsUser->getVar('url');
            $groups   = $xoopsUser->getGroups();
            $isactive = $xoopsUser->isActive();
        }

        $arr = [
            'uid'      => $uid,
            'uname'    => $uname,
            'email'    => $email,
            'url'      => $url,
            'groups'   => $groups,
            'isactive' => $isactive,
        ];

        return $arr;
    }

    /**
     * @return bool
     */
    public function is_user()
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function is_guest()
    {
        if (!$this->is_user()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function is_module_admin()
    {
        global $xoopsUser, $xoopsModule;

        if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function &get_user_groups()
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            $groups = $xoopsUser->getGroups();
        } else {
            $groups = $this->get_user_groups_anonymous();
        }

        return $groups;
    }

    /**
     * @return array
     */
    public function &get_user_groups_anonymous()
    {
        $groups = [XOOPS_GROUP_ANONYMOUS];

        return $groups;
    }

    //--------------------------------------------------------
    // xoops module
    //--------------------------------------------------------
    /**
     * @return array|int|mixed|null
     */
    public function get_mid()
    {
        global $xoopsModule;
        $mid = $xoopsModule->getVar('mid');

        return $mid;
    }

    /**
     * @param string $format
     * @return array|int|mixed|null
     */
    public function get_module_name($format = 's')
    {
        global $xoopsModule;

        return $xoopsModule->getVar('name', $format);
    }

    /**
     * @param $dirname
     * @return bool
     */
    public function get_mid_by_dirname($dirname)
    {
        $mid    = false;
        $module = $this->get_module_by_dirname($dirname);
        if (is_object($module)) {
            $mid = $module->getVar('mid');
        }

        return $mid;
    }

    /**
     * @param        $dirname
     * @param string $format
     * @return bool
     */
    public function get_module_name_by_dirname($dirname, $format = 's')
    {
        $name   = false;
        $module = $this->get_module_by_dirname($dirname);
        if (is_object($module)) {
            $name = $module->getVar('name', $format = 's');
        }

        return $name;
    }

    /**
     * @param $dirname
     * @return bool
     */
    public function is_active_module_by_dirname($dirname)
    {
        $act    = false;
        $module = $this->get_module_by_dirname($dirname);
        if (is_object($module)) {
            $act = $module->getVar('isactive');
        }

        return $act;
    }

    /**
     * @param $dirname
     * @return mixed
     */
    public function get_module_by_dirname($dirname)
    {
        $moduleHandler = xoops_getHandler('module');
        $module         = $moduleHandler->getByDirname($dirname);

        return $module;
    }

    /**
     * @param null $criteria
     * @param bool $id_as_key
     * @return mixed
     */
    public function &get_module_objects($criteria = null, $id_as_key = false)
    {
        $moduleHandler = xoops_getHandler('module');
        $objs           = $moduleHandler->getObjects($criteria, $id_as_key);

        return $objs;
    }

    /**
     * @param $mid
     * @return \XoopsObject
     */
    public function get_module_by_mid($mid)
    {
        $moduleHandler = xoops_getHandler('module');
        $obj            = $moduleHandler->get($mid);

        return $obj;
    }

    // XC 2.1

    /**
     * @return bool
     */
    public function is_active_legacy_module()
    {
        return $this->is_active_module_by_dirname('legacy');
    }

    /**
     * @param null $param
     * @return array
     */
    public function &get_module_list($param = null)
    {
        $isactive       = isset($param['isactive']) ? $param['isactive'] : true;
        $file           = isset($param['file']) ? $param['file'] : null;
        $dirname_except = isset($param['dirname_except']) ? $param['dirname_except'] : null;

        $moduleHandler = xoops_getHandler('module');

        $criteria = new \CriteriaCompo();

        if ($isactive) {
            $criteria->add(new \Criteria('isactive', '1', '='));
        }

        $objs = $moduleHandler->getObjects($criteria);

        $arr = [];

        foreach ($objs as $obj) {
            $mod_id      = $obj->getVar('mid');
            $mod_dirname = $obj->getVar('dirname');

            if ($file) {
                $mod_file = XOOPS_ROOT_PATH . '/modules/' . $mod_dirname . '/' . $file;
                if (!file_exists($mod_file)) {
                    continue;
                }
            }

            if ($dirname_except) {
                if ($mod_dirname == $dirname_except) {
                    continue;
                }
            }

            $arr[$mod_id] = $obj;
        }

        return $arr;
    }

    /**
     * @param      $mod_objs
     * @param null $param
     * @return array
     */
    public function &get_dirname_list($mod_objs, $param = null)
    {
        // none_key must be string, not integer 0
        // 0 match any stings

        $none_flag       = isset($param['none_flag']) ? $param['none_flag'] : false;
        $none_key        = isset($param['none_key']) ? $param['none_key'] : '-';
        $none_value      = isset($param['none_value']) ? $param['none_value'] : '---';
        $dirname_default = isset($param['dirname_default']) ? $param['dirname_default'] : null;
        $flag_dirname    = isset($param['flag_dirname']) ? $param['flag_dirname'] : true;
        $flag_name       = isset($param['flag_name']) ? $param['flag_name'] : true;
        $flag_sanitize   = isset($param['flag_sanitize']) ? $param['flag_sanitize'] : true;
        $sort_asort      = isset($param['sort_asort']) ? $param['sort_asort'] : true;
        $sort_flip       = isset($param['sort_flip']) ? $param['sort_flip'] : true;

        $arr = [];

        if ($none_flag) {
            $arr[$none_key] = $none_value;
        }

        foreach ($mod_objs as $obj) {
            $mod_dirname = $obj->getVar('dirname');
            $mod_name    = $obj->getVar('name');

            $val = '';
            if ($flag_dirname) {
                $val .= $mod_dirname;
            }
            if ($flag_name) {
                if ($val) {
                    $val .= ': ';
                }
                $val .= $mod_name;
            }
            if ($flag_sanitize) {
                $val = happylinux_sanitize($val);
            }

            $arr[$mod_dirname] = $val;
        }

        if ($dirname_default) {
            if (!isset($arr[$dirname_default])) {
                $val = '';
                if ($flag_dirname) {
                    $val .= $dirname_default;
                }
                if ($flag_name) {
                    if ($val) {
                        $val .= ' : ';
                    }
                    $val .= $dirname_default . ' module';
                }
                if ($flag_sanitize) {
                    $val = happylinux_sanitize($val);
                }
                $arr[$dirname_default] = $val;
            }
        }

        if ($sort_asort) {
            asort($arr);
            reset($arr);
        }

        if ($sort_flip) {
            $arr = array_flip($arr);
        }

        return $arr;
    }

    //--------------------------------------------------------
    // xoops user handler
    //--------------------------------------------------------
    // name for "anonymous" if not found
    /**
     * @param     $uid
     * @param int $usereal
     * @return mixed
     */
    public function get_uname_by_uid($uid, $usereal = 0)
    {
        $uname = \XoopsUser::getUnameFromId($uid, $usereal);

        return $uname;
    }

    /**
     * @param $uid
     * @return mixed|string
     */
    public function get_email_by_uid($uid)
    {
        $arr = $this->get_user_by_uid($uid);

        if (isset($arr['email'])) {
            $email = $arr['email'];

            return $email;
        }

        return '';
    }

    /**
     * @param $uid
     * @return array
     */
    public function get_user_by_uid($uid)
    {
        $uid = (int)$uid;
        $ret = [
            'uid'      => $uid,
            'uname'    => '',
            'name'     => '',
            'email'    => '',
            'groups'   => '',
            'isactive' => false,
        ];

        if ($uid <= 0) {
            return $ret;
        }

        $userHandler = xoops_getHandler('user');
        $obj          = $userHandler->get($uid);

        if (!is_object($obj)) {
            return $ret;
        }

        $ret = [
            'uid'      => $uid,
            'uname'    => $obj->getVar('uname'),
            'name'     => $obj->getVar('name'),
            'email'    => $obj->getVar('email'),
            'url'      => $obj->getVar('url'),
            'groups'   => $obj->getGroups(),
            'isactive' => $obj->isActive(),
        ];

        return $ret;
    }

    /**
     * @param int $limit
     * @param int $start
     * @return array|bool
     */
    public function &get_user_list($limit = 0, $start = 0)
    {
        $false = false;

        $this->_user_uid_list = [];
        $this->_user_list     = [];

        $limit    = (int)$limit;
        $start    = (int)$start;
        $criteria = new \CriteriaCompo();
        $criteria->setStart($start);
        $criteria->setLimit($limit);

        $userHandler = xoops_getHandler('user');
        $objs         = $userHandler->getObjects($criteria);

        if (0 == count($objs)) {
            return $false;
        }

        foreach ($objs as $obj) {
            $uid      = $obj->getVar('uid');
            $uname    = $obj->getVar('uname');
            $name     = $obj->getVar('name');
            $email    = $obj->getVar('email');
            $groups   = $obj->getGroups();
            $isactive = $obj->isActive();

            $this->_user_uid_list[]             = $uid;
            $this->_user_list[$uid]['uname']    = $uname;
            $this->_user_list[$uid]['name']     = $name;
            $this->_user_list[$uid]['email']    = $email;
            $this->_user_list[$uid]['groups']   = $groups;
            $this->_user_list[$uid]['isactive'] = $isactive;
        }

        $this->_user_list[0]['uname']    = $this->get_anonymous();
        $this->_user_list[0]['name']     = '';
        $this->_user_list[0]['email']    = '';
        $this->_user_list[0]['groups']   = '';
        $this->_user_list[0]['isactive'] = false;

        return $this->_user_list;
    }

    public function &get_user_uid_list()
    {
        return $this->_user_uid_list;
    }

    /**
     * @param $uname
     * @return array|bool
     */
    public function &get_user_by_uname($uname)
    {
        $false = false;

        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('uname', $uname, '='));

        $userHandler = xoops_getHandler('user');
        $objs         = $userHandler->getObjects($criteria);

        // system error if twe or more
        if (!is_array($objs) || (1 != count($objs))) {
            return $false;
        }

        $arr = [
            'uid'      => $objs[0]->getVar('uid'),
            'uname'    => $objs[0]->getVar('uname'),
            'name'     => $objs[0]->getVar('name'),
            'email'    => $objs[0]->getVar('email'),
            'groups'   => $objs[0]->getGroups(),
            'isactive' => $objs[0]->isActive(),
        ];

        return $arr;
    }

    //--------------------------------------------------------
    // xoops member handler
    //--------------------------------------------------------
    /**
     * @return mixed
     */
    public function &get_group_list()
    {
        $memberHandler = xoops_getHandler('member');
        $list           = $memberHandler->getGroupList();

        return $list;
    }

    //--------------------------------------------------------
    // xoops groupperm handler
    // default: read permission
    //--------------------------------------------------------
    /**
     * @param string $gperm_name
     * @param int    $gperm_modid
     * @param null   $gperm_groupid
     * @return mixed
     */
    public function &get_groupperm_mid_list($gperm_name = 'module_read', $gperm_modid = 1, $gperm_groupid = null)
    {
        if (empty($gperm_groupid)) {
            $gperm_groupid = &$this->get_user_groups();
        }

        $grouppermHandler = xoops_getHandler('groupperm');
        $list              = $grouppermHandler->getItemIds($gperm_name, $gperm_groupid, $gperm_modid);

        return $list;
    }

    /**
     * @param     $gperm_name
     * @param     $gperm_itemid
     * @param     $gperm_groupid
     * @param int $gperm_modid
     * @return bool
     */
    public function check_groupperm_right($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1)
    {
        $grouppermHandler = xoops_getHandler('groupperm');
        if ($grouppermHandler->checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid)) {
            return true;
        }

        return false;
    }

    //--------------------------------------------------------
    // xoops config handler
    //--------------------------------------------------------
    /**
     * @return bool
     */
    public function check_config_search_enable_search()
    {
        $configHandler    = xoops_getHandler('config');
        $xoopsConfigSearch = $configHandler->getConfigsByCat(XOOPS_CONF_SEARCH);
        if (1 == $xoopsConfigSearch['enable_search']) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function get_config_search_keyword_min()
    {
        $configHandler    = xoops_getHandler('config');
        $xoopsConfigSearch = $configHandler->getConfigsByCat(XOOPS_CONF_SEARCH);
        $keyword_min       = $xoopsConfigSearch['keyword_min'];

        return $keyword_min;
    }

    /**
     * @param $mid
     * @return mixed
     */
    public function &get_module_config_by_mid($mid)
    {
        $configHandler = xoops_getHandler('config');
        $config         = $configHandler->getConfigsByCat(0, $mid);

        return $config;
    }

    //--------------------------------------------------------
    // XoopsLists
    //--------------------------------------------------------
    /**
     * @param $dir
     * @return mixed
     */
    public function &get_img_list_as_array($dir)
    {
        require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';

        $arr = \XoopsLists::getImgListAsArray($dir);

        return $arr;
    }

    //--------------------------------------------------------
    // xoops system
    //--------------------------------------------------------
    /**
     * @return string
     */
    public function get_xoops_system()
    {
        $ver = 'xoops_20';
        // XC2.1 legacy
        if ($this->is_active_legacy_module()) {
            $ver = 'xc_21';
        } // xoops 2.2
        elseif (preg_match("/XOOPS[\s+]2.2/i", $this->get_xoops_version())) {
            $ver = 'xoops_22';
        }

        return $ver;
    }

    /**
     * @return array
     */
    public function get_block_admin()
    {
        switch ($this->get_xoops_system()) {
            // XC 2.1 legacy
            case 'xc_21':
                $title = _HAPPYLINUX_AM_BLOCK;
                $url   = XOOPS_URL . '/modules/legacy/admin/index.php?action=BlockList';
                break;
            // xoops 2.2
            case 'xoops_22':
                $title = _HAPPYLINUX_AM_BLOCK;
                $url   = XOOPS_URL . '/modules/system/admin.php?fct=blocksadmin';
                break;
            // xoops 2.0
            case 'xoops_20':
            default:
                $title = _HAPPYLINUX_AM_GROUP_BLOCK;
                $url   = 'myblocksadmin.php';
                break;
        }

        return [$title, $url];
    }

    //--------------------------------------------------------
    // xoops template
    //--------------------------------------------------------
    /**
     * @param null $varname
     * @return array|bool|null
     */
    public function get_template_vars($varname = null)
    {
        global $xoopsTpl;
        if (is_object($xoopsTpl)) {
            if ($varname) {
                return $xoopsTpl->get_template_vars($varname);
            }

            return $xoopsTpl->get_template_vars();
        }

        return false;
    }

    /**
     * @param      $varname
     * @param null $var
     */
    public function assign_template($varname, $var = null)
    {
        global $xoopsTpl;
        if (is_object($xoopsTpl)) {
            if ($var) {
                $xoopsTpl->assign($varname, $var);
            } else {
                $xoopsTpl->assign($varname);
            }
        }
    }

    /**
     * @param        $varname
     * @param        $var
     * @param string $glue
     */
    public function add_template($varname, $var, $glue = '')
    {
        $this->assign_template($varname, $this->get_template_vars($varname) . $glue . $var);
    }

    //========================================================
    // xoops Meta Footer
    // this function is deprecated
    // because XC21 dont support XOOPS_CONF_METAFOOTER
    //========================================================
    /**
     * @return mixed
     */
    public function get_meta_author()
    {
        $configHandler        = xoops_getHandler('config');
        $xoopsConfigMetaFooter = $configHandler->getConfigsByCat(XOOPS_CONF_METAFOOTER);

        return $xoopsConfigMetaFooter['meta_author'];
    }

    /**
     * @return mixed
     */
    public function get_meta_description()
    {
        $configHandler        = xoops_getHandler('config');
        $xoopsConfigMetaFooter = $configHandler->getConfigsByCat(XOOPS_CONF_METAFOOTER);

        return $xoopsConfigMetaFooter['meta_description'];
    }

    // --- class end ---
}
