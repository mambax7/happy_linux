<?php

namespace XoopsModules\Happylinux;

// $Id: xoops_database.php,v 1.3 2007/09/23 08:26:54 ohwada Exp $

// 2007-09-20 K.OHWADA
// Assigning the return value of new by reference is deprecated

// 2006-07-10 K.OHWADA
// this is new file
// porting from class.database.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

//=========================================================
// class Database
// substitute for class XOOPS Database
// this class work for PHP 4
// in PHP 5, occur stric error
//=========================================================

/**
 * Class Database
 * @package XoopsModules\Happylinux
 */
class Database
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \XoopsModules\Happylinux\DatabaseMysql
     */
    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            // Assigning the return value of new by reference is deprecated
            $instance = new DatabaseMysql();
            if (!$instance->connect()) {
                echo "<font color='red'>Unable to connect to database.</font><br>\n";
                die();
            }
        }

        return $instance;
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
