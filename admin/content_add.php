<?php
$include = include ($_SERVER['DOCUMENT_ROOT'] . '/admin/admin_top.php');

if (!$include or $adm_wellcome != "Y") {
    exit;
}

include BASEPATH . 'includes.php';
include BASEPATH . 'admin/functions_adds.php';

if ($url == "http://") {
    $url = "";
}

if ($imgurl == "http://") {
    $imgurl = "";
}

if ($imgurl2 == "http://") {
    $imgurl2 = "";
}

if ($imgurl3 == "http://") {
    $imgurl3 = "";
}

if ($imgurl4 == "http://") {
    $imgurl4 = "";
}

if ($add_new_one != "") {

    $select_max_id = DB::Query("select max(id)+0 from `?_content` ");
    $next_id       = @array_shift(DB::GetArray($select_max_id)) + 1;
    $next_place    = 1;
    $select_langs  = DB::Query("select * from `?_lang` where active=1 order by place");

    while ($get_lang = DB::GetArray($select_langs)) {

        $lang_id = $get_lang['id'];

        if ($another_page[$lang_id] == "http://") {
            $another_page[$lang_id] = "";
        }

        if ($title[$lang_id] != "") {

            eval("\$text[$lang_id] = \$text$lang_id;");

            $select_dublikat2 = DB::Query("select count(*)+0 from `?_content` where title='$title[$lang_id]' and lang_id='$lang_id' and menu_id='$menu_id' ");
            $dublikat         = $dublikat + @array_shift(DB::GetArray($select_dublikat2));
        }
    }

    // даем возможность вводить контент с одинаковым названием
    $dublikat = 0;

    if ($dublikat > 0) {
        $sys_message[] = Dictionary::GetAdminWord(224);
    } else {
        if (defined('_MODULE_TAG') && 2 == 1) {

            $sql_linked_ids = array();

            foreach ($connected_ids as $key => $value) {
                if ($value === 'true') {
                    $sql_linked_ids[] = "('{$next_id}', '{$key}', 'content')";
                }
            }

            if (count($sql_linked_ids) > 0) {
                DB::Query("INSERT into ?_tag_col (`item_id`,`link_id`,`item_table`) values " . implode(', ', $sql_linked_ids));
            }
        }

        $update_places = DB::Query("update `?_content` set place=place+1 where menu_id=$menu_id ");
        $select_langs  = DB::Query("select * from `?_lang` where active=1 order by place");

        while ($get_lang = DB::GetArray($select_langs)) {

            $lang_id = $get_lang['id'];

            if ($another_page[$lang_id] . $title[$lang_id] != "") {

                $SEOTitle[$lang_id]     = ConvertHtmlToAlt($SEOTitle[$lang_id]);
                $title[$lang_id]        = ConvertHtmlToAlt($title[$lang_id]);
                $announcement[$lang_id] = ConvertHtmlToAlt($announcement[$lang_id]);
                $text[$lang_id]         = ConvertHtmlToAlt($text[$lang_id]);
                $slogan[$lang_id]       = ConvertHtmlToAlt($slogan[$lang_id]);


                $q = "insert into `?_content` (slogan,gallery_id,cnc,id,menu_id,active,place,url,imgurl, alt ,imgurl2,imgurl3,imgurl4,title,announcement,text,date,time,view,authorization,another_page,lang_id,SEODescription,SEOKeywords, SEOTitle, category_id) values
					('{$slogan[$lang_id]}','{$gallery_id}','{$cnc}','$next_id','$menu_id','$active[$lang_id]','$next_place','$url','$imgurl', '" . mysql_real_escape_string(clearVal($_POST['alt'][$lang_id])) . "','$imgurl2','$imgurl3','{$imgurl4}',
					'$title[$lang_id]','$announcement[$lang_id]','$text[$lang_id]','$year-$month-$day','" . $hour . ":" . $minute . ":" . $secund . "','$view','$authorization','$another_page[$lang_id]','$lang_id','$SEODescription[$lang_id]','$SEOKeywords[$lang_id]', '$SEOTitle[$lang_id]', '$category_id') ";

                $add_content = DB::Query($q);

				if($add_content) {
					Helpers::findIBlock($next_id, $lang_id, $text[$lang_id]);
				}

                // #  подвязка к пункту меню
                $title[$lang_id]        = str_replace("\\'", "'", $title[$lang_id]);
                $title[$lang_id]        = str_replace("\\&quot;", "&quot;", $title[$lang_id]);
                $announcement[$lang_id] = str_replace("\\'", "'", $announcement[$lang_id]);
                $announcement[$lang_id] = str_replace("\\&quot;", "&quot;", $announcement[$lang_id]);
                $announcement[$lang_id] = str_replace("&temp_gt;", "&gt;", $announcement[$lang_id]);
                $announcement[$lang_id] = str_replace("&temp_lt;", "&lt;", $announcement[$lang_id]);
                $slogan[$lang_id]       = str_replace("\\'", "'", $slogan[$lang_id]);
                $slogan[$lang_id]       = str_replace("\\&quot;", "&quot;", $slogan[$lang_id]);
                $slogan[$lang_id]       = str_replace("&temp_gt;", "&gt;", $slogan[$lang_id]);
                $slogan[$lang_id]       = str_replace("&temp_lt;", "&lt;", $slogan[$lang_id]);
                $text[$lang_id]         = str_replace("\\'", "'", $text[$lang_id]);
                $text[$lang_id]         = str_replace("\\&quot;", "&quot;", $text[$lang_id]);
                $text[$lang_id]         = str_replace("&temp_gt;", "&gt;", $text[$lang_id]);
                $text[$lang_id]         = str_replace("&temp_lt;", "&lt;", $text[$lang_id]);

                eval("\$text$lang_id = \$text[$lang_id];");

                if (!$add_content) {
                    $sys_message[] = " " . Dictionary::GetAdminWord(225) . " \"$title[$lang_id]\"...";
                } else {

                    $sys_message[] = "" . Dictionary::GetAdminWord(226) . " \"$title[$lang_id]\" " . Dictionary::GetAdminWord(227) . " успешно добавлен (<a href=content_edit.php?id=$next_id>" . Dictionary::GetAdminWord(228) . "</a> | <a href=content.php?menu_id=$menu_id>" . Dictionary::GetAdminWord(229) . "</a>).";
                }
            }
        }
    }
}

