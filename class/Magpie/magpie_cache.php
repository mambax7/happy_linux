<?php

namespace XoopsModules\Happylinux\Magpie;

// $Id: magpie_cache.php,v 1.1 2007/05/15 04:56:01 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// base on MagpieRSS v0.72
// 2006-06-04 K.OHWADA
//=========================================================

/*
 * Project:     MagpieRSS: a simple RSS integration tool
 * File:        rss_cache.inc, a simple, rolling(no GC), cache
 *              for RSS objects, keyed on URL.
 * Author:      Kellan Elliott-McCrea <kellan@protest.net>
 * Version:     0.51
 * License:     GPL
 *
 * The lastest version of MagpieRSS can be obtained from:
 * https://magpierss.sourceforge.net
 *
 * For questions, help, comments, discussion, etc., please join the
 * Magpie mailing list:
 * https://lists.sourceforge.net/lists/listinfo/magpierss-general
 *
 */

//class RSSCache {

/**
 * Class magpie_cache
 * @package XoopsModules\Happylinux\Magpie
 */
class magpie_cache
{
    public $BASE_CACHE = './cache';    // where the cache files are stored
    public $MAX_AGE    = 3600;         // when are files stale, default one hour
    public $ERROR      = '';           // accumulate error messages

    //  function RSSCache ($base='', $age='') {
    /**
     * magpie_cache constructor.
     * @param string $base
     * @param string $age
     */
    public function __construct($base = '', $age = '')
    {
        if ($base) {
            $this->BASE_CACHE = $base;
        }
        if ($age) {
            $this->MAX_AGE = $age;
        }

        // attempt to make the cache directory
        if (!file_exists($this->BASE_CACHE)) {
            $status = @mkdir($this->BASE_CACHE, 0755);

            // if make failed
            if (!$status) {
                $this->error("Cache couldn't make dir '" . $this->BASE_CACHE . "'.");
            }
        }
    }

    /*=======================================================================*\
        Function:   set
        Purpose:    add an item to the cache, keyed on url
        Input:      url from wich the rss file was fetched
        Output:     true on sucess
    \*=======================================================================*/
    /**
     * @param $url
     * @param $rss
     * @return int|string
     */
    public function set($url, $rss)
    {
        $this->ERROR = '';
        $cache_file  = $this->file_name($url);
        $fp          = @fopen($cache_file, 'wb');

        if (!$fp) {
            $this->error("Cache unable to open file for writing: $cache_file");

            return 0;
        }

        $data = $this->serialize($rss);
        fwrite($fp, $data);
        fclose($fp);

        return $cache_file;
    }

    /*=======================================================================*\
        Function:   get
        Purpose:    fetch an item from the cache
        Input:      url from wich the rss file was fetched
        Output:     cached object on HIT, false on MISS
    \*=======================================================================*/
    /**
     * @param $url
     * @return int|mixed
     */
    public function get($url)
    {
        $this->ERROR = '';
        $cache_file  = $this->file_name($url);

        if (!file_exists($cache_file)) {
            $this->debug("Cache doesn't contain: $url (cache file: $cache_file)");

            return 0;
        }

        $fp = @fopen($cache_file, 'rb');
        if (!$fp) {
            $this->error("Failed to open cache file for reading: $cache_file");

            return 0;
        }

        if ($filesize = filesize($cache_file)) {
            $data = fread($fp, filesize($cache_file));
            $rss  = $this->unserialize($data);

            return $rss;
        }

        return 0;
    }

    /*=======================================================================*\
        Function:   check_cache
        Purpose:    check a url for membership in the cache
                    and whether the object is older then MAX_AGE (ie. STALE)
        Input:      url from wich the rss file was fetched
        Output:     cached object on HIT, false on MISS
    \*=======================================================================*/
    /**
     * @param $url
     * @return string
     */
    public function check_cache($url)
    {
        $this->ERROR = '';
        $filename    = $this->file_name($url);

        if (file_exists($filename)) {
            // find how long ago the file was added to the cache
            // and whether that is longer then MAX_AGE
            $mtime = filemtime($filename);
            $age   = time() - $mtime;
            if ($this->MAX_AGE > $age) {
                // object exists and is current
                return 'HIT';
            }
            // object exists but is old
            return 'STALE';
        }
        // object does not exist
        return 'MISS';
    }

    /**
     * @param $cache_key
     * @return false|int
     */
    public function cache_age($cache_key)
    {
        $filename = $this->file_name($url);
        if (file_exists($filename)) {
            $mtime = filemtime($filename);
            $age   = time() - $mtime;

            return $age;
        }

        return -1;
    }

    /*=======================================================================*\
        Function:   serialize
    \*=======================================================================*/
    /**
     * @param $rss
     * @return string
     */
    public function serialize($rss)
    {
        return serialize($rss);
    }

    /*=======================================================================*\
        Function:   unserialize
    \*=======================================================================*/
    /**
     * @param $data
     * @return mixed
     */
    public function unserialize($data)
    {
        return unserialize($data);
    }

    /*=======================================================================*\
        Function:   file_name
        Purpose:    map url to location in cache
        Input:      url from wich the rss file was fetched
        Output:     a file name
    \*=======================================================================*/
    /**
     * @param $url
     * @return string
     */
    public function file_name($url)
    {
        $filename = md5($url);

        return implode(DIRECTORY_SEPARATOR, [$this->BASE_CACHE, $filename]);
    }

    /*=======================================================================*\
        Function:   error
        Purpose:    register error
    \*=======================================================================*/
    /**
     * @param     $errormsg
     * @param int $lvl
     */
    public function error($errormsg, $lvl = E_USER_WARNING)
    {
        // append PHP's error message if track_errors enabled
        if (isset($php_errormsg)) {
            $errormsg .= " ($php_errormsg)";
        }
        $this->ERROR = $errormsg;
        if (MAGPIE_DEBUG) {
            trigger_error($errormsg, $lvl);
        } else {
            error_log($errormsg, 0);
        }
    }

    /**
     * @param     $debugmsg
     * @param int $lvl
     */
    public function debug($debugmsg, $lvl = E_USER_NOTICE)
    {
        if (MAGPIE_DEBUG) {
            $this->error("MagpieRSS [debug] $debugmsg", $lvl);
        }
    }
}
