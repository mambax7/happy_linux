<?php

// $Id: object.php,v 1.3 2012/04/10 02:29:32 ohwada Exp $

// 2012-04-02 K.OHWADA
// debug_print_backtrace() in get()

// 2008-12-12 K.OHWADA
// blob in compare_data_type_to_column_text()

// 2008-02-24 K.OHWADA
// XOBJ_DTYPE_URL_AREA

// 2008-01-10 K.OHWADA
// Notice [PHP]: unserialize(): Argument is not an string

// 2007-11-24 K.OHWADA
// compare_data_type_to_column()

// 2007-06-01 K.OHWADA
// use debug_print_backtrace()

// 2006-12-10 K.OHWADA
// add set_var_by_post()

// 2006-11-20 K.OHWADA
// for happy_search
// add set_var_checkbox_by_global_post()

// 2006-11-18 K.OHWADA
// BUG 4378: dont strip slashes in conf_value when magic_quotes_gpc is on
// add setBool() getVarBool() etc

// 2006-09-18 K.OHWADA
// add is_set()
// use prepare_text()

// 2006-07-10 K.OHWADA
// this is new file
// porting from weblinks_object_handler.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

// XC 2.1 defined
if (!defined('XOBJ_DTYPE_FLOAT')) {
    define('XOBJ_DTYPE_FLOAT', 12);
}

if (!defined('XOBJ_DTYPE_BOOL')) {
    define('XOBJ_DTYPE_BOOL', 13);
}

// happy linux original
if (!defined('XOBJ_DTYPE_URL_AREA')) {
    define('XOBJ_DTYPE_URL_AREA', 21);
}

//=========================================================
// class happy_linux_object
// compatible to XoopsObject
// difference
//   strip GPC slashes when set by serVar()
// base on XopsCube's XoopsSimpleObject
//=========================================================

/**
 * Class happy_linux_object
 */
class happy_linux_object extends happy_linux_strings
{
    public $_vars  = [];
    public $_isnew = false;

    public $_ALLOW_TYPES = [
        XOBJ_DTYPE_BOOL,
        XOBJ_DTYPE_INT,
        XOBJ_DTYPE_FLOAT,
        XOBJ_DTYPE_TXTBOX,
        XOBJ_DTYPE_TXTAREA,
        XOBJ_DTYPE_URL,
        XOBJ_DTYPE_URL_AREA,
        XOBJ_DTYPE_ARRAY,
        XOBJ_DTYPE_OTHER,
    ];

    public $_DTYPE_NAMES = [
        XOBJ_DTYPE_BOOL     => 'bool',
        XOBJ_DTYPE_INT      => 'int',
        XOBJ_DTYPE_FLOAT    => 'float',
        XOBJ_DTYPE_TXTBOX   => 'txtbox',
        XOBJ_DTYPE_TXTAREA  => 'txtarea',
        XOBJ_DTYPE_URL      => 'url',
        XOBJ_DTYPE_URL_AREA => 'url_area',
        XOBJ_DTYPE_ARRAY    => 'array',
        XOBJ_DTYPE_OTHER    => 'other',
    ];

    public $_DEBUG = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // function
    //---------------------------------------------------------
    public function setNew()
    {
        $this->_isnew = true;
    }

    public function unsetNew()
    {
        $this->_isnew = false;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->_isnew;
    }

    /**
     * @param      $key
     * @param      $dataType
     * @param null $value
     * @param bool $required
     * @param null $size
     */
    public function initVar($key, $dataType, $value = null, $required = false, $size = null)
    {
        if (!in_array($dataType, $this->_ALLOW_TYPES)) {
            return;
        }

        $this->_vars[$key] = [
            'data_type' => $dataType,
            'value'     => null,
            'required'  => $required ? true : false,
            'maxlength' => $size ? (int)$size : null,
        ];

        $this->assignVar($key, $value);
    }

