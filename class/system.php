<?php
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
// class happy_linux_system
//=========================================================
class happy_linux_system
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

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_system();
        }

        return $instance;
    }

    //--------------------------------------------------------
    // xoops constant
    //--------------------------------------------------------
    public function get_siteurl()
    {
        $url = XOOPS_URL . '/';
        return $url;
    }

    public function get_langcode()
    {
        return _LANGCODE;
    }

    public function get_xoops_version()
    {
        return XOOPS_VERSION;
    }

    //--------------------------------------------------------
    // xoops config
    //--------------------------------------------------------
    public function get_sitename()
    {
        global $xoopsConfig;
        return $xoopsConfig['sitename'];
    }

    public function get_slogan()
    {
        global $xoopsConfig;
        return $xoopsConfig['slogan'];
    }

    public function get_adminmail()
    {
        global $xoopsConfig;
        return $xoopsConfig['adminmail'];
    }

    public function get_anonymous()
    {
        global $xoopsConfig;
        return $xoopsConfig['anonymous'];
    }

    public function get_language()
    {
        global $xoopsConfig;
        return $xoopsConfig['language'];
    }

    public function is_language($lang)
    {
        if ($this->get_language() == $lang) {
            return true;
        }
        return false;
    }

    public function in_array_language(&$arr)
    {
        if (in_array($this->get_language(), $arr)) {
            return true;
        }
        return false;
    }

    public function is_japanese()
    {
        include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/lang_name_ja.php';

        return $this->in_array_language(happy_linux_get_lang_name_ja());
    }

    //--------------------------------------------------------
    // xoops user
    //--------------------------------------------------------
    public function is_owner($uid)
    {
        $uid = (int)$uid;

        if (($uid != 0) && ($uid == $this->get_uid())) {
            return true;
        }

        return false;
    }

    public function get_uid()
    {
        $user = $this->get_user_param();
        return $user['uid'];
    }

    public function get_uname()
    {
        $user = $this->get_user_param();
        return $user['uname'];
    }

    public function get_email()
    {
        $user = $this->get_user_param();
        return $user['email'];
    }

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

        $arr = array(
            'uid'      => $uid,
            'uname'    => $uname,
            'email'    => $email,
            'url'      => $url,
            'groups'   => $groups,
            'isactive' => $isactive,
        );

        return $arr;
    }

    public function is_user()
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            return true;
        }
        return false;
    }

    public function is_guest()
    {
        if (!$this->is_user()) {
            return true;
        }
        return false;
    }

    public function is_module_admin()
    {
        global $xoopsUser, $xoopsModule;

        if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
            return true;;
        }

        return false;
    }

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

    public function &get_user_groups_anonymous()
    {
        $groups = array(XOOPS_GROUP_ANONYMOUS);
        return $groups;
    }

    //--------------------------------------------------------
    // xoops module
    //--------------------------------------------------------
    public function get_mid()
    {
        global $xoopsModule;
        $mid = $xoopsModule->getVar('mid');
        return $mid;
    }

    public function get_module_name($format = 's')
    {
        global $xoopsModule;
        return $xoopsModule->getVar('name', $format);
    }

    public function get_mid_by_dirname($dirname)
    {
        $mid    = false;
        $module =& $this->get_module_by_dirname($dirname);
        if (is_object($module)) {
            $mid = $module->getVar('mid');
        }
        return $mid;
    }

    public function get_module_name_by_dirname($dirname, $format = 's')
    {
        $name   = false;
        $module = $this->get_module_by_dirname($dirname);
        if (is_object($module)) {
            $name = $module->getVar('name', $format = 's');
        }
        return $name;
    }

    public function is_active_module_by_dirname($dirname)
    {
        $act    = false;
        $module =& $this->get_module_by_dirname($dirname);
        if (is_object($module)) {
            $act = $module->getVar('isactive');
        }
        return $act;
    }

    public function get_module_by_dirname($dirname)
    {
        $module_handler = xoops_getHandler('module');
        $module         = $module_handler->getByDirname($dirname);
        return $module;
    }

    public function &get_module_objects($criteria = null, $id_as_key = false)
    {
        $module_handler = xoops_getHandler('module');
        $objs           = $module_handler->getObjects($criteria, $id_as_key);
        return $objs;
    }

    public function get_module_by_mid($mid)
    {
        $module_handler = xoops_getHandler('module');
        $obj            = $module_handler->get($mid);
        return $obj;
    }

    // XC 2.1
    public function is_active_legacy_module()
    {
        return $this->is_active_module_by_dirname('legacy');
    }

    public function &get_module_list($param = null)
    {
        $isactive       = isset($param['isactive']) ? $param['isactive'] : true;
        $file           = isset($param['file']) ? $param['file'] : null;
        $dirname_except = isset($param['dirname_except']) ? $param['dirname_except'] : null;

        $module_handler = xoops_getHandler('module');

        $criteria = new CriteriaCompo();

        if ($isactive) {
            $criteria->add(new Criteria('isactive', '1', '='));
        }

        $objs = $module_handler->getObjects($criteria);

        $arr = array();

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

    public function &get_dirname_list(&$mod_objs, $param = null)
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

        $arr = array();

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
                $val = happy_linux_sanitize($val);
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
                    $val = happy_linux_sanitize($val);
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
    public function get_uname_by_uid($uid, $usereal = 0)
    {
        $uname = XoopsUser::getUnameFromId($uid, $usereal);
        return $uname;
    }

    public function get_email_by_uid($uid)
    {
        $arr = $this->get_user_by_uid($uid);

        if (isset($arr['email'])) {
            $email = $arr['email'];
            return $email;
        }

        return '';
    }

    public function get_user_by_uid($uid)
    {
        $uid = (int)$uid;
        $ret = array(
            'uid'      => $uid,
            'uname'    => '',
            'name'     => '',
            'email'    => '',
            'groups'   => '',
            'isactive' => false
        );

        if ($uid <= 0) {
            return $ret;
        }

        $user_handler = xoops_getHandler('user');
        $obj          = $user_handler->get($uid);

        if (!is_object($obj)) {
            return $ret;
        }

        $ret = array(
            'uid'      => $uid,
            'uname'    => $obj->getVar('uname'),
            'name'     => $obj->getVar('name'),
            'email'    => $obj->getVar('email'),
            'url'      => $obj->getVar('url'),
            'groups'   => $obj->getGroups(),
            'isactive' => $obj->isActive(),
        );

        return $ret;
    }

    public function &get_user_list($limit = 0, $start = 0)
    {
        $false = false;

        $this->_user_uid_list = array();
        $this->_user_list     = array();

        $limit    = (int)$limit;
        $start    = (int)$start;
        $criteria = new CriteriaCompo();
        $criteria->setStart($start);
        $criteria->setLimit($limit);

        $user_handler = xoops_getHandler('user');
        $objs         = $user_handler->getObjects($criteria);

        if (count($objs) == 0) {
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

    public function &get_user_by_uname($uname)
    {
        $false = false;

        $criteria = new CriteriaCompo();
        $criteria->add(new criteria('uname', $uname, '='));

        $user_handler = xoops_getHandler('user');
        $objs         = $user_handler->getObjects($criteria);

        // system error if twe or more
        if (!is_array($objs) || (count($objs) != 1)) {
            return $false;
        }

        $arr = array(
            'uid'      => $objs[0]->getVar('uid'),
            'uname'    => $objs[0]->getVar('uname'),
            'name'     => $objs[0]->getVar('name'),
            'email'    => $objs[0]->getVar('email'),
            'groups'   => $objs[0]->getGroups(),
            'isactive' => $objs[0]->isActive(),
        );

        return $arr;
    }

    //--------------------------------------------------------
    // xoops member handler
    //--------------------------------------------------------
    public function &get_group_list()
    {
        $member_handler = xoops_getHandler('member');
        $list           =& $member_handler->getGroupList();
        return $list;
    }

    //--------------------------------------------------------
    // xoops groupperm handler
    // default: read permission
    //--------------------------------------------------------
    public function &get_groupperm_mid_list($gperm_name = 'module_read', $gperm_modid = 1, $gperm_groupid = null)
    {
        if (empty($gperm_groupid)) {
            $gperm_groupid =& $this->get_user_groups();
        }

        $groupperm_handler = xoops_getHandler('groupperm');
        $list              = $groupperm_handler->getItemIds($gperm_name, $gperm_groupid, $gperm_modid);
        return $list;
    }

    public function check_groupperm_right($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1)
    {
        $groupperm_handler = xoops_getHandler('groupperm');
        if ($groupperm_handler->checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid)) {
            return true;
        }
        return false;
    }

    //--------------------------------------------------------
    // xoops config handler
    //--------------------------------------------------------
    public function check_config_search_enable_search()
    {
        $config_handler    = xoops_getHandler('config');
        $xoopsConfigSearch = $config_handler->getConfigsByCat(XOOPS_CONF_SEARCH);
        if ($xoopsConfigSearch['enable_search'] == 1) {
            return true;
        }
        return false;
    }

    public function get_config_search_keyword_min()
    {
        $config_handler    = xoops_getHandler('config');
        $xoopsConfigSearch = $config_handler->getConfigsByCat(XOOPS_CONF_SEARCH);
        $keyword_min       = $xoopsConfigSearch['keyword_min'];
        return $keyword_min;
    }

    public function &get_module_config_by_mid($mid)
    {
        $config_handler = xoops_getHandler('config');
        $config         = $config_handler->getConfigsByCat(0, $mid);
        return $config;
    }

    //--------------------------------------------------------
    // XoopsLists
    //--------------------------------------------------------
    public function &get_img_list_as_array($dir)
    {
        include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';

        $arr =& XoopsLists::getImgListAsArray($dir);
        return $arr;
    }

    //--------------------------------------------------------
    // xoops system
    //--------------------------------------------------------
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

    public function get_block_admin()
    {
        switch ($this->get_xoops_system()) {
            // XC 2.1 legacy
            case 'xc_21':
                $title = _HAPPY_LINUX_AM_BLOCK;
                $url   = XOOPS_URL . '/modules/legacy/admin/index.php?action=BlockList';
                break;

            // xoops 2.2
            case 'xoops_22':
                $title = _HAPPY_LINUX_AM_BLOCK;
                $url   = XOOPS_URL . '/modules/system/admin.php?fct=blocksadmin';
                break;

            // xoops 2.0
            case 'xoops_20':
            default:
                $title = _HAPPY_LINUX_AM_GROUP_BLOCK;
                $url   = 'myblocksadmin.php';
                break;
        }

        return array($title, $url);
    }

    //--------------------------------------------------------
    // xoops template
    //--------------------------------------------------------
    public function get_template_vars($varname = null)
    {
        global $xoopsTpl;
        if (is_object($xoopsTpl)) {
            if ($varname) {
                return $xoopsTpl->get_template_vars($varname);
            } else {
                return $xoopsTpl->get_template_vars();
            }
        }
        return false;
    }

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

    public function add_template($varname, $var, $glue = '')
    {
        $this->assign_template($varname, $this->get_template_vars($varname) . $glue . $var);
    }

    //========================================================
    // xoops Meta Footer
    // this function is deprecated
    // because XC21 dont support XOOPS_CONF_METAFOOTER
    //========================================================
    public function get_meta_author()
    {
        $config_handler        = xoops_getHandler('config');
        $xoopsConfigMetaFooter = $config_handler->getConfigsByCat(XOOPS_CONF_METAFOOTER);
        return $xoopsConfigMetaFooter['meta_author'];
    }

    public function get_meta_description()
    {
        $config_handler        = xoops_getHandler('config');
        $xoopsConfigMetaFooter = $config_handler->getConfigsByCat(XOOPS_CONF_METAFOOTER);
        return $xoopsConfigMetaFooter['meta_description'];
    }

    // --- class end ---
}
