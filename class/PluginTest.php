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
// class PluginTest
//=========================================================
class PluginTest
{
    public $_plugin;
    public $_post;
    public $_form;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_post    = Post::getInstance();
        $this->_strings = Strings::getInstance();
        $this->_form    = Plugin_test_form::getInstance();
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
        $this->_plugin = &$class;
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
        $plugins   = $this->_post->get_post_text('plugins');
        $post_data = $this->_post->get_post_text('data');

        if (empty($plugins)) {
            xoops_error('no plugins');
            echo "<br>\n";

            return false;
        }

        $this->_plugin->set_flag_print(true);
        $data = null;

        if ($post_data) {
            $str  = '$data = ' . $this->_strings->add_str_to_tail($post_data, ';');
            $ret1 = eval($str);
            if (false === $ret1) {
                xoops_error('cannot eval data');
                echo "<br>\n";

                return false;
            }
        } else {
            $ret2 = $this->_plugin->get_exsample_data();
            if (empty($ret2)) {
                xoops_error('cannot get data');
                echo "<br>\n";

                return false;
            }
            $data = $ret2;
        }

        echo '<h4> plugins </h4>' . "\n";
        echo '<pre>';
        echo happy_linux_sanitize($plugins);
        echo '</pre>';

        echo '<h4> input </h4>' . "\n";
        echo '<pre>';
        echo happy_linux_sanitize_var_export($data);
        echo '</pre>' . "\n";

        echo '<h4> execute </h4>' . "\n";

        $ret = $this->_plugin_execute($data, $plugins);
        if (!$ret) {
            echo '<h4> failed </h4>' . "\n";

            return true;
        }

        $ret = &$this->_plugin->get_items();

        echo '<h4> output </h4>' . "\n";
        echo '<pre>';
        echo happy_linux_sanitize_var_export($ret);
        echo '</pre>' . "\n";

        return true;
    }

    public function _plugin_execute($data, $plugins)
    {
        $this->_plugin->add_plugin_line('test', $plugins);

        return $this->_plugin->_execute($data);
    }

    // --- class end ---
}
