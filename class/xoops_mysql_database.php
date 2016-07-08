<?php
// $Id: xoops_mysql_database.php,v 1.5 2007/09/23 08:26:54 ohwada Exp $

// 2007-09-20 K.OHWADA
// Only variables should be assigned by reference

// 2007-03-01 K.OHWADA
// support mysql 5
// setPrefix() prefix()

// 2006-07-10 K.OHWADA
// this is new file
// porting from class.mysql_database.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

//=========================================================
// class mysql_database
// substitute for class XOOPS XoopsMySQLDatabase
//=========================================================

//---------------------------------------------------------
// TODO
// call connect twice by config and link
//---------------------------------------------------------

class mysql_database extends Database
{

    // Database connection
    public $conn;

    public $prefix;

    // debug
    // BUG 2793: Fatal error: Call to undefined function: _print_sql_error()
    public $flag_print_error = 1;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->setPrefix(XOOPS_DB_PREFIX);
    }

    //---------------------------------------------------------
    // function
    //---------------------------------------------------------
    public function connect()
    {
        $this->conn = mysql_connect(XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS);

        if (!$this->conn) {
            $this->_print_error();
            return false;
        }

        if (!mysql_select_db(XOOPS_DB_NAME)) {
            $this->_print_error();
            return false;
        }

        if (!$this->_set_charset()) {
            $this->_print_error();
            return false;
        }

        return true;
    }

    public function _set_charset()
    {
        if ($this->_is_mysql_ver5() && defined('HAPPY_LINUX_MYSQL_CHARSET')) {
            $sql = 'SET NAMES ' . HAPPY_LINUX_MYSQL_CHARSET;
            $ret = $this->query($sql);
            if (!$ret) {
                return false;
            }
        }
        return true;
    }

    public function _is_mysql_ver5()
    {
        $ver = $GLOBALS['xoopsDB']->getServerVersion();
        if (preg_match("/^4\.1/", $ver)) {
            return true;
        }
        if (preg_match("/^5\./", $ver)) {
            return true;
        }
        return false;
    }

    public function fetchRow($result)
    {
        return @mysql_fetch_row($result);
    }

    public function fetchArray($result)
    {
        return @mysql_fetch_assoc($result);
    }

    public function fetchBoth($result)
    {
        return @mysql_fetch_array($result, MYSQL_BOTH);
    }

    public function getInsertId()
    {
        return mysql_insert_id($this->conn);
    }

    public function getRowsNum($result)
    {
        return @mysql_num_rows($result);
    }

    public function getAffectedRows()
    {
        return mysql_affected_rows($this->conn);
    }

    public function close()
    {
        mysql_close($this->conn);
    }

    public function freeRecordSet($result)
    {
        return mysql_free_result($result);
    }

    public function error()
    {
        return @mysql_error();
    }

    public function errno()
    {
        return @mysql_errno();
    }

    public function quoteString($str)
    {
        $str = "'" . str_replace('\\"', '"', addslashes($str)) . "'";
        return $str;
    }

    public function &queryF($sql, $limit = 0, $start = 0)
    {
        if (!empty($limit)) {
            if (empty($start)) {
                $start = 0;
            }

            $sql = $sql . ' LIMIT ' . (int)$start . ', ' . (int)$limit;
        }

        // Only variables should be assigned by reference
        $result = mysql_query($sql, $this->conn);

        if (!$result) {

            // BUG 2793: Fatal error: Call to undefined function: _print_sql_error()
            // wrong function name
            $this->_print_error($sql);

            // Notice: Only variable references should be returned by reference
            $false = false;
            return $false;
        }

        return $result;
    }

    public function &query($sql, $limit = 0, $start = 0)
    {
        return $this->queryF($sql, $limit, $start);
    }

    public function setPrefix($value)
    {
        $this->prefix = $value;
    }

    public function prefix($tablename = '')
    {
        if ($tablename != '') {
            return $this->prefix . '_' . $tablename;
        } else {
            return $this->prefix;
        }
    }

    //---------------------------------------------------------
    // debug
    //---------------------------------------------------------
    public function _print_error($sql = '')
    {
        if (!$this->flag_print_error) {
            return;
        }

        if ($sql) {
            echo "sql: $sql <br />\n";
        }

        echo "<font color='red'>" . $this->error() . "</font><br />\n";
    }

    //---------------------------------------------------------
}
