<?php

$include = include ($_SERVER['DOCUMENT_ROOT'] . "/admin/admin_top.php");

if (!$include or $adm_wellcome != "Y") {
    exit;
}

$menu_id = $_GET['menu_id'];

if ($menu_id == "") {
    $menu_id = "0";
}

include($_SERVER['DOCUMENT_ROOT'] . "/includes.php");

if (!empty($change_active) and !empty($id) and !empty($lang_id)) {

    $old_active       = @array_shift(DB::GetArray(DB::Query("select active from `?_content` where id=$id and lang_id=$lang_id ")));
    $new_active       = 0;
    $new_active_title = Dictionary::GetAdminWord(84);

    if (empty($old_active)) {
        $new_active       = 1;
        $new_active_title = Dictionary::GetAdminWord(240);
    }

    $update = DB::Query("update `?_content` set active=$new_active where id=$id and lang_id=$lang_id ");

    if (!$update) {
        $sys_message[] = Dictionary::GetAdminWord(471);
    } else {
        $sys_message[] = "" . Dictionary::GetAdminWord(472) . " \"$new_active_title\".";
    }
}

if (!empty($change_menu) and !empty($ids)) {

    $min_place = 0 + @array_shift(DB::GetArray(DB::Query("select min(place) from `?_content` where menu_id=$new_menu_id ")));

    reset($ids);

    $str_ids = @implode(",", $ids);
    $select  = DB::Query("select id,place from `?_content` where find_in_set(id,'$str_ids') order by place desc ");

    while ($get = DB::GetArray($select)) {

        $id            = $get['id'];
        $content_place = $get['place'];

        $update1 = DB::Query("update `?_content` set menu_id=$new_menu_id,place=$min_place where id=$id ");
        $update2 = DB::Query("update `?_content` set place=place+1 where menu_id=$new_menu_id and id!=$id ");
        $update3 = DB::Query("update `?_content` set place=place-1 where menu_id=$menu_id and place>$content_place ");

        if (!$update1 or !$update2 or !$update3) {
            $sys_message[] = Dictionary::GetAdminWord(1098);
        } else {
            $sys_message[] = Dictionary::GetAdminWord(131) . " " . Dictionary::GetAdminWord(459);
        }
    }

    if ($update1 and $update2 and $update3) {
        $menu_id = $new_menu_id;
    }
}

if (!empty($edit_places)) {

    $content_place_keys = @array_keys($content_place);

    while ($id = @array_shift($content_place_keys)) {

        $update = DB::Query("update `?_content` set place=$content_place[$id] where id=$id ");

        if (!$update) {
            $sys_message[] = Dictionary::GetAdminWord(473);
        } else {
            $sys_message[] = Dictionary::GetAdminWord(474);
        }
    }
}

if ($delete == "yes") {

    $select_content = DB::Query("select * from `?_content` where id=$id ");
    $get            = DB::GetArray($select_content);
    $update_places  = DB::Query("update `?_content` set place=place-1 where place>$get[place] and menu_id=$get[menu_id]");
    $delete_content = DB::Query("delete from `?_content` where id=$id ");
    
    DB::Query('delete from ?_product_to_content where contentID = '. $id);
    
    if (!$delete_content) {
        $sys_message[] = Dictionary::GetAdminWord(475);
    } else {
        $sys_message[] = Dictionary::GetAdminWord(476);
    }
}

// Заголовок
echo admin_func_top("Управление контентами");
// Кнопки
//поиск
echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#a5cd38">
	<tr>
	<td width="300" class="td_left" style="vertical-align: top; border: 1px solid #a5cd38;">
	<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td style="padding: 14px 4px 4px 4px; border-bottom: 2px solid #213866;"><form action="/admin/content.php?oper=search" method="post">';
echo Dictionary::GetAdminWord(64) . " " . admin_func_right_input("text", "search", $search, "100", "") . " " . admin_func_right_input("submit", "", Dictionary::GetAdminWord(341), "", "1");
echo '</form></td></tr><tr><td style="padding: 0;">';
echo admin_func_menu_tree("menu_id", "content.php", "&nbsp;&nbsp;", Dictionary::GetAdminWord(231), "1"
        , "width:300px; float:left; font-size:9px;", array($menu_id));
