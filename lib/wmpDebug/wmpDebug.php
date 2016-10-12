<?php
  
class Debug
{
	private static $_errors = array(
		'sql'   => array()
		, 'php' => array()
	);
	
	public static function addSQLError($error, $query)
	{
		self::$_errors['sql'][] = '<b>Error</b> : <i>' . $error . '</i><br /><b>Query</b> : <i>' . $query . '</i>';
	}
	
	public static function hasSQLErrorrs()
	{
		return (bool) (count(self::$_errors['sql']));
	}
	
	public static function getSQLErrorrs()
	{
		return '<ul class="debugSql"><li>' . implode('</li><li>', self::$_errors['sql']) . '</li></ul>';
	}
	
	public static function handler($type, $message, $file, $line, $scope = array())
	{
		// пока просто записываем
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/errors.log', "Type : {$type}\nMessage : {$message}\nFile : {$file}\nLine : {$line}\nScope : {$scope}\n------------\n", FILE_APPEND);
	}
	
	public static function shutdown()
	{
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/errors.log', print_r(error_get_last(), 1) . "\n------------\n", FILE_APPEND);
	}
        
        public static function console_log($data='')
        {
            echo '<script>';
            echo 'console.log('. json_encode($data) .')';
            echo '</script>';
        }

}

// set_error_handler('Debug::handler', E_ALL); 
// register_shutdown_function('Debug::shutdown');