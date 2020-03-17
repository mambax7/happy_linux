<?php

// $Id: time.php,v 1.1 2010/11/07 14:59:17 ohwada Exp $

// 2007-11-11 K.OHWADA
// build_elapse_time()
// happy_linux_get_execution_time()

//=========================================================
// Happy Linux Framework Module
// 2007-03-01 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_time
//=========================================================

/**
 * Class happy_linux_time
 */
class happy_linux_time
{
    public $_flag_mem = false;
    public $_count = 0;
    public $_time_start = 0;
    public $_time_current = 0;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * happy_linux_time constructor.
     * @param bool $flag_mem
     */
    public function __construct($flag_mem = false)
    {
        $this->_flag_mem = $flag_mem;

        $time = $this->get_microtime();
        $this->_time_start = $time;
        $this->_time_current = $time;
    }

    /**
     * @param bool $flag_mem
     * @return \happy_linux_time
     */
    public static function getInstance($flag_mem = false)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self($flag_mem);
        }

        return $instance;
    }

    //-------------------------------------------------------------------
    // measure time
    //-------------------------------------------------------------------

    /**
     * @param null $msg
     * @param bool $format
     */
    public function print_lap_time($msg = null, $format = true)
    {
        [$count, $time_now, $time_elapse, $time_lap] = $this->get_lap_time();
        if ($format) {
            $time_elapse = sprintf('%6.3f', $time_elapse);
            $time_lap = sprintf('%6.3f', $time_lap);
        }

        $mem = '';
        if ($this->_flag_mem && function_exists('memory_get_usage')) {
            $mem = (int)(memory_get_usage() / 1000) . ' KB:';
        }

        echo "$count:\t $mem\t $time_elapse\t $time_lap\t $msg <br>\n";
    }

    /**
     * @return string
     */
    public function build_elapse_time()
    {
        $text = 'execution time : ' . $this->get_elapse_time() . ' sec';

        return $text;
    }

    /**
     * @return array
     */
    public function get_lap_time()
    {
        $time_now = $this->get_microtime();
        $time_elapse = $time_now - $this->_time_start;
        $time_lap = $time_now - $this->_time_current;
        $this->_time_current = $time_now;
        $this->_count++;
        $ret = [$this->_count, $time_now, $time_elapse, $time_lap];

        return $ret;
    }

    /**
     * @param bool $format
     * @return float|int|string
     */
    public function get_elapse_time($format = true)
    {
        $time_elapse = $this->get_microtime() - $this->_time_start;
        if ($format) {
            $time_elapse = sprintf('%6.3f', $time_elapse);
        }

        return $time_elapse;
    }

    /**
     * @return float
     */
    public function get_microtime()
    {
        [$usec, $sec] = explode(' ', microtime());
        $time = (float)$sec + (float)$usec;

        return $time;
    }

    //-------------------------------------------------------------------
    // time utility
    //-------------------------------------------------------------------

    /**
     * @param        $time
     * @param string $y
     * @param string $m
     * @param string $d
     * @param string $h
     * @param string $i
     * @param string $s
     * @return array
     */
    public function &split_time_ymd($time, $y = 'Y', $m = 'n', $d = 'd', $h = 'H', $i = 'i', $s = 's')
    {
        $year = date($y, $time);
        $month = date($m, $time);
        $day = date($d, $time);
        $hour = date($h, $time);
        $min = date($i, $time);
        $sec = date($s, $time);

        $arr = [$year, $month, $day, $hour, $min, $sec];

        return $arr;
    }

    // --- class end ---
}

//=========================================================
// function
//=========================================================
/**
 * @return float|int|string
 */
function happy_linux_get_execution_time()
{
    $time = happy_linux_time::getInstance();

    return $time->get_elapse_time();
}
