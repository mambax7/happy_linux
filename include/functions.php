<?php
// $Id: functions.php,v 1.4 2007/11/11 02:39:22 ohwada Exp $

// 2007-11-01 K.OHWADA
// getSingleton()

// 2007-07-01 K.OHWADA
// use debug_print_backtrace()

// 2006-07-10 K.OHWADA
// this is new file
// porting from rssc_get_handler.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

/**
 * @param $name
 * @return bool|mixed
 */
function &getSingleton($name)
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
    if (happylinux_is_admin() && function_exists('debug_print_backtrace')) {
        echo "getSingleton <br>\n";
        debug_print_backtrace();
    }

    $false = false;

    return $false;
}

/**
 * @param null   $name
 * @param null   $module_dir
 * @param string $prefix
 * @return bool|mixed
 */
function &happylinux_get_handler($name = null, $module_dir = null, $prefix = 'happylinux')
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
        $handler = &$handlers[$module_dir][$name];

        return $handler;
    }
    if (happylinux_is_admin()) {
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
function happylinux_is_admin()
{
    global $xoopsUser;
    if (is_object($xoopsUser) && $xoopsUser->isAdmin()) {
        return true;
    }

    return false;
}

//=========================================================
// function
//=========================================================
/**
 * @return float|int|string
 */
function happylinux_get_execution_time()
{
    $time = XoopsModules\Happylinux\Time::getInstance();

    return $time->get_elapse_time();
}
