<?php

namespace XoopsModules\Happylinux;

// $Id: xoops_block_checker.php,v 1.6 2007/11/26 02:49:28 ohwada Exp $

// 2007-11-24 K.OHWADA
// check_token()

//=========================================================
// Happy Linux Framework Module
// 2007-10-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// NOTE
// xoops 2.0 :      class/xoopsblock.php and kernel/block.php are different
// xoops cube 2.1 : class/xoopsblock.php and kernel/block.php are the same
//---------------------------------------------------------

require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
require_once XOOPS_ROOT_PATH . '/kernel/tplfile.php';

//=========================================================
// class BlockChecker
//=========================================================

/**
 * Class BlockChecker
 * @package XoopsModules\Happylinux
 */
class BlockChecker
{
    public $_tplfileHandler;

    public $_mid                 = 0;
    public $_dirname             = null;
    public $_is_special          = false;
    public $_msg_array           = [];
    public $_error_flag          = false;
    public $_check_same_template = true;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_tplfileHandler = xoops_getHandler('tplfile');
        $this->_get_module_param();
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
    // admin
    //---------------------------------------------------------
    /**
     * @return string
     */
    public function build_menu_check_block()
    {
        $text = '<h4>' . _HAPPYLINUX_XOOPS_BLOCK_TABLE_CHECK . "</h4>\n";
        $text .= $this->check_block();
        $text .= "<br><br>\n";

        return $text;
    }

    /**
     * @param null   $action
     * @param string $op
     * @return string
     */
    public function build_form_remove_block($action = null, $op = 'remove_block')
    {
        $form   = FormLib::getInstance();
        $button = $form->build_lib_button($op, _HAPPYLINUX_DELETE, $action);
        $text   = $form->build_lib_box_style(_HAPPYLINUX_XOOPS_BLOCK_TABLE_REMOVE, _HAPPYLINUX_XOOPS_BLOCK_TABLE_REMOVE_DESC, $button);

        return $text;
    }

    /**
     * @return bool
     */
    public function check_token()
    {
        $form = FormLib::getInstance();

        return $form->check_token();
    }

    /**
     * @return string
     */
    public function execute_remove_block()
    {
        $text = '';
        $ret  = $this->remove_block();
        $text .= $this->_get_msg();
        if ($ret) {
            $text .= '<h4 style="color:#0000ff">' . _HAPPYLINUX_DELETED . "</h4>\n";
        } else {
            $text .= '<h4 style="color:#ff0000">' . _HAPPYLINUX_FAILED . "</h4>\n";
        }
        $text .= _HAPPYLINUX_XOOPS_BLOCK_TABLE_REMOVE_NEXT . "<br><br>\n";
        $text .= '<a href="modules.php">' . _HAPPYLINUX_AM_MODULE . "</a><br>\n";

        return $text;
    }

    //--------------------------------------------------------
    // function
    //--------------------------------------------------------
    /**
     * @return string
     */
    public function check_block()
    {
        $this->_msg_array = [];

        $this->_is_special = $this->_is_special_module($this->_dirname);

        $module_obj   = &$this->_get_module_obj();
        $block_objs   = &$this->_get_block_object_orber_num_by_mid($this->_mid);
        $infos        = &$this->_get_module_info('blocks');
        $overlap_list = &$this->_make_overlap_template_list($infos);

        foreach ($infos as $num => $info) {
            if (!isset($block_objs[$num])) {
                $this->_err(htmlspecialchars($info['name'], ENT_QUOTES | ENT_HTML5) . ': not exist in block table');
                continue;
            }

            $this->_check_block_by_obj($module_obj, $info, $block_objs[$num], $overlap_list);
        }

        return $this->_get_msg();
    }

    /**
     * @return bool
     */
    public function remove_block()
    {
        $error      = false;
        $module_obj = &$this->_get_module_obj();
        $block_objs = &$this->_get_block_object_by_mid($this->_mid);

        foreach ($block_objs as $obj) {
            $ret = $this->_delete_block($obj);
            if (!$ret) {
                $error = true;
            }

            $ret = $this->_delete_tplfile_by_block_obj($module_obj, $obj);
            if (!$ret) {
                $error = true;
            }
        }

        if ($error) {
            return false;
        }

        return true;
    }

