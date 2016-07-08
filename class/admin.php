<?php
// $Id: admin.php,v 1.2 2012/03/17 13:09:23 ohwada Exp $

// 2012-03-01 K.OHWADA
// XOOPS_CUBE_LEGACY

// 2008-01-30 K.OHWADA
// Assigning the return value of new by reference is deprecated

// 2007-09-30 K.OHWADA
// print_modules()
// BUG: typo happy_liunx -> happy_linux

// 2007-09-30 K.OHWADA
// typo cobe -> cube

//=========================================================
// Happy Linux Framework Module
// 2007-06-01 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_admin
//=========================================================
class happy_linux_admin
{
    public $_TIME = 10;    // sec

    // BUG: typo happy_liunx -> happy_linux
    public $_FILE_PRELOAD = 'modules/happy_linux/preload/admin.php';

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
            $instance = new happy_linux_admin();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // public
    //---------------------------------------------------------
    public function print_preferences()
    {
        $mid = $this->_get_mid();

        $url_20 = XOOPS_URL . '/modules/system/admin.php?fct=preferences&op=showmod&mod=' . $mid;
        $url_21 = XOOPS_URL . '/modules/legacy/admin/index.php?action=PreferenceEdit&confmod_id=' . $mid;

        $this->_preload_file();

        $ver = $this->_judge_version();
        switch ($ver) {
            case 'xoops_cube_21':
                $msg = 'XOOPS Cube 2.1';
                $url = $url_21;
                break;

            case 'xoops_22':
                $msg = 'XOOPS 2.2';
                $url = $url_20;
                break;

            case 'xoops_20':
            default:
                $msg = 'XOOPS 2.0';
                $url = $url_20;
                break;
        }

        $this->_print_title(_PREFERENCES);
        $this->_print_judge($ver);
        $this->_print_jump($this->_TIME);

        echo "<ul>\n";
        echo '<li><a href="' . $url_20 . '">XOOPS 2.0 / 2.2</a></li>' . "\n";
        echo '<li><a href="' . $url_21 . '">XOOPS Cube 2.1</a></li>' . "\n";
        echo "</ul>\n";

        $this->_print_js($url, $this->_TIME * 1000);
    }

    public function print_templates()
    {
        $dirname      = $this->_get_dirname();
        $template_set = $this->_get_template_set();

        $url_20 = XOOPS_URL . '/modules/system/admin.php?fct=tplsets&op=listtpl&tplset=' . $template_set . '&moddir=' . $dirname;
        $url_21 = XOOPS_URL . '/modules/legacyRender/admin/index.php?action=TplfileList&tpl_tplset=' . $template_set . '&tpl_module=' . $dirname;

        $this->_preload_file();

        $ver = $this->_judge_version();
        switch ($ver) {
            case 'xoops_cube_21':
                $url = $url_21;
                break;

            case 'xoops_22':
                $url = $url_20;
                break;

            case 'xoops_20':
            default:
                $url = $url_20;
                break;
        }

        $this->_print_title(_HAPPY_LINUX_AM_TEMPLATE);
        $this->_print_judge($ver);
        $this->_print_jump($this->_TIME);

        echo "<ul>\n";
        echo '<li><a href="' . $url_20 . '">XOOPS 2.0 / 2.2 </a></li>' . "\n";
        echo '<li><a href="' . $url_21 . '">XOOPS Cube 2.1</a></li>' . "\n";
        echo "</ul>\n";

        $this->_print_js($url, $this->_TIME * 1000);
    }

    public function print_blocks()
    {
        $url_20 = 'myblocksadmin.php';
        $url_21 = XOOPS_URL . '/modules/legacy/admin/index.php?action=BlockList';
        $url_22 = XOOPS_URL . '/modules/system/admin.php?fct=blocksadmin';

        $this->_preload_file();

        $ver = $this->_judge_version();
        switch ($ver) {
            case 'xoops_cube_21':
                $url = $url_21;
                break;

            case 'xoops_20':
                $url = $url_20;
                break;

            case 'xoops_22':
            default:
                $url = $url_22;
                break;
        }

        $this->_print_title(_HAPPY_LINUX_AM_BLOCK);
        $this->_print_judge($ver);
        $this->_print_jump($this->_TIME);

        echo "<ul>\n";
        echo '<li><a href="' . $url_20 . '">XOOPS 2.0</a> myblocksadmin </li>' . "\n";
        echo '<li><a href="' . $url_22 . '">XOOPS 2.0 / 2.2</a></li>' . "\n";
        echo '<li><a href="' . $url_21 . '">XOOPS Cube 2.1</a></li>' . "\n";
        echo "</ul>\n";

        $this->_print_js($url, $this->_TIME * 1000);
    }

