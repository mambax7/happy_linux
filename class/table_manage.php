<?php

// $Id: table_manage.php,v 1.1 2010/11/07 14:59:22 ohwada Exp $

//================================================================
// Happy Linux Framework Module
// 2007-11-24 K.OHWADA
//================================================================

//================================================================
// class happy_linux_table_manage
//================================================================

/**
 * Class happy_linux_table_manage
 */
class happy_linux_table_manage extends happy_linux_error
{
    public $_DIRNAME;

    public $_config_store_handler;
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
    public $_THIS_TITLE = _HAPPY_LINUX_CONF_TABLE_MANAGE;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * happy_linux_table_manage constructor.
     * @param $dirname
     */
    public function __construct($dirname)
    {
        $this->_DIRNAME = $dirname;

        parent::__construct();

        $this->_post = happy_linux_post::getInstance();
        $this->_form = happy_linux_form_lib::getInstance();
        $this->_strings = happy_linux_strings::getInstance();
        $this->_system = happy_linux_system::getInstance();

        $this->_this_url = xoops_getenv('PHP_SELF');
    }

    /**
     * @param null $dirname
     * @return \happy_linux_table_manage|static
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
    public function set_config_handler($name, $dirname, $prefix)
    {
        $this->_config_store_handler = happy_linux_config_store_handler::getInstance();
        $this->_config_store_handler->set_handler($name, $dirname, $prefix);
    }

    /**
     * @param $class
     */
    public function set_config_define($class)
    {
        $this->_config_store_handler->set_define($class);
    }

    /**
     * @param $class
     */
    public function set_install_class($class)
    {
        $this->_install = &$class;
    }

    public function set_xoops_block_checker()
    {
        $this->_block_checker = happy_linux_xoops_block_checker::getInstance();
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
        if (!$this->check_table_scheme_by_handler($this->_config_store_handler, false)) {
            return false;
        }

        if (!$this->_config_store_handler->compare_to_define()) {
            $this->print_red($this->_config_store_handler->getErrors(1), false);
            echo "<br>\n";
            $this->_print_action_define();

            return false;
        }

        $this->print_blue('check OK');

        return true;
    }

    /**
     * @param $table_short
     * @param $module_dir
     * @param $prefix
     * @return bool
     */
    public function check_table_scheme_by_name($table_short, $module_dir, $prefix)
    {
        return $this->check_table_scheme_by_handler(
            happy_linux_get_handler($table_short, $module_dir, $prefix)
        );
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
        $this->print_bread(_HAPPY_LINUX_XOOPS_BLOCK_TABLE_REMOVE);

        if (!$this->check_token()) {
            xoops_error('Token Error');

            return;
        }

        echo '<h4>' . _HAPPY_LINUX_XOOPS_BLOCK_TABLE_REMOVE . "</h4>\n";
        echo $this->_block_checker->execute_remove_block();
    }

    public function renew_config()
    {
        if (!$this->check_token()) {
            xoops_cp_header();
            $this->print_bread(_HAPPY_LINUX_CONF_TABLE_CONFIG_RENEW);
            xoops_error('Token Error');
            xoops_cp_footer();
            exit();
        }

        $this->_install->truncate_table($this->_install->_config_table);
        $this->_install->init_config();

        redirect_header($this->_this_url, 1, _HAPPY_LINUX_EXECUTED);
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
        echo '<h4>' . sprintf(_HAPPY_LINUX_CONF_TABLE_CHECK, $table) . "</h4>\n";
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
                'url' => 'index.php',
            ],
            [
                'name' => $this->_THIS_TITLE,
                'url' => $this->_this_url,
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
        echo "<br><hr />\n";
        echo '<h4>' . _HAPPY_LINUX_FINISHED . "</h4>\n";
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
        echo _HAPPY_LINUX_CONF_TABLE_REINSTALL . "<br>\n";
    }

    //---------------------------------------------------------
    // form
    //---------------------------------------------------------
    public function print_form_renew_config_table()
    {
        echo $this->_form->build_lib_box_button_style(
            _HAPPY_LINUX_CONF_TABLE_CONFIG_RENEW,
            _HAPPY_LINUX_CONF_TABLE_CONFIG_RENEW_DESC,
            'renew_config',
            _HAPPY_LINUX_EXECUTE
        );
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
