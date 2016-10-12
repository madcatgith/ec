<?php
function console_log($data){
    echo '<script>';
    echo 'console.log('. json_encode($data) .')';
    echo '</script>';
}

$id      = $_REQUEST['id'];
$include = include($_SERVER['DOCUMENT_ROOT'] . "/admin/admin_top.php");

if (!$include or $adm_wellcome != "Y") {
    exit;
}

include(BASEPATH . "includes.php");
include(BASEPATH . "admin/functions_adds.php");

if ($url == "http://") {
    $url = '';
}

if ($imgurl == "http://") {
    $imgurl = '';
}

if ($imgurl2 == "http://") {
    $imgurl2 = '';
}

if ($imgurl3 == "http://") {
    $imgurl3 = '';
}

if ($imgurl4 == "http://") {
    $imgurl4 = '';
}

if ($edit_one != "") {

    $select_langs = DB::Query("select * from `?_lang` where active=1 order by place");

    while ($get_lang = DB::GetArray($select_langs)) {

        $lang_id = $get_lang['id'];

        if ($another_page[$lang_id] == "http://") {
            $another_page[$lang_id] = "";
        }

        if ($another_page[$lang_id] . $title[$lang_id] != "") {

            eval("\$text[$lang_id] = \$text$lang_id;");

            $select_dublikat2 = DB::Query("select count(*)+0 from `?_content` where id!=$id and title='$title[$lang_id]' and lang_id='$lang_id' and menu_id='$menu_id' ");
            $dublikat         = $dublikat + @array_shift(DB::GetArray($select_dublikat2));
        }
    }

    $dublikat = 0;

    if ($dublikat > 0) {
        $sys_message[] = Dictionary::GetAdminWord(534);
    } else {

        if ($old_menu_id != $menu_id) {
            $update_places    = DB::Query("update `?_content` set place=place-1 where place>$place and menu_id=$old_menu_id");
            $select_max_place = DB::Query("select max(place)+0 from `?_content` where menu_id=$menu_id ");
            $place            = @array_shift(DB::GetArray($select_max_place)) + 1;
        }

        $update_all_contents_id = DB::Query("
			update `?_content`
			set menu_id='$menu_id',place='$place',imgurl='$imgurl',imgurl2='$imgurl2',imgurl3='$imgurl3', imgurl4='$imgurl4'
			,cnc = '$cnc',gallery_id='$gallery_id'
			view='$view',date='$year-$month-$day',time='" . $hour . ":" . $minute . ":" . $secund . "',url='$url',authorization='$authorization'
			where id='$id' ");

        $select_langs = DB::Query("select * from `?_lang` where active=1 order by place");

        while ($get_lang = DB::GetArray($select_langs)) {

            $lang_id = $get_lang['id'];

            if ($another_page[$lang_id] . $title[$lang_id] != "") {
                $SEOTitle[$lang_id]     = ConvertHtmlToAlt($SEOTitle[$lang_id]);
                $title[$lang_id]        = ConvertHtmlToAlt($title[$lang_id]);
                $slogan[$lang_id]       = ConvertHtmlToAlt($slogan[$lang_id]);
                $announcement[$lang_id] = ConvertHtmlToAlt($announcement[$lang_id]);
                $text[$lang_id]         = ConvertHtmlToAlt($text[$lang_id]);


                if (defined('_INSTALL_TIPS') && (_INSTALL_TIPS == 1)) {
                    if ($full_tips == 1) {
                        //---убираем пробелы, а также внутри пробелы между типсами---
                        $tips[$lang_id] = trim($tips[$lang_id]);
                        if ($tips[$lang_id] != '') {
                            $tips[$lang_id] = explode(';', $tips[$lang_id]);
                            foreach ($tips[$lang_id] as $key => $tip) {
                                $tips[$lang_id][$key] = trim($tip);
                            }
                            $tips[$lang_id] = implode(';', $tips[$lang_id]);
                        }
                        //---конец подготовки записи в базу данных---
                    } else {
                        //---обьединяем масив в строку---
                        if (sizeof($tips[$lang_id]) > 0) {
                            $tips[$lang_id] = implode(';', $tips[$lang_id]);
                        } else {
                            $tips[$lang_id] = '';
                        }
                        //---конец обьединения---
                    }
                }
                $does_it_exist = DB::Query("select place from `?_content` where id=$id ");
                $does_it_exist = DB::Query("select * from `?_content` where id=$id and lang_id=$lang_id ");

                if (@mysql_num_rows($does_it_exist) == "") {
                    $edit_content = DB::Query("insert into `?_content`
						(
						imgurl
                        ,alt
						,imgurl2
						,imgurl3
						,imgurl4
						,gallery_id
						,slogan
						,cnc
						,id
						,menu_id
						,active
						,place
						,url
						,title
						,announcement
						,tips
						,text
						,date
						,time
						,view
						,authorization
						,another_page
						,lang_id
						,SEODescription
						,SEOKeywords
						,SEOTitle
						,category_id) values
						(
						'{$imgurl}'
                                                ,'" . mysql_real_escape_string(clearVal($_POST['alt'][$lang_id])) . "'
						,'{$imgurl2}'
						,'{$imgurl3}'
						,'{$imgurl4}'
						,'{$_POST["gallery_id"]}'
						,'{$slogan[$lang_id]}'
						,'$cnc'
						,'$id'
						,'$menu_id'
						,'$active[$lang_id]'
						,'$place'
						,'$url'
						,'$title[$lang_id]'
						,'$announcement[$lang_id]'
						,'$tips[$lang_id]'
						,'$text[$lang_id]'
						,'$year-$month-$day'
						,'" . $hour . ":" . $minute . ":" . $secund . "'
						,'$view'
						,'$authorization'
						,'$another_page[$lang_id]'
						,'$lang_id'
						,'$SEODescription[$lang_id]'
						,'$SEOKeywords[$lang_id]'
						,'$SEOTitle[$lang_id]'
						,'$category_id')");
                } else {

                    $edit_content = DB::Query("update `?_content` set
                        alt='" . mysql_real_escape_string(clearVal($_POST['alt'][$lang_id])) . "',
						slogan='{$slogan[$lang_id]}',gallery_id='" . mysql_real_escape_string(clearVal($_POST['gallery_id'])) . "',
						active='$active[$lang_id]',title='$title[$lang_id]',announcement='$announcement[$lang_id]',tips='$tips[$lang_id]',
						text='$text[$lang_id]',another_page='$another_page[$lang_id]',
						SEODescription = '$SEODescription[$lang_id]',SEOKeywords='$SEOKeywords[$lang_id]', SEOTitle = '$SEOTitle[$lang_id]', category_id = '$category_id'
						where lang_id='$lang_id' and id='$id' ");
                }                    //-------------------------------------

                if (defined('_MODULE_TAG') && 2 === 1) {
                    $sql_linked_ids = array();
                    foreach ($connected_ids as $key => $value) {
                        if ($value === 'true') {
                            $sql_linked_ids[] = "('{$id}', '{$key}', 'content')";
                        }
                    }

                    DB::Query("DELETE from ?_tag_col where `item_id` = $id and `item_table` = 'content'");

                    if (count($sql_linked_ids) > 0) {
                        DB::Query("INSERT into ?_tag_col (`item_id`,`link_id`,`item_table`)
							values " . implode(', ', $sql_linked_ids));
                    }
                }

                if (defined('_INSTALL_TIPS') && (_INSTALL_TIPS == 1)) {
                    if ($full_tips == 1) {
                        //----------------удаление старых типсов------------------
                        $tips_old[$lang_id] = trim($tips_old[$lang_id]);
                        if ($tips_old[$lang_id] != '') {
                            $temp_tips_old = '';
                            $temp_tips_old = explode(';', $tips_old[$lang_id]);
                            if ($tips[$lang_id] == '') {
                                foreach ($temp_tips_old as $tipi_old) {
                                    $tipi_old = trim($tipi_old);
                                    $count    = 0;
                                    $count    = array_shift(DB::GetArray(DB::Query("select count(*)+0 from `?_content` where INSTR(tips,'$tipi_old')")));
                                    if ($count == 0) {
                                        DB::Query("delete from `?_tips` where title='$tipi_old' and lang_id=$lang_id ");
                                    }
                                }
                            } else {
                                $temp_array_tip = explode(';', $tips[$lang_id]);
                                foreach ($temp_tips_old as $tipi_old) {
                                    $tipi_old = trim($tipi_old);
                                    if (!in_array($tipi_old, $temp_array_tip)) {
                                        $count = 0;
                                        $count = array_shift(DB::GetArray(DB::Query("select count(*)+0 from `?_content` where INSTR(tips,'$tipi_old')")));
                                        if ($count == 0) {
                                            DB::Query("delete from `?_tips` where title='$tipi_old' and lang_id=$lang_id ");
                                        }
                                    }
                                }
                            }
                        }
                        //----------------конец удаления старых типсов------------
                        //-----------добавление типсов------------------
                        if ($tips[$lang_id] != '') {
                            $temp_array_tip = '';
                            $temp_array_tip = explode(';', $tips[$lang_id]);
                            foreach ($temp_array_tip as $tipi_new) {
                                $select_tip = array_shift(DB::GetArray(DB::Query("select count(*)+0 from `?_tips` where title='$tipi_new' and lang_id=$lang_id ")));
                                if ($select_tip == 0) {
                                    $select_max = array_shift(DB::GetArray(DB::Query("select max(id)+0 from `?_tips`")));
                                    if (!isset($select_max)) {
                                        $select_max = 0;
                                    }
                                    $select_max++;
                                    DB::Query("insert into `?_tips` (id,title,lang_id) values ($select_max,'$tipi_new',$lang_id)");
                                }
                            }
                        }
                        //-----------конец добавления типсов------------
                    }
                }


                if (!$edit_content) {
                    $sys_message[] = "" . Dictionary::GetAdminWord(457) . " \"$title[$lang_id]\"...";
                } else {
                	
					Helpers::findIBlock($id, $lang_id, $text[$lang_id]);
					
                    $sys_message[] = "" . Dictionary::GetAdminWord(131) . " \"$title[$lang_id]\" " . Dictionary::GetAdminWord(459) . " (<a href=content.php?menu_id=$menu_id>" . Dictionary::GetAdminWord(229) . "</a>).";
                }
            }
        }
    }
}

if (defined('_MODULE_TAG') && 2 === 1) {

    $tags_s = DB::Query("select link_id from `?_tag_col` tc where tc.item_id = $id and tc.item_table= 'content'", true);
    $tags   = array();

    while ($get = DB::GetRow($tags_s)) {
        $tags[$get['link_id']] = $get['link_id'];
    }

    $wmpTree = new wmpTree();

    $wmpTree->BranchesSelect = wmpTree::$PreDefSql['tagBranches'];
    $wmpTree->LeavesSelect   = wmpTree::$PreDefSql['tagLeaves'];

    $wmpTree->IsCheckBoxedBranches = false;
    $wmpTree->IsCheckBoxedLeaves   = true;

    $wmpTree->IdsName = 'connected_ids';

    $wmpTree->ShowLeaves = true;

    $treeBody = $wmpTree->func_items_tree("item_id", "#", "&nbsp;&nbsp;", Dictionary::GetAdminWord(231), ""
            , "width:300px; float:left; font-size:9px; border:1px solid silver;"
            , array('checked' => $tags, 'ignored' => array(), 'undetermined' => array()), true, '');
}
// Заголовок
echo admin_func_top(Dictionary::GetAdminWord(486));
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

// Вывод сообщений про обработанность различных действий
echo admin_func_sys_message($sys_message);

echo "<form method=post action=content_edit.php name=content>";
echo admin_func_right_table_start(2);
echo "$formauthorization";
echo "<input type=hidden name=edit_one value=1>";

$select_langs = DB::Query("select * from `?_lang` where active=1 order by place");
while ($get_lang     = DB::GetArray($select_langs)) {
    $lang_id = $get_lang['id'];

    $select_content = DB::Query("select slogan,cnc,id,authorization,SEOKeywords,SEODescription,tips,active,menu_id,place,url,imgurl,imgurl2,imgurl3,imgurl4,gallery_id,title,announcement,text,view,date_format(date, '%Y') as year,date_format(date, '%m') as month,date_format(date, '%d') as day,time,another_page, SEOTitle, category_id from `?_content` where id=$id and lang_id=$lang_id ");
    $get_content    = DB::GetArray($select_content);
    if ($get_content['id'] != "") {
        $active[$lang_id]       = $get_content['active'];
        $menu_id                = $get_content['menu_id'];
        $old_menu_id            = $get_content['menu_id'];
        $place                  = $get_content['place'];
        $url                    = $get_content['url'];
        $imgurl                 = $get_content['imgurl'];
        $imgurl2                = $get_content['imgurl2'];
        $imgurl3                = $get_content['imgurl3'];
        $imgurl4                = $get_content['imgurl4'];
        $view                   = $get_content['view'];
        $authorization          = $get_content['authorization'];
        $title[$lang_id]        = $get_content['title'];
        $announcement[$lang_id] = $get_content['announcement'];
        $slogan[$lang_id]       = $get_content['slogan'];
        $text[$lang_id]         = $get_content['text'];
        $tips[$lang_id]         = $get_content['tips'];
        $SEOTitle[$lang_id]     = $get_content['SEOTitle'];
        $gallery_id             = $get_content['gallery_id'];
        $cnc                    = $get_content['cnc'];

        $text[$lang_id] = str_replace("&temp_gt;", "&gt;", $text[$lang_id]);
        $text[$lang_id] = str_replace("&temp_lt;", "&lt;", $text[$lang_id]);

        $announcement[$lang_id] = str_replace("&temp_gt;", "&gt;", $announcement[$lang_id]);
        $announcement[$lang_id] = str_replace("&temp_lt;", "&lt;", $announcement[$lang_id]);

        $slogan[$lang_id] = str_replace("&temp_gt;", "&gt;", $slogan[$lang_id]);
        $slogan[$lang_id] = str_replace("&temp_lt;", "&lt;", $slogan[$lang_id]);

        $another_page[$lang_id] = $get_content['another_page'];
        $alt[$lang_id]          = $get_content['alt'];
        $year                   = $get_content['year'];
        $month                  = $get_content['month'];
        $day                    = $get_content['day'];
        $category_id            = $get_content['category_id'];
        $temp_time              = @explode(":", $get_content['time']);
        $hour                   = $temp_time['0'];
        $minute                 = $temp_time['1'];
        $secund                 = $temp_time['2'];

        $SEOKeywords[$lang_id]    = $get_content['SEOKeywords'];
        $SEODescription[$lang_id] = $get_content['SEODescription'];
    }
}

echo "<input type=hidden name=id value=\"$id\">";
echo "<input type=hidden name=place value=\"$place\">";
echo "<input type=hidden name=old_menu_id value=\"$old_menu_id\">";
//echo "<input type=hidden name=menu_id value=\"$menu_id\">";
echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data(Dictionary::GetAdminWord(232), "", "1lt");
echo "<td class=tdtr>";

//echo admin_func_menu_tree_select("", "menu_id", "", "&nbsp;&nbsp;&nbsp;&nbsp;", Dictionary::GetAdminWord(231), "0", "0");
echo Helpers::getMenuSelect('menu_id', $menu_id);

echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data(Dictionary::GetAdminWord(233), "", "1l");
echo "<td class=tdr><select name=day>";

$time = time();

if ($day == "")
    $day = date("d", ($time));

$start_of_begin = 1;
$end_date       = 31;

while ($start_of_begin <= $end_date) {
    if (strlen($start_of_begin) == 1) {
        $start_of_begin = "0" . $start_of_begin;
    }

    if ($day == $start_of_begin) {
        $selected = "selected";
    } else {
        $selected = "";
    }
    echo "
		<option $selected>$start_of_begin";
    $start_of_begin = $start_of_begin + 1;
}

echo "</select><select name=month>";

if ($month == "")
    $month = date("m", ($time));

$start_of_begin = 1;
$end_date       = 12;

while ($start_of_begin <= $end_date) {
    if (strlen($start_of_begin) == 1) {
        $start_of_begin = "0" . $start_of_begin;
    }

    if ($month == $start_of_begin) {
        $selected = "selected";
    } else {
        $selected = "";
    }
    echo "
		<option $selected>$start_of_begin";
    $start_of_begin = $start_of_begin + 1;
}
echo "
	</select><select name=year>";

$start_of_begin = date("Y", ($time));

$end_date = $start_of_begin + 5;
if ($year == "")
    $year     = $start_of_begin;
$start_of_begin-=10;

while ($start_of_begin <= $end_date) {
    if ($year == $start_of_begin) {
        $selected = "selected";
    } else {
        $selected = "";
    }
    echo "<option $selected>$start_of_begin";
    $start_of_begin = $start_of_begin + 1;
}
echo "</select>";

echo "&nbsp;&nbsp;";

// час
echo "<select name=hour>";
if ($hour == "")
    $hour = date("H", ($time));

$start_of_begin = 0;
$end_date       = 23;
while ($start_of_begin <= $end_date) {
    if (strlen($start_of_begin) == 1) {
        $start_of_begin = "0" . $start_of_begin;
    }

    if ($hour == $start_of_begin) {
        $selected = "selected";
    } else {
        $selected = "";
    }
    echo "
		<option $selected>$start_of_begin";
    $start_of_begin = $start_of_begin + 1;
}
echo "</select>";

// минута
echo ":<select name=minute>";
if ($minute == "")
    $minute = date("i", ($time));

$start_of_begin = 0;
$end_date       = 59;
while ($start_of_begin <= $end_date) {
    if (strlen($start_of_begin) == 1) {
        $start_of_begin = "0" . $start_of_begin;
    }

    if ($minute == $start_of_begin) {
        $selected = "selected";
    } else {
        $selected = "";
    }
    echo "
		<option $selected>$start_of_begin";
    $start_of_begin = $start_of_begin + 1;
}
echo "</select>";

// секунда
echo ":<select name=secund>";
if ($secund == "")
    $secund = date("s", ($time));

$start_of_begin = 0;
$end_date       = 59;
while ($start_of_begin <= $end_date) {
    if (strlen($start_of_begin) == 1) {
        $start_of_begin = "0" . $start_of_begin;
    }

    if ($secund == $start_of_begin) {
        $selected = "selected";
    } else {
        $selected = "";
    }
    echo "
		<option $selected>$start_of_begin";
    $start_of_begin = $start_of_begin + 1;
}
echo "</select>";


if (count($content_view_type_title) > 1) {
    echo "<tr bgcolor=#FEF8E0>";
    echo "<td class=tdl><nobr><b>" . Dictionary::GetAdminWord(234) . " </b> &nbsp;</nobr></td>";
    echo "<td class=tdr><select name=view>";

    while (list($k, $v) = @each($content_view_type_title)) {
        $selected = "";
        if ($view == $k)
            $selected = "selected";

        echo "<option value=$k $selected>$v";
    }

    echo "</select>";
} else {
    echo '<input type=hidden name=view value="default">';
}
/*
  echo admin_func_right_table_row_start(1);
  echo admin_func_right_table_data(Dictionary::GetAdminWord(235), "", "1l");
  echo "<td class=tdr><select name=authorization>";

  $selected = "";
  if($authorization == 0) $selected['0'] = "selected";
  if($authorization == 1) $selected['1'] = "selected";

  echo "<option value=0 {$selected[0]}>". Dictionary::GetAdminWord(236) ."";
  echo "<option value=1 {$selected[1]}>". Dictionary::GetAdminWord(237) ."";
  echo "</select>";

if ($url == "") {
    $url = "http://";
}

echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data(Dictionary::GetAdminWord(6) . Dictionary::GetAdminWord(1150), "", "2li");
echo admin_func_right_table_data(admin_func_right_input("", "url", $url, 381, 3), "", "2r");
*/
echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data(Dictionary::GetAdminWord(238), "", "2l");
echo admin_func_right_table_data(admin_func_right_input("", "imgurl", $imgurl, "350", 3) . "&nbsp;" . admin_func_right_input("submit", "", Dictionary::GetAdminWord(239), "", "onClick=\"newwin2('files.php?show=u',720,520); return false;\""), "", "1r");
/*
echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data(Dictionary::GetAdminWord(238), "", "2l");
echo admin_func_right_table_data(admin_func_right_input("", "imgurl2", $imgurl2, "350", 3, 'id="imgurl2"') . "&nbsp;" . admin_func_right_input("submit", "", Dictionary::GetAdminWord(239), "", "onClick=\"newwin2('files.php?show=jqGrid&obj=imgurl2',720,520); return false;\""), "", "1r");

/*
  echo admin_func_right_table_row_start(1);
  echo admin_func_right_table_data(Dictionary::GetAdminWord(238) . " Для слайдера на главной [280 x 225]", "", "2lb");
  echo admin_func_right_table_data( admin_func_right_input("", "imgurl4", $imgurl4, "350", 3, 'id="imgurl4"') ."&nbsp;". admin_func_right_input( "submit", "", Dictionary::GetAdminWord(239), "", "onClick=\"newwin2('files.php?show=jqGrid&obj=imgurl4',720,520); return false;\""), "", "1br");

  echo admin_func_right_table_row_start(1);
  echo admin_func_right_table_data(Dictionary::GetAdminWord(238) . " Баннер на главной [1180 x 325]", "", "2l");
  echo admin_func_right_table_data( admin_func_right_input("", "imgurl3", $imgurl3, "350", 3, 'id="imgurl3"') ."&nbsp;". admin_func_right_input( "submit", "", Dictionary::GetAdminWord(239), "", "onClick=\"newwin2('files.php?show=jqGrid&obj=imgurl3',720,520); return false;\""), "", "1r");


if (defined('_MODULE_TAG')) {
    echo admin_func_right_table_row_start(1);
    echo admin_func_right_table_data("Теги:", "", "2lbi");
    echo admin_func_right_table_data($treeBody, "", "1br");
}
 * 
 */

echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data("Слайдер", "", "1lb");
echo admin_func_right_table_data(Helpers::getGallerySelect('gallery_id', $gallery_id), "", "2br");

// единый чпу для языковых версий
echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data("ЧПУ", "", "1lb");

$temp_cnc = '<input type="text" id="cnc" name="cnc" value="' . $cnc . '" style="width: 335px;"><br/>';
$temp_cnc .= '<input type="button" id="cnc_gen" value="Генерировать ЧПУ">';
$temp_cnc .= '<input type="button" id="cnc_check" value="Проверить ЧПУ">';
$temp_cnc .= '<div style="width: 335px; height: 15px; font-size: 12px; padding: 2px 0 2px 0;" id="alert_cnc"></div>';

echo admin_func_right_table_data($temp_cnc, "", "2br");
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#cnc_gen").click(function() {
            var my_string = '';
            $('input[name^="title"]').each(function() {
                var val = $(this).val();
                if (val != '') {
                    my_string = val;
                    return false;
                }
            })
            if (my_string == '') {
                $('#alert_cnc').html('<span style="color: 3f02222;">Введите название меню и нажмите генерировать</div>');
            } else {
                $('#cnc').val(my_replace(alphabet, my_string));
                $('#alert_cnc').html('<span style="color: #18c42c;">ЧПУ успешно сгенерирован</span>');
            }
        });
        $("#cnc_check").click(function() {
            var my_string = $('#cnc').val();
            if (my_string == '') {
                $('#alert_cnc').html('<span style="color: 3f02222;">Поле ЧПУ пустое, сгенерируйте или введите свой</div>');
            } else {
                $.post("adm_ajax.php", {action : "check_cnc", name : my_string, type : "content", menu : "<?= $id; ?>", menu_id : "<?= $menu_id; ?>", lang : "1"},
                function(data) {
                    if (data.flag == 2) {
                        $('#alert_cnc').html('<span style="color: #18c42c;">данный ЧПУ удовлетворяет требованиям<span>');
                    } else if (data.flag == 1) {
                        $('#alert_cnc').html('<span style="color: 3f02222;">такой ЧПУ уже существует</span>');
                    } else {
                        $('#alert_cnc').html('<span style="color: 3f02222;">Ошибка проверки ЧПУ</span>');
                    }
                }, "json");
            }
        });
    });
