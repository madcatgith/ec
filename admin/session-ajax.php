<?

include "../ajax-post.php";
$_GET = Config::$ajaxPost->clean($_GET);
$_POST = Config::$ajaxPost->clean($_POST);

include("../config.php");

?>