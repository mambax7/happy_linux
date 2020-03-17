<?php

// $Id: image_size.php,v 1.1 2010/11/07 14:59:23 ohwada Exp $

// 2009-02-20 K.OHWADA
// $flag_zero in adjust_size()

// 2006-07-10 K.OHWADA
// this is new file
// porting from weblinks_image_size.php

//=========================================================
// Happy Linux Framework Module
// for PHP gennerally
// 2006-07-10 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_image_size
//=========================================================

/**
 * Class happy_linux_image_size
 */
class happy_linux_image_size
{
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

    //---------------------------------------------------------
    // function
    //---------------------------------------------------------

    /**
     * @param $file
     * @return int[]
     */
    public function get_size($file)
    {
        $size = getimagesize($file);    // PHP function
        if (!$size) {
            return [0, 0];
        }

        $width = (int)$size[0];
        $height = (int)$size[1];

        return [$width, $height];
    }

    /**
     * @param      $width
     * @param      $height
     * @param      $max_width
     * @param      $max_height
     * @param bool $flag_zero
     * @return array|int[]
     */
    public function adjust_size($width, $height, $max_width, $max_height, $flag_zero = false)
    {
        if ($flag_zero && ((0 == $width) || (0 == $height))) {
            return [$max_width, 0];
        }

        if ($width > $max_width) {
            $mag = $max_width / $width;
            $width = $max_width;
            $height = $height * $mag;
        }

        if ($height > $max_height) {
            $mag = $max_height / $height;
            $height = $max_height;
            $width = $width * $mag;
        }

        $width = (int)$width;
        $height = (int)$height;

        return [$width, $height];
    }

    /**
     * @param     $width
     * @param     $height
     * @param int $min_width
     * @param int $min_height
     * @return int[]
     */
    public function minimum_size($width, $height, $min_width = 0, $min_height = 0)
    {
        if (empty($width)) {
            $width = $min_width;
        }

        if (empty($height)) {
            $height = $min_height;
        }

        return [$width, $height];
    }

    // --- class end ---
}
