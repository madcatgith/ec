<?

$include = @include("../../admin_top.php");
if (!$include or $adm_wellcome != "Y")
    exit;
$include = include("../../hierarchyhelpers/treeBuilder.php");

if (isset($_GET['item_id']))
    $parent_id = $_GET['item_id'];
else
    $parent_id = 0;

if ($move == "up" or $move == "down") {
    if ($move == "up")
        $move_num = -1;
    if ($move == "down")
        $move_num = 1;

    $select_product = DB::Query("select * from `?_product` where id=$id ");
    $get_product    = DB::GetArray($select_product);

    $change_place1 = DB::Query("update `?_product` set place=place-$move_num where place=$get_product[place]+$move_num and category_id='$get_product[category_id]'");
    $change_place2 = DB::Query("update `?_product` set place=place+$move_num where id=$id");
}


if (!empty($edit_places)) {
    $product_place_keys = @array_keys($product_place);
    while ($id                 = @array_shift($product_place_keys)) {
        $update        = DB::Query("update `?_product` set place=$product_place[$id] where id=$id ");
        if (!$update)
            $sys_message[] = Dictionary::GetAdminWord(896);
        else
            $sys_message[] = Dictionary::GetAdminWord(897);
    }
}

if ($delete == "yes") {
    $select_product = DB::Query("select * from `?_product` where id=$id ");
    $get_product    = DB::GetArray($select_product);
    $update_places  = DB::Query("update `?_product` set place=place-1 where place>$get_product[place] and category_id=$get_product[category_id]");

    $delete_product = DB::Query("delete from `?_product` where id=$id ");
    $delete_banners = DB::Query("delete from `?_banners` where link_to='Product ID: $id' ");

    if (!$delete_product)
        $sys_message[] = Dictionary::GetAdminWord(898);
    else
        $sys_message[] = Dictionary::GetAdminWord(899);
}

if (!empty($change_category) or !empty($delete_one) or !empty($edit_one) or !empty($new_category_id)) {
    include "product_change_category.php";

    if (empty($go_away)) {
        exit;
    }
}

include "product_catalogue_shapka.php";

// Заголовок
echo admin_func_top(Dictionary::GetAdminWord(900));

// Вывод сообщений про обработанность различных действий
echo admin_func_sys_message($sys_message);

$wmpTree                 = new wmpTree();
$wmpTree->BranchesSelect = wmpTree::$PreDefSql['productBranches'];
$wmpTree->ShowLeaves     = false;
$treeBody                = $wmpTree->func_items_tree("item_id", "/admin/lib/wmpProduct/products.php", "&nbsp;&nbsp;", Dictionary::GetAdminWord(231), ""
        , "width:300px; float:left; font-size:9px;", array($parent_id), false, '');

echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#a5cd38">
         <tr>
             <td width="300" class="td_left" style="vertical-align: top; border: 1px solid #a5cd38;">
                 <table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td style="padding: 14px 4px 4px 4px; border-bottom: 2px solid #213866;">
                <form action="/admin/lib/wmpProduct/products.php?oper=search" method="post">';
echo Dictionary::GetAdminWord(64) . " " . admin_func_right_input("text", "search", $search, "100", "") . " " . admin_func_right_input("submit", "", Dictionary::GetAdminWord(341), "", "1");
echo '</form></td></tr><tr><td style="padding: 0;">';
echo $treeBody;
echo '          </td></tr></table>
            </td>
            <td style="vertical-align: top; background: #fff;">';

echo admin_func_right_table_start(0);

echo admin_func_right_table_start(3);
echo admin_func_right_table_row_start(3);
echo "<td class=\"w\" colspan=2 height=\"25\">&nbsp; ";

