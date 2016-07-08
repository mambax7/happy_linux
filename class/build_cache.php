<?php
// $Id: build_cache.php,v 1.2 2007/10/24 03:41:47 ohwada Exp $

// 2007-10-10 K.OHWADA
// divid from happy_linux_build_rss

//=========================================================
// Happy Linux Framework Module
// 2006-09-01 K.OHWADA
//=========================================================

//=========================================================
// class builder base
//=========================================================
class happy_linux_build_cache
{

    // for reserve, not use here
    public $_DIRNAME    = null;
    public $_tepmlate   = null;
    public $_cache_time = 0;

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
            $instance = new happy_linux_build_cache();
        }
        return $instance;
    }

    //=========================================================
    // public
    //=========================================================
    public function build_cache($template, $cache_time = 0, $flag_force = false)
    {

        // build template
        $tpl = new XoopsTpl();

        // use cache
        if (!$flag_force && ($cache_time > 0)) {
            // 2: use cache time in every templates
            $tpl->xoops_setCaching(2);
            $tpl->xoops_setCacheTime($cache_time);
        }

        if ($flag_force || ($cache_time == 0) || !$tpl->is_cached($this->_template)) {
            $this->_assign_cache($tpl);
        }

        $ret = $tpl->fetch($template);
        return $ret;
    }

    public function build_cache_by_cache_id($cache_id, $template, $cache_time = 0, $flag_force = false)
    {

        // build template
        $tpl = new XoopsTpl();

        // use cache
        if (!$flag_force && ($cache_time > 0)) {
            // 2: use cache time in every templates
            $tpl->xoops_setCaching(2);
            $tpl->xoops_setCacheTime($cache_time);
        }

        if ($flag_force || ($cache_time == 0) || !$tpl->is_cached($template, $cache_id)) {
            $this->_assign_cache($tpl);
        }

        $ret = $tpl->fetch($template, $cache_id);
        return $ret;
    }

    public function rebuild_cache($template)
    {
        $this->clear_compiled_tpl($template);
        $this->clear_cache($template);
        $ret = $this->build_cache($template, 0, true);
        return $ret;
    }

    public function clear_cache($template)
    {
        $tpl = new XoopsTpl();
        $tpl->clear_cache($template);
    }

    public function clear_cache_by_cache_id($cache_id, $template)
    {
        $tpl = new XoopsTpl();
        $tpl->clear_cache($template, $cache_id);
    }

    // dir doesn't include XOOPS_ROOT_PATH
    public function clear_compiled_tpl_by_dir($dir)
    {
        $class_dir = happy_linux_dir::getInstance();
        $dir       = $class_dir->strip_slash_from_tail($dir);
        $arr       =& $class_dir->get_files_in_dir($dir, 'html');

        foreach ($arr as $file) {
            if ($file == 'index.html') {
                continue;
            }

            $this->clear_compiled_tpl(XOOPS_ROOT_PATH . '/' . $dir . '/' . $file);
        }
    }

    public function clear_compiled_tpl($template)
    {
        $tpl = new XoopsTpl();
        $tpl->clear_compiled_tpl($template);
    }

    //--------------------------------------------------------
    // set param
    //--------------------------------------------------------
    public function set_dirname($value)
    {
        $this->_DIRNAME = $value;
    }

    public function set_template($value)
    {
        $this->_tepmlate = $value;
    }

    public function set_cache_time($value)
    {
        $this->_cache_time = (int)$value;
    }

    public function get_cache_time()
    {
        return $this->_cache_time;
    }

    //=========================================================
    // over ride
    //=========================================================
    public function _assign_cache(&$tpl)
    {
        // dummy
    }

    // --- class end ---
}
