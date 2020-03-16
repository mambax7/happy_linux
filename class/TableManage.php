<?php

namespace XoopsModules\Happylinux;

// $Id: table_manage.php,v 1.1 2007/11/26 02:49:59 ohwada Exp $

//================================================================
// Happy Linux Framework Module
// 2007-11-24 K.OHWADA
//================================================================

//================================================================
// class TableManage
//================================================================

/**
 * Class TableManage
 * @package XoopsModules\Happylinux
 */
class TableManage extends Error
{
    public $_DIRNAME;

    public $_config_storeHandler;
    public $_block_checker;
    public $_install;
    public $_post;
    public $_form;
    public $_strings;
    public $_system;

    public $_op;
    public $_offset;
    public $_limit;

    public $_MAX_RECORD = 100;
    public $_THIS_TITLE = _HAPPYLINUX_CONF_TABLE_MANAGE;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    /**
     * TableManage constructor.
     * @param $dirname
     */
    public function __construct($dirname)
    {
        $this->_DIRNAME = $dirname;

        parent::__construct();

        $this->_post    = Post::getInstance();
        $this->_form    = FormLib::getInstance();
        $this->_strings = Strings::getInstance();
        $this->_system  = System::getInstance();

        $this->_this_url = xoops_getenv('PHP_SELF');
    }

    /**
     * @param null $dirname
     * @return \XoopsModules\Happylinux\TableManage|static
     */
    public static function getInstance($dirname = null)
    {
        static $instance;
        if (null === $instance) {
            $instance = new static($dirname);
        }

        return $instance;
    }

    //---------------------------------------------------------
    // handler
    //---------------------------------------------------------
    /**
     * @param $name
     * @param $dirname
     * @param $prefix
     */
    public function set_config_handler($name, $dirname, $prefix, $helper)
    {
        $this->_config_storeHandler = ConfigFormHandler::getInstance();
        $this->_config_storeHandler->set_handler($name, $dirname, $prefix, $helper );
    }

    /**
     * @param $class
     */
    public function set_config_define($class)
    {
        $this->_config_storeHandler->set_define($class);
    }

    /**
     * @param $class
     */
    public function set_install_class($class)
    {
        $this->_install = $class;
    }

    public function set_xoops_block_checker()
    {
        $this->_block_checker = BlockChecker::getInstance();
    }

    /**
     * @param $val
     */
    public function set_max_record($val)
    {
        $this->_MAX_RECORD = (int)$val;
    }

    /**
     * @return int
     */
    public function get_max_record()
    {
        return $this->_MAX_RECORD;
    }

    //---------------------------------------------------------
    // $_POST
    //---------------------------------------------------------
    /**
     * @return string|string[]|null
     */
    public function get_post_op()
    {
        $this->op = $this->_post->get_post_get_text('op');

        return $this->op;
    }

    /**
     * @return int
     */
    public function get_post_offset()
    {
        $this->_offset = $this->_post->get_post_get_int('offset');

        return $this->_offset;
    }

    /**
     * @return int
     */
    public function get_post_limit()
    {
        $this->_limit = $this->_post->get_post_get_int('limit');

        return $this->_limit;
    }

    //---------------------------------------------------------
    // calc end
    //---------------------------------------------------------
    /**
     * @param      $start
     * @param null $total
     * @return int
     */
    public function calc_end($start, $total = null)
    {
        return $this->_calc_end($start, $this->_MAX_RECORD, $total);
    }

    /**
     * @param      $start
     * @param      $limit
     * @param null $total
     * @return int
     */
    public function _calc_end($start, $limit, $total = null)
    {
        $end = $start + $limit - 1;
        if ($total && ($end > $total)) {
            $end = $total;
        }

        return $end;
    }

    //---------------------------------------------------------
    // check
    //---------------------------------------------------------
    /**
     * @return bool
     */
    public function check_config_table()
    {
        if (!$this->check_table_scheme_by_handler($this->_config_storeHandler, false)) {
            return false;
        }

        if (!$this->_config_storeHandler->compare_to_define()) {
            $this->print_red($this->_config_storeHandler->getErrors(1), false);
            echo "<br>\n";
            $this->_print_action_define();

            return false;
        }

        $this->print_blue('check OK');

        return true;
    }

    /**
     * @param      $table_short
     * @param      $module_dir
     * @param      $prefix
     * @param null|\Xmf\Module\Helper $helper
     * @return bool
     */
    public function check_table_scheme_by_name($table_short, $module_dir, $prefix, $helper = null)
    {
        if (null === $helper) {
            return $this->check_table_scheme_by_handler(
                happylinux_get_handler($table_short, $module_dir, $prefix)
            );
        } else {
            $handler = $helper->getHandler(ucfirst($table_short));
            return $this->check_table_scheme_by_handler($handler);
        }
    }

