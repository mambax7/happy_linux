<?php

namespace XoopsModules\Happylinux;

/*
 Utility Class Definition

 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 *
 * @package      \module\xsitemap\class
 * @license      https://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2000-2020 &copy; XOOPS Project
 * @author       Mamba <mambax7@gmail.com>
 */

use XoopsModules\Happylinux;
use XoopsModules\Happylinux\Common;
use XoopsModules\Happylinux\Constants;

/**
 * Class Utility
 */
class Utility extends Common\SysUtility
{
    //--------------- Custom module methods -----------------------------

    /**
     * Access the only instance of this class
     *
     * @return object
     *
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public static function getSingleton($name)
    {
        static $singletons;

        if (!isset($singletons[$name])) {
            $class = 'happylinux_' . $name;
            $file  = XOOPS_ROOT_PATH . '/modules/happylinux/class/' . $name . '.php';

            if (file_exists($file)) {
                require_once $file;
            }

            if (class_exists($class)) {
                $singletons[$name] = new $class();
            }
        }

        if (isset($singletons[$name])) {
            $single = &$singletons[$name];

            return $single;
        }
//        if (happylinux_is_admin() && function_exists('debug_print_backtrace')) {
//            echo "getSingleton <br>\n";
//            debug_print_backtrace();
//        }

        $false = false;

        return $false;
    }

    /**
     * @param null   $name
     * @param null   $module_dir
     * @param string $prefix
     * @return bool|mixed
     */
//    public static function &happylinux_get_handler($name = null, $module_dir = null, $prefix = 'happylinux')
    public static function getHandler($name = null, $module_dir = null, $prefix = 'happylinux')
    {
        static $handlers;

        // if $module_dir is not specified
        if (!isset($module_dir)) {
            //if a module is loaded
            if (isset($GLOBALS['xoopsModule']) && is_object($GLOBALS['xoopsModule'])) {
                $module_dir = $GLOBALS['xoopsModule']->getVar('dirname');
            } else {
                trigger_error('No Module is loaded', E_USER_ERROR);
            }
        } else {
            $module_dir = trim($module_dir);
        }

        $name = (!isset($name)) ? $module_dir : trim($name);

        if (!isset($handlers[$module_dir][$name])) {
            $class = $prefix . '_' . $name . 'Handler';
            $file  = XOOPS_ROOT_PATH . '/modules/' . $module_dir . '/class/' . $class . '.php';

            if (file_exists($file)) {
                require_once $file;
            }

            if (class_exists($class)) {
                $handlers[$module_dir][$name] = new $class($module_dir);
            }
        }

        if (isset($handlers[$module_dir][$name])) {
            $han = &$handlers[$module_dir][$name];

            return $han;
        }
        if (self::is_admin()) {
            if (function_exists('debug_print_backtrace')) {
                echo "happylinux_get_handler <br>\n";
                debug_print_backtrace();
            }

            $msg = 'Handler does not exist<br>Module: ' . $module_dir . '<br>Name: ' . $name . '<br>Prefix: ' . $prefix . '<br>';
            trigger_error($msg, E_USER_ERROR);
        }

        $false = false;

        return $false;
    }

    /**
     * @return bool
     */
    public static function is_admin()
    {
        global $xoopsUser;
        if (is_object($xoopsUser) && $xoopsUser->isAdmin()) {
            return true;
        }

        return false;
    }


}
