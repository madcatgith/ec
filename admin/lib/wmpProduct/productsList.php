<?

if (!$include or $adm_wellcome != "Y") {
    exit;
}

if ($move == "up" or $move == "down") {
    if ($move == "up") {
        $move_num = -1;
    }
    if ($move == "down") {
        $move_num = 1;
    }

    $select_product = DB::Query("select * from `?_product` where id=$id ");
    $get_product    = DB::GetArray($select_product);

    $change_place1 = DB::Query("update `?_product` set place=place-$move_num where place=$get_product[place]+$move_num and category_id='$get_product[category_id]'");
    $change_place2 = DB::Query("update `?_product` set place=place+$move_num where id=$id");
}

if (!empty($edit_places)) {

    $product_place_keys = array_keys($product_place);

    while ($id = array_shift($product_place_keys)) {
        $update = DB::Query("update `?_product` set place=$product_place[$id] where id=$id ");
        if (!$update) {
            $sys_message[] = Dictionary::GetAdminWord(896);
        } else {
            $sys_message[] = Dictionary::GetAdminWord(897);
        }
    }
}

if ($delete == "yes" && $_REQUEST["delType"] == "product") {

    $select_product = DB::Query("select * from `?_product` where id={$id}");
    $get_product    = DB::GetArray($select_product);
    $update_places  = DB::Query("update `?_product` set place=place-1 where place>{$get_product['place']} and category_id={$get_product['category_id']}");
    $delete_product = DB::Query("delete from `?_product` where id={$id}");
    $delete_banners = DB::Query("delete from `?_banners` where link_to='Product ID: {$id}' ");

    if (!$delete_product) {
        $sys_message[] = Dictionary::GetAdminWord(898);
    } else {
        $sys_message[] = Dictionary::GetAdminWord(899);
    }
}

if (!empty($change_category) or !empty($delete_one) or !empty($edit_one) or !empty($new_category_id)) {

    include "product_change_category.php";

    if (empty($go_away)) {
        exit;
    }
}

echo admin_func_right_table_start(3);
echo admin_func_right_table_row_start(3);

$anch = '<a href="/admin/lib/wmpProduct/product_add.php?category_id=' . $parent_id . '" style="text-decoration:none; margin-left:20px;"><img src="/admin/g/nw.gif" border=0 alt="Добавить продукт" width=11 height=12></a>';

echo "<td class=\"w\" colspan=2 height=\"25\" style='padding-left: 20px; font-weight:bold; color:white;' > Продукты ";
echo $anch;
echo "</td></tr>";

echo admin_func_right_table_start("");
echo "<form action=catalogue.php method=post>$formauthorization";
echo admin_func_right_table_row_start("");
echo admin_func_right_table_data(Dictionary::GetAdminWord(178), "100%", "");
echo admin_func_right_table_data(Dictionary::GetAdminWord(478), "", "");
echo admin_func_right_table_data(Dictionary::GetAdminWord(350), "", "");
echo admin_func_right_table_data(Dictionary::GetAdminWord(479), "", "");
echo admin_func_right_table_data("<img src=/admin/g/v.gif width=11 height=10 border=0>", $width, $type);

if (empty($search)) {

    $select_category  = DB::Query("select * from `?_menu`where id='{$parent_id}' and lang_id=1");
    $get_category     = DB::GetArray($select_category);
    $select_max_place = DB::Query("select max(place)+0 from `?_product` where category_id='{$get_category['id']}'");
    $max_place        = array_shift(DB::GetArray($select_max_place));
    $select_fm        = DB::Query("select count(*)+0 from `?_menu` where menu_id='{$get_category['id']}' and lang_id=1");
    $sub_categorys    = array_shift(DB::GetArray($select_fm));

    if (empty($get_category['title'])) {
        $get_category[title] = Dictionary::GetAdminWord(296);
    }
}
if (!empty($search)) {
    $additional_for_db = "concat(lower(title),' ',lower(text),' ',lower(price),' ',lower(field_name1),' ',lower(field_name2),' ',lower(field_name3),' ',lower(field_name4),' ',lower(field_value1),' ',lower(field_value2),' ',lower(field_value3),' ',lower(field_value4)) like '%$search%'";
} else {
    $additional_for_db = "category_id={$get_category['id']}";
}

$select_product = DB::Query("select * from `?_product` where {$additional_for_db} order by place,lang_id ");

while ($get_product = DB::GetArray($select_product)) {
    if ($get_product['another_page'] != "") {
        $get_product['title'] = $get_product['another_page'];
    }
    if (!in_array($get_product['id'], $array_product_ids)) {
        $array_products[]       = $get_product['title'];
        $array_product_ids[]    = $get_product['id'];
        $array_product_places[] = $get_product['place'];
    }
}

while (count($array_product_ids) > 0) {

    $get_product['id']    = array_shift($array_product_ids);
    $id                   = $get_product['id'];
    $get_product['title'] = array_shift($array_products);
    $get_product['place'] = array_shift($array_product_places);

    echo admin_func_right_table_row_start(2);
    echo "<td>&nbsp;&nbsp;";
    if (count($array_product_ids) > 0)
        echo "<span style='font-family:Arial'>&#9500;</span>";
    else
        echo "<span style='font-family:Arial'>&#9492;</span>";
    echo " $get_product[title]";

    // языки
    echo "<td>";
    $langs_array = "";

    array_pop($langs_array);

    $select_c = DB::Query("select lang_id from `?_product` where id={$get_product['id']} order by lang_id ");

    while ($get_c = DB::GetArray($select_c)) {
        $select_l      = DB::Query("select title_short from `?_lang` where id={$get_c['lang_id']}");
        $langs_array[] = array_shift(DB::GetArray($select_l));
    }
    echo "<nobr style=\"font-size:10px;\">" . @implode(", ", $langs_array) . "</nobr>";
    echo '</td>';

    echo admin_func_right_table_data(
            admin_func_right_link(
                    "<img src=\"/admin/g/e.gif\" border=0 alt=\"" . Dictionary::GetAdminWord(371) . "\" width=10 height=12>"
                    , "product_edit.php?id=$get_product[id]", "", ""
            )
            . "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;"
            . admin_func_right_link(
                    "<img src=\"/admin/g/d.gif\" border=0 alt=\""
                    . Dictionary::GetAdminWord(354)
                    . "\" width=12 height=12 onclick=\"return confirm('"
                    . Dictionary::GetAdminWord(902)
                    . "');\">"
                    , "catalogue.php?item_id=$category_id&id={$get_product['id']}&delete=yes&delType=product"
                    , ""
                    , ""
            )
            , ""
            , 5);

    echo "<td><nobr>";
    echo admin_func_right_input("", "product_place[{$id}]", $get_product['place'], 50, 3);
    echo "</nobr>";

    echo admin_func_right_table_data(admin_func_right_input("checkbox", "ids[]", $get_product['id'], "", 3), "", 2);
}
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data(
        Dictionary::GetAdminWord(439)
        . admin_func_right_input("submit", "change_category", Dictionary::GetAdminWord(903), "", 1)
        . admin_func_right_input("submit", "delete_one", Dictionary::GetAdminWord(441), "", 5)
        . admin_func_right_input("hidden", "item_id", $category_id, "", 3)
        . admin_func_right_input("hidden", "search", $search, "", 3)
        . admin_func_right_input("submit", "edit_places", Dictionary::GetAdminWord(484), "", 1), "", "11r");
echo admin_func_right_table_end();