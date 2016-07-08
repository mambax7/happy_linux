<?php
// $Id: module_install.php,v 1.6 2009/01/05 17:50:39 ohwada Exp $

// 2008-12-20 K.OHWADA
// preg_match_column_type_array()
// No such file or directory

// 2008-01-20 K.OHWADA
// get_column_type()

// 2008-01-10 K.OHWADA
// Notice [PHP]: Only variables should be assigned by reference

// 2007-11-24 K.OHWADA
// drop_table()

//=========================================================
// Happy Linux Framework Module
// 2007-11-11 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_module_install
//=========================================================
class happy_linux_module_install
{
    public $_db;
    public $_config_define;
    public $_config_table;

    public $_count_insert = 0;
    public $_flag_error   = false;
    public $_errors       = array();
    public $_msgs         = array();

    public $_DEBUG_SQL   = false;
    public $_DEBUG_ERROR = false;
    public $_DEBUG_TPL   = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_db = XoopsDatabaseFactory::getDatabaseConnection();
    }

    public function set_config_define_class(&$class)
    {
        $this->_config_define =& $class;
    }

    public function set_config_table_name($name)
    {
        $this->_config_table = $this->prefix($name);
    }

    //=========================================================
    // public
    //=========================================================
    public function create_config_table()
    {
        $sql = '
CREATE TABLE ' . $this->_config_table . " (
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

        return $this->query($sql);
    }

    //---------------------------------------------------------
    // install
    //---------------------------------------------------------
    public function check_init_config()
    {
        return $this->_get_config_count_all();
    }

    public function init_config()
    {
        $this->clear_error();
        $this->_count_insert = 0;
        $define_arr          =& $this->_config_define->get_define();

        // list from Define
        foreach ($define_arr as $id => $def) {
            $row = array(
                'conf_id'        => $id,
                'conf_name'      => $def['name'],
                'conf_valuetype' => $def['valuetype'],
                'conf_value'     => $this->_get_conf_value_for_input($def['default'], $def['valuetype']),
            );

            $this->_insert_config($row);
            $this->_count_insert++;
        }

        return $this->return_errors();
    }

    //---------------------------------------------------------
    // update
    //---------------------------------------------------------
    public function check_update_config()
    {
        $define_arr =& $this->_config_define->get_define();
        $config_arr =& $this->_get_config_name_array();

        foreach ($define_arr as $def) {
            if (!in_array($def['name'], $config_arr)) {
                return false;
            }
        }
        return true;
    }

    public function update_config()
    {
        $this->clear_error();
        $this->_count_insert = 0;
        $define_arr          =& $this->_config_define->get_define();

        // list from Define
        foreach ($define_arr as $id => $def) {

            // if exist
            if ($this->_get_config_count_by_confid($id)) {
                continue;
            }

            // insert, when not in MySQL
            $row = array(
                'conf_id'        => $id,
                'conf_name'      => $def['name'],
                'conf_valuetype' => $def['valuetype'],
                'conf_value'     => $this->_get_conf_value_for_input($def['default'], $def['valuetype']),
            );

            $this->_insert_config($row);
            $this->_count_insert++;
        }

        return $this->return_errors();
    }

    public function check_and_update_table($table, $ver)
    {
        $func_check  = '_check_' . $table . '_' . $ver;
        $func_update = '_update_' . $table . '_' . $ver;
        $table_name  = '_' . $table . '_table';

        if (!$this->$func_check()) {
            $this->clear_error();
            $this->$func_update();
            $this->set_msg($this->build_update_msg($this->$table_name));
        }
    }

    public function drop_table($table)
    {
        $sql = 'DROP TABLE ' . $table;
        return $this->query($sql);
    }

    public function truncate_table($table)
    {
        $sql = 'TRUNCATE TABLE ' . $table;
        return $this->query($sql);
    }

    //---------------------------------------------------------
    // check
    //---------------------------------------------------------
    public function exists_config_table()
    {
        return $this->exists_table($this->_config_table);
    }

    public function exists_table($table)
    {
        $sql = 'SHOW TABLES LIKE ' . $this->quote($table);

        $res =& $this->query($sql);
        if (!$res) {
            return false;
        }

        while ($row = $this->_db->fetchRow($res)) {
            if (strtolower($row[0]) == strtolower($table)) {
                return true;
            }
        }

        return false;
    }

    public function exists_column($table, $column)
    {
        $row =& $this->get_column_row($table, $column);
        if (is_array($row)) {
            return true;
        }
        return false;
    }

    public function exists_config_item_by_name_array(&$name_arr)
    {
        foreach ($name_arr as $name) {
            $count = $this->_get_config_count_by_name($name);
            if ($count == 0) {
                return false;
            }
        }
        return true;
    }

    public function preg_match_column_type($table, $column, $type)
    {
        $pattern = '/' . preg_quote($type) . '/i';
        $subject = $this->get_column_type($table, $column);
        if (preg_match($pattern, $subject)) {
            return true;
        }
        return false;
    }

    public function preg_match_column_type_array($table, $column, $type_array)
    {
        $subject = $this->get_column_type($table, $column);
        foreach ($type_array as $type) {
            $pattern = '/' . preg_quote($type) . '/i';
            if (preg_match($pattern, $subject)) {
                return true;
            }
        }
        return false;
    }

    //---------------------------------------------------------
    // get
    //---------------------------------------------------------
    public function get_column_type($table, $column)
    {
        $row =& $this->get_column_row($table, $column);
        if (isset($row['Type'])) {
            return $row['Type'];
        }
        return false;
    }

    public function &get_column_row($table, $column)
    {
        $false = false;

        $sql = 'SHOW COLUMNS FROM ' . $table . ' LIKE ' . $this->quote($column);

        $res =& $this->query($sql);
        if (!$res) {
            return $false;
        }

        while ($row = $this->_db->fetchArray($res)) {
            if ($row['Field'] == $column) {
                return $row;
            }
        }

        return $false;
    }

    public function get_count_all($table)
    {
        $sql = 'SELECT count(*) FROM ' . $table;
        return $this->get_count_by_sql($sql);
    }

    public function get_count_by_sql($sql)
    {
        $res =& $this->query($sql);
        if (!$res) {
            return $res;
        }

        $row   = $this->_db->fetchRow($res);
        $count = (int)$row[0];
        if (empty($count)) {
            $count = 0;
        }

        return $count;
    }

    public function &get_rows_by_sql($sql, $limit = 0, $offset = 0)
    {
        $res =& $this->query($sql, $limit, $offset);
        if (!$res) {
            return $res;
        }

        $arr = array();

        // Notice [PHP]: Only variables should be assigned by reference
        while ($row = $this->_db->fetchArray($res)) {
            $arr[] = $row;
        }

        return $arr;
    }

    //---------------------------------------------------------
    // query
    //---------------------------------------------------------
    public function prefix($name)
    {
        return $this->_db->prefix($name);
    }

    public function &query($sql, $limit = 0, $offset = 0)
    {
        if ($this->_DEBUG_SQL) {
            echo $this->_sanitize($sql) . "<br />\n";
        }
        $res = $this->_db->queryF($sql, (int)$limit, (int)$offset);
        if (!$res) {
            $err = $this->_db->error();
            $this->_set_error($err);
            if ($this->_DEBUG_ERROR) {
                echo $this->_get_error_line($err);
            }
        }
        return $res;
    }

    public function quote($str)
    {
        $str = "'" . addslashes($str) . "'";
        return $str;
    }

    //---------------------------------------------------------
    // message
    //---------------------------------------------------------
    public function get_message()
    {
        $text = '';
        if (count($this->_msgs) == 0) {
            return $text;
        }

        foreach ($this->_msgs as $msg) {
            $text .= $msg . "<br />\n";
        }

        return $text;
    }

    public function build_create_config_msg()
    {
        return $this->build_create_msg($this->_config_table);
    }

    public function get_init_config_msg()
    {
        return $this->build_init_msg($this->_config_table);
    }

    public function get_update_config_msg()
    {
        return $this->build_update_msg($this->_config_table, $this->_count_insert);
    }

    public function build_create_msg($table)
    {
        return $this->_build_msg($table, true, 'created');
    }

    public function build_init_msg($table)
    {
        return $this->_build_msg($table, true, 'initialized');
    }

    public function build_update_msg($table, $flag = true)
    {
        return $this->_build_msg($table, $flag, 'updated');
    }

    public function set_msg($msg)
    {
        if ($msg) {
            $this->_msgs[] = $msg;
        }
    }

    public function clear_error()
    {
        $this->_errors = array();
    }

    public function return_flag_error()
    {
        if ($this->_flag_error) {
            return false;
        }
        return true;
    }

    public function return_errors()
    {
        if (count($this->_errors)) {
            return false;
        }
        return true;
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------
    public function get_post_op()
    {
        $op = '';
        if (isset($_POST['op'])) {
            $op = $_POST['op'];
        } elseif (isset($_GET['op'])) {
            $op = $_GET['op'];
        }
        return $op;
    }

    //---------------------------------------------------------
    // template
    //---------------------------------------------------------
    public function clear_compiled_tpl_by_dir($dir)
    {
        $tpl = new XoopsTpl();

        $arr =& $this->_get_files_in_dir($dir);
        if (!is_array($arr) || !count($arr)) {
            return false;
        }

        foreach ($arr as $file) {
            if ($this->_DEBUG_TPL) {
                echo " $file <br />\n";
            }
            $tpl->clear_compiled_tpl($file);
        }
    }

    public function build_tpl_msg()
    {
        $msg = null;
        if (count($this->_errors)) {
            $msg = $this->_get_errors();
        } else {
            $msg = 'local template cleared';
        }
        return $msg;
    }

    //---------------------------------------------------------
    // xoops module table
    //---------------------------------------------------------
    // Notice [PHP]: Only variables should be assigned by reference
    // XOOPS 2.0.17 : non reference
    public function &get_xoops_module_objects_isactive()
    {
        $module_handler = xoops_getHandler('module');
        $criteria       = new CriteriaCompo();
        $criteria->add(new Criteria('isactive', '1', '='));
        $ret = $module_handler->getObjects($criteria);
        return $ret;
    }

    //=========================================================
    // private
    //=========================================================
    //---------------------------------------------------------
    // insert config
    //---------------------------------------------------------
    public function _insert_config(&$row)
    {
        return $this->query($this->_build_insert_config_sql($row));
    }

    public function _build_insert_config_sql(&$row)
    {
        $aux_int_1  = 0;
        $aux_int_2  = 0;
        $aux_text_1 = '';
        $aux_text_2 = '';

        foreach ($row as $k => $v) {
            ${$k} = $v;
        }

        $sql = 'INSERT INTO ' . $this->_config_table . ' (';
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

    //---------------------------------------------------------
    // get
    //---------------------------------------------------------
    public function _get_config_count_all()
    {
        return $this->get_count_all($this->_config_table);
    }

    public function _get_config_count_by_confid($id)
    {
        $sql = 'SELECT count(*) FROM ' . $this->_config_table . ' WHERE conf_id=' . (int)$id;
        return $this->get_count_by_sql($sql);
    }

    public function _get_config_count_by_name($name)
    {
        $sql = 'SELECT count(*) FROM ' . $this->_config_table . ' WHERE conf_name=' . $this->quote($name);
        return $this->get_count_by_sql($sql);
    }

    public function &_get_config_name_array()
    {
        $arr = array();

        $sql  = 'SELECT * FROM ' . $this->_config_table . ' ORDER BY conf_id ASC';
        $rows =& $this->get_rows_by_sql($sql);

        if (is_array($rows) && (count($rows) > 0)) {
            foreach ($rows as $row) {
                $arr[] = $row['conf_name'];
            }
        }

        return $arr;
    }

    public function &_get_config_name_value_array()
    {
        $arr = array();

        $sql  = 'SELECT * FROM ' . $this->_config_table . ' ORDER BY conf_id ASC';
        $rows =& $this->get_rows_by_sql($sql);

        if (is_array($rows) && (count($rows) > 0)) {
            foreach ($rows as $row) {
                $arr[$row['conf_name']] = $row['conf_value'];
            }
        }

        return $arr;
    }

    //---------------------------------------------------------
    // set value
    //---------------------------------------------------------
    public function _get_conf_value_for_input($value, $valuetype)
    {
        switch ($valuetype) {
            case 'bool':
                $val = (bool)$value;
                break;

            case 'int':
                $val = (int)$value;
                break;

            case 'float':
                $val = (float)$value;
                break;

            case 'array':
                if (!is_array($value)) {
                    $value = explode('|', trim($value));
                }
                $val = serialize($value);
                break;

            case 'text':
            case 'textarea':
            case 'other':
            default:
                $val = $value;
                break;
        }
        return $val;
    }

    //---------------------------------------------------------
    // dirctory
    //---------------------------------------------------------
    public function &_get_files_in_dir($dir_in, $ext = 'html')
    {
        $arr   = array();
        $false = false;

        // No such file or directory
        $dir = $dir_in . '/';
        if (!is_dir($dir)) {
            $this->_set_error('not exist directory : ' . $dir);
            return $false;
        }

        $dh = opendir($dir);
        if (!$dh) {
            $this->_set_error('cannot open directory : ' . $dir);
            return $false;
        }

        $pattern = "/\." . preg_quote($ext) . "$/";

        while (false !== ($file = readdir($dh))) {
            // omit index.html
            if ($file == 'index.html') {
                continue;
            }

            $file_full = $dir . $file;

            if (!is_dir($file_full) && is_file($file_full)) {
                if (($ext && preg_match($pattern, $file)) || ($ext === '')) {
                    $arr[] = $file_full;
                }
            }
        }

        closedir($dh);

        return $arr;
    }

    //---------------------------------------------------------
    // message
    //---------------------------------------------------------
    public function _build_msg($table, $flag, $msg_finished)
    {
        $table = $this->_sanitize($table);
        $msg   = null;
        if (count($this->_errors)) {
            $msg = $this->_highlight('ERROR: <b>' . $table . "</b><br />\n");
            $msg .= $this->_get_errors();
        } elseif ($flag) {
            $msg = '<b>' . $table . '</b> ' . $msg_finished;
        }
        return $msg;
    }

    public function _set_error($msg)
    {
        $this->_flag_error = true;
        $this->_errors[]   = $msg;
    }

    public function get_error_str($flag_highlight = false)
    {
        return $this->_get_errors($flag_highlight);
    }

    public function _get_errors($flag_highlight = true)
    {
        $text = '';
        if (count($this->_errors) == 0) {
            return $text;
        }

        foreach ($this->_errors as $msg) {
            $text .= $this->_get_error_line($msg, $flag_highlight);
        }

        return $text;
    }

    public function _get_error_line($str, $flag_highlight = true)
    {
        $str = $this->_sanitize($str);
        if ($flag_highlight) {
            $str = $this->_highlight($str);
        }
        return $str . "<br />\n";
    }

    public function _sanitize($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }

    public function _highlight($str)
    {
        return '<span style="color:#ff0000;">' . $str . '</span>';
    }

    // --- class end ---
}