    //--------------------------------------------------------
    // private
    //--------------------------------------------------------
    /**
     * @param $infos
     * @return array
     */
    public function &_make_overlap_template_list($infos)
    {
        // some module has same name templates
        // ex) news, newbb, pical, etc

        $template_list = [];
        $overlap_list  = [];

        foreach ($infos as $num => $info) {
            if (isset($info['template']) && $info['template']) {
                $template = $info['template'];

                // add in the overlap list, if already in the template list.
                if (in_array($template, $template_list)) {
                    $overlap_list[] = $template;
                } else {
                    $template_list[] = $template;
                }
            }
        }

        return $overlap_list;
    }

    /**
     * @param $module_obj
     * @param $info
     * @param $block_obj
     * @param $overlap_list
     */
    public function _check_block_by_obj($module_obj, $info, $block_obj, $overlap_list)
    {
        $this->_error_flag = false;

        $dirname = $module_obj->getVar('dirname', 'n');
        $bid     = $block_obj->getVar('bid', 'n');
        $name    = htmlspecialchars($info['name'], ENT_QUOTES | ENT_HTML5);

        if (isset($info['file']) && ($info['file'] != $block_obj->getVar('func_file', 'n'))) {
            $this->_err($name . ': file unmatch');
        }

        if (isset($info['show_func']) && ($info['show_func'] != $block_obj->getVar('show_func', 'n'))) {
            $this->_err($name . ': show_func unmatch');
        }

        if (isset($info['edit_func']) && ($info['edit_func'] != $block_obj->getVar('edit_func', 'n'))) {
            $this->_err($name . ': edit_func unmatch');
        }

        if (isset($info['template']) && $info['template']) {
            $template = $info['template'];

            if ($template == $block_obj->getVar('template', 'n')) {
                $count = $this->_get_tplfile_count_by_file($bid, $dirname, $template);
                if (0 == $count) {
                    $this->_err($name . ': template not exist in tplfile');
                }
                if ($this->_check_same_template && !in_array($template, $overlap_list)) {
                    $count = $this->_get_tplfile_count_by_file(null, $dirname, $template);
                    if ($count > 1) {
                        $this->_err($name . ': same name templates exist in tplfile');
                    }
                }
            } else {
                $this->_err($name . ': template unmatch');
            }
        }

        if (isset($info['options'])) {
            $option_arr_1 = explode('|', $info['options']);
            $option_arr_2 = explode('|', $block_obj->getVar('options', 'n'));

            if (count($option_arr_1) != count($option_arr_2)) {
                $this->_err($name . ': options count unmatch');
            }
            if ($this->_is_special && ($option_arr_1[0] != $option_arr_2[0])) {
                $this->_err($name . ': options dirname unmatch');
            }
        }

        if (!$this->_error_flag) {
            $this->_msg($name . ': OK');
        }
    }

    /**
     * @param $msg
     */
    public function _msg($msg)
    {
        $this->_msg_array[] = $msg;
    }

    /**
     * @param $msg
     */
    public function _err($msg)
    {
        $this->_msg_array[] = $this->_highlight($msg);
        $this->_error_flag  = true;
    }

    /**
     * @return string
     */
    public function _get_msg()
    {
        $msg = implode("<br>\n", $this->_msg_array);

        return $msg;
    }

    /**
     * @param $msg
     * @return string|null
     */
    public function _highlight($msg)
    {
        $text = null;
        if ($msg) {
            $text = '<span style="color: #ff0000;">' . $msg . '</span>';
        }

        return $text;
    }

    //--------------------------------------------------------
    // special module
    //--------------------------------------------------------
    /**
     * @param $dirname
     * @return bool
     */
    public function _is_special_module($dirname)
    {
        $dir = XOOPS_ROOT_PATH . '/modules/' . $dirname;

        if (file_exists($dir . '/include/weblinks_version.php')) {
            return true;
        }

        if (file_exists($dir . '/include/rssc_version.php')) {
            return true;
        }

        if (file_exists($dir . '/include/whatsnew_version.php')) {
            return true;
        }

        if (file_exists($dir . '/include/happy_search_version.php')) {
            return true;
        }

        return false;
    }