if (0) {
    echo "<select onChange='javascript: document.location=this.value' style=\"font-size:9px\">";
    echo "<option value=\"products.php?category_id=0\" >" . Dictionary::GetAdminWord(112) . "";

    $max_level = Registry::get('maxMenuLevel');
    unset($eval_array);
    for ($i = $max_level; $i >= 1; $i--) {
        $next           = $i + 1;
        unset($temp_plus);
        if ($i != $max_level)
            $temp_plus      = $eval_array[$next];
        $eval_array[$i] = "\n" .
                "\$select_category" . $i . " = DB::Query(\"select * from `?_product_category` where category_id=\$pre" . $i . "_id and lang_id='" . Lang::getID() . "' order by place,id \");\n" .
                "\n" .
                "\n" .
                "while(\$get_category" . $i . " = DB::GetArray(\$select_category" . $i . "))\n" .
                "{\n" .
                "  \$temp = \"\$get_category" . $i . "[id]\";\n" .
                "  unset(\$selected);\n" .
                "  if(\$temp == \$category_id) \$selected = \"selected\";\n" .
                "  \n" .
                "  echo \"<option value=\\\"products.php?category_id=\$temp\\\" \$selected>" . str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $i - 1) . "\";\n" .
                "  echo \"\$get_category" . $i . "[title]\";\n" .
                "  \$pre" . ($i + 1) . "_id = \$get_category" . $i . "[id];\n" .
                "  $temp_plus\n" .
                "}";
    }

    $pre1_id = 0;
    eval($eval_array[1]);
    echo "</select>";
}

echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data("<IMG src=\"/admin/g/p.gif\" width=1 height=1 border=0>", "", 7);
echo admin_func_right_table_end();

echo admin_func_right_table_start("");
echo "<form action=products.php method=post>$formauthorization";
echo admin_func_right_table_row_start("");
echo admin_func_right_table_data(Dictionary::GetAdminWord(178), "100%", "");
echo admin_func_right_table_data(Dictionary::GetAdminWord(478), "", "");
echo admin_func_right_table_data(Dictionary::GetAdminWord(350), "", "");
echo admin_func_right_table_data(Dictionary::GetAdminWord(479), "", "");
echo admin_func_right_table_data("<img src=/admin/g/v.gif width=11 height=10 border=0>", $width, $type);

