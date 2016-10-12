<?php

class cPDO extends PDO {
    
    private $queryCount = 0;
    private $log = array();
    protected $_table_prefix = null;
    protected $_table_suffix = null;

    public function __construct($dsn, $user = null, $password = null, $driver_options = array(), $prefix = null, $suffix = null)
    {
        $this->_table_prefix = $prefix;
        $this->_table_suffix = $suffix;
        parent::__construct($dsn, $user, $password, $driver_options);
    }    
    
    public function query($statement)
    {
        $this->setLog(debug_backtrace());  
        ++$this->queryCount;
        $statement = $this->_tablePrefixSuffix($statement);
        $query = parent::query($statement);
        
        if(!is_object($query)) {
            if(IS_WMP) {
                _d(array($this->errorInfo(), $statement));
            }
            die('Database error.');
        }
        
        return $query;
    }

    public function exec($statement)
    {        
        ++$this->queryCount;
        $statement = $this->_tablePrefixSuffix($statement);
        return parent::exec($statement);
    }
    
    public function execute($input_parameters = array())
    {
        ++$this->queryCount;
        return parent::execute($input_parameters);
    }
    
    public function prepare($statement, $driver_options = array())
    {
        $this->setLog(debug_backtrace());        
        $statement = $this->_tablePrefixSuffix($statement);
        return parent::prepare($statement, $driver_options);
    }

    public function getCount()
    {
        return $this->queryCount;
    }   
    
    public function getLog()
    {
        return $this->log;
    }
    
    public function setLog($debug_backtrace = array())
    {
        if(!empty($debug_backtrace) && is_array($debug_backtrace) && !empty($debug_backtrace[0])) {
            $this->log[] = array_shift($debug_backtrace);
        }        
    }
    
    protected function _tablePrefixSuffix($statement)
    {
        return str_replace($this->_table_suffix, $this->_table_prefix, $statement);
    }
}


class DB
{

    public static $host;
    public static $user;
    public static $pass;
    public static $dbname;
    public static $table_prefix     = '';
    public static $mysql_version    = '';
    public static $mysql_error;
    public static $mysql_error_num;
    public static $connection;
    public static $connected        = true;
    public static $query_id;
    public static $queries_count    = 0;
    public static $MySQL_time_taken = 0;
    
    public static $all_queries = array();

    public static function Connect($showErrors = true)
    {
        if (!self::$connection = @mysql_connect(self::$host, self::$user, self::$pass)) {
            if ($showErrors) {
                self::DisplayError(mysql_error(), mysql_errno());
            } else {
                return false;
            }
        }

        if (!@mysql_select_db(self::$dbname, self::$connection)) {
            if ($showErrors) {
                self::DisplayError(mysql_error(), mysql_errno());
            } else {
                return false;
            }
        }

        self::$mysql_version = mysql_get_server_info();

        mysql_query('SET NAMES utf8;');

        if (!defined('COLLATE')) {
            define("COLLATE", "utf8");
        }

        return true;
    }

    public static function Query($query, $showErrors = false)
    {

        $time_before = self::GetRealTime();

        if (!self::$connected)
            self::Connect();

        $query = str_replace('?_', self::$table_prefix, $query);

        if (!(self::$query_id = mysql_query($query, self::$connection) )) {

            self::$mysql_error = mysql_error();
            self::$mysql_error_num = mysql_errno();

            Debug::addSQLError(self::$mysql_error, $query);

            if ($showErrors)
                self::DisplayError(self::$mysql_error, self::$mysql_error_num, $query);
        }

        self::$MySQL_time_taken += self::GetRealTime() - $time_before;

        self::$queries_count++;
        
        self::$all_queries[] = $query;

        return self::$query_id;
    }

    public static function GetRow($query_id = '')
    {
        if ($query_id == '')
            $query_id = self::$query_id;
        if (empty($query_id))
            return null;
        //      $e = new ErrorException();
        //      echo $e->getTraceAsString(). "<br /><br />\n\n\n";
        return mysql_fetch_assoc($query_id);
    }

    public static function GetArray($query_id = '')
    {
        if ($query_id == '')
            $query_id = self::$query_id;
        if (empty($query_id))
            return null;
        //      $e = new ErrorException();
        //      echo $e->getTraceAsString(). "<br /><br />\n\n\n";

        return mysql_fetch_assoc($query_id);
    }

    public static function SuperQuery($query, $isMulty = false, $showErrors = false, $debug = false)
    {
        if (!$isMulty) {
            self::Query($query, $showErrors, $debug);
            $data = self::GetRow(self::$query_id);
            self::Free(self::$query_id);
            return $data;
        } else {
            self::Query($query, $showErrors, $debug);

            $rows = array();
            while ($row  = self::GetRow(self::$query_id)) {
                $rows[] = $row;
            }

            self::Free(self::$query_id);

            return $rows;
        }
    }

    public static function Free($query_id = '')
    {
        if ($query_id == '')
            $query_id = self::$query_id;

        @mysql_free_result($query_id);
    }

    public static function GetRealTime()
    {
        list($seconds, $microSeconds) = explode(' ', microtime());
        return ((float) $seconds + (float) $microSeconds);
    }

    public static function DisplayError($error, $error_num, $query = '')
    {
        if ($query) {
            // Safify query
            $query     = preg_replace("/([0-9a-f]){32}/", "********************************", $query); // Hides all hashes
            $query_str = "$query";
        }

        echo '
            <?xml version="1.0" encoding="iso-8859-1"?>
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
            <title>MySQL Fatal Error</title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <style type="text/css">
            <!--
            body {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10px;
            font-style: normal;
            color: #000000;
            }
            -->
            </style>
            </head>
            <body>
            <font size="4">MySQL Error!</font>
            <br />------------------------<br />
            <br />

            <u>The Error returned was:</u>
            <br />
            <strong>' . $error . '</strong>

            <br /><br />
            </strong><u>Error Number:</u>
            <br />
            <strong>' . $error_num . '</strong>
            <br />
            <br />

            <textarea name="" rows="10" cols="52" wrap="virtual">' . $query_str . '</textarea><br />

            </body>
            </html>
            <?';

        exit();
    }

    static public function simpleLangQuery($table)
    {

        $data    = array();
        $slQuery = DB::Query('select c.*, cl.* from `?_' . $table . '` c, `?_' . $table . '_lang` cl where c.active and cl.id=c.id and cl.lang_id=' . Lang::getID());

        while ($row = DB::GetArray($slQuery)) {
            $data[$row['id']] = $row;
        }

        return $data;
    }

    static public function affectedRows()
    {
        return mysql_affected_rows();
    }

    static public function insertID()
    {
        return mysql_insert_id();
    }

}
