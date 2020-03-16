<?php

namespace XoopsModules\Happylinux;

// $Id: config_baseHandler.php,v 1.82 2010/11/07 14:59:23 ohwada Exp $

// 2007-11-24 K.OHWADA
// move get_first_obj_from_objs() to objectHandler.php

// 2007-05-12 K.OHWADA
// get_value_by_name()

// 2006-11-18 K.OHWADA
// BUG 4378: dont strip slashes in conf_value when magic_quotes_gpc is on

// 2006-09-20 K.OHWADA
// save conf_valuetype to DB

// 2006-07-10 K.OHWADA
// this is new file
// porting from weblinks_config_storeHandler

//================================================================
// Happy Linux Framework Module
// this file contain 2 class
//   ConfigBase
//   happylinux_config_baseHandler
// 2006-07-10 K.OHWADA
//================================================================

//================================================================
// class config_base
// modify form system XoopsConfigItem
//================================================================

/**
 * Class ConfigBase
 * @package XoopsModules\Happylinux
 */
class ConfigBase extends BaseObject
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $this->initVar('id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('conf_id', XOBJ_DTYPE_INT, 0, true);
        $this->initVar('conf_name', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('conf_value', XOBJ_DTYPE_TXTAREA);
        $this->initVar('conf_valuetype', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('aux_int_1', XOBJ_DTYPE_INT, 0);
        $this->initVar('aux_int_2', XOBJ_DTYPE_INT, 0);
        $this->initVar('aux_text_1', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('aux_text_2', XOBJ_DTYPE_TXTBOX, null, false, 255);
    }

    //---------------------------------------------------------
    // set value
    //---------------------------------------------------------
    /**
     * @param      $value
     * @param bool $force_slash
     */
    public function setConfValueForInput(&$value, $force_slash = false)
    {
        // BUG 4378: dont strip slashes in conf_value when magic_quotes_gpc is on
        switch ($this->get('conf_valuetype')) {
            case 'bool':
                $this->setBool('conf_value', $value);
                break;
            case 'int':
                $this->setInt('conf_value', $value);
                break;
            case 'float':
                $this->setFloat('conf_value', $value);
                break;
            case 'text':
                $this->setVarTxtbox('conf_value', $value, $force_slash);
                break;
            case 'textarea':
                $this->setVarTxtarea('conf_value', $value, $force_slash);
                break;
            case 'array':
                if (!is_array($value)) {
                    $value = explode('|', trim($value));
                }
                $this->setVarArray('conf_value', $value);
                break;
            case 'other':
            default:
                $this->setAsIs('conf_value', $value);
                break;
        }
    }

    //---------------------------------------------------------
    // get value
    //---------------------------------------------------------
    /**
     * @param string $format
     * @return float|int|mixed|string|string[]|null
     */
    public function &getConfValueForOutput($format = 's')
    {
        switch ($this->get('conf_valuetype')) {
            case 'bool':
                $value = &$this->getVarBool('conf_value');
                break;
            case 'int':
                $value = &$this->getVarInt('conf_value');
                break;
            case 'float':
                $value = &$this->getVarFloat('conf_value');
                break;
            case 'text':
                $value = &$this->getVarTxtbox('conf_value', $format);
                break;
            case 'textarea':
                $value = &$this->getVarTxtarea('conf_value', $format);
                break;
            case 'array':
                $value = &$this->getVarArray('conf_value');
                break;
            case 'other':
            default:
                $value = &$this->getVarAsIs('conf_value');
                break;
        }

        return $value;
    }

    /**
     * @param string $format
     * @return array
     */
    public function getConfVarAll($format = 's')
    {
        $ret                 = $this->getVarAll($format);
        $ret['value_output'] = $this->getConfValueForOutput($format);

        return $ret;
    }

    // --- class end ---
}
