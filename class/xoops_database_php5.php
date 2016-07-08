<?php
// $Id: xoops_database_php5.php,v 1.1 2007/09/23 08:27:34 ohwada Exp $

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

    public static function getInstance()
    {
        if (Database::$_singleton == null) {
            $singleton = new mysql_database();
            if (!$singleton->connect()) {
                echo "<font color='red'>Unable to connect to database.</font><br />\n";
                die();
            }
            Database::$_singleton = $singleton;
        }
        return Database::$_singleton;
    }

    //---------------------------------------------------------
    // function
    //---------------------------------------------------------
    public function prefix($tablename = '')
    {
        if ($tablename != '') {
            return XOOPS_DB_PREFIX . '_' . $tablename;
        } else {
            return XOOPS_DB_PREFIX;
        }
    }

    //---------------------------------------------------------
}
