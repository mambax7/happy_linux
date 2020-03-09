<?php

namespace XoopsModules\Happy_linux;

// $Id: config_base_handler.php,v 1.82 2010/11/07 14:59:23 ohwada Exp $

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

//=========================================================
// class config handler
//=========================================================
class ConfigBaseHandler extends  ObjectHandler
{
    public $_cached_by_confid = [];
    public $_cached_by_name   = [];

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
    public function _build_insert_sql($obj)
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

    public function _build_update_sql($obj)
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
        $this->_cached_by_confid = [];
        $this->_cached_by_name   = [];

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

        $obj = &$this->get_by_confid($id);
        if (!is_object($obj)) {
            return false;
        }

        $obj->setConfValueForInput($value, true);
        $ret = $this->update($obj);

        return $ret;
    }

    public function update_by_name($name, $value)
    {
        $obj = &$this->get_by_name($name);
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
        $obj = &$this->get_by_confid($id);
        if (is_object($obj)) {
            $val = $obj->get('conf_value');
        }

        return $val;
    }

    public function get_value_by_name($name)
    {
        $val = false;
        $obj = &$this->get_by_name($name);
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
) ENGINE=MyISAM
";

        $ret = $this->query($sql);

        return $ret;
    }

    // --- class end ---
}
