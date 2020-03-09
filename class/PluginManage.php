<?php

namespace XoopsModules\Happy_linux;

// $Id: plugin_manage.php,v 1.1 2008/02/26 15:35:43 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// this file contain 3 class
//   happy_linux_plugin_manage
//   happy_linux_plugin_test
//   happy_linux_plugin_test_form
// 2008-02-17 K.OHWADA
//=========================================================

//=========================================================
// class PluginManage
//=========================================================
class PluginManage
{
    public $_plugin;
    public $_post;
    public $_test;
    public $_form;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_post = Post::getInstance();
        $this->_test = Plugin_test::getInstance();
        $this->_form = Plugin_test_form::getInstance();
    }

    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    public function set_plugin_class(&$class)
    {
        $class->set_lang_name(_HAPPY_LINUX_PLUGIN_NAME);
        $class->set_lang_usage(_HAPPY_LINUX_PLUGIN_USAGE);
        $class->set_lang_decription(_HAPPY_LINUX_PLUGIN_DESCRIPTION);

        $this->_plugin = &$class;
        $this->_test->set_plugin_class($class);
    }

    //---------------------------------------------------------
    // post
    //---------------------------------------------------------
    public function get_op()
    {
        return $this->_post->get_post_text('op');
    }

    //---------------------------------------------------------
    // form
    //---------------------------------------------------------
    public function show_form()
    {
        $this->_show_form();
    }

    public function _show_form()
    {
        $this->_plugin->init_class_list();

        echo '<h4>' . _HAPPY_LINUX_PLUGIN_LIST . "</h4>\n";
        echo $this->_plugin->build_table();

        echo '<h4>' . _HAPPY_LINUX_PLUGIN_TEST . "</h4>\n";
        $data = null;

        $plugin_data = $this->_plugin->get_exsample_data();
        if (is_array($plugin_data)) {
            $data = var_export($plugin_data, true);
        }

        $this->_form->show($data);
    }

    //---------------------------------------------------------
    // excute
    //---------------------------------------------------------
    public function execute()
    {
        return $this->_execute();
    }

    public function _execute()
    {
        echo '<h4>' . _HAPPY_LINUX_PLUGIN_TEST . "</h4>\n";

        $this->_form->show();
        echo "<br><hr>\n";

        $this->_test->execute();
        $this->print_footer();
    }

    public function print_footer()
    {
        $url = xoops_getenv('PHP_SELF');
        echo "<hr><br>\n";
        echo '<a href="' . $url . '"> - ' . _HAPPY_LINUX_PLUGIN_LIST . "</a>\n";
    }

    // --- class end ---
}
