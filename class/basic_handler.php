<?php
// $Id: basic_handler.php,v 1.2 2012/03/18 08:18:30 ohwada Exp $

// 2012-03-01 K.OHWADA
// get_columns()

// 2008-01-30 K.OHWADA
// change get_object_by_id()

// 2007-11-11 K.OHWADA
// _load_config_once()
// update_config_by_name()
// get_count_by_tablename()
// BUG: Fatal error: Call to a member function set_vars()

// 2007-09-20 K.OHWADA
// BUG 4707: Only variables should be assigned by reference in weblinks
// PHP 5.2: Assigning the return value of new by reference

// 2007-06-01 K.OHWADA
// divid to basic_object
// get_objects_from_rows()

// 2007-03-01 K.OHWADA
// add renew_prefix()
// call happy_linux_error() in happy_linux_basic_handler()

// 2006-11-19 K.OHWADA
// BUG 4380: Only variables should be assigned by reference

// 2006-09-20 K.OHWADA
// add setVar() getVar() etc
// add getInsertId()

// 2006-07-10 K.OHWADA
// this is new file
// porting from weblinks_db_basic_base.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/basic_object.php';

//=========================================================
// class happy_linux_basic_handler
// this class is used by command line
// this class handle MySQL table directly
// this class does not use another class
//=========================================================
class happy_linux_basic_handler extends happy_linux_error
{
    // class instance
    public $_db;

    // variable
    public $_DIRNAME;
    public $_table;
    public $_id_name;
    public $_table_name_short;
    public $_class_name = 'happy_linux_basic';

    // cache
    public $_cached = array();

