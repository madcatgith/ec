<?php

session_start();
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');
error_reporting(0);
ini_set('display_errors', 0);

define("DBHOST", 'localhost');
define("DBUSER", 'optfashion_def');
define("DBPASS", 'asdfg');
define("DBNAME", 'optfashion_ec');
define("DBPREFIX", 'wmp_');
define('_PREFIX', DBPREFIX);

define('BASEPATH', __DIR__ . '/');

define('MEDIA', '/files/medialibrary/');
define('MEDIA_PATH', BASEPATH . 'files/medialibrary/');
define('MEDIA_CACHE', BASEPATH . 'templates/medialibrary/');
define('RESIZE_PATH', BASEPATH . 'files/resize_cache/');

define('BASE_URL', 'http://' . getenv('HTTP_HOST'));
define('REPLY_EMAIL', 'noreply@' . str_replace('www.', '', getenv('HTTP_HOST')));

define('IS_WMP', (int) (in_array(getenv('REMOTE_ADDR'), array('91.197.146.98', '89.252.54.33'))));

require_once BASEPATH . 'helpers/helpers.php';

try {
    $db = new cPDO('mysql:host=' . DBHOST . ';dbname=' . DBNAME . ';charset=utf8', DBUSER, DBPASS, array(), DBPREFIX, '?_');
} catch(PDOException $ex) {
    die($ex->getMessage());
}

$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

Registry::set('db', $db);

include_once BASEPATH . '/lib/modules.php';

Lang::init();

/*
 * Дикий костыль
 */
if (mb_strpos(getenv('SCRIPT_FILENAME'), 'admin/mGrid') !== false) {
    if(!isset(Lang::$langArray[1])) {
        Lang::$langArray[1] = Registry::get('db')->query('select l.*, l.http_accept_language cnc from ?_lang l where id = 1 limit 1')->fetch();
    }
}

# пока пусть живет
$files_folder = '/files';
Registry::set('maxMenuLevel', 6);

# Информация для подключения к MySQL:
DB::$host = DBHOST;   # хост подключения
DB::$user = DBUSER;   # имя юзера
DB::$pass = DBPASS;   # пароль
DB::$dbname = DBNAME;   # имя Базы Данных
DB::$table_prefix = DBPREFIX; # префикс	

if (mb_strpos(getenv('SCRIPT_FILENAME'), 'admin/') !== false) {
    DB::Connect();
}