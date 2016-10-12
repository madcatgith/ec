<?php

$include = @include("../../admin_top.php");

if (!$include or $adm_wellcome != "Y") {
    exit;
}

$include = require_once ("../../hierarchyhelpers/treeBuilder.php");

if (isset($_REQUEST['item_id']) && $_REQUEST['item_id'] > 0) {
    $parent_id = $_REQUEST['item_id'];
} else {
    $parent_id = 3;
}

echo admin_func_sys_message($sys_message);
echo admin_func_top(Dictionary::GetAdminWord(900));

$wmpTree                 = new wmpTree();
$wmpTree->BranchesSelect = wmpTree::$PreDefSql['productBranches'];
$wmpTree->ShowLeaves     = false;

$parents = array();
$query   = DB::Query('select id, menu_id from ?_menu where lang_id=1');

while ($r = DB::GetArray($query)) {
    if (!isset($parents[$r['menu_id']])) {
        $parents[$r['menu_id']] = array();
    }
    $parents[$r['menu_id']][] = $r['id'];
}

$treeBody = $wmpTree->func_items_tree("item_id", "/admin/lib/wmpProduct/catalogue.php", "&nbsp;&nbsp;", Dictionary::GetAdminWord(231), "", "width:300px; float:left; font-size:9px;", array($parent_id), false, null, ' and id in (' . getIDs($parents, 3) . ')');
echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#a5cd38"><tr><td width="300" class="td_left" style="vertical-align: top; border: 1px solid #a5cd38;"><table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td style="padding: 14px 4px 4px 4px; border-bottom: 2px solid #213866;"><form action="/admin/lib/wmpProduct/catalogue.php?oper=search" method="post">';
echo Dictionary::GetAdminWord(64) . " " . admin_func_right_input("text", "search", $search, "100", "") . " " . admin_func_right_input("submit", "", Dictionary::GetAdminWord(341), "", "1");
echo '</form></td></tr><tr><td style="padding: 0;">';
echo $treeBody;
echo '</td></tr></table></td><td style="vertical-align: top; background: #fff;">';

echo admin_func_right_table_start(0);

// продукты
require_once ("productsList.php");
// конец центрального блока 

echo "<br>";
echo "<table border=0 cellspacing=5 cellpadding=0 width=600>";
echo "<tr bgcolor=ffffff>";
echo "<td colspan=2>";
echo "<b>" . Dictionary::GetAdminWord(356) . "</b>";
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"/admin/g/n.gif\" border=0 alt=\"" . Dictionary::GetAdminWord(901) . "\" width=11 height=12>";
echo "<td>";
echo Dictionary::GetAdminWord(318);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"/admin/g/e.gif\" border=0 alt=\"" . Dictionary::GetAdminWord(371) . "\" width=10 height=12>";
echo "<td>";
echo Dictionary::GetAdminWord(626);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"/admin/g/d.gif\" border=0 alt=\"" . Dictionary::GetAdminWord(354) . "\" width=12 height=12>";
echo "<td>";
echo Dictionary::GetAdminWord(905);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=/admin/g/f2.gif width=12 height=9 border=0>";
echo "<td>";
echo Dictionary::GetAdminWord(877);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=/admin/g/f.gif width=15 height=9 border=0>";
echo "<td>";
echo Dictionary::GetAdminWord(878);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"/admin/g/1.gif\" border=0 alt=\"" . Dictionary::GetAdminWord(827) . "\" width=11 height=12>";
echo "<td>";
echo Dictionary::GetAdminWord(906);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"/admin/g/2.gif\" border=0 alt=\"" . Dictionary::GetAdminWord(828) . "\" width=11 height=12>";
echo "<td>";
echo Dictionary::GetAdminWord(907);
echo "</table>";

$select_config = DB::Query("select education from `?_config` ");
if (@array_shift(DB::GetArray($select_config)) == "0") {

    echo "<br><br>";
    echo "<table border=0 cellspacing=5 cellpadding=0 width=600>";
    echo "<tr bgcolor=ffffff>";
    echo "<td colspan=2>";
    echo "<b>" . Dictionary::GetAdminWord(246) . "</b>";
    echo "<tr bgcolor=ffffff>";
    echo "<td>";

    echo "<table cellspacing=0 cellpadding=0 border=0>";
    echo "<tr bgcolor=627080 height=22>";
    echo "<td class=\"w\"><nobr>&nbsp; ";
    echo "<a href=products.php class=\"w\">" . Dictionary::GetAdminWord(231) . "</a> <font style=\"font-size:15px\"><b>&raquo;</b>&nbsp;";
    echo "</nobr></table>";

    echo "<td>";
    echo Dictionary::GetAdminWord(908);
    echo "<tr bgcolor=ffffff>";
    echo "<td>";
    echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
    echo "<tr bgcolor=E8E8E8 height=22>";
    echo "<th class=\"top\"><nobr>&nbsp;" . Dictionary::GetAdminWord(178) . "&nbsp;</nobr></th>";
    echo "</table>";
    echo "<td>";
    echo Dictionary::GetAdminWord(909);
    echo "<tr bgcolor=ffffff>";
    echo "<td>";
    echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
    echo "<tr bgcolor=E8E8E8 height=22>";
    echo "<th class=\"top\"><nobr>&nbsp;" . Dictionary::GetAdminWord(478) . "&nbsp;</nobr></th>";
    echo "</table>";
    echo "<td>";
    echo Dictionary::GetAdminWord(910);
    echo "<tr bgcolor=ffffff>";
    echo "<td>";
    echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
    echo "<tr bgcolor=E8E8E8 height=22>";
    echo "<th class=\"top\"><nobr>&nbsp;" . Dictionary::GetAdminWord(350) . "&nbsp;</nobr></th>";
    echo "</table>";
    echo "<td>";
    echo Dictionary::GetAdminWord(911);
    echo "<tr bgcolor=ffffff>";
    echo "<td>";
    echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
    echo "<tr bgcolor=E8E8E8 height=22>";
    echo "<th class=\"top\"><nobr>&nbsp;" . Dictionary::GetAdminWord(479) . "&nbsp;</nobr></th>";
    echo "</table>";
    echo "<td>";
    echo Dictionary::GetAdminWord(912);
    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080 height=22>";
    echo "<tr bgcolor=E8E8E8>";
    echo "<th class=\"top\"><nobr>&nbsp;<img src=/admin/g/v.gif width=11 height=10 border=0>&nbsp;</nobr></th>";
    echo "</table>";
    echo "<td>";
    echo "" . Dictionary::GetAdminWord(913) . "<br> &nbsp;&nbsp;" . Dictionary::GetAdminWord(650) . "";
    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<input type=submit class=button value=\"" . Dictionary::GetAdminWord(495) . "\">";
    echo "<td>";
    echo Dictionary::GetAdminWord(914);
    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<input type=submit class=button value=\"" . Dictionary::GetAdminWord(441) . "\">";
    echo "<td>";
    echo Dictionary::GetAdminWord(915);
    echo "</table>";
}

include("../../admin_footer.php");