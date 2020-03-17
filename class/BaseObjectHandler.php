<?php

namespace XoopsModules\Happylinux;

// $Id: objectHandler.php,v 1.15 2007/11/26 02:49:28 ohwada Exp $

// 2007-11-24 K.OHWADA
// move get_first_obj_from_objs() from config_baseHandler.php
// compare_to_scheme()

// 2007-09-20 K.OHWADA
// PHP5.2: Assigning the return value of new by reference is deprecated

// 2007-08-01 K.OHWADA
// get_field_meta_name_array()

// 2007-06-23 K.OHWADA
// delete_by_id()
// BUG: getList()

// 2007-05-12 K.OHWADA
// add loadCache()

// 2007-03-01 K.OHWADA
// add _DEBUG_QUERY
// add set_table_name()

// 2006-12-10 K.OHWADA
// small change _check_class()

// 2006-11-19 K.OHWADA
// BUG 4380: Only variables should be assigned by reference

// 2006-09-20 K.OHWADA
// add set_cache_by_obj()
// add clean() get_objects_asc()
// add drop_table()
// add get_row_by_sql()
// same bugs

// 2006-07-10 K.OHWADA
// this is new file
// porting from weblinks_objectHandler.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

//=========================================================
// class object_genericHandler
// support XC2.1 \CriteriaElement class
//=========================================================

/**
 * Class ObjectHandler
 * @package XoopsModules\Happylinux
 */
class BaseObjectHandler extends Error
{
    public $_DIRNAME;

    public $_DEBUG_INSERT = true;
    public $_DEBUG_UPDATE = true;
    public $_DEBUG_DELETE = true;

    public $_DEBUG_QUERY       = true;
    public $_DEBUG_QUERY_FORCE = true;

    public $_db;
    public $_table;
    public $_id_name;
    public $_class_name;
    public $_table_name_short;

    public $_criteria_class_name = 'CriteriaElement';

    public $_MODE_CRITERIA = 0;    // 0: XOOPS 2.0, 1: XC2.1

    public $_STRING_TYPES = [
        XOBJ_DTYPE_TXTBOX,
        XOBJ_DTYPE_TXTAREA,
        XOBJ_DTYPE_URL,
    ];

    public $_cached = [];

    public $_magic_word;

    public $_field_meta_array = [];
    public $_field_name_array = [];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    /**
     * ObjectHandler constructor.
     * @param $dirname
     * @param $table_name
     * @param $id_name
     * @param $class_name
     */
    public function __construct($dirname, $table_name, $id_name, $class_name)
    {
        parent::__construct();

        $this->_db = \XoopsDatabaseFactory::getDatabaseConnection();

        $this->_DIRNAME = $dirname;

        $this->set_table_name($table_name);
        $this->set_id_name($id_name);
        $this->set_class_name($class_name);

        $this->_magic_word = xoops_makepass();
    }

    //---------------------------------------------------------
    // set param
    //---------------------------------------------------------
    /**
     * @param $name
     */
    public function set_table_name($name)
    {
        $this->_table_name_short = $name;
        $this->_table            = $this->prefix($name);
    }

    /**
     * @param $name
     */
    public function set_id_name($name)
    {
        $this->_id_name = $name;
    }

    /**
     * @param $name
     */
    public function set_class_name($name)
    {
        $this->_class_name = $name;
    }

    //---------------------------------------------------------
    // prefix
    //---------------------------------------------------------
    /**
     * @param $prefix
     */
    public function renew_prefix($prefix)
    {
        if ($prefix) {
            $this->setPrefix($prefix);
            $this->set_table_name($this->_table_name_short);
        }
    }

    /**
     * @param $name
     * @return string
     */
    public function prefix($name)
    {
        $ret = $this->db_prefix($this->_DIRNAME . '_' . $name);

        return $ret;
    }

    //---------------------------------------------------------
    // create
    //---------------------------------------------------------
    /**
     * @param bool $isNew
     * @return mixed|null
     */
    public function &create($isNew = true)
    {
        $obj = null;
        if (class_exists($this->_class_name)) {
            // Assigning the return value of new by reference is deprecated
            $obj = new $this->_class_name();

            if ($isNew) {
                $obj->setNew();
            }
        }

        return $obj;
    }

