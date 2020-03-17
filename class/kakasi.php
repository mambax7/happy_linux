<?php

// $Id: kakasi.php,v 1.1 2010/11/07 14:59:20 ohwada Exp $

// 2007-10-10 K.OHWADA
// not use happy_linux_dir

// 2007-09-20 K.OHWADA
// happy_linux_dir

//=========================================================
// Happy Linux Framework Module
// 2007-06-01 K.OHWADA
//=========================================================

include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/dir.php';

//=========================================================
// class  happy_linux_kakasi
// requre happy_linux_dir
//=========================================================

//---------------------------------------------------------
// kanji kana simple inverter
// http://kakasi.namazu.org/
// kakasi [options] [jisyo1 [jisyo2 [jisyo1,,]]]
// -w: wkatigaki
// -c: read except for the blank and the line feed which is contained in the kanji phrase.
// -i{jis, oldjis, euc, dec, sjis}: charset
// The repartition of the character
// a: ASCII
// j: JIS romaji
// g: DEC graphic
// k: katakana (GR repartition in JIS x0201)
// J: kanji
// H: hiragana
// K: kakatakan (in 5 repartition)
// E: kigou (except the above)
//---------------------------------------------------------

/**
 * Class happy_linux_kakasi
 */
class happy_linux_kakasi
{
    public $_kakasi_path = '/usr/local/bin/kakasi';
    public $_mode_excute = 0;    // file mode

    public $_encoding = 'euc';
    public $_dicts = [];

    public $_words = '';
    public $_errors = '';
    public $_cmd_error = '';
    public $_dir_work = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // caller can change
        $this->_dir_work = XOOPS_ROOT_PATH . '/modules/happy_linux/cache';
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
    // public
    //---------------------------------------------------------

    /**
     * @param $str
     * @param $opt
     * @return bool
     */
    public function execute($str, $opt)
    {
        if (!$this->is_executable_kakasi()) {
            return false;
        }

        if ($this->_mode_excute) {
            return $this->execute_pipe($str, $opt);
        }

        return $this->execute_file($str, $opt);
    }

    // this method works well in MS-Windows

    /**
     * @param $str
     * @param $opt
     * @return bool
     */
    public function execute_file($str, $opt)
    {
        $this->_words = '';
        $this->_errors = '';
        $this->_cmd_error = '';

        $file_in = tempnam($this->_dir_work, 'kki');
        $file_out = tempnam($this->_dir_work, 'kko');

        $cmd = $this->_kakasi_path . ' ' . $opt;

        // set content
        $fp_in = fopen($file_in, 'wb');
        if (!$fp_in) {
            $this->_cmd_error = 'cannot open file: ' . $file_in;

            return false;
        }

        fwrite($fp_in, $str);
        fclose($fp_in);

        // kakasi
        exec("$cmd < $file_in > $file_out");

        // get parsing words
        $fp_out = fopen($file_out, 'rb');
        if (!$fp_out) {
            $this->_cmd_error = 'cannot open file: ' . $file_out;

            return false;
        }
        while ($w = fgets($fp_out)) {
            $this->_words .= $w . "\n";
        }
        fclose($fp_out);

        unlink($file_in);
        unlink($file_out);

        return true;
    }

    // this method is more efficient than using file
    // but, doesn't work in MS-Windows

    /**
     * @param $str
     * @param $opt
     * @return bool
     */
    public function execute_pipe($str, $opt)
    {
        $this->_words = '';
        $this->_errors = '';
        $this->_cmd_error = '';

        $cmd = $this->_kakasi_path . ' ' . $opt;

        $descriptorspec = [
            0 => ['pipe', 'r'],  // stdin
            1 => ['pipe', 'w'],  // stdout
            2 => ['pipe', 'w'],  // stderr
        ];

        $pipes = [];

        $rp = proc_open($cmd, $descriptorspec, $pipes);
        if (!is_resource($rp)) {
            $this->_cmd_error = 'cannot excute command: ' . $opt;

            return false;
        }

        // set content
        fwrite($pipes[0], $str);
        fclose($pipes[0]);

        // get parsing words
        while ($w = fgets($pipes[1])) {
            $this->_words .= $w . "\n";
        }
        fclose($pipes[1]);

        // get errors
        while ($w = fgets($pipes[2])) {
            $this->_errors .= $w . "\n";
        }
        fclose($pipes[2]);

        proc_close($rp);

        return true;
    }

    /**
     * @param null $path
     * @return bool
     */
    public function is_executable_kakasi($path = null)
    {
        if (empty($path)) {
            $path = $this->_kakasi_path;
        }

        if (file_exists($path)) {
            if (function_exists('is_executable')) {
                return is_executable($path);
            }

            return true;    // WIN or PHP 4
        }

        return false;
    }

    /**
     * @return string
     */
    public function get_opt()
    {
        $opt = $this->get_opt_encoding() . ' ' . $this->get_opt_dicts();

        return $opt;
    }

    /**
     * @return string
     */
    public function get_opt_dicts()
    {
        $opt = '';
        if ($this->_dicts) {
            $opt = ' ' . implode(', ', $this->_dicts);
        }

        return $opt;
    }

    /**
     * @return string
     */
    public function get_opt_encoding()
    {
        $opt = '';
        if ($this->_encoding) {
            $opt = ' -i' . $this->_encoding;
        }

        return $opt;
    }

    //---------------------------------------------------------
    // set and get property
    //---------------------------------------------------------

    /**
     * @param $val
     */
    public function set_kakasi_path($val)
    {
        $this->_kakasi_path = $val;
    }

    /**
     * @param $val
     */
    public function set_mode_execute($val)
    {
        $this->_mode_excute = (int)$val;
    }

    /**
     * @param $value
     */
    public function set_dir_work($value)
    {
        $this->_dir_work = $value;
    }

    /**
     * @return string
     */
    public function get_words()
    {
        return $this->_words;
    }

    /**
     * @return string
     */
    public function get_errors()
    {
        return $this->_errors;
    }

    /**
     * @return string
     */
    public function get_cmd_error()
    {
        return $this->_cmd_error;
    }

    /**
     * @return string|null
     */
    public function get_dir_work()
    {
        return $this->_dir_work;
    }

    // --- class end ---
}