    /**
     * @param $key
     * @return bool
     */
    public function is_set($key)
    {
        if (isset($this->_vars[$key])) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // set value just as original
    //---------------------------------------------------------

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        if (!isset($this->_vars[$key])) {
            return;
        }

        switch ($this->_vars[$key]['data_type']) {
            case XOBJ_DTYPE_BOOL:
                $this->setBool($key, $value);
                break;
            case XOBJ_DTYPE_INT:
                $this->setInt($key, $value);
                break;
            case XOBJ_DTYPE_FLOAT:
                $this->setFloat($key, $value);
                break;
            case XOBJ_DTYPE_TXTBOX:
            case XOBJ_DTYPE_TXTAREA:
            case XOBJ_DTYPE_URL:
            case XOBJ_DTYPE_URL_AREA:
            case XOBJ_DTYPE_OTHER:
                $this->setAsIs($key, $value);
                break;
        }
    }

    /**
     * @param $key
     * @param $value
     */
    public function setBool($key, $value)
    {
        if (!isset($this->_vars[$key])) {
            return;
        }

        $val = 0;
        if ($value) {
            $val = 1;
        }
        $this->_vars[$key]['value'] = $val;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setInt($key, $value)
    {
        if (!isset($this->_vars[$key])) {
            return;
        }

        $this->_vars[$key]['value'] = (int)$value;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setFloat($key, $value)
    {
        if (!isset($this->_vars[$key])) {
            return;
        }

        $this->_vars[$key]['value'] = (float)$value;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setAsIs($key, $value)
    {
        if (!isset($this->_vars[$key])) {
            return;
        }

        $this->_vars[$key]['value'] = $value;
    }

    /**
     * @param $key
     * @param $value
     */
    public function assignVar($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * @param $values
     */
    public function assignVars($values)
    {
        foreach ($values as $key => $value) {
            $this->assignVar($key, $value);
        }
    }

    //---------------------------------------------------------
    // get value just as set
    //---------------------------------------------------------

    /**
     * @param $key
     * @return bool|mixed
     */
    public function get($key)
    {
        if (isset($this->_vars[$key]['value'])) {
            return $this->_vars[$key]['value'];
        }

        if ($this->_DEBUG) {
            echo "object.php get(): $key <br>\n";
            debug_print_backtrace();
        }

        return false;
    }

    /**
     * @return array
     */
    public function &gets()
    {
        $ret = [];
        foreach ($this->_vars as $key => $value) {
            $ret[$key] = $value['value'];
        }

        return $ret;
    }

    //---------------------------------------------------------
    // set value just after formating
    //---------------------------------------------------------

    /**
     * @param      $key
     * @param      $value
     * @param bool $not_gpc
     */
    public function setVar($key, $value, $not_gpc = false)
    {
        if (!isset($this->_vars[$key])) {
            return;
        }

        switch ($this->_vars[$key]['data_type']) {
            case XOBJ_DTYPE_BOOL:
                $this->setBool($key, $value);
                break;
            case XOBJ_DTYPE_INT:
                $this->setInt($key, $value);
                break;
            case XOBJ_DTYPE_FLOAT:
                $this->setFloat($key, $value);
                break;
            // strip GPC slashes
            case XOBJ_DTYPE_TXTBOX:
                $this->setVarTxtbox($key, $value, $not_gpc);
                break;
            // strip GPC slashes
            case XOBJ_DTYPE_TXTAREA:
                $this->setVarTxtarea($key, $value, $not_gpc);
                break;
            // strip GPC slashes
            case XOBJ_DTYPE_URL:
                $this->setVarUrl($key, $value, $not_gpc);
                break;
            // strip GPC slashes
            case XOBJ_DTYPE_URL_AREA:
                $this->setVarUrlArea($key, $value, $not_gpc);
                break;
            // strip GPC slashes
            case XOBJ_DTYPE_ARRAY:
                $this->setVarArray($key, $value, $not_gpc);
                break;
            case XOBJ_DTYPE_OTHER:
                $this->setAsIs($key, $value);
                break;
        }
    }

    /**
     * @param      $key
     * @param      $value
     * @param bool $not_gpc
     */
    public function setVarTxtbox($key, $value, $not_gpc = false)
    {
        if (!isset($this->_vars[$key])) {
            return;
        }

        // strip GPC slashes
        $value = $this->prepare_text($value, $not_gpc);

        if ((null !== $this->_vars[$key]['maxlength']) && (mb_strlen($value) > $this->_vars[$key]['maxlength'])) {
            $this->_vars[$key]['value'] = xoops_substr($value, 0, $this->_vars[$key]['maxlength'], null);
        } else {
            $this->_vars[$key]['value'] = $value;
        }
    }

    /**
     * @param      $key
     * @param      $value
     * @param bool $not_gpc
     */
    public function setVarTxtarea($key, $value, $not_gpc = false)
    {
        if (!isset($this->_vars[$key])) {
            return;
        }

        // strip GPC slashes
        $value = $this->prepare_textarea($value, $not_gpc);

        $this->_vars[$key]['value'] = $value;
    }

    /**
     * @param      $key
     * @param      $value
     * @param bool $not_gpc
     */
    public function setVarUrl($key, $value, $not_gpc = false)
    {
        if (!isset($this->_vars[$key])) {
            return;
        }

        // strip GPC slashes
        $value = $this->prepare_url($value, $not_gpc);

        if ((null !== $this->_vars[$key]['maxlength']) && (mb_strlen($value) > $this->_vars[$key]['maxlength'])) {
            $this->_vars[$key]['value'] = xoops_substr($value, 0, $this->_vars[$key]['maxlength'], null);
        } else {
            $this->_vars[$key]['value'] = $value;
        }
    }

    /**
     * @param      $key
     * @param      $value
     * @param bool $not_gpc
     */
    public function setVarUrlArea($key, $value, $not_gpc = false)
    {
        if (!isset($this->_vars[$key])) {
            return;
        }

        // strip GPC slashes
        $value = $this->prepare_url($value, $not_gpc);

        $this->_vars[$key]['value'] = $value;
    }

    /**
     * @param      $key
     * @param      $value
     * @param bool $not_gpc
     */
    public function setVarArray($key, $value, $not_gpc = false)
    {
        if (!isset($this->_vars[$key])) {
            return;
        }

        // strip GPC slashes
        $value = &$this->prepare_array($value, $not_gpc);

        $this->_vars[$key]['value'] = serialize($value);
    }

    /**
     * @param      $values
     * @param bool $not_gpc
     */
    public function setVars($values, $not_gpc = false)
    {
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $this->setVar($key, $value, $not_gpc);
            }
        } else {
            if ($this->_DEBUG) {
                echo "object.php setVars(): $values, $not_gpc <br>\n";
                if ($this->exist_debug_print_backtrace()) {
                    debug_print_backtrace();
                }
            }
        }
    }

    //---------------------------------------------------------
    // get value just after formating
    //---------------------------------------------------------

    /**
     * @param        $key
     * @param string $format
     * @return float|int|mixed|string|string[]|null
     */
    public function &getVar($key, $format = 's')
    {
        $value = null;

        if (!isset($this->_vars[$key])) {
            return $value;
        }

        switch ($this->_vars[$key]['data_type']) {
            case XOBJ_DTYPE_BOOL:
                $value = &$this->getVarBool($key);
                break;
            case XOBJ_DTYPE_INT:
                $value = &$this->getVarInt($key);
                break;
            case XOBJ_DTYPE_FLOAT:
                $value = &$this->getVarFloat($key);
                break;
            case XOBJ_DTYPE_TXTBOX:
                $value = &$this->getVarTxtbox($key, $format);
                break;
            case XOBJ_DTYPE_TXTAREA:
                $value = &$this->getVarTxtarea($key, $format);
                break;
            case XOBJ_DTYPE_URL:
                $value = &$this->getVarUrl($key, $format);
                break;
            case XOBJ_DTYPE_URL_AREA:
                $value = &$this->getVarUrlArea($key, $format);
                break;
            case XOBJ_DTYPE_ARRAY:
                $value = &$this->getVarArray($key);
                break;
            case XOBJ_DTYPE_OTHER:
                $value = &$this->getVarAsIs($key);
                break;
        }

        return $value;
    }

    /**
     * @param $key
     * @return int|null
     */
    public function &getVarBool($key)
    {
        $value = null;

        if (!isset($this->_vars[$key])) {
            return $value;
        }

        $value = (int)$this->_vars[$key]['value'];

        return $value;
    }

    /**
     * @param $key
     * @return int|null
     */
    public function &getVarInt($key)
    {
        $value = null;

        if (!isset($this->_vars[$key])) {
            return $value;
        }

        $value = (int)$this->_vars[$key]['value'];

        return $value;
    }

    /**
     * @param $key
     * @return float|null
     */
    public function &getVarFloat($key)
    {
        $value = null;

        if (!isset($this->_vars[$key])) {
            return $value;
        }

        $value = (float)$this->_vars[$key]['value'];

        return $value;
    }

    /**
     * @param        $key
     * @param string $format
     * @return string|string[]|null
     */
    public function &getVarTxtbox($key, $format = 's')
    {
        $value = null;

        if (!isset($this->_vars[$key])) {
            return $value;
        }

        $value = $this->_vars[$key]['value'];
        $value = $this->sanitize_format_text($value, $format);

        return $value;
    }

    /**
     * @param        $key
     * @param string $format
     * @return string|string[]|null
     */
    public function &getVarTxtarea($key, $format = 's')
    {
        $value = null;

        if (!isset($this->_vars[$key])) {
            return $value;
        }

        $value = $this->_vars[$key]['value'];
        $value = $this->sanitize_format_textarea($value, $format);

        return $value;
    }

    /**
     * @param        $key
     * @param string $format
     * @return string|null
     */
    public function &getVarUrl($key, $format = 's')
    {
        $value = null;

        if (!isset($this->_vars[$key])) {
            return $value;
        }

        $value = $this->_vars[$key]['value'];
        $value = $this->sanitize_format_url($value, $format);

        return $value;
    }

    /**
     * @param        $key
     * @param string $format
     * @return string|null
     */
    public function &getVarUrlArea($key, $format = 's')
    {
        return $this->getVarUrl($key, $format);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function &getVarArray($key)
    {
        $value = null;

        // unserialize(): Argument is not an string
        if (!isset($this->_vars[$key]) || empty($this->_vars[$key]['value'])) {
            return $value;
        }

        $value = $this->_vars[$key]['value'];
        $value = unserialize($value);

        return $value;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function &getVarAsIs($key)
    {
        $value = null;

        if (!isset($this->_vars[$key])) {
            return $value;
        }

        $value = $this->_vars[$key]['value'];

        return $value;
    }

    /**
     * @param string $format
     * @return array
     */
    public function &getVarAll($format = 'n')
    {
        $ret = [];
        foreach ($this->_vars as $k => $v) {
            $ret[$k] = $this->getVar($k, $format);
        }

        return $ret;
    }

    //---------------------------------------------------------
    // set utility
    //---------------------------------------------------------

    /**
     * @param $key
     */
    public function set_var_by_global_post($key)
    {
        if (isset($_POST[$key])) {
            $this->setVar($key, $_POST[$key]);
        }
    }

    /**
     * @param $post
     * @param $key
     */
    public function set_var_by_post($post, $key)
    {
        if (isset($post[$key])) {
            $this->setVar($key, $post[$key]);
        }
    }

    /**
     * @param     $key
     * @param int $default
     */
    public function set_var_checkbox_by_global_post($key, $default = 0)
    {
        $val = $default;
        if (isset($_POST[$key])) {
            $val = $_POST[$key];
        }
        $this->setVar($key, $val);
    }

    /**
     * @param     $post
     * @param     $key
     * @param int $default
     */
    public function set_var_checkbox_by_post($post, $key, $default = 0)
    {
        $val = $default;
        if (isset($post[$key])) {
            $val = $post[$key];
        }
        $this->setVar($key, $val);
    }

    public function _set_vars_insert()
    {
        $this->setVars($_POST);
    }

    public function _set_vars_update()
    {
        $this->setVars($_POST);
    }

    //---------------------------------------------------------
    // get utility
    //---------------------------------------------------------

    /**
     * @param        $key
     * @param string $format
     * @param string $default
     * @return string
     */
    public function get_var_url_null($key, $format = 's', $default = 'http://')
    {
        $url   = $this->get($key);
        $value = $this->substute_http($value, $default);
        $value = $this->sanitize_format_url($value, $format);

        return $value;
    }

    /**
     * @param        $key
     * @param string $format
     * @param int    $max
     * @return string
     */
    public function get_var_text_short($key, $format = 's', $max = 100)
    {
        $value = $this->get($key);
        $value = $this->sanitize_format_text_short($value, $format, $max);

        return $value;
    }

    //---------------------------------------------------------
    // compare
    //---------------------------------------------------------

    /**
     * @return array
     */
    public function get_scheme()
    {
        return $this->_vars;
    }

    /**
     * @param $key
     * @param $column_type
     * @return bool
     */
    public function compare_data_type_to_column($key, $column_type)
    {
        if (!isset($this->_vars[$key])) {
            return false;
        }

        $ret = false;
        switch ($this->_vars[$key]['data_type']) {
            case XOBJ_DTYPE_BOOL:
            case XOBJ_DTYPE_INT:
                $ret = $this->compare_data_type_to_column_int($column_type);
                break;
            case XOBJ_DTYPE_FLOAT:
                $ret = $this->compare_data_type_to_column_float($column_type);
                break;
            case XOBJ_DTYPE_URL:
            case XOBJ_DTYPE_TXTBOX:
                $ret = $this->compare_data_type_to_column_char($column_type);
                break;
            case XOBJ_DTYPE_URL_AREA:
            case XOBJ_DTYPE_TXTAREA:
                $ret = $this->compare_data_type_to_column_text($column_type);
                break;
            case XOBJ_DTYPE_OTHER:
                $ret = $this->compare_data_type_to_column_other($column_type);
                break;
            default:
                $ret = $this->compare_data_type_to_column_default($column_type);
                break;
        }

        return $ret;
    }

    /**
     * @param $column_type
     * @return bool
     */
    public function compare_data_type_to_column_int($column_type)
    {
        if (preg_match('/int/', $column_type)) {
            return true;
        }

        return false;
    }

    /**
     * @param $column_type
     * @return bool
     */
    public function compare_data_type_to_column_float($column_type)
    {
        if (preg_match('/double/', $column_type)) {
            return true;
        }

        return false;
    }

    /**
     * @param $column_type
     * @return bool
     */
    public function compare_data_type_to_column_char($column_type)
    {
        if (preg_match('/char/', $column_type)) {
            return true;
        }
        if (preg_match('/binary/', $column_type)) {
            return true;
        }

        return false;
    }

    /**
     * @param $column_type
     * @return bool
     */
    public function compare_data_type_to_column_text($column_type)
    {
        if (preg_match('/text/', $column_type)) {
            return true;
        }
        if (preg_match('/blob/', $column_type)) {
            return true;
        }

        return false;
    }

    /**
     * @param $column_type
     * @return bool
     */
    public function compare_data_type_to_column_other($column_type)
    {
        if ($this->compare_data_type_to_column_char($column_type)) {
            return true;
        }

        if ($this->compare_data_type_to_column_text($column_type)) {
            return true;
        }

        return false;
    }

    /**
     * @param $column_type
     * @return bool
     */
    public function compare_data_type_to_column_default($column_type)
    {
        return false;
    }

    /**
     * @param $key
     * @return bool|string
     */
    public function get_data_type_name($key)
    {
        if (!isset($this->_vars[$key])) {
            return false;
        }

        $data_type = $this->_vars[$key]['data_type'];

        if (isset($this->_DTYPE_NAMES[$data_type])) {
            return $this->_DTYPE_NAMES[$data_type];
        }

        return false;
    }

    /**
     * @param        $value
     * @param string $default
     * @return string
     */
    public function substute_http($value = '', $default = 'http://')
    {
        if ('' == $value) {
            $value = $default;
        }

        return $value;
    }
}
// --- class end ---
