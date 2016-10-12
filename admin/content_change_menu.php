<?

//$include = @include("admin_top.php");
//if(!$include or $adm_wellcome != "Y") exit;
unset($go_away);

if($ids == "")
{
  $sys_message[] = Dictionary::GetAdminWord(454);
  $go_away = 1;
}
else
{



if($delete_one != "")
{

$ids = @array_reverse($ids);
for($i=0;$i<count($ids);$i++)
{
  $id = $ids[$i];

  $select_content = DB::Query("select id,title,menu_id,place from `?_content` where id=$id order by lang_id desc ");
  $get_content = DB::GetArray($select_content);
  $old_menu_id = $get_content[menu_id];
  $old_place   = $get_content[place];
  $title       = $get_content[title];

  $update = DB::Query("update `?_content` set place=place-1 where place>$old_place and menu_id=$old_menu_id ");

  $delete_content = DB::Query("delete from `?_content` where id='$id' ");
  if(!$delete_content) $sys_message[] = "". Dictionary::GetAdminWord(455) ." \"$title\"...";
  else $sys_message[] = "". Dictionary::GetAdminWord(131) ." \"$title\" ". Dictionary::GetAdminWord(456) ."";
  $go_away = 1;
}
}
else
{


if($edit_one != "")
{

$ids = @array_reverse($ids);
for($i=0;$i<count($ids);$i++)
{
  $id = $ids[$i];

  $select_content = DB::Query("select id,title,menu_id,place from `?_content` where id=$id order by lang_id desc ");
  $get_content = DB::GetArray($select_content);
  $old_menu_id = $get_content[menu_id];
  $old_place   = $get_content[place];
  $title       = $get_content[title];

  if($old_menu_id != $menu_id)
  {
    $place = 1;

    $update = DB::Query("update `?_content` set place=place-1 where place>$old_place and menu_id=$old_menu_id ");
    $update = DB::Query("update `?_content` set place=place+1 where menu_id=$menu_id ");

    $edit_content = DB::Query("update `?_content` set menu_id='$menu_id',place='$place' where id='$id' ");
  }
  else $edit_content = 1;
  if(!$edit_content) $sys_message[] = "". Dictionary::GetAdminWord(457) ." \"$title\"...";
  else $sys_message[] = "". Dictionary::GetAdminWord(458) ." \"$title\" ". Dictionary::GetAdminWord(459) ."";
}
}



// Заголовок
echo admin_func_top(Dictionary::GetAdminWord(460));

if(count($sys_message) > 0)
{
  echo admin_func_right_table_start(4);
  echo admin_func_right_table_row_start(2);
  echo"<td height=30 class=\"mes\">";

  $temp = @implode("</li>\n\r<li>", $sys_message);
  $temp = "<li>". $temp ."</li>";

  echo $temp;

  echo admin_func_right_table_end();
  exit;
}

echo "<table border=0 cellpadding=3 cellspacing=0 class=tdall>";
echo "<form method=post action=content.php name=content>";
echo "$formauthorization";
echo "<input type=hidden name=edit_one value=1>";

if($new_menu_id != "")
{
  $menu_id = $new_menu_id;
  $prev_menu_id = $menu_id;
}
else $prev_menu_id = "0";

echo "<input type=hidden name=menu_id value=\"$menu_id\">";
echo "<tr bgcolor=#FEF8E0>";
echo "<td valign=top><nobr><b>". Dictionary::GetAdminWord(232) ." &nbsp;</b></nobr></td>";
echo "<td width=100%>";

$str_ids = "";
for($i=0;$i<count($ids);$i++) $str_ids .= "&ids[]=$ids[$i]";

$dos_menu_id = $menu_id;
if($dos_menu_id > 0)
{
  do{
    $select_cur_position = DB::Query("select * from `?_menu` where id=$dos_menu_id and lang_id='$default_lang' ");
    $get_dos_menu = DB::GetArray($select_cur_position);
    $str_cur_position = " / <a href=content.php?new_menu_id=$get_dos_menu[id]". $str_ids .">$get_dos_menu[title]</a>$str_cur_position";
    $dos_menu_id = $get_dos_menu[menu_id];
  }while($dos_menu_id > 0);
}

echo "<a href=content.php?". $str_ids ."&new_menu_id=0>". Dictionary::GetAdminWord(231) ."</a>$str_cur_position";
echo "<br><hr noshade size=1px>";

$select_menus = DB::Query("select * from `?_menu` where menu_id=$prev_menu_id and lang_id='$default_lang' order by place");

echo "<table border=1 cellpadding=5 cellspacing=0>";
if(@mysql_num_rows($select_menus) != "") echo "". Dictionary::GetAdminWord(248) ."<br><br>";

while($get_menus = DB::GetArray($select_menus))
{
  echo "<tr>";
  echo "<td><nobr>";
  echo "<a href=content.php?new_menu_id=$get_menus[id]";

  for($i=0;$i<count($ids);$i++) echo "&ids[]=$ids[$i]";

  echo " style=\"color:black\">$get_menus[title]</a>";
  echo "</nobr>";
}
echo "</table><br>";
echo "<tr bgcolor=#FEF8E0>";
echo "<td class=tdt valign=top>";
echo "<b>". Dictionary::GetAdminWord(461) ."</b>";
echo "<td class=tdt>";

$tipa_echo = "";
for($i=0;$i<count($ids);$i++)
{
  $select_content = DB::Query("select id,title, menu_id,place from `?_content` where id=$ids[$i] order by lang_id ");
  $get_content = DB::GetArray($select_content);

  $tipa_echo .= "$get_content[title]<br>";
  $tipa_echo .= "<input type=hidden name=ids[] value=\"$ids[$i]\">";
}
echo "$tipa_echo";

echo "</td></table><br>";
echo "<input class=button type=submit value=\"". Dictionary::GetAdminWord(462) ."\">";
echo "</form>";

}
}
//include("admin_footer.php");
?>