    /**
     * @param $obj
     * @return bool
     */
    public function _check_class($obj)
    {
        if (mb_strtolower(get_class($obj)) == mb_strtolower($this->_class_name)) {
            return true;
        }
        if (is_a($obj, $this->_class_name)) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function _class_name()
    {
        return $this->_class_name;
    }

    //---------------------------------------------------------
    // pubilic function
    //---------------------------------------------------------
    /**
     * @param $id
     * @return bool
     */
    public function is_exist($id)
    {
        $sql   = 'SELECT count(*) FROM ' . $this->_table . ' WHERE ' . $this->_id_name . '=' . (int)$id;
        $count = $this->get_count_by_sql($sql);
        if ($count) {
            return true;
        }

        return false;
    }

    /**
     * @param $id
     * @return mixed|null
     */
    public function &get($id)
    {
        $ret = null;
        $sql = 'SELECT * FROM ' . $this->_table . ' WHERE ' . $this->_id_name . '=' . (int)$id;

        $result = &$this->query($sql);
        if (!$result) {
            return $ret;
        }

        if (1 == $this->getRowsNum($result)) {
            $ret = &$this->create();
            $ret->assignVars($this->fetchArray($result));
            $ret->unsetNew();
        }

        return $ret;
    }

    /**
     * @param $id
     * @return mixed|null
     */
    public function &getCache($id)
    {
        if (isset($this->_cached[$id])) {
            return $this->_cached[$id];
        }

        $obj = &$this->get($id);
        if (is_object($obj)) {
            $this->_cached[$id] = $obj;
        }

        return $obj;
    }

    public function loadCache()
    {
        $criteria  = null;
        $limit     = 0;
        $start     = 0;
        $id_as_key = true;

        $this->_cached = &$this->getObjects($criteria, $limit, $start, $id_as_key);
    }

    /**
     * @param $obj
     */
    public function set_cache_by_obj($obj)
    {
        $id = $this->_get_id_value_by_obj($obj);
        if ($id) {
            $this->_cached[$id] = $obj;
        }
    }

    /**
     * @param        $id
     * @param string $format
     * @return array
     */
    public function &getVarAll($id, $format = 'n')
    {
        $arr = [];
        $obj = $this->get($id);
        if (is_object($obj)) {
            $arr = &$obj->getVarAll($format);
        }

        return $arr;
    }

    /**
     * @param null $criteria
     * @param bool $param1
     * @param bool $param2
     * @param bool $param3
     * @return array
     */
    public function &getObjects($criteria = null, $param1 = false, $param2 = false, $param3 = false)
    {
        $ret = [];

        if ($this->_check_criteria_class($criteria)) {
            if ($this->_MODE_CRITERIA) {
                $sql = $this->_build_object_sql_new($criteria);
            } else {
                $sql = $this->_build_object_sql($criteria);
            }

            $limit = $criteria->getLimit();
            $start = $criteria->getStart();

            $ret = &$this->get_objects_by_sql($sql, $limit, $start, $param1);
        } else {
            $sql = 'SELECT * FROM ' . $this->_table;
            $sql .= ' ORDER BY ' . $this->_id_name . ' ASC';

            $ret = &$this->get_objects_by_sql($sql, $param1, $param2, $param3);
        }

        return $ret;
    }

    // BUG: return the first field value instead of id

    /**
     * @param null $criteria
     * @return array|bool
     */
    public function &getList($criteria = null)
    {
        $ret   = [];
        $limit = $start = 0;

        if ($this->_check_criteria_class($criteria)) {
            if ($this->_MODE_CRITERIA) {
                $sql = $this->_build_list_sql_new($criteria);
            } else {
                $sql = $this->_build_list_sql($criteria);
            }

            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        } else {
            $sql .= ' ORDER BY ' . $this->_id_name;
        }

        $ret = &$this->get_first_rows_by_sql($sql, $limit, $start);

        return $ret;
    }

    /**
     * @param null|\CriteriaCompo $criteria
     * @return bool|mixed
     */
    public function getCount($criteria = null)
    {
        $ret = [];

        $sql = 'SELECT COUNT(*) FROM ' . $this->_table;

        if ($this->_check_criteria_class($criteria)) {
            if ($this->_MODE_CRITERIA) {
                $sql = $this->_build_count_sql_new($criteria);
            } else {
                $sql = $this->_build_count_sql($criteria);
            }
        }

        return $this->get_count_by_sql($sql);
    }

    /**
     * @param \XoopsObject|BaseObject $obj
     * @param bool         $force
     * @return bool|mixed
     */
    public function insert($obj, $force = false)
    {
        if ($this->_DEBUG_INSERT) {
            return $this->_insert($obj, $force);
        }

        return true;
    }

    /**
     * @param      $obj
     * @param bool $force
     * @return bool|mixed
     */
    public function _insert($obj, $force = false)
    {
        if (!$this->_check_class($obj)) {
            $this->_set_errors($this->_table . ': not match class');

            return false;
        }

        if (!$obj->isNew()) {
            $this->_set_errors($this->_table . ': not new object');

            return false;
        }

        if ($this->_MODE_CRITERIA) {
            $sql = $this->_build_insert_sql_new($obj);
        } else {
            $sql = $this->_build_insert_sql($obj);
        }

        $result = $force ? $this->queryF($sql) : $this->query($sql);
        if (!$result) {
            return false;
        }

        $newid = $this->getInsertId();
        $obj->setVar($this->_id_name, $newid);

        return $newid;
    }

    /**
     * @param      $obj
     * @param bool $force
     * @return bool
     */
    public function update($obj, $force = false)
    {
        if ($this->_DEBUG_UPDATE) {
            return $this->_update($obj, $force);
        }

        return true;
    }

    /**
     * @param      $obj
     * @param bool $force
     * @return bool
     */
    public function _update($obj, $force = false)
    {
        if (!$this->_check_class($obj)) {
            $this->_set_errors($this->_table . ': not match class');

            return false;
        }

        $id = $this->_get_id_value_by_obj($obj);
        if (empty($id)) {
            $this->_set_errors($this->_table . ': not exist primary id');

            return false;
        }

        if ($this->_MODE_CRITERIA) {
            $sql = $this->_build_update_sql_new($obj);
        } else {
            $sql = $this->_build_update_sql($obj);
        }

        $ret = $force ? $this->queryF($sql) : $this->query($sql);

        if (isset($this->_cached[$id])) {
            unset($this->_cached[$id]);
        }

        return $ret;
    }

    /**
     * @param $obj
     * @return bool|int
     */
    public function _get_id_value_by_obj($obj)
    {
        $val = false;
        if (is_object($obj)) {
            $val = (int)$obj->get($this->_id_name);
        }

        return $val;
    }

    /**
     * @param \XoopsObject|BaseObject $obj
     * @param bool         $force
     * @return bool
     */
    public function delete($obj, $force = false)
    {
        if ($this->_DEBUG_DELETE) {
            return $this->_delete($obj, $force);
        }

        return true;
    }

    /**
     * @param      $obj
     * @param bool $force
     * @return bool
     */
    public function _delete($obj, $force = false)
    {
        $id  = $this->_get_id_value_by_obj($obj);
        $sql = 'DELETE FROM ' . $this->_table . ' WHERE ' . $this->_id_name . '=' . (int)$id;
        $ret = $force ? $this->queryF($sql) : $this->query($sql);

        if (isset($this->_cached[$id])) {
            unset($this->_cached[$id]);
        }

        return $ret;
    }

    /**
     * @param      $id
     * @param bool $force
     * @return bool
     */
    public function delete_by_id($id, $force = false)
    {
        $obj = &$this->get($id);

        return $this->delete($obj, $force);
    }

    /**
     * @param      $obj
     * @param bool $force
     * @return bool
     */
    public function deleteAll($obj, $force = false)
    {
        return $this->_delete_all($obj, $force);
    }

    /**
     * @param      $criteria
     * @param bool $force
     * @return bool
     */
    public function _delete_all($criteria, $force = false)
    {
        $objs = &$this->getObjects($criteria);

        $flag = true;

        foreach ($objs as $obj) {
            $flag &= $this->delete($obj, $force);
        }

        return $flag;
    }

    //---------------------------------------------------------
    // field
    //---------------------------------------------------------
    // get_field_meta_name_array() => "SHOW COLUMNS"
    /**
     * @param $name
     * @return bool
     */
    public function existsFieldName($name)
    {
        $sql = 'SHOW COLUMNS FROM ' . $this->_table . ' LIKE ' . $this->quote($name);

        $res = &$this->query($sql, 0, 0, true);
        if (!$res) {
            return false;
        }

        while (false !== ($row = $this->fetchArray($res))) {
            if ($row['Field'] == $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array|bool
     */
    public function &get_all_columns()
    {
        $sql = 'SHOW COLUMNS FROM ' . $this->_table;

        return $this->get_rows_by_sql($sql, 0, 0, true);
    }

    /**
     * @return bool
     */
    public function compare_to_scheme()
    {
        $this->_clear_errors();

        $column_arr = &$this->get_all_columns();
        if (!is_array($column_arr) || (0 == count($column_arr))) {
            $this->_set_errors('not get columns');

            return false;
        }

        $obj = &$this->create();
        if (!is_object($obj)) {
            $this->_set_errors('not create object');

            return false;
        }

        $scheme_arr = $obj->get_scheme();

        foreach ($scheme_arr as $scheme_name => $scheme) {
            $flag_match       = false;
            $scheme_type_name = $obj->get_data_type_name($scheme_name);

            foreach ($column_arr as $column) {
                $column_name = $column['Field'];
                $column_type = $column['Type'];

                if ($column_name == $scheme_name) {
                    if (!$obj->compare_data_type_to_column($scheme_name, $column_type)) {
                        $this->_set_errors("$scheme_name : unmatch1 type : $scheme_type_name != $column_type");
                    }

                    $flag_match = true;
                    break;
                }
            }

            if (!$flag_match) {
                $this->_set_errors("$scheme_name : not exists in table");
            }
        }

        foreach ($column_arr as $column) {
            $flag_match  = false;
            $column_name = $column['Field'];
            $column_type = $column['Type'];

            foreach ($scheme_arr as $scheme_name => $scheme) {
                $scheme_type_name = $obj->get_data_type_name($scheme_name);

                if ($column_name == $scheme_name) {
                    if (!$obj->compare_data_type_to_column($scheme_name, $column_type)) {
                        $this->_set_errors("$scheme_name : unmatch2 type : $scheme_type_name != $column_type");
                    }

                    $flag_match = true;
                    break;
                }
            }

            if (!$flag_match) {
                $this->_set_errors("$column_name : not exists in scheme");
            }
        }

        return $this->returnExistError();
    }

    // for lower compatblity
    // caller : weblinks_linkHandler.php
    /**
     * @return array|bool
     */
    public function &get_field_meta_name_array()
    {
        $arr_meta = [];
        $arr_name = [];

        $sql = 'SELECT * FROM ' . $this->_table;

        $res = &$this->query($sql);
        if (!$res) {
            $false = false;

            return $false;
        }

        $num = $this->getFieldsNum($res);

        for ($i = 0; $i < $num; ++$i) {
            $meta = mysql_fetch_field($res);
            if (is_object($meta)) {
                $arr_meta[] = $meta;
                $arr_name[] = $meta->name;
            }
        }

        $this->_field_meta_array = &$arr_meta;
        $this->_field_name_array = &$arr_name;

        return $arr_meta;
    }

    /**
     * @return array
     */
    public function &get_field_meta_array()
    {
        return $this->_field_meta_array;
    }

    /**
     * @return array
     */
    public function &get_field_name_array()
    {
        return $this->_field_name_array;
    }

    //---------------------------------------------------------
    // table
    //---------------------------------------------------------
    /**
     * @return bool
     */
    public function existsTable()
    {
        $arr = &$this->get_table_name_array();
        if (!is_array($arr) || (0 == count($arr))) {
            return false;
        }

        $table_name = mb_strtolower($this->_table);

        if (in_array($table_name, $arr)) {
            return true;
        }

        return false;
    }

    /**
     * @return array|bool
     */
    public function &get_table_name_array()
    {
        $arr = [];

        $sql = 'SHOW TABLES';

        $res = &$this->queryF($sql);
        if (!$res) {
            $false = false;

            return $false;
        }

        while (false !== ($myrow = &$this->fetchRow($res))) {
            $arr[] = mb_strtolower($myrow[0]);
        }

        return $arr;
    }

    //---------------------------------------------------------
    // create & drop table
    //---------------------------------------------------------
    /**
     * @param $magic
     * @return bool
     */
    public function drop_table($magic)
    {
        $ret = false;
        if ($magic === $this->_magic_word) {
            $sql = 'DROP TABLE ' . $this->_table;
            $ret = $this->query($sql);
        }

        return $ret;
    }

    /**
     * @param $magic
     * @return bool
     */
    public function clean_table($magic)
    {
        $ret = false;
        if ($magic === $this->_magic_word) {
            $sql = 'DELETE FROM ' . $this->_table;
            $ret = $this->query($sql);
        }

        return $ret;
    }

    /**
     * @return string
     */
    public function get_magic_word()
    {
        return $this->_magic_word;
    }

    //---------------------------------------------------------
    // execute query
    //---------------------------------------------------------
    /**
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function &get_objects_asc($limit = 0, $start = 0)
    {
        $criteria = new \CriteriaCompo();
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $objs = &$this->getObjects($criteria);

        return $objs;
    }

    /**
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function &get_objects_desc($limit = 0, $start = 0)
    {
        $sort     = $this->_id_name . ' DESC';
        $criteria = new \CriteriaCompo();
        $criteria->setSort($sort);
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $objs = &$this->getObjects($criteria);

        return $objs;
    }

    /**
     * @param $key
     * @param $value
     * @return bool|mixed
     */
    public function get_count_by_key_value($key, $value)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria($key, $value, '='));

        return $this->getCount($criteria);
    }

    /**
     * @param $key
     * @param $value
     * @return bool|mixed
     */
    public function &get_one_by_key_value($key, $value)
    {
        return $this->get_first_obj_from_objs($this->get_all_by_key_value($key, $value));
    }

    /**
     * @param $key
     * @param $value
     * @return array
     */
    public function &get_all_by_key_value($key, $value)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria($key, $value, '='));

        return $this->getObjects($criteria);
    }