    public function print_modules($flag = false)
    {
        $dirname  = $this->_get_dirname();
        $url_20   = XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin';
        $url_u_20 = XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=update&module=' . $dirname;
        $url_21   = XOOPS_URL . '/modules/legacy/admin/index.php?action=ModuleList';
        $url_u_21 = XOOPS_URL . '/modules/legacy/admin/index.php?action=ModuleUpdate&dirname=' . $dirname;

        $this->_preload_file();

        $ver = $this->_judge_version();
        switch ($ver) {
            case 'xoops_cube_21':
                $url = $url_21;
                break;

            case 'xoops_20':
            case 'xoops_22':
            default:
                $url = $url_20;
                break;
        }

        $this->_print_title(_HAPPY_LINUX_AM_MODULE);
        $this->_print_judge($ver);
        $this->_print_jump($this->_TIME);

        echo "<ul>\n";
        echo '<li><a href="' . $url_20 . '">XOOPS 2.0 / 2.2</a></li>' . "\n";
        echo '<li><a href="' . $url_21 . '">XOOPS Cube 2.1</a></li>' . "\n";
        echo "</ul><br />\n";

        echo '<b>' . _HAPPY_LINUX_AM_MODULE_UPDATE . "</b><br />\n";
        echo "<ul>\n";
        echo '<li><a href="' . $url_u_20 . '">XOOPS 2.0 / 2.2</a></li>' . "\n";
        echo '<li><a href="' . $url_u_21 . '">XOOPS Cube 2.1</a></li>' . "\n";
        echo "</ul>\n";

        $this->_print_js($url, $this->_TIME * 1000);
    }

    //---------------------------------------------------------
    // private
    //---------------------------------------------------------
    public function _print_title($title)
    {
        echo '<h4>' . $title . "</h4>\n";
    }

    public function _print_judge($ver)
    {
        $name = $this->_get_name($ver);
        echo sprintf(_HAPPY_LINUX_AM_JUDGE, $name);
        echo "<br /><br />\n";
    }

    public function _print_jump($time)
    {
        echo sprintf(_HAPPY_LINUX_AM_JUMP, $time);
        echo "<br />\n";
        echo _HAPPY_LINUX_AM_JUMP_IFNO1;
        echo "<br />\n";
        echo sprintf(_HAPPY_LINUX_AM_JUMP_IFNO2, $time);
        echo "<br /><br />\n";
    }

    public function _print_js($url, $time)
    {
        ?>
        <script type="text/javascript">
            //<![CDATA[
            function happy_linux_init() {
                setTimeout('happy_linux_jump()', <?php echo $time;
                    ?>);
            }
            function happy_linux_jump() {
                window.location = '<?php echo $url;
                    ?>';
            }
            window.onload = happy_linux_init;
            //]]>
        </script>
        <?php

    }

    public function _get_name($ver)
    {
        switch ($ver) {
            case 'xoops_cube_21':
                $name = 'XOOPS Cube 2.1';
                break;

            case 'xoops_22':
                $name = 'XOOPS 2.2';
                break;

            case 'xoops_20':
                $name = 'XOOPS 2.0';
                break;

            default:
                $name = 'Unknown';
                break;
        }

        return $name;
    }

    public function _preload_file()
    {
        if (file_exists(XOOPS_ROOT_PATH . '/' . $this->_FILE_PRELOAD)) {
            include_once XOOPS_ROOT_PATH . '/' . $this->_FILE_PRELOAD;
        }

        if (defined('HAPPY_LINUX_ADMIN_TIME')) {
            $this->_TIME = HAPPY_LINUX_ADMIN_TIME;
        }

        if (defined('HAPPY_LINUX_ADMIN_MAJOR_VERSION')) {
            return HAPPY_LINUX_ADMIN_MAJOR_VERSION;
        }
    }

    public function _judge_version()
    {
        if ($this->_is_version_xc_21() && $this->_is_active_legacy_module()) {
            $ver = 'xoops_cube_21';
        } elseif ($this->_is_version_xoops_22()) {
            $ver = 'xoops_22';
        } elseif ($this->_is_version_xoops_20() && $this->_method_exists_xoops_block_get_by_module()) {
            $ver = 'xoops_20';
        } else {
            $ver = 'unknown';
        }
        return $ver;
    }

    public function _is_version_xc_21()
    {
        // XOOPS Cube Legacy 2.1
        if (defined('XOOPS_CUBE_LEGACY')) {
            $this->_TIME = 1;   // 1 sec
            return true;
        }
        if (preg_match("/XOOPS[\s+]Cube.*[\s+]2\.1/i", XOOPS_VERSION)) {
            return true;
        }
        return false;
    }

    public function _is_version_xoops_22()
    {
        // XOOPS 2.2
        if (preg_match("/XOOPS[\s+]2\.2/i", XOOPS_VERSION)) {
            return true;
        }
        return false;
    }

    public function _is_version_xoops_20()
    {
        // XOOPS 2.0
        if (preg_match("/XOOPS[\s+]2\.0/i", XOOPS_VERSION)) {
            return true;
        }
        return false;
    }

    public function _get_mid()
    {
        global $xoopsModule;
        return $xoopsModule->getVar('mid');
    }

    public function _get_dirname()
    {
        global $xoopsModule;
        return $xoopsModule->getVar('dirname');
    }

    public function _get_template_set()
    {
        global $xoopsConfig;
        return $xoopsConfig['template_set'];
    }

    public function _is_active_legacy_module()
    {
        return $this->_is_active_module_by_dirname('legacy');
    }

    public function _is_active_module_by_dirname($dirname)
    {
        $act            = false;
        $module_handler = xoops_getHandler('module');
        $module         = $module_handler->getByDirname($dirname);
        if (is_object($module)) {
            $act = $module->getVar('isactive');
        }
        return $act;
    }

    public function _method_exists_xoops_block_get_by_module()
    {
        include_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';

        // Assigning the return value of new by reference is deprecated
        $block = new XoopsBlock();

        if (method_exists($block, 'getByModule')) {
            return true;
        }
        return false;
    }

    // --- class end ---
}

?>
