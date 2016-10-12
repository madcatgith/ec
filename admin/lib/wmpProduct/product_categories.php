<?

$include = @include("../../admin_top.php");
if(!$include or $adm_wellcome != "Y") exit;

if($category_id == "") $category_id = "0";

if($move == "up" or $move == "down")
{
  if($move == "up")   $move_num = -1;
  if($move == "down") $move_num = 1;

  $select_product_category = DB::Query("select * from `?_product_category` where id=$id ");
  $get_product_category = DB::GetArray($select_product_category);

  $change_place1 = DB::Query("update `?_product_category` set place=place-$move_num where place=$get_product_category[place]+$move_num and category_id='$get_product_category[category_id]'");
  $change_place2 = DB::Query("update `?_product_category` set place=place+$move_num where id=$id");
}

if($delete == "yes")
{
  $select_subproduct_category = DB::Query("select count(*)+0 from `?_product_category` where category_id=$id ");
  if(@array_shift(DB::GetArray($select_subproduct_category)) > 0) $sys_message[] = Dictionary::GetAdminWord(886);
  else
  {
    $select_product_category = DB::Query("select * from `?_product_category` where id=$id ");
    $get_product_category = DB::GetArray($select_product_category);
    $update_places = DB::Query("update `?_product_category` set place=place-1 where place>$get_product_category[place] and category_id=$get_product_category[category_id]");

    $delete_product_category    = DB::Query("delete from `?_product_category` where id=$id ");
    $delete_content = DB::Query("delete from `?_product` where category_id=$id ");
    if(!$delete_product_category or !$delete_content) $sys_message[] = Dictionary::GetAdminWord(706);
    else $sys_message[] = Dictionary::GetAdminWord(707);
  }
}


$select_max_place = DB::Query("select max(place)+0 from `?_product_category` where category_id='$category_id' ");
$max_place = @array_shift(DB::GetArray($select_max_place));

$dos_category_id = $category_id;
if($dos_category_id > 0) {
  do{
    $select_cur_position = DB::Query("select * from `?_product_category` where id=$dos_category_id and lang_id='" . Lang::getID() . "'");
    $get_dos_product_category = DB::GetArray($select_cur_position);
    $str_cur_position = " <font style=\"font-size:15px\"><b>&raquo;</b> <a class=\"w\" href=product_categories.php?category_id=$get_dos_product_category[id]>$get_dos_product_category[title]</a>$str_cur_position";
    $dos_category_id = $get_dos_product_category[category_id];
  }while($dos_category_id > 0);
}

include "product_catalogue_shapka.php";

// Заголовок
echo admin_func_top(Dictionary::GetAdminWord(887));

// Вывод сообщений про обработанность различных действий
echo admin_func_sys_message($sys_message);

echo admin_func_right_table_start(3);

echo admin_func_right_table_row_start(3);
echo admin_func_right_table_data( "<a href=product_categories.php class=\"w\">". Dictionary::GetAdminWord(296) ."</a>$str_cur_position &nbsp;&nbsp;&nbsp;". admin_func_right_link( "<img src=\"g/nw.gif\" border=0 alt=\"". Dictionary::GetAdminWord(888) ."\" width=11 height=12>", "product_category_add.php?category_id=$category_id", "", ""), "", "7w");

echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data( "<IMG src=\"p.gif\" width=1 height=1 border=0>", "", 7);
echo admin_func_right_table_end();

echo admin_func_right_table_start("");
echo "$formauthorization";
echo admin_func_right_table_row_start("");
echo admin_func_right_table_data( Dictionary::GetAdminWord(178), "", "");
echo admin_func_right_table_data( Dictionary::GetAdminWord(350), "", "");
echo admin_func_right_table_data( Dictionary::GetAdminWord(479), "", "");


$select_product_categories = DB::Query("select * from `?_product_category` where category_id=$category_id order by place,lang_id ");
while($get_product_category = DB::GetArray($select_product_categories))
{
  if(!@in_array($get_product_category[id],$array_category_ids))
  {
    $array_product_categories[]       = $get_product_category[title];
    $array_category_ids[]    = $get_product_category[id];
    $array_product_category_places[] = $get_product_category[place];
  }
}

