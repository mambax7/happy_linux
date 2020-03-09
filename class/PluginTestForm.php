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
// class PluginTestForm
//=========================================================
class PluginTestForm extends FormLib
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

        $this->_post = Post::getInstance();
    }

    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
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
