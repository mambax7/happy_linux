<?php

namespace XoopsModules\Happylinux;

// $Id: DatabaseMysql.php,v 1.5 2007/09/23 08:26:54 ohwada Exp $

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
// class DatabaseMysql
// substitute for class XOOPS XoopsMySQLDatabase
//=========================================================

//---------------------------------------------------------
// TODO
// call connect twice by config and link
//---------------------------------------------------------

/**
 * Class DatabaseMysql
 * @package XoopsModules\Happylinux
 */
class DatabaseMysql extends Database
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
        $this->conn = mysql_connect(XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS);

        if (!$this->conn) {
            $this->_print_error();

            return false;
        }

        if (!mysqli_select_db($GLOBALS['xoopsDB']->conn, XOOPS_DB_NAME)) {
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
        if ($this->_is_mysql_ver5() && defined('HAPPYLINUX_MYSQL_CHARSET')) {
            $sql = 'SET NAMES ' . HAPPYLINUX_MYSQL_CHARSET;
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
        $ver = $GLOBALS['xoopsDB']->getServerVersion();
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
     * @return mixed
     */
    public function fetchRow($result)
    {
        return @$GLOBALS['xoopsDB']->fetchRow($result);
    }

    /**
     * @param $result
     * @return mixed
     */
    public function fetchArray($result)
    {
        return @$GLOBALS['xoopsDB']->fetchArray($result);
    }

    /**
     * @param $result
     * @return mixed
     */
    public function fetchBoth($result)
    {
        return @$GLOBALS['xoopsDB']->fetchBoth($result, MYSQL_BOTH);
    }

    /**
     * @return mixed
     */
    public function getInsertId()
    {
        return $GLOBALS['xoopsDB']->getInsertId($this->conn);
    }

    /**
     * @param $result
     * @return mixed
     */
    public function getRowsNum($result)
    {
        return @$GLOBALS['xoopsDB']->getRowsNum($result);
    }

    /**
     * @return mixed
     */
    public function getAffectedRows()
    {
        return $GLOBALS['xoopsDB']->getAffectedRows($this->conn);
    }

    public function close()
    {
        $GLOBALS['xoopsDB']->close($this->conn);
    }

    /**
     * @param $result
     * @return mixed
     */
    public function freeRecordSet($result)
    {
        return $GLOBALS['xoopsDB']->freeRecordSet($result);
    }

    /**
     * @return mixed
     */
    public function error()
    {
        return @$GLOBALS['xoopsDB']->error();
    }

    /**
     * @return mixed
     */
    public function errno()
    {
        return @$GLOBALS['xoopsDB']->errno();
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
     * @return bool
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
        $result = $GLOBALS['xoopsDB']->queryF($sql, $this->conn);

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
     * @return bool
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