echo '          </td></tr></table>
	</td>
	<td style="vertical-align: top; background: #fff;">';
echo admin_func_sys_message($sys_message);
echo admin_func_right_table_start(0);

echo "<form action=content.php method=post>";
echo admin_func_right_table_row_start(0);

// Обработка переданных данных на поиск
unset($upper_text);
unset($where_plus);
$order_by = "order by place,lang_id";
if (empty($search)) {
    if (empty($menu_id)) {
        $get_menu['id']    = "0";
        $get_menu['title'] = Dictionary::GetAdminWord(231);
    } else {
        $select_menu = DB::Query("select * from `?_menu` where id='$menu_id' order by lang_id ");
        $get_menu    = DB::GetArray($select_menu);
    }

    $select_max_place = DB::Query("select max(place)+0 from `?_content` where menu_id='$get_menu[id]' ");
    $max_place        = @array_shift(DB::GetArray($select_max_place));
    $select_fm        = DB::Query("select count(*)+0 from `?_menu` where menu_id='$get_menu[id]' ");
    $sub_menus        = @array_shift(DB::GetArray($select_fm));

    $title = strip_tags(ConvertAltToHtml($get_menu['title']));

    if ($sub_menus > 0) {
        $upper_text = admin_func_right_link("<span style=\"text-decoration:none;\"><img src=g/f2.gif border=0> &nbsp;</span>$title", "content.php?menu_id=$get_menu[id]", "$title", "");
    } else {
        $upper_text = admin_func_right_link("<img src=g/f.gif border=0> &nbsp;$title", "content.php?menu_id=$get_menu[id]", "$title", "");
    }

    $where_plus = "and menu_id='$menu_id'";
    $order_by   = "order by place, order_date desc, lang_id";
} else {

    $upper_text = "Поиск: " . $search;
    $where_plus = "and concat(lower(title),' ',lower(text)) like lower('%$search%')";
    $order_by   = "order by id desc,lang_id";
}

echo admin_func_right_table_row_start(4);
echo admin_func_right_table_data($upper_text, "", '12');
echo "<td colspan=3 align=right><nobr>";
echo "<a href=content_add.php?menu_id=$get_menu[id] class=add>";
echo "<span>добавить контент</span> ";
echo "<img src=\"g/n.gif\" border=0 alt=\"" . Dictionary::GetAdminWord(480) . "\" width=11 height=12>&nbsp;";
echo "</a>";
echo "</nobr>";

if (empty($search)) {
    echo "<td colspan=2 align=right></td>";
} else {
    echo "<td align=right></td>";
}

echo admin_func_right_table_row_start(4);

//собственно выведение грида с контентами - его шапка
echo admin_func_right_table_data('#', "", "0");
echo admin_func_right_table_data('Дата', "", "0");
echo admin_func_right_table_data(Dictionary::GetAdminWord(178), "100%", "0");
echo admin_func_right_table_data(Dictionary::GetAdminWord(478), "", "0");
echo admin_func_right_table_data(Dictionary::GetAdminWord(350), "", "0");

if (empty($search)) {
    echo admin_func_right_table_data(Dictionary::GetAdminWord(479), "", "0");
}

echo admin_func_right_table_data("<img src=g/v.gif border=0>", "", "2");

// собственно выбирание контентов
$contents       = array();
$select_content = DB::Query("select *, date_format(date, '%d.%m.%Y') as date, date_format(date, '%Y%m%d') as order_date from `?_content` where 1 {$where_plus} {$order_by}");

while ($row = DB::GetArray($select_content)) {

    if ($row['another_page'] != '') {
        $row['title'] = $row['another_page'];
    }

    if (isset($contents[$row['id']])) {
        $contents[$row['id']]['activity'][$row['lang_id']] = $row['active'];
    } else {
        $contents[$row['id']] = array(
            'id'       => $row['id'],
            'title'    => $row['title'],
            'date'     => $row['date'],
            'place'    => $row['place'],
            'view'     => $row['view'],
            'menu_id'  => $row['menu_id'],
            'activity' => array(
                $row['lang_id'] => $row['active']
            ),
            'cities'   => array()
        );
    }
}

$iteration = 0;
$languages = Lang::get();