</script>
<?php
$select_langs = DB::Query("select * from `?_lang` where active=1 order by place");
while ($get_lang     = DB::GetArray($select_langs)) {
    $lang_id           = $get_lang['id'];
    $checked[$lang_id] = "";
    if ($active[$lang_id] == 1)
        $checked[$lang_id] = "checked";

    unset($checked);
    if (!empty($active[$lang_id]))
        $checked = "checked";
    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data("<br>", "", "2");
    echo admin_func_right_table_row_start(3);
    echo admin_func_right_table_data("<font color=white>[{$get_lang['title']}]", "", 1);
    echo admin_func_right_table_data(admin_func_right_input("checkbox", "active[$lang_id]", 1, "", $checked) . "<font color=white>" . Dictionary::GetAdminWord(240), "", 2);
    echo admin_func_right_table_row_start(4);
    echo admin_func_right_table_data(Dictionary::GetAdminWord(13), "", "2li");
    echo admin_func_right_table_data(admin_func_right_input("", "another_page[$lang_id]", $another_page[$lang_id], "425", $checked), "", "2r");
    if (defined('_INSTALL_TIPS') && (_INSTALL_TIPS == 1)) {
        if ($full_tips == 1) {
            echo admin_func_right_table_row_start(4);
            echo admin_func_right_table_data("Облако тагов", "", "2li");
            echo admin_func_right_table_data("<input type=hidden name=\"tips_old[$lang_id]\" value=\"{$tips[$lang_id]}\" />" . admin_func_right_input("", "tips[$lang_id]", $tips[$lang_id], "425", 3), "", "2r");
        } else {
            $tips_temp  = explode(';', $tips[$lang_id]);
            $query_tips = DB::Query("select * from `?_tips` where lang_id=$lang_id");
            echo admin_func_right_table_row_start(1);
            echo admin_func_right_table_data("Выберите таги", "", "1l");
            echo "<td class=tdr><input type=hidden name=\"tips_old[$lang_id]\" value=\"{$tips[$lang_id]}\" /><select name=\"tips[$lang_id][]\" multiple=\"multiple\" size=\"5\">";
            while ($get_tip    = DB::GetArray($query_tips)) {
                $selected = "";
                if (in_array($get_tip['title'], $tips_temp))
                    $selected = "selected";
                echo "<option value=\"{$get_tip['title']}\" $selected>{$get_tip['title']}</option>";
            }
            echo "</select>";
        }
    }
    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data("&nbsp;", "", "7lr");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data('Title', "", "1l");
    $temp_in_title = '<input type="text" name="SEOTitle[' . $lang_id . ']" id="SEOTitle_' . $lang_id . '" value="' . $SEOTitle[$lang_id] . '" style="width: 335px;" />';
    echo admin_func_right_table_data($temp_in_title, "", "2r");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data(Dictionary::GetAdminWord(178), "", "1l");
    $temp_in_title = '<input type="text" name="title[' . $lang_id . ']" id="title_' . $lang_id . '" value="' . $title[$lang_id] . '" style="width: 335px;" />';
    echo admin_func_right_table_data($temp_in_title, "", "2r");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data("SEODescription", "", "1lb");
    echo admin_func_right_table_data(admin_func_right_input("", "SEODescription[$lang_id]", $SEODescription[$lang_id], 335, 3), "", "2br");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data("SEOKeywords", "", "1lb");
    echo admin_func_right_table_data(admin_func_right_input("", "SEOKeywords[$lang_id]", $SEOKeywords[$lang_id], 335, 3), "", "2br");

    // echo admin_func_right_table_row_start(2);
    // echo admin_func_right_table_data("Слоган", "", "1l");
    // echo admin_func_right_table_data( admin_func_right_input( "", "slogan[$lang_id]", $slogan[$lang_id], 335, 3), "", "2r");

    //echo admin_func_right_table_row_start(1);
    //echo admin_func_right_table_data('Альтернативный текст', "", "2lb");
    //echo admin_func_right_table_data(admin_func_right_input("", "alt[$lang_id]", $alt[$lang_id], 380, 3), "", "1br");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data(Dictionary::GetAdminWord(241) . ' <a href="#" onclick="window.open(\'/admin/ckeditor/?name=announcement'. $lang_id .'\', \'CKEditor\', \'resizable=yes,width=\'+screen.width+\',height=\'+screen.height+\',left=30,top=30\');">Html редактор</a>', "", "3l");
    echo admin_func_right_table_data("<textarea id=\"announcement$lang_id\" name=\"announcement[$lang_id]\" style=\"width:425px;height:80px;\">$announcement[$lang_id]</textarea>", "", "2r");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data(Dictionary::GetAdminWord(242), "", "3l");
    echo admin_func_right_table_data('<a href="#" onclick="window.open(\'/admin/ckeditor/?name=text'. $lang_id .'\', \'CKEditor\', \'resizable=yes,width=\'+screen.width+\',height=\'+screen.height+\',left=30,top=30\');">Html редактор</a>', "", "2r");
    
    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data("<textarea id=text$lang_id name=text$lang_id style=\"width:595px;height:170px;\">$text[$lang_id]</textarea>", "", "7lbr");
}