    /**
     * @param $objs
     * @return bool|mixed
     */
    public function &get_first_obj_from_objs($objs)
    {
        $obj   = false;
        $count = count($objs);

        if (!is_array($objs) || (0 == $count)) {
            return $obj;
        }

        if (isset($objs[0]) && is_object($objs[0])) {
            $obj = $objs[0];
        }

        if ($count > 1) {
            $id = '';
            if (is_object($obj)) {
                $id = $obj->get($this->_id_name);
            }
            $msg = $this->_table . " : $id : too many matched ";
            $this->_set_errors($msg);
        }

        return $obj;
    }

    //---------------------------------------------------------
    // execute query by sql
    //---------------------------------------------------------
    /**
     * @param null $sql
     * @return bool|mixed
     */
    public function get_count_by_sql($sql = null)
    {
        $result = $this->query($sql);

        if (!$result) {
            return false;
        }

        list($count) = $this->fetchRow($result);

        return $count;
    }

    /**
     * @param null $sql
     * @param int  $limit
     * @param int  $start
     * @param bool $id_as_key
     * @return array
     */
    public function &get_objects_by_sql($sql = null, $limit = 0, $start = 0, $id_as_key = false)
    {
        $ret = [];

        $result = &$this->query($sql, $limit, $start);

        if (!$result) {
            return $ret;
        }

        while (false !== ($row = &$this->fetchArray($result))) {
            $obj = $this->create();
            if (null !== $obj) {
                $obj->assignVars($row);
                $obj->unsetNew();

                if ($id_as_key) {
                    $id       = $this->_get_id_value_by_obj($obj);
                    $ret[$id] = $obj;
                } else {
                    $ret[] = $obj;
                }

                unset($obj);
            }
        }

        return $ret;
    }