    /**
     * @param      $handler
     * @param bool $flag_ok
     * @return bool
     */
    public function check_table_scheme_by_handler($handler, $flag_ok = true)
    {
        if (!$handler->compare_to_scheme()) {
            $this->print_red('<b> Fatal Error </b>', false);
            $this->print_red($handler->getErrors(1), false);
            echo "<br>\n";
            $this->_print_action_scheme();

            return false;
        }
        if ($flag_ok) {
            $this->print_blue('check OK');
        }

        return true;
    }

    public function check_xoops_block_table()
    {
        echo $this->_block_checker->build_menu_check_block();
    }

    //---------------------------------------------------------
    // action
    //---------------------------------------------------------
    public function remove_block()
    {
        $this->print_bread(_HAPPYLINUX_XOOPS_BLOCK_TABLE_REMOVE);

        if (!$this->check_token()) {
            xoops_error('Token Error');

            return;
        }

        echo '<h4>' . _HAPPYLINUX_XOOPS_BLOCK_TABLE_REMOVE . "</h4>\n";
        echo $this->_block_checker->execute_remove_block();
    }

    public function renew_config()
    {
        if (!$this->check_token()) {
            xoops_cp_header();
            $this->print_bread(_HAPPYLINUX_CONF_TABLE_CONFIG_RENEW);
            xoops_error('Token Error');
            xoops_cp_footer();
            exit();
        }

        $this->_install->truncate_table($this->_install->_config_table);
        $this->_install->init_config();

        redirect_header($this->_this_url, 1, _HAPPYLINUX_EXECUTED);
        exit();
    }

    //---------------------------------------------------------
    // print
    //---------------------------------------------------------
    public function print_title()
    {
        echo '<h3>' . $this->_THIS_TITLE . "</h3>\n";
    }

    /**
     * @param $table
     */
    public function print_table_check($table)
    {
        echo '<h4>' . sprintf(_HAPPYLINUX_CONF_TABLE_CHECK, $table) . "</h4>\n";
    }

    /**
     * @param $name
     */
    public function print_bread($name)
    {
        echo $this->build_bread_crumb($name);
    }

    /**
     * @param $name
     * @return string
     */
    public function build_bread_crumb($name)
    {
        $arr = [
            [
                'name' => $this->_system->get_module_name(),
                'url'  => 'index.php',
            ],
            [
                'name' => $this->_THIS_TITLE,
                'url'  => $this->_this_url,
            ],
            [
                'name' => $name,
            ],
        ];

        return $this->_form->build_html_bread_crumb($arr);
    }

    /**
     * @param $msg
     */
    public function print_blue($msg)
    {
        echo '<div style="color: #0000ff;">' . $msg . "</div>\n";
    }

    /**
     * @param $msg
     */
    public function print_red($msg)
    {
        echo '<div style="color: #ff0000;">' . $msg . "</div>\n";
    }

    /**
     * @param $msg
     * @return string
     */
    public function build_span_red_bold($msg)
    {
        return '<span style="color: #ff0000; font_weight: bold;">' . $msg . '</span>';
    }

    public function print_finish()
    {
        echo "<br><hr>\n";
        echo '<h4>' . _HAPPYLINUX_FINISHED . "</h4>\n";
        echo '<a href="' . $this->_this_url . '"> &gt;&gt; ' . $this->_THIS_TITLE . "</a><br>\n";
    }

    public function _print_action_define()
    {
        $this->print_form_renew_config_table();
    }

    public function _print_action_scheme()
    {
        echo 'check manually by phpMyAdmin or other tool' . "<br>\n";
    }

    public function _print_action_reinstall()
    {
        echo _HAPPYLINUX_CONF_TABLE_REINSTALL . "<br>\n";
    }

    //---------------------------------------------------------
    // form
    //---------------------------------------------------------
    public function print_form_renew_config_table()
    {
        echo $this->_form->build_lib_box_button_style(_HAPPYLINUX_CONF_TABLE_CONFIG_RENEW, _HAPPYLINUX_CONF_TABLE_CONFIG_RENEW_DESC, 'renew_config', _HAPPYLINUX_EXECUTE);
    }

    public function print_form_remove_xoops_block_table()
    {
        echo $this->_block_checker->build_form_remove_block();
    }

    /**
     * @return bool
     */
    public function check_token()
    {
        return $this->_form->check_token();
    }

    //---------------------------------------------------------
    // form
    //---------------------------------------------------------
    /**
     * @param $str
     * @return string|string[]|null
     */
    public function sanitize_text($str)
    {
        return $this->_strings->sanitize_text($str);
    }

    // --- class end ---
}
