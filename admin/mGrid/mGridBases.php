<?php

$include = @include("../admin_top.php");
if(!$include or $adm_wellcome != "Y") exit;

global $bases;
$bases = $_GET['db'];
if (!isset($bases)) {
	echo 'не выбрана ни одна база данных для редактирования!';
	exit;
}
// для дебага
if (isset ($_GET['debug']) && $_GET['debug'] = 1) 
    error_reporting(E_ALL);
    
//Подключение конфигурационного файла
$path = BASEPATH."/admin/mGrid/mGridTable".$bases.".php";
if (file_exists(BASEPATH."/admin/mGrid/mGridTable".$bases.".php"))
  include BASEPATH."/admin/mGrid/mGridTable".$bases.".php";
else
  die("
    <div style='color: #A77777; padding: 20px;'>
      File {$path} does not exist!<br />\n
      Script work is stopped
    </div>
  ");
 
$lang = $from['lang'];
if (isset($_GET['lang'])) {
	$lang = intval($_GET['lang']);
}

include BASEPATH."/admin/mGrid/engine/mGridGenerate.php"; 