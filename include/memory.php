<?php

// $Id: memory.php,v 1.1 2010/11/07 14:59:12 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2007-11-01 K.OHWADA
//=========================================================

/**
 * @return bool|string
 */
function happy_linux_build_memory_usage_mb()
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
function happy_linux_get_memory_usage_mb()
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
function happy_linux_memory_get_usage()
{
    if (function_exists('memory_get_usage')) {
        return memory_get_usage();
    }

    return false;
}

/**
 * @return bool|int
 */
function happy_linux_memory_get_peak_usage()
{
    if (function_exists('memory_get_peak_usage')) {
        return memory_get_peak_usage();
    }

    return false;
}
