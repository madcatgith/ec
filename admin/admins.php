<?
$include = include $_SERVER['DOCUMENT_ROOT'] . '/admin/admin_top.php';
if(!$include or $adm_wellcome != "Y" or Admin::$current->info['status'] != 1) exit;

if($delete == "yes")
{

  $check_adminusers = DB::Query("select status from `?_admin` where id=$aid");
  if(@array_shift(DB::GetArray($check_adminusers)) == 1)
  {
    $check_adminusers=DB::Query("select count(*) from `?_admin` where status=1");
    if(@array_shift(DB::GetArray($check_adminusers)) == 1)
    {
      $sys_message[] = Dictionary::GetAdminWord(346);
      $error = 1;
    }
  }

  if($error == "")
  {
    $delete_admin = DB::Query("delete from `?_admin` where id=$aid ");
    if($delete_admin) $sys_message[] = Dictionary::GetAdminWord(347);
  }
}

if($adminuser != "" and $adminpass != "")
{

  $check_adminuser = DB::Query("select adminuser from `?_admin` where adminuser='$adminuser'");
  $get_adminuser   = DB::GetArray($check_adminuser);

  if("{$get_adminuser["adminuser"]}" == $adminuser) $sys_message[] = Dictionary::GetAdminWord(348);
  else
  {
    $add_admin = DB::Query("insert into `?_admin` (id,adminuser,adminpass,status) values ('','{$adminuser}','{$adminpass}','{$status}') ");
    if($add_admin) $sys_message[] = Dictionary::GetAdminWord(347);
  }
}

// Заголовок
echo admin_func_top(Dictionary::GetAdminWord(349));

// Вывод сообщений про обработанность различных действий
echo admin_func_sys_message($sys_message);

echo admin_func_right_table_start("");
//echo "<table cellspacing=1 cellpadding=2 border=0 width=\"100%\" bgcolor=627080>";
echo admin_func_right_table_row_start("");
echo admin_func_right_table_data(Dictionary::GetAdminWord(220), "50%", "");
echo admin_func_right_table_data(Dictionary::GetAdminWord(164), "25%", "");
echo admin_func_right_table_data(Dictionary::GetAdminWord(165), "25%", "");
echo admin_func_right_table_data(Dictionary::GetAdminWord(350), "", "");

$select_admins = DB::Query("select * from `?_admin` order by id ");
while($get_admin = DB::GetArray($select_admins))
{
    switch ($get_admin["status"]) {
        case 0:
            $get_admin["status"] = Dictionary::GetAdminWord(352);
        break;
        case 1:
            $get_admin["status"] = Dictionary::GetAdminWord(351) ;
        break;
        case 2:
            $get_admin["status"] = Dictionary::GetWord(10111);
        break;
    }

  echo admin_func_right_table_row_start(2);
  echo admin_func_right_table_data( $get_admin["status"], "", 2);
  echo admin_func_right_table_data( $get_admin[adminuser], "", 2);
  echo admin_func_right_table_data( $get_admin[adminpass], "", "2i");
  echo admin_func_right_table_data( "&nbsp;<a href=admins.php?$hrefauthorization&delete=yes&aid=$get_admin[id] onclick=\"return confirm('". Dictionary::GetAdminWord(353) ."');\"><img src=\"g/d.gif\" border=0 alt=\"". Dictionary::GetAdminWord(354) ."\" width=12 height=12></a>&nbsp;", "", "2i");
}


echo admin_func_right_table_row_start(2);;
echo "<form action=admins.php method=post>";
echo "<td>";

echo admin_func_right_table_start(3);
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data( "<select name=status><option value=\"0\">". Dictionary::GetAdminWord(352) ."<option value=\"1\">". Dictionary::GetAdminWord(351) ."<option value=\"2\">" . Dictionary::GetWord(10111) ."</select>", "", 2);
echo admin_func_right_table_end();

echo admin_func_right_table_data( admin_func_right_input( "", "adminuser", "", 100, 3), "", 2);
echo admin_func_right_table_data( admin_func_right_input( "", "adminpass", "", 100, 3), "", 2);

echo admin_func_right_table_data( "$formauthorization &nbsp;" . admin_func_right_input( "image", "", "", "", 4), "", 2);
echo "</form>";

echo admin_func_right_table_end();




echo "<br>";
echo "<table border=0 cellspacing=5 cellpadding=0 width=600>";
echo "<tr bgcolor=ffffff>";
echo "<td colspan=2>";
echo "<b>". Dictionary::GetAdminWord(356) ."</b>";
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"g/n.gif\" border=0 alt=\"". Dictionary::GetAdminWord(355) ."\" width=11 height=12>";
echo "<td>";
echo Dictionary::GetAdminWord(357);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"g/d.gif\" border=0 alt=\"". Dictionary::GetAdminWord(354) ."\" width=12 height=12>";
echo "<td>";
echo Dictionary::GetAdminWord(358);
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
echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
echo "<tr bgcolor=E8E8E8 height=22>";
echo "<th class=\"top_1\"><nobr>&nbsp;". Dictionary::GetAdminWord(220) ."&nbsp;</nobr></th>";
echo "</table>";
echo "<td>";
echo Dictionary::GetAdminWord(359);

echo "<tr bgcolor=ffffff>";
echo "<td>";
echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
echo "<tr bgcolor=E8E8E8 height=22>";
echo "<th class=\"top_1\"><nobr>&nbsp;". Dictionary::GetAdminWord(164) ."&nbsp;</nobr></th>";
echo "</table>";
echo "<td>";
echo Dictionary::GetAdminWord(360);

echo "<tr bgcolor=ffffff>";
echo "<td>";
echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
echo "<tr bgcolor=E8E8E8 height=22>";
echo "<th class=\"top_1\"><nobr>&nbsp;". Dictionary::GetAdminWord(165) ."&nbsp;</nobr></th>";
echo "</table>";
echo "<td>";
echo Dictionary::GetAdminWord(361);

echo "<tr bgcolor=ffffff>";
echo "<td>";
echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
echo "<tr bgcolor=E8E8E8 height=22>";
echo "<th class=\"top_1\"><nobr>&nbsp;". Dictionary::GetAdminWord(350) ."&nbsp;</nobr></th>";
echo "</table>";
echo "<td>";
echo Dictionary::GetAdminWord(362);
echo "</table>";
}

include("admin_footer.php"); ?>