    //--------------------------------------------------------
    // module handler
    //--------------------------------------------------------
    /**
     * @return bool|FALSE|mixed|\XoopsModule
     */
    public function &_get_module_obj()
    {
        global $xoopsModule;

        return $xoopsModule;
    }

    public function _get_module_param()
    {
        global $xoopsModule;
        $this->_mid     = $xoopsModule->getVar('mid', 'n');
        $this->_dirname = $xoopsModule->getVar('dirname', 'n');
    }

    /**
     * @param null $name
     * @return array|bool|string
     */
    public function &_get_module_info($name = null)
    {
        global $xoopsModule;

        return $xoopsModule->getInfo($name);
    }

    //--------------------------------------------------------
    // block handler
    //--------------------------------------------------------
    /**
     * @param $mid
     * @return array
     */
    public function &_get_block_object_orber_num_by_mid($mid)
    {
        $arr  = [];
        $objs = &$this->_get_block_object_by_mid($mid);
        foreach ($objs as $obj) {
            $arr[$obj->getVar('func_num', 'n')] = $obj;
        }

        return $arr;
    }

    /**
     * @param      $mid
     * @param bool $asobject
     * @return mixed
     */
    public function &_get_block_object_by_mid($mid, $asobject = true)
    {
        $objs = \XoopsBlock::getByModule($mid, $asobject);

        return $objs;
    }

    /**
     * @param $obj
     * @return mixed
     */
    public function _delete_block($obj)
    {
        // NOT use xoops_getHandler in xoops 2.0.16jp

        $msg = 'block: ' . $obj->getVar('bid') . ' ' . $obj->getVar('name', 's');
        $ret = $obj->delete();
        if ($ret) {
            $this->_msg($msg);
        } else {
            $this->_err($msg . ' Failed');
        }

        return $ret;
    }

    //--------------------------------------------------------
    // tplfile handler
    //--------------------------------------------------------
    /**
     * @param null $block_id
     * @param null $module
     * @param null $file
     * @return mixed
     */
    public function _get_tplfile_count_by_file($block_id = null, $module = null, $file = null)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('tpl_type', 'block'));
        if (isset($block_id)) {
            $criteria->add(new \Criteria('tpl_refid', $block_id));
        }
        if (isset($module)) {
            $criteria->add(new \Criteria('tpl_module', $module));
        }
        if (isset($file)) {
            $criteria->add(new \Criteria('tpl_file', $file));
        }
        $count = $this->_tplfileHandler->getCount($criteria);

        return $count;
    }

    /**
     * @param $module_obj
     * @param $block_obj
     * @return bool
     */
    public function _delete_tplfile_by_block_obj($module_obj, $block_obj)
    {
        $error    = false;
        $dirname  = $module_obj->getVar('dirname', 'n');
        $bid      = $block_obj->getVar('bid', 'n');
        $template = $block_obj->getVar('template', 'n');

        if ('' != $template) {
            if ($this->_check_same_template) {
                $tpl_objs = &$this->_get_tplfile_objects_by_block_id(null, $dirname, $template);
            } else {
                $tpl_objs = &$this->_get_tplfile_objects_by_block_id($bid);
            }

            if (is_array($tpl_objs) && count($tpl_objs)) {
                foreach ($tpl_objs as $obj) {
                    $ret = $this->_delete_tplfile($obj);
                    if (!$ret) {
                        $error = true;
                    }
                }
            }
        }

        if ($error) {
            return false;
        }

        return true;
    }

    /**
     * @param null $block_id
     * @param null $module
     * @param null $file
     * @return mixed
     */
    public function &_get_tplfile_objects_by_block_id($block_id = null, $module = null, $file = null)
    {
        $objs = $this->_tplfileHandler->find(null, 'block', $block_id, $module, $file);

        return $objs;
    }

    /**
     * @param $obj
     */
    public function _delete_tplfile($obj)
    {
        $msg = 'tplfile: ' . $obj->getVar('tpl_id') . ' ' . $obj->getVar('tpl_file');
        $ret = $this->_tplfileHandler->delete($obj);
        if ($ret) {
            $this->_msg($msg);
        } else {
            $this->_err($msg . ' Failed');
        }

        return $ret;
    }

    // --- class end ---
}
