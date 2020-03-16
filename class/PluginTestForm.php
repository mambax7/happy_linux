<?php

namespace XoopsModules\Happylinux;

// $Id: plugin_manage.php,v 1.1 2008/02/26 15:35:43 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// this file contain 3 class
//   happylinux_plugin_manage
//   happylinux_plugin_test
//   happylinux_plugin_test_form
// 2008-02-17 K.OHWADA
//=========================================================


//=========================================================
// class PluginTestForm
//=========================================================

/**
 * Class PluginTestForm
 * @package XoopsModules\Happylinux
 */
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

    /**
     * @return \XoopsModules\Happylinux\Form|\XoopsModules\Happylinux\FormLib|\XoopsModules\Happylinux\Html|\XoopsModules\Happylinux\PluginTestForm|static
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
    // show form
    //---------------------------------------------------------
    /**
     * @param null $data
     */
    public function show($data = null)
    {
        return $this->_show($data);
    }

    /**
     * @param null $data
     */
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
        echo $this->build_form_table_title(_HAPPYLINUX_PLUGIN_TEST);

        $cap_plugins = $this->build_form_caption(_HAPPYLINUX_PLUGIN, _HAPPYLINUX_PLUGIN_DESC);
        $ele_plugins = $this->build_html_textarea('plugins', $plugins);
        echo $this->build_form_table_line($cap_plugins, $ele_plugins);

        $cap_data = $this->build_form_caption(_HAPPYLINUX_PLUGIN_TESTDATA, _HAPPYLINUX_PLUGIN_TESTDATA_DESC);
        $ele_data = $this->build_html_textarea('data', $data, $this->_DATA_ROWS, $this->_DATA_COLS);
        echo $this->build_form_table_line($cap_data, $ele_data);

        $ele_submit = $this->build_html_input_submit('submit', _HAPPYLINUX_EXECUTE);
        echo $this->build_form_table_line('', $ele_submit, 'foot', 'foot');

        echo $this->build_form_table_end();
        echo $this->build_form_end();
        // --- form end ---
    }

    // --- class end ---
}
