<?php

// $Id: xoops_database_php5.php,v 1.1 2010/11/07 14:59:21 ohwada Exp $

// 2007-09-20 K.OHWADA
// PHP5.2
// modify from xoops_database.php()

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

//=========================================================
// class Database
// substitute for class XOOPS Database
// this class work only for PHP 5
//=========================================================

/**
 * Class Database
 */
class Database
{
    private static $_singleton = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \mysql_database|null
     */
    public static function getInstance()
    {
        if (null == self::$_singleton) {
            $singleton = new mysql_database();
            if (!$singleton->connect()) {
                echo "<font color='red'>Unable to connect to database.</font><br>\n";
                die();
            }
            self::$_singleton = $singleton;
        }

        return self::$_singleton;
    }

    //---------------------------------------------------------
    // function
    //---------------------------------------------------------

    /**
     * @param string $tablename
     * @return string
     */
    public function prefix($tablename = '')
    {
        if ('' != $tablename) {
            return XOOPS_DB_PREFIX . '_' . $tablename;
        }

        return XOOPS_DB_PREFIX;
    }

    //---------------------------------------------------------
}
