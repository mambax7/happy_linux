<?php
// $Id: config_base_handler.php,v 1.6 2007/11/26 02:49:28 ohwada Exp $

// 2007-11-24 K.OHWADA
// move get_first_obj_from_objs() to object_handler.php

// 2007-05-12 K.OHWADA
// get_value_by_name()

// 2006-11-18 K.OHWADA
// BUG 4378: dont strip slashes in conf_value when magic_quotes_gpc is on

// 2006-09-20 K.OHWADA
// save conf_valuetype to DB

// 2006-07-10 K.OHWADA
// this is new file
// porting from weblinks_config_store_handler

//================================================================
// Happy Linux Framework Module
// this file contain 2 class
//   happy_linux_config_base
//   happy_linux_config_base_handler
// 2006-07-10 K.OHWADA
//================================================================

//================================================================
// class happy_linux_config_base
// modify form system XoopsConfigItem
//================================================================

class happy_linux_config_base extends happy_linux_object
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
    public function &getConfValueForOutput($format = 's')
    {
        switch ($this->get('conf_valuetype')) {
            case 'bool':
                $value =& $this->getVarBool('conf_value');
                break;

            case 'int':
                $value =& $this->getVarInt('conf_value');
                break;

            case 'float':
                $value =& $this->getVarFloat('conf_value');
                break;

            case 'text':
                $value =& $this->getVarTxtbox('conf_value', $format);
                break;

            case 'textarea':
                $value =& $this->getVarTxtarea('conf_value', $format);
                break;

            case 'array':
                $value =& $this->getVarArray('conf_value');
                break;

            case 'other':
            default:
                $value =& $this->getVarAsIs('conf_value');
                break;
        }

        return $value;
    }

    public function getConfVarAll($format = 's')
    {
        $ret                 = $this->getVarAll($format);
        $ret['value_output'] = $this->getConfValueForOutput($format);
        return $ret;
    }

    // --- class end ---
}