if (defined('_MODULE_TAG')) {

    $wmpTree = new wmpTree();

    $wmpTree->BranchesSelect = wmpTree::$PreDefSql['tagBranches'];
    $wmpTree->LeavesSelect   = wmpTree::$PreDefSql['tagLeaves'];

    $wmpTree->IsCheckBoxedBranches = false;
    $wmpTree->IsCheckBoxedLeaves   = true;

    $wmpTree->IdsName = 'connected_ids';

    $wmpTree->ShowLeaves = true;

    $treeBody = $wmpTree->func_items_tree(
            "item_id", "#", "&nbsp;&nbsp;", Dictionary::GetAdminWord(231), ""
            , "width:300px; float:left; font-size:9px; border:1px solid silver;"
            , array('checked' => array(), 'ignored' => array(), 'undetermined' => array()), true, ''
    );
}

// Заголовок
echo admin_func_top(Dictionary::GetAdminWord(230));
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
//echo admin_func_right_table_start(0);
echo "<form method=post action=content_add.php name=content>";
echo '<table cellspacing="0" cellpadding="3" border="0" bgcolor="#ffffff" width="100%">';
echo "$formauthorization";
echo "<input type=hidden name=add_new_one value=1>";
echo admin_func_right_table_row_start(0);

if ($menu_id == "")
    $menu_id = "0";

if ($menu_id != "0") {
    $select_menu_name = DB::Query("select title from `?_menu` where id=$menu_id and lang_id=\"$default_lang\" ");
    $menu_name        = @array_shift(DB::GetArray($select_menu_name));
} else
    $menu_name = Dictionary::GetAdminWord(231);

//echo "<input type=hidden name=menu_id value=\"$menu_id\">";
echo "<tr bgcolor=#FEF8E0>";
echo "<td valign=top class=tdlt><nobr><b>" . Dictionary::GetAdminWord(232) . " &nbsp;</b></nobr></td>";
echo "<td class=tdtr>";

//echo admin_func_menu_tree_select("", "menu_id", "", "&nbsp;&nbsp;&nbsp;&nbsp;", Dictionary::GetAdminWord(231), "0", "0");
echo Helpers::getMenuSelect('menu_id', filter_input(INPUT_GET, 'menu_id', FILTER_VALIDATE_INT));

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
    echo admin_func_right_table_row_start(1);
    echo admin_func_right_table_data(Dictionary::GetAdminWord(234), "", "1l");
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
  echo admin_func_right_table_data( Dictionary::GetAdminWord(235), "", "1l");
  echo "<td class=tdr ><select name=authorization>";

  $selected = "";
  if($authorization == 0) $selected['0'] = "selected";
  if($authorization == 1) $selected['1'] = "selected";

  echo "<option value=0 {$selected[0]}>". Dictionary::GetAdminWord(236) ."";
  echo "<option value=1 {$selected[1]}>". Dictionary::GetAdminWord(237) ."";
  echo "</select>";
 */
