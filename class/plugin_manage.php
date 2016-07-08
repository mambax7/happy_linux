<?php
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
// class happy_linux_plugin_manage
//=========================================================
class happy_linux_plugin_manage
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
        $this->_post = happy_linux_post::getInstance();
        $this->_test = happy_linux_plugin_test::getInstance();
        $this->_form = happy_linux_plugin_test_form::getInstance();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_plugin_manage();
        }
        return $instance;
    }

    public function set_plugin_class(&$class)
    {
        $class->set_lang_name(_HAPPY_LINUX_PLUGIN_NAME);
        $class->set_lang_usage(_HAPPY_LINUX_PLUGIN_USAGE);
        $class->set_lang_decription(_HAPPY_LINUX_PLUGIN_DESCRIPTION);

        $this->_plugin =& $class;
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
        echo "<br /><hr />\n";

        $this->_test->execute();
        $this->print_footer();
    }

    public function print_footer()
    {
        $url = xoops_getenv('PHP_SELF');
        echo "<hr /><br />\n";
        echo '<a href="' . $url . '"> - ' . _HAPPY_LINUX_PLUGIN_LIST . "</a>\n";
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_plugin_test
//=========================================================
class happy_linux_plugin_test
{
    public $_plugin;
    public $_post;
    public $_form;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_post    = happy_linux_post::getInstance();
        $this->_strings = happy_linux_strings::getInstance();
        $this->_form    = happy_linux_plugin_test_form::getInstance();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_plugin_test();
        }
        return $instance;
    }

    public function set_plugin_class(&$class)
    {
        $this->_plugin =& $class;
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
            echo "<br />\n";
            return false;
        }

        $this->_plugin->set_flag_print(true);
        $data = null;

        if ($post_data) {
            $str  = '$data = ' . $this->_strings->add_str_to_tail($post_data, ';');
            $ret1 = eval($str);
            if ($ret1 === false) {
                xoops_error('cannot eval data');
                echo "<br />\n";
                return false;
            }
        } else {
            $ret2 = $this->_plugin->get_exsample_data();
            if (empty($ret2)) {
                xoops_error('cannot get data');
                echo "<br />\n";
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

        $ret =& $this->_plugin->get_items();

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

//=========================================================
// class happy_linux_plugin_test_form
//=========================================================
class happy_linux_plugin_test_form extends happy_linux_form_lib
{
    public $_post;

    public $_DATA_ROWS = 10;
    public $_DATA_COLS = 50;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $this->_post = happy_linux_post::getInstance();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_plugin_test_form();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // show form
    //---------------------------------------------------------
    public function show($data = null)
    {
        return $this->_show($data);
    }

    public function _show($data = null)
    {
        $plugins = $this->_post->get_post_text('plugins');

        if (empty($data)) {
            $data = $this->_post->get_post_text('data');
        }

        // --- form begin ---
        echo $this->build_form_begin();
        echo $this->build_token();
        echo $this->build_html_input_hidden('op', 'execute');

        echo $this->build_form_table_begin();
        echo $this->build_form_table_title(_HAPPY_LINUX_PLUGIN_TEST);

        $cap_plugins = $this->build_form_caption(_HAPPY_LINUX_PLUGIN, _HAPPY_LINUX_PLUGIN_DESC);
        $ele_plugins = $this->build_html_textarea('plugins', $plugins);
        echo $this->build_form_table_line($cap_plugins, $ele_plugins);

        $cap_data = $this->build_form_caption(_HAPPY_LINUX_PLUGIN_TESTDATA, _HAPPY_LINUX_PLUGIN_TESTDATA_DESC);
        $ele_data = $this->build_html_textarea('data', $data, $this->_DATA_ROWS, $this->_DATA_COLS);
        echo $this->build_form_table_line($cap_data, $ele_data);

        $ele_submit = $this->build_html_input_submit('submit', _HAPPY_LINUX_EXECUTE);
        echo $this->build_form_table_line('', $ele_submit, 'foot', 'foot');

        echo $this->build_form_table_end();
        echo $this->build_form_end();
        // --- form end ---
    }

    // --- class end ---
}
