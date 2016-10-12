<?php

	$include = @include($_SERVER['DOCUMENT_ROOT']."/admin/admin_top.php");  
	if(!$include or $adm_wellcome != "Y") exit;

	if($menu_id == "") $menu_id = "0";

	if(!empty($change_active) and !empty($id) and !empty($lang_id))
	{
		$old_active = @array_shift(DB::GetArray(DB::Query("select active from `?_menu` where id=$id and lang_id=$lang_id ")));
		$new_active = 0;
		$new_active_title = Dictionary::GetAdminWord(84);
		if(empty($old_active))
		{
			$new_active = 1;
			$new_active_title = Dictionary::GetAdminWord(240);
		}

		$update = DB::Query("update `?_menu` set active=$new_active where id=$id and lang_id=$lang_id ");
		if(!$update) $sys_message[] = Dictionary::GetAdminWord(471);
		else $sys_message[] = "". Dictionary::GetAdminWord(472) ." \"$new_active_title\".";
	}

	if($menu_place != "")
	{
		$menu_place_keys = @array_keys($menu_place);
		while($id = @array_shift($menu_place_keys))
		{
			$update = DB::Query("update `?_menu` set place=$menu_place[$id] where id=$id ");
			if(!$update) $sys_message[] = Dictionary::GetAdminWord(864);
			else $sys_message[] = Dictionary::GetAdminWord(865);
		}
	}

	if($delete == "yes")
	{
		$select_submenu = DB::Query("select count(*)+0 from `?_menu` where menu_id=$id ");
		if(@array_shift(DB::GetArray($select_submenu)) > 0) $sys_message[] = Dictionary::GetAdminWord(866);
		else
		{
			$select_menu = DB::Query("select * from `?_menu` where id=$id ");
			$get = DB::GetArray($select_menu);
			$update_places = DB::Query("update `?_menu` set place=place-1 where place>$get[place] and menu_id=$get[menu_id]");

			$delete_menu    = DB::Query("delete from `?_menu` where id=$id ");
			$delete_content = DB::Query("delete from `?_content` where menu_id=$id ");
			if(!$delete_menu or !$delete_content) $sys_message[] = Dictionary::GetAdminWord(867);
			else $sys_message[] = Dictionary::GetAdminWord(868);
			
		}
                
            Helpers::buildMenuAlias();
	}

        Helpers::buildMenuAlias();
        
	$select_max_place = DB::Query("select max(place)+0 from `?_menu` where menu_id='$menu_id' ");
	$max_place = @array_shift(DB::GetArray($select_max_place));

	// Заголовок
	echo admin_func_top(Dictionary::GetAdminWord(869));

	// Кнопки
	//поиск
	echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#a5cd38">
	<tr>
	<td width="300" class="td_left" style="vertical-align: top; border: 1px solid #a5cd38;">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">                 
	<tr><td style="padding: 0;">';
	echo admin_func_menu_tree("menu_id", "menus.php", "&nbsp;&nbsp;", Dictionary::GetAdminWord(231), "1"
	, "width:300px; float:left; font-size:9px;", array($menu_id));
	echo '          </td></tr></table>
	</td>
	<td style="vertical-align: top; background: #fff;">';
	echo admin_func_sys_message($sys_message);

	echo admin_func_right_table_start(0);

	echo admin_func_right_table_start(0);
	echo admin_func_right_table_row_start(3);
	echo admin_func_right_table_data(
	admin_func_menu_tree_select("", "menu_id", "menus.php", "&nbsp;&nbsp;&nbsp;&nbsp;", Dictionary::GetAdminWord(296), "1", "0") ." &nbsp; ". admin_func_right_link("<img src=\"g/nw.gif\" border=0 alt=\"". Dictionary::GetAdminWord(870) ."\">", "menu_add.php?menu_id=$menu_id", "$get[title]", ""), "", 3);
	echo admin_func_right_table_row_start(3);
	echo admin_func_right_table_data("", "", 3);
	echo admin_func_right_table_end();

	echo admin_func_right_table_start(0);
	echo "<form action=menus.php method=post>";
	echo admin_func_right_table_row_start(0);
	echo admin_func_right_table_data( Dictionary::GetAdminWord(178), "100%", "0");
	echo admin_func_right_table_data( Dictionary::GetAdminWord(478), "", "0");
	echo admin_func_right_table_data(Dictionary::GetAdminWord(350), "", "0");
	echo admin_func_right_table_data(Dictionary::GetAdminWord(479), "", "0");

	$select_menus = DB::Query("select * from `?_menu` where menu_id=$menu_id order by place,lang_id ");
	while($get = DB::GetArray($select_menus))
	{
		if(!@in_array($get[id],$array_menu_ids))  {
			$array_menus[]       = strip_tags(ConvertAltToHtml($get['title']));
			$array_menu_ids[]    = $get['id'];
			$array_menu_places[] = $get['place'];
		}
	}

	while(count($array_menu_ids)){
		
		$get['id'] = @array_shift($array_menu_ids);
		$get['title'] = @array_shift($array_menus);
		$get['place'] = @array_shift($array_menu_places);
		$id = $get['id'];

		$select_fm = DB::Query("select count(*)+0 from `?_menu` where menu_id='$get[id]' ");
		$sub_menus = @array_shift(DB::GetArray($select_fm));

		if($sub_menus > 0)  {
			$get['title'] = admin_func_right_link("<img src=g/f2.gif width=12 height=9 border=0>", "menus.php?menu_id=$get[id]", "", "") ." ". admin_func_right_link($get['title'], "menus.php?menu_id=$get[id]", "", "");
		}
		else    {
			$get['title'] = "<img src=g/f.gif width=15 height=9 border=0> ". $get['title'];
		}

		unset($langs_array);
		$select_c = DB::Query("select lang_id,active from `?_menu` where id=$get[id] order by lang_id ");
		while($get_c = DB::GetArray($select_c))   {
			if(!empty($get_c['active'])) {
				$temp_color = "000000";
				$active_title = Dictionary::GetAdminWord(240);
			}
			else
			{
				$temp_color = "CCCCCC";
				$active_title = Dictionary::GetAdminWord(84);
			}

			$select_l = DB::Query("select title_short,title from `?_lang` where id=$get_c[lang_id] ");
			$get_l = DB::GetArray($select_l);
			$langs_array[] = admin_func_right_link("<font color=$temp_color>". $get_l['title_short'] ."</font>", "menus.php?menu_id=$menu_id&id=$get[id]&lang_id=$get_c[lang_id]&change_active=1", "$get_l[title]: $active_title", "");
		}
		$langs_array = @implode(", ", $langs_array);

		echo admin_func_right_table_row_start(2);
		
		$title = $get['title'];
		
		echo admin_func_right_table_data($title, "", "2");
		echo admin_func_right_table_data($langs_array, "", "2lang");
		echo admin_func_right_table_data("".
		"<nobr>&nbsp;".
		admin_func_right_link("<img src=\"g/n.gif\" align=absmiddle border=0 alt=\"". Dictionary::GetAdminWord(355) ."\" width=10 height=12>", "menu_add.php?menu_id=$get[id]", "", "").
		"&nbsp;&nbsp;|&nbsp;&nbsp;".
		admin_func_right_link("<img src=\"g/e.gif\" align=absmiddle border=0 alt=\"". Dictionary::GetAdminWord(371) ."\" width=10 height=12>", "menu_edit.php?id=$get[id]", "", "").
		"&nbsp;&nbsp;|&nbsp;&nbsp;".
		admin_func_right_link("<img src=\"g/d.gif\" align=absmiddle border=0 alt=\"". Dictionary::GetAdminWord(354) ."\" width=12 height=12 onclick=\"return confirm('". Dictionary::GetAdminWord(871) ." \\n ". Dictionary::GetAdminWord(872) ."');\">", "menus.php?menu_id=$menu_id&id=$get[id]&delete=yes", "", "").
		"&nbsp;</nobr>", "", "2");
		echo admin_func_right_table_data(admin_func_right_input("text", "menu_place[$id]", "$get[place]", "30", "3") . '', "", "1");
	}
	echo "<input type=hidden name=menu_id value=\"$menu_id\">";
	echo admin_func_right_table_row_start(2);
	echo admin_func_right_table_data( admin_func_right_input( "submit", "", Dictionary::GetAdminWord(484), "", 1), "", "11r");
	echo "</form>";
	echo admin_func_right_table_end();


	$select_config = DB::Query("select education from `?_config` ");
	if(@array_shift(DB::GetArray($select_config)) == "0")
	{
		echo "";
		echo "<table border=0 cellspacing=5 cellpadding=0 width=688 class=menu_bg_2>";
		echo "<tr >";
		echo "<td colspan=2>";
		echo "<table border=0 cellspacing=5 cellpadding=0 width=688 class=menu_bg>";
		echo "<tr >";
		echo "<td colspan=2><br>";
		echo "<b>". Dictionary::GetAdminWord(356) ."</b>";
		echo "<tr >";
		echo "<td width=20>";
		echo "<span style=\"color:000000; font-size: 10px; text-decoration: underline;\">". Dictionary::GetAdminWord(873) ."</span>";
		echo "<td>";
		echo Dictionary::GetAdminWord(1159) . Dictionary::GetAdminWord(240) . Dictionary::GetAdminWord(485) ."";
		echo "<tr >";
		echo "<td width=20>";
		echo "<span style=\"color:cccccc; font-size: 10px; text-decoration: underline;\">". Dictionary::GetAdminWord(873) ."</span>";
		echo "<td>";
		echo Dictionary::GetAdminWord(1159) . Dictionary::GetAdminWord(84) . Dictionary::GetAdminWord(485) ."";
		echo "<tr>";
		echo "<td width=40>";
		echo "<img src=\"g/n.gif\" border=0 alt=\"". Dictionary::GetAdminWord(870) ."\" width=11 height=12>";
		echo "<td>";
		echo Dictionary::GetAdminWord(874);
		echo "<tr>";
		echo "<td width=20>";
		echo "<img src=\"g/e.gif\" border=0 alt=\"". Dictionary::GetAdminWord(371) ."\" width=10 height=12>";
		echo "<td>";
		echo Dictionary::GetAdminWord(875);
		echo "<tr>";
		echo "<td width=20>";
		echo "<img src=\"g/d.gif\" border=0 alt=\"". Dictionary::GetAdminWord(354) ."\" width=12 height=12>";
		echo "<td>";
		echo Dictionary::GetAdminWord(876);
		echo "<tr>";
		echo "<td width=20>";
		echo "<img src=g/f2.gif width=12 height=9 border=0>";
		echo "<td>";
		echo Dictionary::GetAdminWord(877);
		echo "<tr>";
		echo "<td width=20>";
		echo "<img src=g/f.gif width=15 height=9 border=0>";
		echo "<td>";
		echo Dictionary::GetAdminWord(878);
		echo "</table>";



		echo "<br><br>";
		echo "<table border=0 cellspacing=5 cellpadding=0 width=600>";
		echo "<tr bgcolor=ffffff>";
		echo "<td colspan=2>";
		echo "<b>". Dictionary::GetAdminWord(246) ."</b>";
		echo "<tr bgcolor=ffffff>";
		echo "<td>";
		echo "<select style=\"font-size: 10px;\"><option>". Dictionary::GetAdminWord(296) ."</select>";
		echo "<td>";
		echo Dictionary::GetAdminWord(488);
		echo "<tr bgcolor=ffffff>";
		echo "<td>";
		echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
		echo "<tr bgcolor=E8E8E8 height=22>";
		echo "<th class=\"top\"><nobr>&nbsp;". Dictionary::GetAdminWord(178) ."&nbsp;</nobr></th>";
		echo "</table>";
		echo "<td>";
		echo Dictionary::GetAdminWord(302);
		echo "<tr bgcolor=ffffff>";
		echo "<td>";
		echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
		echo "<tr bgcolor=E8E8E8 height=22>";
		echo "<th class=\"top\"><nobr>&nbsp;". Dictionary::GetAdminWord(478) ."&nbsp;</nobr></th>";
		echo "</table>";
		echo "<td>";
		echo Dictionary::GetAdminWord(879);
		echo "<tr bgcolor=ffffff>";
		echo "<td>";
		echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
		echo "<tr bgcolor=E8E8E8 height=22>";
		echo "<th class=\"top\"><nobr>&nbsp;". Dictionary::GetAdminWord(350) ."&nbsp;</nobr></th>";
		echo "</table>";
		echo "<td>";
		echo Dictionary::GetAdminWord(880);
		echo "<tr bgcolor=ffffff>";
		echo "<td>";
		echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
		echo "<tr bgcolor=E8E8E8 height=22>";
		echo "<th class=\"top\"><nobr>&nbsp;". Dictionary::GetAdminWord(479) ."&nbsp;</nobr></th>";
		echo "</table>";
		echo "<td>";
		echo "". Dictionary::GetAdminWord(881) ." <br>&nbsp;&nbsp;". Dictionary::GetAdminWord(835) ."";
		echo "</table>";
		echo "</td>";
		echo "</tr>";
		echo"</table>";
	}

	//конец таблицы
	echo '</td></tr></table>';

	include(BASEPATH . "admin/admin_footer.php");