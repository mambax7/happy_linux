<?php
// $Id: memory.php,v 1.1 2007/11/11 02:39:22 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2007-11-01 K.OHWADA
//=========================================================

/**
 * @return bool|string
 */
function happylinux_build_memory_usage_mb()
{
    if (function_exists('memory_get_usage')) {
        $usage = sprintf('%6.3f', memory_get_usage() / 1000000);

        return 'memory usage : ' . $usage . ' MB';
    }

    return false;
}

/**
 * @return bool|string
 */
function happylinux_get_memory_usage_mb()
{
    if (function_exists('memory_get_usage')) {
        $usage = sprintf('%6.3f', memory_get_usage() / 1000000);

        return $usage;
    }

    return false;
}

/**
 * @return bool|int
 */
function happylinux_memory_get_usage()
{
    if (function_exists('memory_get_usage')) {
        return memory_get_usage();
    }

    return false;
}

/**
 * @return bool|int
 */
function happylinux_memory_get_peak_usage()
{
    if (function_exists('memory_get_peak_usage')) {
        return memory_get_peak_usage();
    }

    return false;
}