echo "
	<script language=\"JavaScript\">
	function newwin(url,width,height) {
	window.open(url, 'window', 'width='+width+',height='+height+',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes');
	}
	</script>
	<script language=\"JavaScript\">
	function newwin2(url,width,height) {
	window.open(url, 'window', 'width='+width+',height='+height+',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes');
	}
	</script>";

echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data(admin_func_right_input("submit", "", Dictionary::GetAdminWord(538), "163", 1), "", "7");
echo admin_func_right_table_end();
echo"</form>";

$select_config = DB::Query("select education from `?_config` ");
if (@array_shift(DB::GetArray($select_config)) == "0") {

    echo "<br><br>";
    echo "<table border=0 cellspacing=5 cellpadding=0 width=600>";
    echo "<tr bgcolor=ffffff>";
    echo "<td colspan=2>";
    echo "<b>" . Dictionary::GetAdminWord(246) . "</b>";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=#FEF8E0>";
    echo "<td valign=top><nobr><b>" . Dictionary::GetAdminWord(232) . " &nbsp;</b></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo Dictionary::GetAdminWord(535);

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=#FEF8E0>";
    echo "<td valign=top><nobr>" . Dictionary::GetAdminWord(248) . "</nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(248) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(194) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=#FEF8E0>";
    echo "<td valign=top><nobr><b>" . Dictionary::GetAdminWord(233) . " &nbsp;</b></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(250) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(251) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=#FEF8E0>";
    echo "<td valign=top><nobr><b>" . Dictionary::GetAdminWord(234) . " &nbsp;</b></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(252) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(253) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=#FEF8E0>";
    echo "<td valign=top><nobr><b>" . Dictionary::GetAdminWord(235) . " &nbsp;</b></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(254) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(255) . " <br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(256) . "" . Dictionary::GetAdminWord(1147) . "<a href=users.php>" . Dictionary::GetAdminWord(200) . "</a>" . Dictionary::GetAdminWord(1147) . " ";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=#FEF8E0>";
    echo "<td valign=top><nobr><i>" . Dictionary::GetAdminWord(257) . " &nbsp;</i></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(258) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(259) . " <i>" . Dictionary::GetAdminWord(1146) . "</i>.";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=#FEF8E0>";
    echo "<td valign=top><nobr><i>" . Dictionary::GetAdminWord(238) . " &nbsp;</i></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(260) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(261) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<trы>";
    echo "<td valign=top><font color=white><b>[" . Dictionary::GetAdminWord(262) . "]</b></font></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(263) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(264) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr>";
    echo "<td valign=top><nobr><font color=white><input type=checkbox name=active['1'] value=1 > " . Dictionary::GetAdminWord(240) . "</nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(537) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(266) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=f5f5f5>";
    echo "<td valign=top><nobr><i>" . Dictionary::GetAdminWord(267) . " &nbsp;</i></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(268) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(269) . " <br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(270) . "<br>&nbsp;&nbsp;<i>" . Dictionary::GetAdminWord(1146) . "</i>";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top><nobr><b>" . Dictionary::GetAdminWord(178) . " &nbsp;</b></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo Dictionary::GetAdminWord(271);

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top><nobr><font class=blue><u>" . Dictionary::GetAdminWord(243) . "</nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(272) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(273) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top><nobr><font class=blue><u>" . Dictionary::GetAdminWord(244) . "</nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(274) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(275) . " <br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(276) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<input type=submit class=button value=\" " . Dictionary::GetAdminWord(538) . "\">";
    echo "<td valign=top>";
    echo Dictionary::GetAdminWord(539);
    echo "</table>";
}

echo '</td></tr></table>';
include(BASEPATH . "admin/admin_footer.php");
?>
