<?php

$include = include_once($_SERVER['DOCUMENT_ROOT'] . "/admin/admin_top.php");

if( ! $include or $adm_wellcome != "Y") 
	exit;

$lang_array = Lang::getLanguages();
$parent_cat = 1;

if (isset($_GET['id']))
	$parent_cat = (int) $_GET['id'];

$select_gallery = DB::Query("SELECT * FROM ?_gallery_category WHERE id=" . $parent_cat . " LIMIT 1");

if ($get = DB::GetArray($select_gallery)) {
	$category = $get;
	echo admin_func_top('Управление галереей ' . $get['title']);
	echo admin_func_sys_message($sys_message);
} else
	die('Не выбрана галерея или галерея уже не существует.');
	
$wmpTree                 = new wmpTree();
$wmpTree->leafLink       = '/admin/lib/wmpGallery/controller.php?action=show&id={id}';
$wmpTree->BranchesSelect = wmpTree::$PreDefSql['galleryBranches'];
$wmpTree->ShowLeaves     = false;
$treeBody                = $wmpTree->func_items_tree("item_id", "gallery.php", "&nbsp;&nbsp;", Dictionary::GetAdminWord(231), "", "width:300px; float:left; font-size:9px;", array($category['id']));

echo '<table width="100%" cellspacing="0" cellpadding="0" border="0">';
echo '<tr><td style="padding: 0; vertical-align: top; width: 300px;">';
echo $treeBody;
echo '</td><td style="vertical-align: top; border-left: 2px solid #000;">';

switch (isset($_REQUEST['action']) ? $_REQUEST['action'] : '') {
	case 'show':
	default:
		$gallery = new JQGallery($parent_cat);
		echo $gallery->show();
	break;
}

echo '</td></tr></table>';

include BASEPATH . "admin/admin_footer.php";