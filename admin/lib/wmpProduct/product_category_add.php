<?

$include = @include("../../admin_top.php");
if(!$include or $adm_wellcome != "Y") exit;

if($add_new_one != "")
{
  $select_max_id = DB::Query("select max(id)+0 from `?_product_category` ");
  $next_id = @array_shift(DB::GetArray($select_max_id)) + 1;

  $select_max_place = DB::Query("select max(place)+0 from `?_product_category` where category_id=$category_id ");
  $next_place = @array_shift(DB::GetArray($select_max_place)) + 1;

  $select_langs = DB::Query("select * from `?_lang` where active=1 order by place");
  while($get_lang = DB::GetArray($select_langs))
  {
    $lang_id = $get_lang[id];
    if($title[$lang_id] != "")
    {
      $title[$lang_id] = str_replace("\"","&quot;", $title[$lang_id]);

      $select_dublikat2 = DB::Query("select count(*)+0 from `?_product_category`
      where title='$title[$lang_id]' and lang_id='$lang_id' and category_id='$category_id' ");
      $dublikat = $dublikat + @array_shift(DB::GetArray($select_dublikat2));
    }
  }

  if($dublikat > 0) $sys_message[] = "". Dictionary::GetAdminWord(229) ."";
  else
  {
    $select_langs = DB::Query("select * from `?_lang` where active=1 order by place");
    while($get_lang = DB::GetArray($select_langs))
    {
      $lang_id = $get_lang[id];
      if($title[$lang_id] != "")
      {
        $add_product_category = DB::Query("insert into `?_product_category`
         (id,category_id,active,place,title,lang_id) values
         ('$next_id','$category_id','$active[$lang_id]','$next_place','$title[$lang_id]','$lang_id') ");

       if(!$add_product_category) $sys_message[] = "". Dictionary::GetAdminWord(230) ." \"$title[$lang_id]\"...";
       else $sys_message[] = "". Dictionary::GetAdminWord(111) ."\"$title[$lang_id]\" ". Dictionary::GetAdminWord(331) ."";
      }
    }
  }
}

include "product_catalogue_shapka.php";

// Заголовок
echo admin_func_top(Dictionary::GetAdminWord(278));

// Вывод сообщений про обработанность различных действий
echo admin_func_sys_message($sys_message);

echo admin_func_right_table_start("");

echo admin_func_right_table_row_start(2);
echo "<td valign=top>";
echo admin_func_right_table_start(2);
echo "<form method=post action=product_category_add.php>";
echo "$formauthorization";
echo "<input type=hidden name=add_new_one value=1>";
echo "<input type=hidden name=category_id value=\"$category_id\">";
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data( Dictionary::GetAdminWord(333), "", "9b");
echo "<td width=100%>";

  echo "<select name=category_id>";
  echo "<option value=\"0\">". Dictionary::GetAdminWord(296) ."";

  $max_level = Registry::get('maxMenuLevel');
  unset($eval_array);
  for($i=$max_level;$i>=1;$i--)
  {
    $next = $i + 1;
    unset($temp_plus);
    if($i != $max_level) $temp_plus = $eval_array[$next];
    $eval_array[$i] = "\n".
                      "\$select_product_category". $i ." = DB::Query(\"select * from `?_product_category` where category_id=\$pre". $i ."_id and lang_id='" . Lang::getID() . "' order by place,id \");\n".
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

echo "</td>";

$select_langs = DB::Query("select * from `?_lang` where active=1 order by place");
while($get_lang = DB::GetArray($select_langs))
{
  $lang_id = $get_lang[id];
  $checked = "";
  if($active[$lang_id] == 1) $checked = "checked";

  echo admin_func_right_table_row_start(2);
  echo admin_func_right_table_data( Dictionary::GetAdminWord(178) ." [$get_lang[title_short]]", "", 1);
  echo admin_func_right_table_data( admin_func_right_input( "", "title[$lang_id]", $title[$lang_id], 230, 3) ."&nbsp;". admin_func_right_input( "checkbox", "active[$lang_id]", 1, "", $checked) . Dictionary::GetAdminWord(240), "100%", 2);
}

echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data( admin_func_right_input( "submit", "", Dictionary::GetAdminWord(245), "", 1), "", 7);
echo "</form>";
echo admin_func_right_table_end();
echo admin_func_right_table_end();


include("admin_footer.php"); ?>