<?php

$include = include("admin_top.php");

if (!$include or $adm_wellcome != "Y")
    exit;

if ($edit_one != "") {

    $select_langs = DB::Query("select * from `?_lang` where active=1 order by place");

    while ($get_lang = DB::GetArray($select_langs)) {

        $lang_id                     = $get_lang['id'];
        $langField["shortAddress"]   = "shortAddress";
        $langField["logo"]           = "logo";
        $langField["button"]         = "button";
        $langField["SEOTitle"]       = "SEOTitle";
        $langField["SEOKeywords"]    = "SEOKeywords";
        $langField["SEODescription"] = "SEODescription";
        $langField["title"]          = "title";
        $langField["text"]           = "text";
        $langField["postal"]         = "postal";
        $langField["above_menu"]     = "above_menu";
		$langField["about_us"]     	 = "about_us";
        $langField["copyright"]      = "copyright";
        $langField["owner"]          = "owner";
        $langField["text"]           = "text";
        $langField["skype"]          = "skype";
        $langField["schedule"]       = "schedule";

        if ($title[$lang_id] != "") {

            $_POST["logo"][$lang_id]   = ${"logo" . $lang_id . "_full"};
            $_POST["button"][$lang_id] = ${"button" . $lang_id . "_full"};

            $data["id"]          = 0;
            $data["lang_id"]     = $lang_id;
            $data["publicEmail"] = $email;
            $data["email"]       = $email;
            $data["education"]   = $education;
            $data["galleryID"]   = $galleryID;
			
			$data["phone"]		 = addslashes(serialize($phone));
			$data["phone_one"]	 = addslashes(serialize($phone_one));

            foreach ($langField as $key => $value)
                $data[$value] = (get_magic_quotes_gpc() ? mysql_real_escape_string(stripcslashes($_POST[$key][$lang_id])) : mysql_real_escape_string($_POST[$key][$lang_id]));

            $edit_config   = DB::Query("replace into ?_config (`" . implode("`, `", array_keys($data)) . "`)values('" . implode("', '", array_values($data)) . "')");
            $sys_message[] = (!$edit_config) ? Dictionary::GetAdminWord(498) : Dictionary::GetAdminWord(499);
        }
    }
}

// Заголовок
echo admin_func_top(Dictionary::GetAdminWord(500));

// Вывод сообщений про обработанность различных действий
echo admin_func_sys_message($sys_message);

echo admin_func_right_table_start(2);
echo "<form method=post action=db_config.php name=foreverForm>";
echo $formauthorization;
echo "<input type=hidden name=edit_one value=1>";

// Вылавливаем из БД информацию
$no_array_fields = array_fill_keys(array('id', 'galleryID', 'lang_id', 'publicEmail', 'email', 'order_email', 'education', 'place_limit', 'reserv_time', 'delivery', 'delivery_text'), 1);
$select_langs    = mysql_query("select * from `" . _PREFIX . "lang` where active=1 order by place");

while ($get_lang = mysql_fetch_array($select_langs)) {

    $lang_id = $get_lang["id"];

    $select   = mysql_query("select * from `" . _PREFIX . "config` where lang_id={$lang_id}");
    $fieldSet = mysql_fetch_assoc($select);

    foreach ($fieldSet as $key => $value) {
        if (isset($no_array_fields[$key])) {
            $$key = htmlspecialchars($value, ENT_QUOTES);
        } else {
            $k           = &$$key;
            $k[$lang_id] = htmlspecialchars($value, ENT_QUOTES);
        }
    }
	
	$phone = $fieldSet['phone'];
	$phone_one = $fieldSet['phone_one'];
}

if (!empty($education))
    $education = "checked";

$checked = (!empty($education)) ? ' checked="checked"' : '';

echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data(Dictionary::GetAdminWord(173), "", "1lt");
echo admin_func_right_table_data(admin_func_right_input("", "email", $email, 425, 3), "", "2tr");

/*echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data("Публичный E-mail:", "", "1l");
echo admin_func_right_table_data(admin_func_right_input("", "publicEmail", $publicEmail, 425, 3), "", "2r");


$phone = unserialize($phone);
foreach(array(0, 1) as $i) {
	echo admin_func_right_table_row_start(1);
	echo admin_func_right_table_data("Телефон #". ($i + 1), "", "1l");
	echo admin_func_right_table_data(admin_func_right_input("", "phone[". $i ."][code]", $phone[$i]['code'], 50, 3) .' '. admin_func_right_input("", "phone[". $i ."][number]", $phone[$i]['number'], 80, 3) , "", "2r");
}

$phone_one = unserialize($phone_one);
echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data("Телефон в красном блоке", "", "1lb");
echo admin_func_right_table_data(admin_func_right_input("", "phone_one[code]", $phone_one['code'], 50, 3) .' '. admin_func_right_input("", "phone_one[number]", $phone_one['number'], 80, 3) , "", "2br");
*/

echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data("Галерея на главной", "", "1lb");
echo admin_func_right_table_data(Helpers::getGallerySelect('galleryID', $galleryID), "", "2br");

echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data(Dictionary::GetAdminWord(501), $width, "1lb");
echo admin_func_right_table_data(admin_func_right_input("checkbox", "education", 1, "", $checked) . Dictionary::GetAdminWord(502), "", "2br");

$select_langs = DB::Query("select * from `?_lang` where active=1 order by place, id");
while ($get_lang     = DB::GetArray($select_langs)) {

    $lang_id = $get_lang['id'];
    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data("&nbsp;", "", "7");
    echo admin_func_right_table_row_start(3);
    echo admin_func_right_table_data("<font color=white>[$get_lang[title]]</font>", "", "8");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data(Dictionary::GetAdminWord(503), "", "1l");
    echo admin_func_right_table_data(admin_func_right_input("", "title[{$lang_id}]", $title[$lang_id], 425, 3), "", "2r");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data('SEOTitle: ', "", "1l");
    echo admin_func_right_table_data(admin_func_right_input("", "SEOTitle[{$lang_id}]", $SEOTitle[$lang_id], 425, 3), "", "2r");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data('SEOKeywords: ', "", "1l");
    echo admin_func_right_table_data(admin_func_right_input("", "SEOKeywords[{$lang_id}]", $SEOKeywords[$lang_id], 425, 3), "", "2r");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data('SEODescription: ', "", "1l");
    echo admin_func_right_table_data(admin_func_right_input("", "SEODescription[{$lang_id}]", $SEODescription[$lang_id], 425, 3), "", "2r");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data(Dictionary::GetAdminWord(504), "", "1l");
    echo admin_func_right_table_data(admin_func_right_input("", "logo" . $lang_id . "_full", $logo[$lang_id], 350, 3) . "&nbsp;" . admin_func_right_input("submit", "", Dictionary::GetAdminWord(239), "", "onClick=\"newwin2('files.php?show=logo" . $lang_id . "_full',720,520); return false;\""), "", "2r");

    # echo admin_func_right_table_row_start(2);
    # echo admin_func_right_table_data( "Адрес:", "", "1l");
    # echo admin_func_right_table_data( admin_func_right_input( "", "shortAddress[{$lang_id}]", $shortAddress[$lang_id], 350, 3) , "", "2r");
   
    /*
    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data("Skype:", "", "1l");
    echo admin_func_right_table_data(admin_func_right_input("", "skype[{$lang_id}]", $skype[$lang_id], 350, 3), "", "2r");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data("График работы:", "", "1l");
    echo admin_func_right_table_data(admin_func_right_input("", "schedule[{$lang_id}]", $schedule[$lang_id], 350, 3), "", "2r");
    */
    # echo admin_func_right_table_row_start(2);
    # echo admin_func_right_table_data(Dictionary::GetAdminWord(505), "", "1l");
    # echo admin_func_right_table_data(admin_func_right_input("", "owner[$lang_id]", $owner[$lang_id], 425, 3), "", "2r");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data(Dictionary::GetAdminWord(506), "", "1l");
    echo admin_func_right_table_data(admin_func_right_input("", "copyright[$lang_id]", $copyright[$lang_id], 425, 3), "", "2r");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data(Dictionary::GetAdminWord(507), "", "1l");
    echo admin_func_right_table_data(admin_func_right_input("", "postal[$lang_id]", $postal[$lang_id], 425, 3), "", "2r");

    # echo admin_func_right_table_row_start(2);        
    # echo admin_func_right_table_data( Dictionary::GetAdminWord(508), "", "1l");
    # echo admin_func_right_table_data( admin_func_right_input( "", "button". $lang_id ."_full", $button[$lang_id], 350, 3) ."&nbsp;". admin_func_right_input( "submit", "", Dictionary::GetAdminWord(239), "", "onClick=\"newwin2('files.php?show=button". $lang_id ."_full',720,520); return false;\""), "", "2r");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data('О товарах(на главной)', "", "1l");
    echo admin_func_right_table_data("<textarea name=above_menu[$lang_id] style=\"width:425px;height:100px;\">$above_menu[$lang_id]</textarea>", "", "2r");

	echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data('О нас(на главной)', "", "1lb");
    echo admin_func_right_table_data("<textarea name=about_us[$lang_id] style=\"width:425px;height:100px;\">$about_us[$lang_id]</textarea>", "", "2br");
	
    # echo admin_func_right_table_row_start(2);        
    # echo admin_func_right_table_data( 'Текст в шапке на главной странице', "", "8lr");
    # echo admin_func_right_table_row_start(2);
    # echo admin_func_right_table_data( "<textarea name=text[$lang_id] style=\"width:595px;height=100px;\">$text[$lang_id]</textarea>", "", "7lbr");
}

echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data("Карта сайта", "", "1lb");
echo admin_func_right_table_data('<a class="savebtn" id="map_btn">Обновить</a>', "", "2br");

echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data(admin_func_right_input("submit", "", Dictionary::GetAdminWord(462), "", 1), "", "7");
echo "</form>";
echo admin_func_right_table_end();
echo admin_func_right_table_end();
?>
<div id="msgPopup" style="display:none;">
    <h3 class="msg"></h3>
</div>
<script type="text/javascript">
    function newwin2(url, width, height) {
        window.open(url, 'window', 'width=' + width + ',height=' + height + ',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes');
    }
    
    $('a#map_btn').click(function() {
        $.post('/admin/request.php?fn=map/generate', {
        }, function(data) {
            if(data.action) {
                alert('Карта сайта успешно обновлена!');
            }
        }, 'json');
    });    
</script>
<?

$select_config = DB::Query("select education from `?_config` ");

if (array_shift(DB::GetArray($select_config)) == "0") {

    echo "<br><br>";
    echo "<table border=0 cellspacing=5 cellpadding=0 width=600>";
    echo "<tr bgcolor=ffffff>";
    echo "<td colspan=2>";
    echo "<b>" . Dictionary::GetAdminWord(246) . "</b>";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=#FEF8E0>";
    echo "<td valign=top><nobr><b>" . Dictionary::GetAdminWord(173) . " &nbsp;</b></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(511) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(512) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(513) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=#FEF8E0>";
    echo "<td valign=top><nobr><b>" . Dictionary::GetAdminWord(501) . "</b></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(514) . "" . Dictionary::GetAdminWord(1147) . "<i>" . Dictionary::GetAdminWord(502) . "</i>" . Dictionary::GetAdminWord(1147) . " " . Dictionary::GetAdminWord(515) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(516) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(517) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<trы>";
    echo "<td valign=top><font color=white><b>[" . Dictionary::GetAdminWord(262) . "]</b></font></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(518) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(519) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top><nobr><b>Расширенная модель <br /> \"Облака тагов\" &nbsp;</b></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "При выставленной галочке позволяет добавлять набор тагов в текстовой строке через запятую (в контенте и продуктах) ; при снятой галочке добавление происходит через словарь тагов, а в контенте и продукте происходит лишь выбор тагов из списка, внесенного в словарь. Первый вариант предназначен для больших проектов, второй - для небольших. ";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top><nobr><b>" . Dictionary::GetAdminWord(503) . " &nbsp;</b></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(503) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(521) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top><nobr><b>" . Dictionary::GetAdminWord(505) . " &nbsp;</b></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo "" . Dictionary::GetAdminWord(522) . "<br>&nbsp;&nbsp;" . Dictionary::GetAdminWord(523) . "";

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top><nobr><b>" . Dictionary::GetAdminWord(506) . " &nbsp;</b></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo Dictionary::GetAdminWord(524);

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top><nobr><b>" . Dictionary::GetAdminWord(525) . " &nbsp;</b></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo Dictionary::GetAdminWord(526);

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<table cellspacing=1 cellpadding=3 border=0 bgcolor=627080>";
    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top><nobr><b>" . Dictionary::GetAdminWord(510) . " &nbsp;</b></nobr></td>";
    echo "</nobr></table>";
    echo "<td valign=top>";
    echo Dictionary::GetAdminWord(527);

    echo "<tr bgcolor=ffffff>";
    echo "<td valign=top>";
    echo "<input type=submit class=button value=\" " . Dictionary::GetAdminWord(528) . "\">";
    echo "<td valign=top>";
    echo Dictionary::GetAdminWord(529);
    echo "</table>";
}

include("admin_footer.php");