/*
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

if (defined('_MODULE_TAG')) {
    echo admin_func_right_table_row_start(1);
    echo admin_func_right_table_data("Теги:", "", "2lbi");
    echo admin_func_right_table_data($treeBody, "", "1br");
}
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
                $.post("adm_ajax.php", {action: "check_cnc", name: my_string, type: "content", menu: "<?= $id; ?>", menu_id: "<?= $menu_id; ?>", lang: "1"},
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

    echo "<tr bgcolor=#ffffff>";
    echo "<td colspan=2 height=12px>";
    echo admin_func_right_table_row_start(3);
    echo admin_func_right_table_data("<font color=white>[{$get_lang['title']}]</font>", $width, 1);
    echo admin_func_right_table_data("<font color=white>" . admin_func_right_input("checkbox", "active[$lang_id]", 1, "", $checked[$lang_id]) . Dictionary::GetAdminWord(240) . "</font>", $width, 1);
    echo admin_func_right_table_row_start(4);
    echo admin_func_right_table_data(Dictionary::GetAdminWord(13) . Dictionary::GetAdminWord(1150), "", "2li");
    if ($another_page[$lang_id] == "")
        $another_page[$lang_id] = "http://";
    echo admin_func_right_table_data(admin_func_right_input("", "another_page[$lang_id]", $another_page[$lang_id], 382, 3), "", "2r");

    echo admin_func_right_table_row_start(2);
    echo "<td colspan=2 height=12px class=tdlr style=\"font-size:6px\">&nbsp;";

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

   // echo admin_func_right_table_row_start(1);
   // echo admin_func_right_table_data('Альтернативный текст', "", "2lb");
   // echo admin_func_right_table_data(admin_func_right_input("", "alt[$lang_id]", '', 380, 3), "", "1br");
    
    // echo admin_func_right_table_row_start(2);
    // echo admin_func_right_table_data("Слоган", "", "1l");
    // echo admin_func_right_table_data( admin_func_right_input( "", "slogan[$lang_id]", $slogan[$lang_id], 335, 3), "", "2r");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data(Dictionary::GetAdminWord(241) . ' <a href="#" onclick="window.open(\'/admin/ckeditor/?name=announcement'. $lang_id .'\', \'CKEditor\', \'resizable=yes,width=\'+screen.width+\',height=\'+screen.height+\',left=30,top=30\');">Html редактор</a>', "", "3l");
    echo admin_func_right_table_data("<textarea id=\"announcement$lang_id\" name=\"announcement[$lang_id]\" style=\"width:425px;height:80px;\">$announcement[$lang_id]</textarea>", "", "2r");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data(Dictionary::GetAdminWord(242), "", "3l");
    echo admin_func_right_table_data('<a href="#" onclick="window.open(\'/admin/ckeditor/?name=text'. $lang_id .'\', \'CKEditor\', \'resizable=yes,width=\'+screen.width+\',height=\'+screen.height+\',left=0,top=0\');">Html редактор</a>', "", "2r");
    
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
echo admin_func_right_table_data(admin_func_right_input("submit", "", Dictionary::GetAdminWord(245), "", 1), "", "7");
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
    echo "" . Dictionary::GetAdminWord(247) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=#FEF8E0>";
    echo "<td valign=top><nobr>" . Dictionary::GetAdminWord(248) . "</nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(249) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(194) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=#FEF8E0>";
    echo "<td valign=top><nobr><b>" . Dictionary::GetAdminWord(233) . " &nbsp;</b></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(250) . " <br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(251) . "";

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
    echo "" . Dictionary::GetAdminWord(254) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(255) . " <br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(256) . " " . Dictionary::GetAdminWord(1147) . "<a href=users.php>" . Dictionary::GetAdminWord(200) . "</a>" . Dictionary::GetAdminWord(1147) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=#FEF8E0>";
    echo "<td valign=top><nobr><i>" . Dictionary::GetAdminWord(257) . " &nbsp;</i></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(258) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(258) . " <i>" . Dictionary::GetAdminWord(1146) . "</i>";

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
    echo "<tr>";
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
    echo "" . Dictionary::GetAdminWord(265) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(266) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=f5f5f5>";
    echo "<td valign=top><nobr><i>" . Dictionary::GetAdminWord(267) . " &nbsp;</i></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(268) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(269) . " <br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(270) . " <br>&nbsp;&nbsp;<i>" . Dictionary::GetAdminWord(1146) . "</i>";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top><nobr><b>" . Dictionary::GetAdminWord(178) . " &nbsp;</b></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(271) . "";

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
    echo "<input type=submit class=button value=\" " . Dictionary::GetAdminWord(245) . "\">";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(277) . "";
    echo "</table>";
}

echo '</td></tr></table>';

include(BASEPATH . "admin/admin_footer.php");


//----------------
return;
