<?php

// $Id: xoops_mysql_database.php,v 1.1 2010/11/07 14:59:18 ohwada Exp $

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

/**
 * Class mysql_database
 */
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

    /**
     * @return bool
     */
    public function connect()
    {
        $this->conn = mysqli_connect(XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS);

        if (!$this->conn) {
            $this->_print_error();

            return false;
        }

        if (!mysqli_select_db($this->conn, XOOPS_DB_NAME)) {
            $this->_print_error();

            return false;
        }

        if (!$this->_set_charset()) {
            $this->_print_error();

            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
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

    /**
     * @return bool
     */
    public function _is_mysql_ver5()
    {
        $ver = mysqli_get_server_info($this->conn);
        if (preg_match("/^4\.1/", $ver)) {
            return true;
        }
        if (preg_match("/^5\./", $ver)) {
            return true;
        }

        return false;
    }

    /**
     * @param $result
     * @return array|null
     */
    public function fetchRow($result)
    {
        return @mysqli_fetch_row($result);
    }

    /**
     * @param $result
     * @return string[]|null
     */
    public function fetchArray($result)
    {
        return @mysqli_fetch_assoc($result);
    }

    /**
     * @param $result
     * @return array|null
     */
    public function fetchBoth($result)
    {
        return @mysqli_fetch_array($result, MYSQLI_BOTH);
    }

    /**
     * @return int|string
     */
    public function getInsertId()
    {
        return mysqli_insert_id($this->conn);
    }

    /**
     * @param $result
     * @return int
     */
    public function getRowsNum($result)
    {
        return @mysqli_num_rows($result);
    }

    /**
     * @return int
     */
    public function getAffectedRows()
    {
        return mysqli_affected_rows($this->conn);
    }

    public function close()
    {
        mysqli_close($this->conn);
    }

    /**
     * @param $result
     */
    public function freeRecordSet($result)
    {
        return mysqli_free_result($result);
    }

    /**
     * @return string
     */
    public function error()
    {
        return @mysqli_error($this->conn);
    }

    /**
     * @return int
     */
    public function errno()
    {
        return @mysqli_errno($this->conn);
    }

    /**
     * @param $str
     * @return string
     */
    public function quoteString($str)
    {
        $str = "'" . str_replace('\\"', '"', addslashes($str)) . "'";

        return $str;
    }

    /**
     * @param     $sql
     * @param int $limit
     * @param int $start
     * @return bool|\mysqli_result
     */
    public function &queryF($sql, $limit = 0, $start = 0)
    {
        if (!empty($limit)) {
            if (empty($start)) {
                $start = 0;
            }

            $sql = $sql . ' LIMIT ' . (int)$start . ', ' . (int)$limit;
        }

        // Only variables should be assigned by reference
        $result = mysqli_query($this->conn, $sql);

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

    /**
     * @param     $sql
     * @param int $limit
     * @param int $start
     * @return bool|\mysqli_result
     */
    public function &query($sql, $limit = 0, $start = 0)
    {
        return $this->queryF($sql, $limit, $start);
    }

    /**
     * @param $value
     */
    public function setPrefix($value)
    {
        $this->prefix = $value;
    }

    /**
     * @param string $tablename
     * @return string
     */
    public function prefix($tablename = '')
    {
        if ('' != $tablename) {
            return $this->prefix . '_' . $tablename;
        }

        return $this->prefix;
    }

    //---------------------------------------------------------
    // debug
    //---------------------------------------------------------

    /**
     * @param string $sql
     */
    public function _print_error($sql = '')
    {
        if (!$this->flag_print_error) {
            return;
        }

        if ($sql) {
            echo "sql: $sql <br>\n";
        }

        echo "<font color='red'>" . $this->error() . "</font><br>\n";
    }

    //---------------------------------------------------------
}
