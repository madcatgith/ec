<?

$include = @include("admin_top.php");
if(!$include or $adm_wellcome != "Y") exit;

// Заголовок
echo admin_func_top(Dictionary::GetAdminWord(278));

// Вывод сообщений про обработанность различных действий
echo admin_func_sys_message($sys_message);

echo admin_func_right_table_start("");

echo admin_func_right_table_row_start(2);
echo "<td valign=top>";

echo admin_func_right_table_start(2);
echo "
<form action=files.php method=post>
<input type=hidden name=add_yes55 value=1>
<input type=hidden name=d_path value=$d_path>
<input type=hidden name=show value=$show>";
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data( Dictionary::GetAdminWord(178), "", 1);
echo admin_func_right_table_data( admin_func_right_input( "", "title", $title, 475, 3), "", 2);
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data( Dictionary::GetAdminWord(279), "", 1);
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data( "<textarea name=text style=\"width:575px;height=170px;\">$text</textarea>", "", 7);
echo admin_func_right_table_row_start(2);
echo "$formauthorization";
echo admin_func_right_table_data( admin_func_right_input( "submit", "", Dictionary::GetAdminWord(245), "", 1), "", 7);
echo "</form>";
echo admin_func_right_table_end();
echo admin_func_right_table_end();

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
echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
echo "<tr bgcolor=ffffff height=22>";
echo "<th><nobr>&nbsp;". Dictionary::GetAdminWord(178) ."&nbsp;</nobr></th>";
echo "</table>";
echo "<td>";
echo "". Dictionary::GetAdminWord(280) ."";

echo "<tr bgcolor=ffffff>";
echo "<td>";
echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
echo "<tr bgcolor=ffffff height=22>";
echo "<th><nobr>&nbsp;". Dictionary::GetAdminWord(279) ."&nbsp;</nobr></th>";
echo "</table>";
echo "<td>";
echo "". Dictionary::GetAdminWord(281) ."";

echo "<tr bgcolor=ffffff>";
echo "<td valign=top>";
echo "<input type=submit class=button value=\" ". Dictionary::GetAdminWord(245) ."\">";
echo "<td valign=top>";
echo "". Dictionary::GetAdminWord(282) ."";

echo "</table>";
}

include("admin_footer.php"); ?>