foreach ($contents as $key => $content) {

    $langs = array();

    foreach ($content['activity'] as $langID => $activity) {
        if ($activity) {
            $langs[] = admin_func_right_link('<span  class=lang><font color="db131d">' . Lang::get($langID, 'title_short') . "</font></span>", "content.php?menu_id={$content['menu_id']}&id={$key}&lang_id={$content['lang_id']}&change_active=0", Lang::get($langID, 'title') . Dictionary::GetAdminWord(240), "");
        } else {
            $langs[] = admin_func_right_link('<span  class=lang><font color="CCCCCC">' . Lang::get($langID, 'title_short') . "</font></span>", "content.php?menu_id={$content['menu_id']}&id={$key}&lang_id={$content['lang_id']}&change_active=1", Lang::get($langID, 'title') . Dictionary::GetAdminWord(84), "");
        }
    }

    $title = $content['title'] . ' <font style="color: sienna;">(' . $content_view_type_title[$content['view']] . ')</font>';

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data(++$iteration, '', "2title");
    echo admin_func_right_table_data($content['date'], '', "2title");
    echo admin_func_right_table_data($title, "", "2title", false);
    echo admin_func_right_table_data(implode(', ', $langs), "", "2lang");
    echo admin_func_right_table_data("" .
            "<div  width=100% align=center>&nbsp;" .
            admin_func_right_link("<img src=\"g/e.gif\"  border=0 alt=\"" . Dictionary::GetAdminWord(371) . "\" width=10 height=12>", "content_edit.php?menu_id={$content['menu_id']}&id={$key}", "", "") .
            "&nbsp;&nbsp;<img src=g/ggg.gif style='position:relative; top:3px;'>&nbsp;&nbsp;" .
            admin_func_right_link("<img src=\"g/d.gif\"  border=0 alt=\"" . Dictionary::GetAdminWord(354) . "\" width=12 height=12 onclick=\"return confirm('" . Dictionary::GetAdminWord(481) . "\\n" . Dictionary::GetAdminWord(482) . "');\">", "content.php?menu_id={$content['menu_id']}&id={$key}&delete=yes", "", "") .
            "&nbsp;</div>", "", "2");

    if (empty($search)) {
        echo admin_func_right_table_data(admin_func_right_input("text", "content_place[{$key}]", $content['place'], "30", "3"), "", "1");
    }

    echo admin_func_right_table_data(admin_func_right_input("checkbox", "ids[]", $content['id'], "", "3"), "", "1");
}

echo "<input type=hidden name=menu_id value=\"$menu_id\">";
echo "<tr bgcolor=ffffff><td colspan=10 align=right>";
if (empty($search))
    echo " <input type=submit class=button value=\"" . Dictionary::GetAdminWord(484) . "\" name=edit_places>";
echo "<br><br>";
echo Dictionary::GetAdminWord(439);
//echo "<br>";
//echo " <input class=button name=delete_one type=submit value=\"". Dictionary::GetAdminWord(441) ."\" onclick=\"return confirm('". Dictionary::GetAdminWord(483) ."');\" >";
echo "<br>";
echo admin_func_menu_tree_select("", "new_menu_id", "", "&nbsp;&nbsp;&nbsp;&nbsp;", Dictionary::GetAdminWord(231), "0", "0");
echo " <input type=submit class=button name=change_menu value=\"" . Dictionary::GetAdminWord(495) . "\">";
echo "</td>";
echo "</table>";

