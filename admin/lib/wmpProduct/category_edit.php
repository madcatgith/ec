<?

$include = @include("../../admin_top.php");
if(!$include or $adm_wellcome != "Y") exit;
$include = require_once ("../../hierarchyhelpers/treeBuilder.php");

if($edit_new_one != ""){
  $select_langs = DB::Query("select * from `?_lang` where active=1 order by place");
  while($get_lang = DB::GetArray($select_langs))
  {
    $lang_id = $get_lang[id];
    if($title[$lang_id] != "")
    {
      $select_dublikat2 = DB::Query("select count(*)+0 from `?_product_category`
      where id!=$id and title='$title[$lang_id]' and lang_id='$lang_id' and category_id='$category_id' ");
      $dublikat = $dublikat + @array_shift(DB::GetArray($select_dublikat2));
    }
  }

  if($dublikat > 0) $sys_message[] = Dictionary::GetAdminWord(629);
  else
  {
    if($old_category_id != $category_id)
    {
      $update_places = DB::Query("update `?_product_category` set place=place-1 where place>$place and category_id=$old_category_id");
      $select_max_place = DB::Query("select max(place)+0 from `?_product_category` where category_id=$category_id ");
      $place = @array_shift(DB::GetArray($select_max_place)) + 1;
    }

    $edit = DB::Query("update `?_product_category` set category_id='$category_id',place='$place' where id='$id' ");

    $select_langs = DB::Query("select * from `?_lang` where active=1 order by place");
    while($get_lang = DB::GetArray($select_langs))
    {
      $lang_id = $get_lang[id];
      if($title[$lang_id] != "")
      {
        $title[$lang_id] = str_replace("\"","&quot;", $title[$lang_id]);

        $does_it_exist = DB::Query("select * from `?_product_category` where id=$id and lang_id=$lang_id ");
        if(@mysql_num_rows($does_it_exist) == "")
        {
          $edit_product_category = DB::Query("insert into `?_product_category`
           (id,category_id,active,place,title,lang_id) values
           ('$id','$category_id','$active[$lang_id]','$place','$title[$lang_id]','$lang_id') ");
        }
        else
        {
          $edit_product_category = DB::Query("update `?_product_category` set active='$active[$lang_id]',title='$title[$lang_id]' where lang_id='$lang_id' and id='$id' ");
        }

       if(!$edit_product_category) $sys_message[] = " ". Dictionary::GetAdminWord(630) ." \"$title[$lang_id]\"...";
       else $sys_message[] = "". Dictionary::GetAdminWord(111) ." \"$title[$lang_id]\" ". Dictionary::GetAdminWord(577) ."";
      }
    }
  }
}

// Заголовок
echo admin_func_top(Dictionary::GetAdminWord(578));

// Вывод сообщений про обработанность различных действий
echo admin_func_sys_message($sys_message);

$wmpTree = new wmpTree();
$wmpTree->BranchesSelect = wmpTree::$PreDefSql['productBranches'];
$wmpTree->ShowLeaves = false;
$treeBody = $wmpTree->func_items_tree("item_id", "/admin/lib/wmpProduct/catalogue.php", "&nbsp;&nbsp;", Dictionary::GetAdminWord(231), ""
                            , "width:300px; float:left; font-size:9px;", array($id), false, '');

echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#a5cd38">
         <tr>
             <td width="300" class="td_left" style="vertical-align: top; border: 1px solid #a5cd38;">
                 <table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td style="padding: 14px 4px 4px 4px; border-bottom: 2px solid #213866;">
                <form action="/admin/lib/wmpProduct/catalogue.php?oper=search" method="post">';
echo Dictionary::GetAdminWord(64) ." ". admin_func_right_input("text", "search", $search, "100", "") ." ". admin_func_right_input("submit", "", Dictionary::GetAdminWord(341), "", "1");
echo '</form></td></tr><tr><td style="padding: 0;">';
echo $treeBody;
echo '          </td></tr></table>
            </td>
            <td style="vertical-align: top; background: #fff;">';

echo admin_func_right_table_start("");

echo admin_func_right_table_row_start(2);
echo "<td valign=top>";
echo admin_func_right_table_start(2);
echo "<form method=post action=category_edit.php>";
echo "$formauthorization";
echo "<input type=hidden name=edit_new_one value=1>";




$select_langs = DB::Query("select * from `?_lang` where active=1 order by place");
while($get_lang = DB::GetArray($select_langs))
{
  $lang_id = $get_lang[id];

  $select_product_category = DB::Query("select * from `?_product_category` where id=$id and lang_id=$lang_id ");
  $get_product_category = DB::GetArray($select_product_category);
  if($get_product_category[id] != "")
  {
    $active[$lang_id] = $get_product_category[active];
    $title[$lang_id] = $get_product_category[title];
    $category_id = $get_product_category[category_id];
    $old_category_id = $get_product_category[category_id];
    $place = $get_product_category[place];
    for($i = 1; $i < 6; $i++) {
 				$count[$i] 		= $get_product_category["count_" . $i];
 				$discount[$i] = $get_product_category["discount_" . $i];
		}
  }
}
/*
if($new_category_id != "")
{
  $category_id = $new_category_id;
  $prev_category_id = $category_id;
}
else $prev_category_id = "0";
*/
echo "<input type=hidden name=id value=\"$id\">";
echo "<input type=hidden name=place value=\"$place\">";
echo "<input type=hidden name=old_category_id value=\"$old_category_id\">";
//echo "<input type=hidden name=category_id value=\"$category_id\">";
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data( Dictionary::GetAdminWord(333), "", "9b");

  echo "<td><select name=category_id>";
  echo "<option value=\"0\">". Dictionary::GetAdminWord(296) ."";

  $max_level = Registry::get('maxMenuLevel');
  unset($eval_array);
  for($i=$max_level;$i>=1;$i--)
  {
    $next = $i + 1;
    unset($temp_plus);
    if($i != $max_level) $temp_plus = $eval_array[$next];
    $eval_array[$i] = "\n".
                      "\$select_product_category". $i ." = DB::Query(\"select * from `?_product_category` where id!=$id and category_id=\$pre". $i ."_id and lang_id='" . Lang::getID() . "' order by place,id \");\n".
                      "\n".
                      "\n".
                      "while(\$get_product_category". $i ." = DB::GetArray(\$select_product_category". $i ."))\n".
                      "{\n".
                      "  \$temp = \"\$get_product_category". $i ."[id]\";\n".
                      "  unset(\$selected);\n".
                      "  if(\$temp == \$category_id) \$selected = \"selected\";\n".
                      "  \n".
                      "  echo \"<option value=\\\"\$temp\\\" \$selected>". str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $i-1) ."\";\n".
                      "  echo \"\$get_product_category". $i ."[title]\";\n".
                      "  \$pre". ($i+1) ."_id = \$get_product_category". $i ."[id];\n".
                      "  $temp_plus\n".
                      "}";
  }

  $pre1_id = 0;
  eval($eval_array[1]);

  echo "</select>";

/*
		дополнительные поля
*/
/*
for($i = 1; $i < 6; $i++) {
  echo admin_func_right_table_row_start(2);
  echo admin_func_right_table_data( Dictionary::GetUniqueWord(201) . " [{$i}]" , "", 1);
  echo admin_func_right_table_data(
   admin_func_right_input( "", "count[{$i}]", $count[$i], 50, 3) . " " . Dictionary::GetUniqueWord(200) . " " .
   admin_func_right_input( "", "discount[{$i}]", $discount[$i], 50, 3) . " %"
  , "100%", 2);
}
*/
$select_langs = DB::Query("select * from `?_lang` where active=1 order by place");
while($get_lang = DB::GetArray($select_langs))
{
  $lang_id = $get_lang[id];
  $checked[$lang_id] = "";
  if($active[$lang_id] == 1) $checked[$lang_id] = "checked";

  echo admin_func_right_table_row_start(2);
  echo admin_func_right_table_data( Dictionary::GetAdminWord(178) ." [$get_lang[title_short]]", "", 1);
  echo admin_func_right_table_data( admin_func_right_input( "", "title[$lang_id]", $title[$lang_id], 250, 3) ."&nbsp;". admin_func_right_input( "checkbox", "active[$lang_id]", 1, "", $checked[$lang_id]) . Dictionary::GetAdminWord(240), "", 2);
}


echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data( admin_func_right_input( "submit", "", Dictionary::GetAdminWord(538), "", 1), "", 7);
echo "</form>";
echo admin_func_right_table_end();
echo admin_func_right_table_end() ."<br>";

$select_config = DB::Query("select education from `?_config` ");
if(@array_shift(DB::GetArray($select_config)) == "0")
{

echo "<br><br>";
echo "<table border=0 cellspacing=5 cellpadding=0 width=600>";
echo "<tr bgcolor=ffffff>";
echo "<td colspan=2>";
echo "<b>". Dictionary::GetAdminWord(246) ."</b>";

echo "<tr bgcolor=ffffff>";
echo "<td valign=top>";
echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
echo "<tr bgcolor=#ffffff>";
echo "<td valign=top><nobr><b>". Dictionary::GetAdminWord(232) ." &nbsp;</b></nobr></td>";
echo "</nobr></table>";
echo "<td valign=top>";
echo Dictionary::GetAdminWord(232);

echo "<tr bgcolor=ffffff>";
echo "<td valign=top>";
echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
echo "<tr bgcolor=#ffffff>";
echo "<td valign=top><nobr>". Dictionary::GetAdminWord(248) ."</nobr></td>";
echo "</nobr></table>";
echo "<td valign=top>";
echo Dictionary::GetAdminWord(632);

echo "<tr bgcolor=ffffff>";
echo "<td valign=top>";
echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
echo "<tr bgcolor=ffffff>";
echo "<td valign=top><nobr><b>". Dictionary::GetAdminWord(178) ." &nbsp;</b></nobr></td>";
echo "</nobr></table>";
echo "<td valign=top>";
echo Dictionary::GetAdminWord(336);

echo "<tr bgcolor=ffffff>";
echo "<td valign=top>";
echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
echo "<tr bgcolor=ffffff>";
echo "<td valign=top><nobr><input type=checkbox name=active[1] value=1 > ". Dictionary::GetAdminWord(240) ."</nobr></td>";
echo "</nobr></table>";
echo "<td valign=top>";
echo Dictionary::GetAdminWord(337);

echo "<tr bgcolor=ffffff>";
echo "<td valign=top>";
echo "<input type=submit class=button value=\" ". Dictionary::GetAdminWord(538) ."\">";
echo "<td valign=top>";
echo Dictionary::GetAdminWord(580);
echo "</table>";
}

include("admin_footer.php"); ?>