if (empty($search)) {
    $select_category = DB::Query(
                    "select * 
            from `?_product_category` 
            where id='{$parent_id}' 
      ");
    $get_category    = DB::GetArray($select_category);

    $select_max_place = DB::Query(
                    "select max(place)+0 
            from `?_product` 
            where category_id='{$get_category['id']}' 
      ");
    $max_place        = @array_shift(DB::GetArray($select_max_place));

    $select_fm     = DB::Query(
                    "select count(*)+0 
            from `?_product_category` 
            where category_id='{$get_category['id']}' 
      ");
    $sub_categorys = @array_shift(DB::GetArray($select_fm));

    if (empty($get_category['title']))
        $get_category[title] = Dictionary::GetAdminWord(296);

    echo admin_func_right_table_row_start(1);
    echo "<td><nobr>";
    if ($sub_categorys > 0)
        echo admin_func_right_link("<img src=/admin/g/f2.gif width=12 height=9 border=0> &nbsp;</span> $get_category[title]", "products.php?category_id=$get_category[id]", "", "");
    else
        echo "<img src=/admin/g/f.gif width=15 height=9 border=0> $get_category[title]";
    echo "</nobr>";
    echo admin_func_right_table_data(admin_func_right_link("<span style=\"font-size:10px;color=orange;text-decoration:none\">добавить продукт</span> <img src=\"/admin/g/n.gif\" border=0 alt=\"" . Dictionary::GetAdminWord(901) . "\" width=11 height=12>&nbsp;", "product_add.php?category_id=$get_category[id]", "", ""), "", "7r");

    echo "<td colspan=2 align=right><nobr>";
}
if (!empty($search))
    $additional_for_db = "concat(lower(title),' ',lower(text),' ',lower(price),' ',lower(field_name1),' ',lower(field_name2),' ',lower(field_name3),' ',lower(field_name4),' ',lower(field_value1),' ',lower(field_value2),' ',lower(field_value3),' ',lower(field_value4)) like '%$search%'";
else
    $additional_for_db = "category_id=$get_category[id]";

$select_product = DB::Query("select * from `?_product` where $additional_for_db order by place,lang_id ");
while ($get_product    = DB::GetArray($select_product)) {
    if ($get_product[another_page] != "")
        $get_product[title] = $get_product[another_page];

    if (!@in_array($get_product[id], $array_product_ids)) {
        $array_products[]       = $get_product[title];
        $array_product_ids[]    = $get_product[id];
        $array_product_places[] = $get_product[place];
    }
}

while (count($array_product_ids) > 0) {
    $get_product[id]    = @array_shift($array_product_ids);
    $id                 = $get_product[id];
    $get_product[title] = @array_shift($array_products);
    $get_product[place] = @array_shift($array_product_places);

    echo admin_func_right_table_row_start(2);
    echo "<td>&nbsp;&nbsp;";
    if (count($array_product_ids) > 0)
        echo "<span style='font-family:Arial'>&#9500;</span>";
    else
        echo "<span style='font-family:Arial'>&#9492;</span>";
    echo " $get_product[title]";
    echo "<td>";

    $langs_array = "";
    @array_pop($langs_array);
    $select_c    = DB::Query("select lang_id from `?_product` where id=$get_product[id] order by lang_id ");
    while ($get_c       = DB::GetArray($select_c)) {
        $select_l      = DB::Query("select title_short from `?_lang` where id=$get_c[lang_id] ");
        $langs_array[] = @array_shift(DB::GetArray($select_l));
    }

    echo "<nobr style=\"font-size:10px;\">" . @implode(", ", $langs_array) . "</nobr>";

    echo admin_func_right_table_data(admin_func_right_link("<img src=\"/admin/g/e.gif\" border=0 alt=\"" . Dictionary::GetAdminWord(371) . "\" width=10 height=12>", "product_edit.php?id=$get_product[id]", "", "") . "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;" . admin_func_right_link("<img src=\"/admin/g/d.gif\" border=0 alt=\"" . Dictionary::GetAdminWord(354) . "\" width=12 height=12 onclick=\"return confirm('" . Dictionary::GetAdminWord(902) . "');\">", "products.php?category_id=$category_id&id=$get_product[id]&delete=yes", "", ""), "", 5);

    echo "<td><nobr>";
    /*
      if($get_product[place] > 1) echo "&nbsp;<a href=products.php?category_id=$category_id&id=$get_product[id]&move=up><img src=\"g/1.gif\" border=0 alt=\"". Dictionary::GetAdminWord(827) ."\" width=11 height=12></a>&nbsp;&nbsp;";
      else echo "<div align=right>";
      if($get_product[place] < $max_place) echo "&nbsp;&nbsp;<a href=products.php?category_id=$category_id&id=$get_product[id]&move=down><img src=\"g/2.gif\" border=0 alt=\"". Dictionary::GetAdminWord(828) ."\" width=11 height=12></a>&nbsp;";
      else echo "&nbsp;"; */

    echo admin_func_right_input("", "product_place[$id]", $get_product[place], 50, 3);

    echo "</nobr>";
    echo admin_func_right_table_data(admin_func_right_input("checkbox", "ids[]", $get_product[id], "", 3), "", 2);
}
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data(Dictionary::GetAdminWord(439) . admin_func_right_input("submit", "change_category", Dictionary::GetAdminWord(903), "", 1) . admin_func_right_input("submit", "delete_one", Dictionary::GetAdminWord(441), "", 5) . admin_func_right_input("hidden", "category_id", $category_id, "", 3) . admin_func_right_input("hidden", "search", $search, "", 3) . admin_func_right_input("submit", "edit_places", Dictionary::GetAdminWord(484), "", 1), "", "11r");


echo admin_func_right_table_end();

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
?>