    // config table
    public $_conf_cached     = array();
    public $_conf_table      = null;
    public $_conf_id_name    = 'conf_id';
    public $_conf_name_name  = 'conf_name';
    public $_conf_value_name = 'conf_value';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname)
    {
        $this->_DIRNAME = $dirname;
        parent::__construct();
        $this->_db = XoopsDatabaseFactory::getDatabaseConnection();
    }

    public static function getInstance($dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_basic_handler($dirname);
        }
        return $instance;
    }

    //---------------------------------------------------------
    // set param
    //---------------------------------------------------------
    public function set_table_name($name)
    {
        $this->_table_name_short = $name;
        $this->set_table_full($this->prefix($name));
        $this->set_conf_table($this->_table);
    }

    public function set_table_full($name)
    {
        $this->_table = $name;
    }

    public function set_id_name($name)
    {
        $this->_id_name = $name;
    }

    public function set_class_name($name)
    {
        $this->_class_name = $name;
    }

    public function set_conf_table($name)
    {
        $this->_conf_table = $name;
    }

    public function set_conf_id_name($name)
    {
        $this->_conf_id_name = $name;
    }

    public function set_conf_name_name($name)
    {
        $this->_conf_name_name = $name;
    }

    public function set_conf_value_name($name)
    {
        $this->_conf_value_name = $name;
    }

    //---------------------------------------------------------
    // prefix
    //---------------------------------------------------------
    public function renew_prefix($prefix)
    {
        if ($prefix) {
            $this->setPrefix($prefix);
            $this->set_table_name($this->_table_name_short);
        }
    }

    public function prefix($name)
    {
        $ret = $this->db_prefix($this->_DIRNAME . '_' . $name);
        return $ret;
    }

    //---------------------------------------------------------
    // create
    // compatible for object_handler
    //---------------------------------------------------------
    public function &create()
    {
        $obj = null;

        if (class_exists($this->_class_name)) {
            // Assigning the return value of new by reference
            $obj = new $this->_class_name();
        }

        return $obj;
    }

    public function _check_class(&$obj)
    {
        if (strtolower(get_class($obj)) == strtolower($this->_class_name)) {
            return true;
        }

        return false;
    }

    public function _class_name()
    {
        return $this->_class_name;
    }

    //---------------------------------------------------------
    // get_object
    // compatible for object_handler
    //---------------------------------------------------------
    public function &get_cache_object_by_id($id)
    {
        $row =& $this->get_cache_row($id);
        if (!is_array($row) || !count($row)) {
            return $false;
        }

        $obj =& $this->create();
        if (!is_object($obj)) {
            return $false;
        }

        $obj->set_vars($row);
        return $obj;
    }

    public function &get_object_by_id($id)
    {
        $false = false;

        $row =& $this->get_row_by_id($id);
        if (!is_array($row) || !count($row)) {
            return $false;
        }

        $obj =& $this->create();
        if (!is_object($obj)) {
            return $false;
        }

        $obj->set_vars($row);
        return $obj;
    }

    public function &get_objects($limit = 0, $offset = 0)
    {
        $rows =& $this->get_rows($id);
        $objs =& $this->get_objects_from_rows($rows);
        return $objs;
    }

    public function &get_objects_from_rows(&$rows)
    {
        $objs = array();

        foreach ($rows as $row) {
            if (is_array($row) && count($row)) {
                $obj =& $this->create();

                // Fatal error: Call to a member function set_vars()
                if (is_object($obj)) {
                    $obj->set_vars($row);
                    $objs[] =& $obj;
                    unset($obj);
                }
            }
        }

        return $objs;
    }

    //---------------------------------------------------------
    // get_count
    //---------------------------------------------------------
    public function get_count_by_tablename($name, $dirname = null)
    {
        if (empty($dirname)) {
            $dirname = $this->_DIRNAME;
        }

        $table = $this->db_prefix($dirname . '_' . $name);
        $sql   = 'SELECT count(*) FROM ' . $table;
        $count = $this->get_count_by_sql($sql);
        return $count;
    }

    public function get_count_all()
    {
        $sql   = 'SELECT count(*) FROM ' . $this->_table;
        $count = $this->get_count_by_sql($sql);
        return $count;
    }

    public function is_exist($id)
    {
        $sql   = 'SELECT count(*) FROM ' . $this->_table . ' WHERE ' . $this->_id_name . '=' . (int)$id;
        $count = $this->get_count_by_sql($sql);
        if ($count) {
            return true;
        }
        return false;
    }

    //---------------------------------------------------------
    // get_row
    //---------------------------------------------------------
    public function has_cached()
    {
        if (count($this->_cached) > 0) {
            return true;
        }
        return false;
    }

    public function set_cached(&$arr)
    {
        if (is_array($arr) && count($arr)) {
            $this->_cached = $arr;
        }
    }

    public function &get_cached_rows()
    {
        return $this->_cached;
    }

    public function &get_cache_row($id)
    {
        $row = false;
        if (isset($this->_cached[$id])) {
            $row =& $this->_cached[$id];
        } else {
            $row =& $this->get_row_by_id($id);
            if (is_array($row) && count($row)) {
                $this->_cached[$id] = $row;
            }
        }
        return $row;
    }

    public function &get_row_by_id($id)
    {
        $sql = 'SELECT * FROM ' . $this->_table . ' WHERE ' . $this->_id_name . '=' . (int)$id;
        $row =& $this->get_row_by_sql($sql);
        return $row;
    }

    public function &get_rows($limit = 0, $offset = 0, $id_as_key = false)
    {
        $sql = 'SELECT * FROM ' . $this->_table . ' ORDER BY ' . $this->_id_name;
        $arr =& $this->get_rows_by_sql($sql, $limit, $offset, $id_as_key);
        return $arr;
    }

    public function &get_id_array($limit = 0, $offset = 0)
    {
        $sql = 'SELECT ' . $this->_id_name . ' FROM ' . $this->_table . ' ORDER BY ' . $this->_id_name;
        $arr =& $this->get_first_row_by_sql($sql, $limit, $offset);
        return $arr;
    }

    //---------------------------------------------------------
    // delete
    //---------------------------------------------------------
    public function delete_by_id($id)
    {
        $sql = 'DELETE FROM ' . $this->_table . ' WHERE ' . $this->_id_name . '=' . (int)$id;
        $ret = $this->query($sql);
        return $ret;
    }

    //---------------------------------------------------------
    // execute sql
    //---------------------------------------------------------
    public function get_count_by_sql($sql)
    {
        $res =& $this->query($sql);
        if (!$res) {
            return 0;
        }

        $array = $this->fetchRow($res);
        $count = (int)$array[0];

        if (empty($count)) {
            $count = 0;
        }

        $this->freeRecordSet($res);
        return $count;
    }

    public function &get_row_by_sql($sql)
    {
        $res =& $this->query($sql);
        $arr =& $this->fetchArray($res);
        $this->freeRecordSet($res);
        return $arr;
    }

    public function &get_rows_by_sql($sql, $limit = 0, $offset = 0, $id_as_key = false)
    {
        $res =& $this->query($sql, $limit, $offset);
        if (!$res) {
            return $res;
        }

        $arr = array();

        while ($row =& $this->fetchArray($res)) {
            if ($id_as_key) {
                $arr[$row[$this->_id_name]] = $row;
            } else {
                $arr[] = $row;
            }
        }

        $this->freeRecordSet($res);
        return $arr;
    }

    public function &get_first_row_by_sql($sql, $limit = 0, $offset = 0)
    {
        $res =& $this->query($sql, $limit, $offset);
        if (!$res) {
            return $res;
        }

        $arr = array();

        while ($row =& $this->fetchRow($res)) {
            $arr[] = $row[0];
        }

        $this->freeRecordSet($res);
        return $arr;
    }

    //---------------------------------------------------------
    // database class
    //---------------------------------------------------------
    // always use db->queryF
    //
    // BUG 4380: Only variables should be assigned by reference
    // different function definition in XoopsDatabase
    //   xoops 2.0.15 :    query()
    //   xoops 2.0.16 jp: &query()
    //---------------------------------------------------------
    public function &query($sql, $limit = 0, $offset = 0)
    {
        $limit  = (int)$limit;
        $offset = (int)$offset;

        $res = $this->_db->queryF($sql, $limit, $offset);

        $this->_print_db_sql($sql, $limit, $offset);

        if (!$res) {
            $this->_set_db_error($sql, $limit, $offset);
        }

        return $res;
    }

    public function getRowsNum($res)
    {
        $ret = $this->_db->getRowsNum($res);
        return $ret;
    }

    public function getFieldsNum($res)
    {
        $ret = $this->_db->getFieldsNum($res);
        return $ret;
    }

    public function &fetchArray($res)
    {
        $ret = $this->_db->fetchArray($res);
        return $ret;
    }

    public function &fetchRow($res)
    {
        $ret = $this->_db->fetchRow($res);
        return $ret;
    }

    public function getInsertId()
    {
        $ret = $this->_db->getInsertId();
        return $ret;
    }

    public function freeRecordSet($result)
    {
        $ret = $this->_db->freeRecordSet($result);
        return $ret;
    }

    public function setPrefix($value)
    {
        $this->_db->setPrefix($value);
    }

    public function db_prefix($tablename = '')
    {
        // if tablename is empty, only prefix will be returned
        $ret = $this->_db->prefix($tablename);
        return $ret;
    }

    public function get_db_error()
    {
        $err = $this->_db->error();
        return $err;
    }

    // strip GPC slashes when set object by serVar();
    public function quote($str)
    {
        $str = "'" . addslashes($str) . "'";
        return $str;
    }

    //---------------------------------------------------------
    // update config
    //---------------------------------------------------------
    public function update_config_by_name($name, $value, $force = false)
    {
        if (!$force) {
            return true;
        }   // no action

        $sql = 'UPDATE ' . $this->_conf_table . ' SET ';
        $sql .= 'conf_value=' . $this->quote($value) . ' ';
        $sql .= 'WHERE conf_name=' . $this->quote($name);
        return $this->query($sql);
    }

    //---------------------------------------------------------
    // get config
    //---------------------------------------------------------
    public function _load_config_once()
    {
        if (!$this->_has_conf_cached()) {
            $this->_get_config_data();
        }
    }

    public function &_get_config_data()
    {
        $arr = array();

        $sql  = 'SELECT * FROM ' . $this->_conf_table . ' ORDER BY ' . $this->_conf_id_name . ' ASC';
        $rows =& $this->get_rows_by_sql($sql);

        if (is_array($rows) && (count($rows) > 0)) {
            foreach ($rows as $row) {
                $arr[$row[$this->_conf_name_name]] = $row[$this->_conf_value_name];
            }
        }

        $this->_conf_cached = $arr;
        return $arr;
    }

    public function &get_conf()
    {
        $ret = false;
        if (isset($this->_conf_cached)) {
            $ret = $this->_conf_cached;
        }
        return $ret;
    }

    // BUG 4707: Only variables should be assigned by reference
    // in weblinks, store array in cache
    public function &get_conf_by_name($name)
    {
        $ret = false;
        if (isset($this->_conf_cached[$name])) {
            $ret = $this->_conf_cached[$name];
        }
        return $ret;
    }

    public function _has_conf_cached()
    {
        if (count($this->_conf_cached) > 0) {
            return true;
        }
        return false;
    }

    //---------------------------------------------------------
    // column
    //---------------------------------------------------------
    public function get_columns()
    {
        $sql  = 'SHOW COLUMNS FROM ' . $this->_table;
        $rows = $this->get_rows_by_sql($sql);
        if (!is_array($rows)) {
            return false;
        }
        return $rows;
    }

    public function update_column_type($fields)
    {
        $arr = array();
        foreach ($fields as $field) {
            $arr[] = ' MODIFY `' . $field['field'] . '`  ' . $field['type'] . ' ';
        }

        $sql = 'ALTER TABLE ' . $this->_table;
        $sql .= implode(', ', $arr);

        return $this->query($sql);
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------
    public function &convert_string_to_array($str, $pattern = '&')
    {
        $str_arr = explode($pattern, $str);

        $i   = 0;
        $arr = array();
        foreach ($str_arr as $value) {
            $value = trim($value);

            if ($value == '') {
                continue;
            }

            $arr[++$i] = $value;
        }

        return $arr;
    }

    // --- class end ---
}