    /**
     * @param $sql
     * @return mixed
     */
    public function &get_row_by_sql($sql)
    {
        $res = &$this->query($sql);
        $arr = &$this->fetchArray($res);

        return $arr;
    }

    /**
     * @param      $sql
     * @param int  $limit
     * @param int  $offset
     * @param bool $force
     * @return array|bool
     */
    public function &get_rows_by_sql($sql, $limit = 0, $offset = 0, $force = false)
    {
        $res = &$this->query($sql, $limit, $offset, $force);
        if (!$res) {
            return $res;
        }

        $arr = [];

        while (false !== ($row = &$this->fetchArray($res))) {
            $arr[] = $row;
        }

        return $arr;
    }

    /**
     * @param     $sql
     * @param int $limit
     * @param int $start
     * @return array|bool
     */
    public function &get_first_rows_by_sql($sql, $limit = 0, $start = 0)
    {
        $res = &$this->query($sql, $limit, $start);
        if (!$res) {
            return $res;
        }

        $arr = [];

        while (false !== ($row = &$this->fetchRow($res))) {
            $arr[] = $row[0];
        }

        return $arr;
    }

    /**
     * @param $criteria
     * @return bool
     */
    public function _check_criteria_class($criteria)
    {
        if (isset($criteria) && is_a($criteria, $this->_criteria_class_name)) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // database class
    //---------------------------------------------------------
    // BUG 4380: Only variables should be assigned by reference
    // different function definition in XoopsDatabase
    //   xoops 2.0.15 :    query()
    //   xoops 2.0.16 jp: &query()
    //---------------------------------------------------------
    /**
     * @param      $sql
     * @param int  $limit
     * @param int  $offset
     * @param bool $force
     * @return bool
     */
    public function &query($sql, $limit = 0, $offset = 0, $force = false)
    {
        $limit  = (int)$limit;
        $offset = (int)$offset;

        $res = true;
        if ($this->_DEBUG_QUERY) {
            if ($force) {
                $res = $this->_db->queryF($sql, $limit, $offset);
            } else {
                $res = $this->_db->query($sql, $limit, $offset);
            }
        }

        $this->_print_db_sql($sql, $limit, $offset);

        if (!$res) {
            $this->_set_db_error($sql, $limit, $offset);
        }

        return $res;
    }

    /**
     * @param     $sql
     * @param int $limit
     * @param int $offset
     * @return bool
     */
    public function &queryF($sql, $limit = 0, $offset = 0)
    {
        $limit  = (int)$limit;
        $offset = (int)$offset;

        $res = true;
        if ($this->_DEBUG_QUERY_FORCE) {
            $res = $this->_db->queryF($sql, $limit, $offset);
        }

        $this->_print_db_sql($sql, $limit, $offset);

        if (!$res) {
            $this->_set_db_error($sql, $limit, $offset);
        }

        return $res;
    }

    /**
     * @param $res
     * @return mixed
     */
    public function getRowsNum($res)
    {
        $ret = $this->_db->getRowsNum($res);

        return $ret;
    }

    /**
     * @param $res
     * @return mixed
     */
    public function getFieldsNum($res)
    {
        $ret = $this->_db->getFieldsNum($res);

        return $ret;
    }

    /**
     * @param $res
     * @return mixed
     */
    public function &fetchArray($res)
    {
        $ret = $this->_db->fetchArray($res);

        return $ret;
    }

    /**
     * @param $res
     * @return mixed
     */
    public function &fetchRow($res)
    {
        $ret = $this->_db->fetchRow($res);

        return $ret;
    }

    /**
     * @return mixed
     */
    public function getInsertId()
    {
        $ret = $this->_db->getInsertId();

        return $ret;
    }

    /**
     * @param $result
     * @return mixed
     */
    public function freeRecordSet($result)
    {
        $ret = $this->_db->freeRecordSet($result);

        return $ret;
    }

    /**
     * @param $value
     */
    public function setPrefix($value)
    {
        $this->_db->setPrefix($value);
    }

    /**
     * @param string $tablename
     * @return string
     */
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

    /**
     * @param $str
     * @return string
     */
    public function quoteString($str)
    {
        $str = $this->quote($str);

        return $str;
    }

    /**
     * @param $str
     * @return string
     */
    public function quote($str)
    {
        $str = "'" . addslashes($str) . "'";

        return $str;
    }

    //---------------------------------------------------------
    // build sql
    // require XOOPS 2.0 \CriteriaElement class
    //---------------------------------------------------------
    /**
     * @param null $criteria
     * @return string
     */
    public function _build_object_sql($criteria = null)
    {
        $sql = 'SELECT * FROM ' . $this->_table;

        if ($this->_check_criteria_class($criteria)) {
            $sql .= ' ' . $criteria->renderWhere();

            $sort = $criteria->getSort();

            if ($sort) {
                $sql .= ' ORDER BY ' . $sort;
            } else {
                $sql .= ' ORDER BY ' . $this->_id_name . ' ' . $criteria->getOrder();
            }
        }

        return $sql;
    }

    /**
     * @param null $criteria
     * @return string
     */
    public function _build_list_sql($criteria = null)
    {
        $sql = 'SELECT ' . $this->_id_name . ' FROM ' . $this->_table;

        if ($this->_check_criteria_class($criteria)) {
            $sql .= ' ' . $criteria->renderWhere();

            $sort = $criteria->getSort();

            if ($sort) {
                $sql .= ' ORDER BY ' . $sort;
            } else {
                $sql .= ' ORDER BY ' . $this->_id_name . ' ' . $criteria->getOrder();
            }
        }

        return $sql;
    }

    /**
     * @param null $criteria
     * @return string
     */
    public function _build_count_sql($criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->_table;

        if ($this->_check_criteria_class($criteria)) {
            $sql .= ' ' . $criteria->renderWhere();
        }

        return $sql;
    }

    // override this function

    /**
     * @param $obj
     */
    public function _build_insert_sql($obj)
    {
        // dummy
    }

    // override this function

    /**
     * @param $obj
     */
    public function _build_update_sql($obj)
    {
        // dummy
    }

    //---------------------------------------------------------
    // build sql
    // require XC2.1 \CriteriaElement class
    //---------------------------------------------------------
    /**
     * @param null $criteria
     * @return string
     */
    public function _build_object_sql_new($criteria = null)
    {
        $sql = 'SELECT * FROM ' . $this->_table;

        if ($this->_check_criteria_class($criteria)) {
            $sql .= $this->_build_object_sql_addtion($criteria);
        }

        return $sql;
    }

    /**
     * @param null $criteria
     * @return string
     */
    public function _build_list_sql_new($criteria = null)
    {
        $sql = 'SELECT ' . $this->_id_name . ' FROM ' . $this->_table;

        if ($this->_check_criteria_class($criteria)) {
            $sql .= $this->_build_object_sql_addtion($criteria);
        }

        return $sql;
    }

    /**
     * @param $criteria
     * @return string
     */
    public function _build_object_sql_addtion($criteria)
    {
        $sql = '';

        $where = $this->_makeCriteria4sql($criteria);

        if (trim($where)) {
            $sql .= ' WHERE ' . $where;
        }

        $sorts = [];

        foreach ($criteria->getSorts() as $sort) {
            $sorts[] = $sort['sort'] . ' ' . $sort['order'];
        }

        if ('' != $criteria->getSort()) {
            $sql .= ' ORDER BY ' . implode(',', $sorts);
        }

        return $sql;
    }

    /**
     * @param null $criteria
     * @return string
     */
    public function _build_count_sql_new($criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->_table;

        if ($this->_check_criteria_class($criteria)) {
            $where = $this->_build_count_sql_where($criteria);

            if ($where) {
                $sql .= ' WHERE ' . $where;
            }
        }

        return $sql;
    }

    /**
     * @param $obj
     * @return string
     */
    public function _build_insert_sql_new($obj)
    {
        $fileds = [];
        $values = [];

        $arr = $this->_makeVars4sql($obj);

        foreach ($arr as $_name => $_value) {
            $fields[] = $_name;
            $values[] = $_value;
        }

        $sql = @sprintf('INSERT INTO ' . $this->_table . ' ( %s ) VALUES ( %s )', implode(',', $fields), implode(',', $values));

        return $sql;
    }

    /**
     * @param $obj
     * @return string
     */
    public function _build_update_sql_new($obj)
    {
        $set_lists = [];
        $where     = '';

        $arr = $this->_makeVars4sql($obj);

        foreach ($arr as $_name => $_value) {
            if ($_name == $this->_id_name) {
                $where = "${_name}=${_value}";
            } else {
                $set_lists[] = "${_name}=${_value}";
            }
        }

        $sql = @sprintf('UPDATE ' . $this->_table . ' SET %s WHERE %s', implode(',', $set_lists), $where);

        return $sql;
    }

    /**
     * @param $obj
     * @return array
     */
    public function _makeVars4sql($obj)
    {
        $ret = [];

        foreach ($obj->gets() as $key => $value) {
            $dataType = $obj->mVars[$key]['data_type'];

            if (in_array($dataType, $this->_STRING_TYPES)) {
                $ret[$key] = $this->_db->quoteString($value);
            } else {
                $ret[$key] = $value;
            }
        }

        return $ret;
    }

    /**
     * @param $criteria
     * @return string|null
     */
    public function _makeCriteria4sql($criteria)
    {
        $dmmyObj = &$this->create();

        return $this->_makeCriteriaElement4sql($criteria, $dmmyObj);
    }

    /**
     * @param $criteria
     * @param $obj
     * @return string|null
     */
    public function _makeCriteriaElement4sql($criteria, $obj)
    {
        if (is_a($criteria, $this->_criteria_class_name)) {
            if ($criteria->hasChildElements()) {
                $queryString = '';
                $maxCount    = $criteria->getCountChildElements();

                for ($i = 0; $i < $maxCount; ++$i) {
                    $queryString .= ' ' . $this->_makeCriteria4sql($criteria->getChildElement($i));

                    if (($i + 1) != $maxCount) {
                        $queryString .= ' ' . $criteria->getCondition($i);
                    }
                }

                return '(' . $queryString . ')';
            }
            $name  = $criteria->getName();
            $value = $criteria->getValue();

            if (null != $name && isset($obj->_vars[$name])) {
                $value = $this->_makeCriteriaElement4sql_datatype($criteria, $obj);
            }

            if (null != $name) {
                return $name . ' ' . $criteria->getOperator() . ' ' . $value;
            }

            return null;

            return $string;
        }
    }

    /**
     * @param $criteria
     * @param $obj
     * @return float|int|string
     */
    public function _makeCriteriaElement4sql_datatype($criteria, $obj)
    {
        $name     = $criteria->getName();
        $value    = $criteria->getValue();
        $dataType = $obj->_vars[$name]['data_type'];

        if (in_array($dataType, $this->_STRING_TYPES)) {
            $value = $this->quoteString($value);
        } else {
            switch ($dataType) {
                case XOBJ_DTYPE_BOOL:
                    $value = $value ? '1' : '0';
                    break;
                case XOBJ_DTYPE_INT:
                    $value = (int)$value;
                    break;
                case XOBJ_DTYPE_FLOAT:
                    $value = (float)$value;
                    break;
                default:
                    $value = $criteria->getValue();
            }
        }

        return $value;
    }

    //---------------------------------------------------------
    // set parameter
    //---------------------------------------------------------
    /**
     * @param $val
     */
    public function set_criteria_class_name($val)
    {
        $this->_criteria_class_name = $val;
    }

    /**
     * @param $val
     */
    public function set_mode_criteria($val)
    {
        $this->_MODE_CRITERIA = (int)$val;
    }

    /**
     * @param $val
     */
    public function set_debug_insert($val)
    {
        $this->_DEBUG_INSERT = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_debug_update($val)
    {
        $this->_DEBUG_UPDATE = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_debug_delete($val)
    {
        $this->_DEBUG_DELETE = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_debug_query($val)
    {
        $this->_DEBUG_QUERY = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_debug_query_force($val)
    {
        $this->_DEBUG_QUERY_FORCE = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_debug_query_res($val)
    {
        $this->_DEBUG_QUERY_RES = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_debug_query_force_res($val)
    {
        $this->_DEBUG_QUERY_FORCE_RES = (bool)$val;
    }

    //---------------------------------------------------------
    // get parameter
    //---------------------------------------------------------
    /**
     * @return mixed
     */
    public function get_table_name()
    {
        return $this->_table;
    }

    // --- class end ---
}