//=========================================================
// class config handler
//=========================================================
class happy_linux_config_base_handler extends happy_linux_object_handler
{
    public $_cached_by_confid = array();
    public $_cached_by_name   = array();

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname, $table_name, $id_name, $class_name)
    {
        parent::__construct($dirname, $table_name, $id_name, $class_name);
    }

    //---------------------------------------------------------
    // basic function
    //---------------------------------------------------------
    public function _build_insert_sql(&$obj)
    {
        foreach ($obj->gets() as $k => $v) {
            ${$k} = $v;
        }

        $sql = 'INSERT INTO ' . $this->_table . ' (';
        $sql .= 'conf_id, ';
        $sql .= 'conf_name, ';
        $sql .= 'conf_value, ';
        $sql .= 'conf_valuetype, ';
        $sql .= 'aux_int_1, ';
        $sql .= 'aux_int_2, ';
        $sql .= 'aux_text_1, ';
        $sql .= 'aux_text_2 ';
        $sql .= ') VALUES (';
        $sql .= (int)$conf_id . ', ';
        $sql .= $this->quote($conf_name) . ', ';
        $sql .= $this->quote($conf_value) . ', ';
        $sql .= $this->quote($conf_valuetype) . ', ';
        $sql .= (int)$aux_int_1 . ', ';
        $sql .= (int)$aux_int_2 . ', ';
        $sql .= $this->quote($aux_text_1) . ', ';
        $sql .= $this->quote($aux_text_2) . ' ';
        $sql .= ')';

        return $sql;
    }

    public function _build_update_sql(&$obj)
    {
        foreach ($obj->gets() as $k => $v) {
            ${$k} = $v;
        }

        $sql = 'UPDATE ' . $this->_table . ' SET ';
        $sql .= 'conf_name=' . $this->quote($conf_name) . ', ';
        $sql .= 'conf_value=' . $this->quote($conf_value) . ', ';
        $sql .= 'conf_valuetype=' . $this->quote($conf_valuetype) . ', ';
        $sql .= 'aux_int_1=' . (int)$aux_int_1 . ', ';
        $sql .= 'aux_int_2=' . (int)$aux_int_2 . ', ';
        $sql .= 'aux_text_1=' . $this->quote($aux_text_1) . ', ';
        $sql .= 'aux_text_2=' . $this->quote($aux_text_2) . ' ';
        $sql .= 'WHERE conf_id=' . (int)$conf_id;

        return $sql;
    }

    //---------------------------------------------------------
    // load
    //---------------------------------------------------------
    public function load()
    {
        $this->_cached_by_confid = array();
        $this->_cached_by_name   = array();

        $objs = $this->getObjects();

        foreach ($objs as $obj) {
            $arr                                      = $obj->getConfVarAll('s');
            $this->_cached_by_confid[$arr['conf_id']] = $arr;
            $this->_cached_by_name[$arr['conf_name']] = $arr;
        }
    }

    //---------------------------------------------------------
    // get_cache
    //---------------------------------------------------------
    public function get_cache_by_confid($id)
    {
        $ret = false;
        if (isset($this->_cached_by_confid[$id])) {
            $ret = $this->_cached_by_confid[$id];
        }
        return $ret;
    }

    public function get_cache_by_confid_key($id, $key)
    {
        $ret = false;
        if (isset($this->_cached_by_confid[$id][$key])) {
            $ret = $this->_cached_by_confid[$id][$key];
        }
        return $ret;
    }

    public function get_cache_by_name($name)
    {
        $ret = false;
        if (isset($this->_cached_by_name[$name])) {
            $ret = $this->_cached_by_name[$name];
        }
        return $ret;
    }

    public function get_cache_by_name_key($name, $key)
    {
        $ret = false;
        if (isset($this->_cached_by_name[$name][$key])) {
            $ret = $this->_cached_by_name[$name][$key];
        }
        return $ret;
    }

    //---------------------------------------------------------
    // update
    //---------------------------------------------------------
    public function update_by_confid($id, $value)
    {
        $id = (int)$id;
        if ($id <= 0) {
            return false;
        }

        $obj =& $this->get_by_confid($id);
        if (!is_object($obj)) {
            return false;
        }

        $obj->setConfValueForInput($value, true);
        $ret = $this->update($obj);
        return $ret;
    }

    public function update_by_name($name, $value)
    {
        $obj =& $this->get_by_name($name);
        if (!is_object($obj)) {
            return false;
        }

        $obj->setConfValueForInput($value, true);
        $ret = $this->update($obj);
        return $ret;
    }

    //---------------------------------------------------------
    // get
    //---------------------------------------------------------
    public function &get_by_confid($id)
    {
        return $this->get_one_by_key_value('conf_id', (int)$id);
    }

    public function &get_by_name($name)
    {
        return $this->get_one_by_key_value('conf_name', $name);
    }

    public function get_value_by_confid($id)
    {
        $val = false;
        $obj =& $this->get_by_confid($id);
        if (is_object($obj)) {
            $val = $obj->get('conf_value');
        }
        return $val;
    }

    public function get_value_by_name($name)
    {
        $val = false;
        $obj =& $this->get_by_name($name);
        if (is_object($obj)) {
            $val = $obj->get('conf_value');
        }
        return $val;
    }

    //---------------------------------------------------------
    // create_table
    //---------------------------------------------------------
    public function create_table()
    {
        // BUG : cannot create table in MySQL 3.23
        // remove last ';'
        $sql = '
CREATE TABLE ' . $this->_table . " (
  id smallint(5) unsigned NOT NULL auto_increment,
  conf_id smallint(5) unsigned NOT NULL default 0,
  conf_name      varchar(255) NOT NULL default '',
  conf_valuetype varchar(255) NOT NULL default '',
  conf_value text NOT NULL,
  aux_int_1 int(5) default '0',
  aux_int_2 int(5) default '0',
  aux_text_1 varchar(255) default '',
  aux_text_2 varchar(255) default '',
  PRIMARY KEY (id),
  KEY conf_id (conf_id)
) TYPE=MyISAM
";

        $ret = $this->query($sql);
        return $ret;
    }

    // --- class end ---
}