$select_config = DB::Query("select education from `?_config` ");
if (@array_shift(DB::GetArray($select_config)) == "0") {
    echo "<table border=0 cellspacing=5 cellpadding=0 width=688 class=menu_bg_2>";
    echo "<tr >";
    echo "<td colspan=2>";
    echo "<table border=0 cellspacing=5 cellpadding=0 width=688 class=menu_bg>";
    echo "<tr >";
    echo "<td colspan=2><br>";
    echo "<b class=cap>" . Dictionary::GetAdminWord(356) . "</b>";
    echo "<tr>";
    echo "<td width=20>";
    echo "<span class=rus>" . Dictionary::GetAdminWord(873) . "</span>";
    echo "<td>";
    echo Dictionary::GetAdminWord(1159) . Dictionary::GetAdminWord(240) . Dictionary::GetAdminWord(485) . "";
    echo "<tr >";
    echo "<td width=20>";
    echo "<span style=\"color:cccccc; font-size: 10px; text-decoration: underline;\">" . Dictionary::GetAdminWord(873) . "</span>";
    echo "<td>";
    echo Dictionary::GetAdminWord(1159) . Dictionary::GetAdminWord(84) . Dictionary::GetAdminWord(485) . "";
    echo "<tr >";
    echo "<td width=20>";
    echo "<img src=\"g/n.gif\" border=0 alt=\"" . Dictionary::GetAdminWord(480) . "\" width=11 height=12>";
    echo "<td>";
    echo Dictionary::GetAdminWord(230);
    echo "<tr >";
    echo "<td width=20>";
    echo "<img src=\"g/e.gif\" border=0 alt=\"" . Dictionary::GetAdminWord(371) . "\" width=10 height=12>";
    echo "<td>";
    echo Dictionary::GetAdminWord(486);
    echo "<tr>";
    echo "<td width=20>";
    echo "<img src=\"g/d.gif\" border=0 alt=\"" . Dictionary::GetAdminWord(354) . "\" width=12 height=12>";
    echo "<td>";
    echo Dictionary::GetAdminWord(487);
    echo "</table>";

    echo "<br><br>";
    echo "<table border=0 cellspacing=5 cellpadding=0 width=600>";
    echo "<tr bgcolor=ffffff>";
    echo "<td colspan=2>";
    echo "<b class=cap>" . Dictionary::GetAdminWord(24) . "</b>";
    echo "<tr bgcolor=ffffff>";
    echo "<td>";
    echo "<select style=\"font-size: 10px;\"><option>" . Dictionary::GetAdminWord(231) . "</select>";
    echo "<td>";
    echo Dictionary::GetAdminWord(488);
    echo "<tr bgcolor=ffffff>";
    echo "<td>";
    echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
    echo "<tr class=menu_bg height=22>";
    echo "<th class=\"top\"><nobr>&nbsp;" . Dictionary::GetAdminWord(178) . "&nbsp;</nobr></th>";
    echo "</table>";
    echo "<td>";
    echo Dictionary::GetAdminWord(489);
    echo "<tr bgcolor=ffffff>";
    echo "<td>";
    echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
    echo "<tr class=menu_bg height=22>";
    echo "<th class=\"top\"><nobr>&nbsp;" . Dictionary::GetAdminWord(478) . "&nbsp;</nobr></th>";
    echo "</table>";
    echo "<td>";
    echo Dictionary::GetAdminWord(490);
    echo "<tr bgcolor=ffffff>";
    echo "<td>";
    echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
    echo "<tr class=menu_bg height=22>";
    echo "<th class=\"top\"><nobr>&nbsp;" . Dictionary::GetAdminWord(350) . "&nbsp;</nobr></th>";
    echo "</table>";
    echo "<td>";
    echo Dictionary::GetAdminWord(491);
    echo "<tr bgcolor=ffffff>";
    echo "<td>";
    echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
    echo "<tr class=menu_bg height=22>";
    echo "<th class=\"top\"><nobr>&nbsp;" . Dictionary::GetAdminWord(479) . "&nbsp;</nobr></th>";
    echo "</table>";
    echo "<td>";
    echo Dictionary::GetAdminWord(492);
    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080 height=22>";
    echo "<tr class=menu_bg>";
    echo "<th class=\"top\"><nobr>&nbsp;<img src=g/v.gif  border=0>&nbsp;</nobr></th>";
    echo "</table>";
    echo "<td>";
    echo "" . Dictionary::GetAdminWord(493) . "<br> &nbsp;&nbsp;" . Dictionary::GetAdminWord(493) . "";
    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<input type=submit class=button value=\"" . Dictionary::GetAdminWord(484) . "\">";
    echo "<td>";
    echo Dictionary::GetAdminWord(496);
    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<input type=submit class=button value=\"" . Dictionary::GetAdminWord(441) . "\">";
    echo "<td>";
    echo Dictionary::GetAdminWord(496);
    echo "</table>";
    echo "</td>";
    echo "</tr>";
    echo"</table>";
}
//конец таблицы
echo '</td></tr></table>';

include(BASEPATH . "admin/admin_footer.php");
?>