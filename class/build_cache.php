<?php

// $Id: build_cache.php,v 1.1 2010/11/07 14:59:20 ohwada Exp $

// 2007-10-10 K.OHWADA
// divid from happy_linux_build_rss

//=========================================================
// Happy Linux Framework Module
// 2006-09-01 K.OHWADA
//=========================================================

//=========================================================
// class builder base
//=========================================================

/**
 * Class happy_linux_build_cache
 */
class happy_linux_build_cache
{
    // for reserve, not use here
    public $_DIRNAME = null;
    public $_tepmlate = null;
    public $_cache_time = 0;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
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

    //=========================================================
    // public
    //=========================================================

    /**
     * @param      $template
     * @param int  $cache_time
     * @param bool $flag_force
     * @return mixed
     */
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

        if ($flag_force || (0 == $cache_time) || !$tpl->is_cached($this->_template)) {
            $this->_assign_cache($tpl);
        }

        $ret = $tpl->fetch($template);

        return $ret;
    }

    /**
     * @param      $cache_id
     * @param      $template
     * @param int  $cache_time
     * @param bool $flag_force
     * @return mixed
     */
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

        if ($flag_force || (0 == $cache_time) || !$tpl->is_cached($template, $cache_id)) {
            $this->_assign_cache($tpl);
        }

        $ret = $tpl->fetch($template, $cache_id);

        return $ret;
    }

    /**
     * @param $template
     * @return mixed
     */
    public function rebuild_cache($template)
    {
        $this->clear_compiled_tpl($template);
        $this->clear_cache($template);
        $ret = $this->build_cache($template, 0, true);

        return $ret;
    }

    /**
     * @param $template
     */
    public function clear_cache($template)
    {
        $tpl = new XoopsTpl();
        $tpl->clear_cache($template);
    }

    /**
     * @param $cache_id
     * @param $template
     */
    public function clear_cache_by_cache_id($cache_id, $template)
    {
        $tpl = new XoopsTpl();
        $tpl->clear_cache($template, $cache_id);
    }

    // dir doesn't include XOOPS_ROOT_PATH

    /**
     * @param $dir
     */
    public function clear_compiled_tpl_by_dir($dir)
    {
        $class_dir = happy_linux_dir::getInstance();
        $dir = $class_dir->strip_slash_from_tail($dir);
        $arr = &$class_dir->get_files_in_dir($dir, 'html');

        foreach ($arr as $file) {
            if ('index.html' == $file) {
                continue;
            }

            $this->clear_compiled_tpl(XOOPS_ROOT_PATH . '/' . $dir . '/' . $file);
        }
    }

    /**
     * @param $template
     */
    public function clear_compiled_tpl($template)
    {
        $tpl = new XoopsTpl();
        $tpl->clear_compiled_tpl($template);
    }

    //--------------------------------------------------------
    // set param
    //--------------------------------------------------------

    /**
     * @param $value
     */
    public function set_dirname($value)
    {
        $this->_DIRNAME = $value;
    }

    /**
     * @param $value
     */
    public function set_template($value)
    {
        $this->_tepmlate = $value;
    }

    /**
     * @param $value
     */
    public function set_cache_time($value)
    {
        $this->_cache_time = (int)$value;
    }

    /**
     * @return int
     */
    public function get_cache_time()
    {
        return $this->_cache_time;
    }

    //=========================================================
    // over ride
    //=========================================================

    /**
     * @param $tpl
     */
    public function _assign_cache($tpl)
    {
        // dummy
    }

    // --- class end ---
}
