<?php

// $Id: bin_file.php,v 1.1 2010/11/07 14:59:20 ohwada Exp $

// 2007-06-10 K.OHWADA
// divid from bin_base

//=========================================================
// Happy Linux Framework Module
// 2007-06-01 K.OHWADA
//=========================================================

// === class begin ===
if (!class_exists('happy_linux_bin_file')) {
    //=========================================================
    // class happy_linux_bin_file
    // this class is used by command line
    //=========================================================

    /**
     * Class happy_linux_bin_file
     */
    class happy_linux_bin_file extends happy_linux_file
    {
        //---------------------------------------------------------
        // constructor
        //---------------------------------------------------------
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * @return \happy_linux_bin_file|\happy_linux_file|static
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
