<?php

namespace XoopsModules\Happylinux;

// $Id: bin_file.php,v 1.1 2007/06/17 03:20:40 ohwada Exp $

// 2007-06-10 K.OHWADA
// divid from BinBase

//=========================================================
// Happy Linux Framework Module
// 2007-06-01 K.OHWADA
//=========================================================

// === class begin ===
if (!class_exists('happylinux_bin_file')) {
    //=========================================================
    // class bin_file
    // this class is used by command line
    //=========================================================

    /**
     * Class BinFile
     * @package XoopsModules\Happylinux
     */
    class BinFile extends File
    {
        //---------------------------------------------------------
        // constructor
        //---------------------------------------------------------
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * @return \XoopsModules\Happylinux\BinFile|\XoopsModules\Happylinux\File|static
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
        // bin
        //---------------------------------------------------------
        /**
         * @param        $filename
         * @param string $mode
         * @return bool
         */
        public function open_bin($filename, $mode = 'w')
        {
            if ($this->_flag_write) {
                return $this->fopen($filename, $mode);
            }

            return true;    // no action
        }

        /**
         * @param $flag_chmod
         */
        public function close_bin($flag_chmod)
        {
            if ($this->_flag_write) {
                $this->fclose_chmod($flag_chmod);
            }
        }

        /**
         * @param $data
         */
        public function write_bin($data)
        {
            if ($this->_flag_write) {
                $this->fwrite($data);
            }
        }

        // --- class end ---
    }
    // === class end ===
}