while(count($array_category_ids))
{
  $get_product_category[id] = @array_shift($array_category_ids);
  $get_product_category[title] = @array_shift($array_product_categories);
  $get_product_category[place] = @array_shift($array_product_category_places);

  $select_fm = DB::Query("select count(*)+0 from `?_product_category` where category_id='$get_product_category[id]' ");
  $sub_product_categories = @array_shift(DB::GetArray($select_fm));

  echo  admin_func_right_table_row_start(2);
  echo "<td width=\"90%\">";
  if($sub_product_categories > 0) echo admin_func_right_link( "<span style=\"text-decoration:none;\"><img src=g/f2.gif width=12 height=9 border=0> &nbsp;</span> $get_product_category[title]", "product_categories.php?category_id=$get_product_category[id]", "", "");
  else echo "<img src=g/f.gif width=15 height=9 border=0> $get_product_category[title]";
  echo admin_func_right_table_data( "&nbsp;". admin_func_right_link( "<img src=\"g/n.gif\" border=0 alt=\"". Dictionary::GetAdminWord(888) ."\" width=11 height=12>", "product_category_add.php?category_id=$get_product_category[id]", "", "") ."&nbsp;|&nbsp;". admin_func_right_link( "<img src=\"g/e.gif\" border=0 alt=\"". Dictionary::GetAdminWord(371) ."\" width=10 height=12>", "product_category_edit.php?id=$get_product_category[id]", "", "") ."&nbsp;|&nbsp; ". admin_func_right_link( "<img src=\"g/d.gif\" border=0 alt=\"". Dictionary::GetAdminWord(354) ."\"  onclick=\"return confirm('". Dictionary::GetAdminWord(1172) ."')\" width=11 height=12>", "product_categories.php?category_id=$category_id&id=$get_product_category[id]&delete=yes", "", "") ."&nbsp;", "", 5);
  
  echo "<td><nobr>";
  if($get_product_category[place] > 1) echo admin_func_right_link( "<img src=\"g/1.gif\" border=0 alt=\"вверх\" width=11 height=12 hspace=\"5\">", "product_categories.php?category_id=$category_id&id=$get_product_category[id]&move=up", "", "");
  else echo "<div align=right>";
  if($get_product_category[place] < $max_place) echo admin_func_right_link( "<img src=\"g/2.gif\" border=0 alt=\"вниз\" width=11 height=12 hspace=\"5\">", "product_categories.php?category_id=$category_id&id=$get_product_category[id]&move=down", "", "");
  else echo "&nbsp;";
  echo "</nobr>";
}
echo admin_func_right_table_end();

echo "<br>";
echo "<table border=0 cellspacing=5 cellpadding=0 width=600>";
echo "<tr bgcolor=ffffff>";
echo "<td colspan=2>";
echo "<b>". Dictionary::GetAdminWord(356) ."</b>";
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"g/n.gif\" border=0 alt=\"". Dictionary::GetAdminWord(888) ."\" width=11 height=12>";
echo "<td>";
echo Dictionary::GetAdminWord(726);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"g/e.gif\" border=0 alt=\"". Dictionary::GetAdminWord(371) ."\" width=10 height=12>";
echo "<td>";
echo Dictionary::GetAdminWord(727);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"g/d.gif\" border=0 alt=\"". Dictionary::GetAdminWord(354) ."\" width=12 height=12>";
echo "<td>";
echo Dictionary::GetAdminWord(728);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=g/f2.gif width=12 height=9 border=0>";
echo "<td>";
echo Dictionary::GetAdminWord(890);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=g/f.gif width=15 height=9 border=0>";
echo "<td>";
echo Dictionary::GetAdminWord(891);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"g/1.gif\" border=0 alt=\"вверх\" width=11 height=12>";
echo "<td>";
echo Dictionary::GetAdminWord(892);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"g/2.gif\" border=0 alt=\"вниз\" width=11 height=12>";
echo "<td>";
echo Dictionary::GetAdminWord(893);
echo "</table>";

$select_config = DB::Query("select education from `?_config` ");
if(@array_shift(DB::GetArray($select_config)) == "0")
{

echo "<br><br>";
echo "<table border=0 cellspacing=5 cellpadding=0 width=600>";
echo "<tr bgcolor=ffffff>";
echo "<td colspan=2>";
echo "<b>". Dictionary::GetAdminWord(246) ."</b>";
echo "<tr bgcolor=ffffff>";
echo "<td>";

echo "<table cellspacing=0 cellpadding=0 border=0>";
echo "<tr bgcolor=627080 height=22>";
echo "<td class=\"w\"><nobr>&nbsp; ";
echo "<a href=content.php class=\"w\">". Dictionary::GetAdminWord(231) ."</a> <font style=\"font-size:15px\"><b>&raquo;</b>&nbsp;";
echo "</nobr></table>";

echo "<td>";
echo Dictionary::GetAdminWord(894);
echo "<tr bgcolor=ffffff>";
echo "<td>";
echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
echo "<tr bgcolor=E8E8E8 height=22>";
echo "<th class=\"top\"><nobr>&nbsp;". Dictionary::GetAdminWord(178) ."&nbsp;</nobr></th>";
echo "</table>";
echo "<td>";
echo Dictionary::GetAdminWord(336);
echo "<tr bgcolor=ffffff>";
echo "<td>";
echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
echo "<tr bgcolor=E8E8E8 height=22>";
echo "<th class=\"top\"><nobr>&nbsp;". Dictionary::GetAdminWord(350) ."&nbsp;</nobr></th>";
echo "</table>";
echo "<td>";
echo Dictionary::GetAdminWord(729);
echo "<tr bgcolor=ffffff>";
echo "<td>";
echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
echo "<tr bgcolor=E8E8E8 height=22>";
echo "<th class=\"top\"><nobr>&nbsp;". Dictionary::GetAdminWord(479) ."&nbsp;</nobr></th>";
echo "</table>";
echo "<td>";
echo "". Dictionary::GetAdminWord(895) ." <br>&nbsp;&nbsp;". Dictionary::GetAdminWord(835) ."";
echo "</table>";
}

include("admin_footer.php");

?>