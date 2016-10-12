<?php
foreach (array($_GET, $_POST) as $arr) {
    extract($arr, EXTR_OVERWRITE);
}

if (!empty($show))
    $no_top = "Y";

$include = @include("admin_top.php");
if (!$include or $adm_wellcome != "Y")
    exit;

include("config.php");
include("functions.php");

$path      = $path_1 . $d_path;
$host      = $host_1 . $d_path;
$path_name = $d_path;
if ($path_name == "") {
    $path_title = Dictionary::GetAdminWord(693);
} else {
    $path_title = $path_name;
}
$max_upload  = get_cfg_var("upload_max_filesize"); //Узнать максимальный размер файла для загрузки
$max_post    = get_cfg_var("post_max_size"); //Узнать максимальный размер суммы, загружаемых файлов
ereg("^([[:digit:]])", $max_upload, $upload_size);
$upload_size = $upload_size[0] * 1024 * 1024; //Перевести $max_upload в байты
//Вывести заголовки страницы и таблицы
print_header($path_title);

if ($title != "" and $text != "" and $add_yes55 == 1) {

    $add_yes55 = "";
    $title     = htmlspecialchars($title);
    $text      = htmlspecialchars($text);

    $check_file_cats = DB::Query("select title from `?_file_cats` where title='$title'");
    $get_file_cats   = DB::GetArray($check_file_cats);

    if ($get_file_cats['title'] == $title)
        $sys_message[] = "" . Dictionary::GetAdminWord(694) . " {$title} " . Dictionary::GetAdminWord(695) . "";
    else {
        $add_file_cats = DB::Query("insert into `?_file_cats` (title,text) values ('$title','$text') ");
        if ($add_file_cats) {
            $select        = DB::Query("select id from `?_file_cats` where title='$title' ");
            $fcid          = @array_shift(DB::GetArray($select));
            $file          = $_SERVER['DOCUMENT_ROOT'] . $files_folder . "/" . $fcid;
            mkdir($file, 0777);
            $sys_message[] = "" . Dictionary::GetAdminWord(111) . " {$title} " . Dictionary::GetAdminWord(331) . "";

            include("config.php");
        }
    }
}
if ($delete == "yes" and $fcid != "") {

    $delete_file_cats = DB::Query("delete from `?_file_cats` where id=" . $fcid);

    if (!$delete_file_cats)
        $sys_message[] = Dictionary::GetAdminWord(706);
    else {

        $file = $_SERVER['DOCUMENT_ROOT'] . $files_folder . "/" . $fcid;

        rmdir($file);

        $sys_message[] = Dictionary::GetAdminWord(707);

        include("config.php");
    }
}

if (@$write) {
    $sys_message[] = base64_decode(str_replace(" ", "+", $write));
}
$select_file_cats = DB::Query("select * from `?_file_cats` order by id");


// Заголовок
echo admin_func_top(Dictionary::GetAdminWord(708));
// Вывод сообщений про обработанность различных действий

echo admin_func_sys_message($sys_message);


echo admin_func_right_table_start("");
echo admin_func_right_table_row_start("");
echo admin_func_right_table_data(Dictionary::GetAdminWord(319), "35%", "");
echo admin_func_right_table_data(Dictionary::GetAdminWord(279), "65%", "");
echo admin_func_right_table_data(Dictionary::GetAdminWord(350), "", "");

while ($get_file_cats = DB::GetArray($select_file_cats)) {
    echo admin_func_right_table_row_start(1);
    echo admin_func_right_table_data(admin_func_right_link($get_file_cats['title'], "files.php?show={$show}&callback={$_REQUEST['callback']}&obj={$obj}&d_path=/{$get_file_cats['id']}", "", ""), "35%", 9);
    echo admin_func_right_table_data(nl2br("$get_file_cats[text]"), "", "2i");
    echo admin_func_right_table_data(admin_func_right_link("<img src=\"g/e.gif\" border=0 alt=\"" . Dictionary::GetAdminWord(371) . "\" width=10 height=12>", "file_cats_edit.php?id=$get_file_cats[id]", "", "") . "&nbsp; &nbsp;|&nbsp; &nbsp;<a href=files.php?show=$show&d_path=$d_path&fcid=$get_file_cats[id]&delete=yes onclick=\"return confirm('" . Dictionary::GetAdminWord(709) . "\\n " . Dictionary::GetAdminWord(710) . "');\"><img src=\"g/d.gif\" border=0 alt=\"" . Dictionary::GetAdminWord(354) . "\" width=12 height=12></a>", "", 5);
}

echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data(admin_func_right_link("Галлереи (общая папка)", "files.php?show={$show}&callback={$_REQUEST['callback']}&obj={$obj}&d_path=/gallery", "", ""), "35%", 9);
echo admin_func_right_table_data(nl2br("сюда складываются все изображения загруженные напрямую из редактора галлерей"), "", "2i");
echo admin_func_right_table_data("", "", 5);


echo admin_func_right_table_row_start(1);
echo "<form action=files.php method=post>";
echo "<input type=hidden name=add_yes55 value=1>";
echo "<input type=hidden name=d_path value=\"$d_path\">";
echo "<input type=hidden name=obj value=\"$obj\">";
echo "<input type=hidden name=show value=\"$show\">";
echo admin_func_right_table_data(admin_func_right_input("", "title", "", 170, 3), "", "");

echo "<td>";

echo admin_func_right_table_start(3);
echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data("<textarea name=text rows=1 style=\"width:350px;\"></textarea>", "", "");
echo admin_func_right_table_end();

echo "$formauthorization";
echo admin_func_right_table_data("&nbsp;" . admin_func_right_input("image", "", "", "", 4), "", 2);
echo "</form>";

echo admin_func_right_table_end() . "<br><br>";


echo admin_func_right_table_start(3);
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data("<img src=\"g/v2.gif\" hspace=0 vspace=0 border=0>", "70%", 6);
echo "<th class=right class=\"gr\"><nobr>&nbsp;&nbsp;&nbsp;";
$select    = DB::Query("select title from `?_file_cats` where id=$fcid_ululu ");
$cat_title = @array_shift(DB::GetArray($select));
echo "" . Dictionary::GetAdminWord(711) . " \"$cat_title\"";
echo "&nbsp;&nbsp;&nbsp;</nobr></th>";
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data("<IMG src=\"p.gif\" width=1 height=1 border=0>", "", 7);
echo admin_func_right_table_end();

echo "<form action=action.php method=post id=\"actionTable\">";
echo admin_func_right_table_start("");
echo admin_func_right_table_row_start("");
echo admin_func_right_table_data("<img src=g/v.gif border=0>", "", "");
echo admin_func_right_table_data(Dictionary::GetAdminWord(712), "20%", "");
echo admin_func_right_table_data(Dictionary::GetAdminWord(713), "80%", "");
//

if (!is_dir($path)) {
    echo '<!--' . "\n" . ' Can`t open non-directory: ' . $path . "\n" . '-->';
} else {
    echo '<!--' . "\n" . ' Opened directory: ' . $path . "\n" . '-->';
}

//
$dir        = opendir($path); //Откраваем манипулятор директорией
$array_file = array(); //массив файлов
$array_dir  = array(); //массив папок

while ($list_file = readdir($dir)) {
    $isdir = is_dir($path . "/" . $list_file);
    if ($isdir == true) {
        if (($list_file != ".") && ($list_file != "..")) { // Элементы каталогов "." и ".." не вносятся в массив директорий.
            array_push($array_dir, $list_file); //Добавить в массив $array_dir папки
        }
    }
    if ($isdir == false) {
        array_push($array_file, $list_file); //Добавить в массив $array_file файлы
    }
}

array_multisort($array_file, SORT_ASC, SORT_STRING); //Сортировать массив файлов
reset($array_dir);
reset($array_file);

while (list ($key, $val) = each($array_file)) {
    $is_html  = preg_match("/(\.html$)|(\.htm$)|(\.shtml$)/mi", $val);
    $is_image = preg_match("/(\.gif$)|(\.jpg$)|(\.jpeg$)|(\.png$)|(\.bmp$)/mi", $val);
    $is_text  = preg_match("/(\.txt$)/mi", $val);
    $is_zip   = preg_match("/(\.zip$)|(\.rar$)|(\.tgz$)|(\.tar$)|(\.gz$)/mi", $val);
    $is_php   = preg_match("/(\.php$)|(\.phtml$)|(\.php3$)|(\.php4$)/mi", $val);
    $is_cgi   = preg_match("/(\.cgi$)|(\.pl$)|(\.pm$)|(\.bat$)|(\.exe$)/mi", $val);
    $is_js    = preg_match("/(\.js$)/mi", $val);

    if ($is_html) {
        $icon = "images/html_icon.gif";
        $alt  = Dictionary::GetAdminWord(714);
    } elseif ($is_image) {
        $icon   = "/image.php?" . Image::mEncrypt('height=25&src=' . $host . '/' . $val);
        $alt    = " Файл рисунка";
        $razmer = getimagesize($path . "/" . $val);
        $width  = $razmer[0];
        $height = $razmer[1];
    } elseif ($is_text) {
        $icon = "images/text_icon.gif";
        $alt  = Dictionary::GetAdminWord(715);
    } elseif ($is_js) {
        $icon = "images/js_icon.gif";
        $alt  = "JavaScript";
    } elseif ($is_css) {
        $icon = "images/css_icon.gif";
        $alt  = Dictionary::GetAdminWord(716);
    } elseif ($is_php) {
        $icon = "images/php_icon.gif";
        $alt  = Dictionary::GetAdminWord(717);
    } elseif ($is_cgi) {
        $icon = "images/cgi_icon.gif";
        $alt  = Dictionary::GetAdminWord(718);
    } elseif ($is_zip) {
        $icon = "images/zip_icon.gif";
        $alt  = Dictionary::GetAdminWord(719);
    } else {
        $icon = "images/file_icon.gif";
        $alt  = Dictionary::GetAdminWord(720);
    }

    //Вывести строку файла в таблице
    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data(admin_func_right_input("checkbox", "filename[]", $val, $size, 3, '', array(
        'data' => array(
            'path' => $host . '/' . $val
        )
            )), 25, 5);
    echo "<td valign=top><nobr>&nbsp;<a data-path=" . $host . '/' . $val . " href='" . $host . "/" . $val . "' ";
    if ($show == "y") {
        echo " target=_blank ";
        //echo "OnClick=\"";
        //if($is_image) echo "closewin";
        //else echo "closewin2";
        //echo "('".$host."/".$val."','".$val."')\"";
    } elseif ($show == "u") {
        echo "OnClick=\"";
        echo "closewin3";
        echo "('" . $host . "/" . $val . "','" . $val . "')\"";
    } elseif ($show == "jqGrid") {
        echo "OnClick=\"";
        echo "jqGrid";
        echo "('" . $host . "/" . $val . "','" . $val . "')\"";
    } elseif ($show == "subscribe") {
        echo "OnClick=\"";
        echo "closewinsubscribe";
        echo "('" . $files_folder . $d_path . "/" . $val . "')\"";
    } elseif ($show == "big_img") {
        echo "OnClick=\"";
        echo "closewinbig_img";
        echo "('" . $host . "/" . $val . "','" . $val . "')\"";
    } elseif ($show == "big_img1") {
        echo "OnClick=\"";
        echo "closewinbig_img1";
        echo "('" . $host . "/" . $val . "','" . $val . "')\"";
    } elseif ($show == "big_img2") {
        echo "OnClick=\"";
        echo "closewinbig_img2";
        echo "('" . $host . "/" . $val . "','" . $val . "')\"";
    } elseif ($show == "big_img3") {
        echo "OnClick=\"";
        echo "closewinbig_img3";
        echo "('" . $host . "/" . $val . "','" . $val . "')\"";
    } elseif ($show == "big_img4") {
        echo "OnClick=\"";
        echo "closewinbig_img4";
        echo "('" . $host . "/" . $val . "','" . $val . "')\"";
    } elseif ($show == "big_img5") {
        echo "OnClick=\"";
        echo "closewinbig_img5";
        echo "('" . $host . "/" . $val . "','" . $val . "')\"";
    } elseif ($show == "small_img") {
        echo "OnClick=\"";
        echo "closewinsmall_img";
        echo "('" . $host . "/" . $val . "','" . $val . "')\"";
    } elseif ($show == "small_imgurl") {
        echo "OnClick=\"";
        echo "closewinsmall_imgurl";
        echo "('" . $host . "/" . $val . "','" . $val . "')\"";
    } elseif ($show == "ban") {
        echo "OnClick=\"";
        echo "closewin4";
        echo "('" . $host . "/" . $val . "','" . $width . "','" . $height . "')\"";
    } elseif ($show == 'imgurl3_full') {
        echo "OnClick=\"";
        echo "closewinforeverFormWH";
        echo "('" . $host . "/" . $val . "','" . $width . "','" . $height . "')\"";
    } elseif (!empty($show)) {
        if (strstr($show, "_full")) {
            echo "OnClick=\"";
            echo "closewinforeverForm";
            echo "('" . $host . "/" . $val . "')\"";
        } else {
            echo "OnClick=\"";
            echo "closewinforeverForm";
            echo "('" . $files_folder . $d_path . "/" . $val . "','" . $width . "','" . $height . "')\"";
        }
    } else
        echo " target=_blank ";

    unset($width_height);
    //if($width_height == "") $width_height = " width=25 height=24";

    echo " class=\"imgLink\"><img src=" . $icon . " alt=\"$alt\" $width_height border=0 align=top> <font class=font_2>" . $val . "</a></font> &nbsp; &nbsp; &nbsp;</nobr></td>";
    echo admin_func_right_table_data($host . "/" . $val, "100%", 9);
}// while (list ($key, $val) = each ($array_file))

closedir($dir); //Закрыть манипулятор директории

echo admin_func_right_table_row_start(2);
echo "<input type=hidden name=d_path value=" . $d_path . " >";
echo "<input type=hidden name=show value=" . $show . " >";
echo "<input type=hidden name=delete value=\"TRUE\">";

$buttons = Dictionary::GetAdminWord(721) . admin_func_right_input("submit", "", Dictionary::GetAdminWord(441), $size, 1);

if (
        isset($_REQUEST['callback']) && $_REQUEST['callback'] == 'addImagesToGallery' && isset($_REQUEST['show']) && $_REQUEST['show'] == 'jqGallery'
) {
    $buttons .= '&nbsp;' . admin_func_right_input('button', '', 'Добавить в галлерею', '130', 1, '', array('id' => 'filesAddImageButton')) .
            ' 
			<script type="text/javascript">
			$("#actionTable tr").click(function(e)
			{
				if (e.target.type != "checkbox") {
					if ($(this).find("input").is(":checked")) 
						$(this).find("input").attr("checked", false);
					else
						$(this).find("input").attr("checked", true);
				}
			})
			</script>
		';
    ?>
    <script type="text/javascript">
        $('#filesAddImageButton').live('click', function()
        {

            var images = [];

            $('input').filter('[type=checkbox]').filter(':checked').each(function()
            {
                if ($(this).data('path'))
                    images.push($(this).data('path'));
            });

            if (images.length)
                window.opener.<?= $_REQUEST['callback']; ?>(images);

            delete images;
            window.close();
            return false;
        });
        $('a.imgLink').click(function()
        {
            window.opener.<?= $_REQUEST['callback']; ?>([$(this).data('path')]);
            window.close();
            return false;
        });
    </script>
    <?
}

echo admin_func_right_table_data($buttons, "", 12);
echo admin_func_right_table_end();
echo "</form>";
echo "<br /><br />";

//Вывести форму для загрузки файлов


echo admin_func_right_table_start(3);
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data("<img src=\"g/v2.gif\" hspace=0 vspace=0 border=0>", "70%", 6);
echo admin_func_right_table_data(Dictionary::GetAdminWord(722) . " \"$cat_title\"", "", "th1");
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data("<IMG src=\"p.gif\" width=1 height=1 border=0>", "", 7);
echo admin_func_right_table_end();

echo admin_func_right_table_start("");
echo admin_func_right_table_row_start(3);
echo "<form enctype='multipart/form-data' action=upload.php method=POST>";
echo admin_func_right_table_data(Dictionary::GetAdminWord(723) . " " . $max_upload . " <i>(" . Dictionary::GetAdminWord(723) . " " . $max_post . ")</i>", "", "5w");

//брендирование
if (defined('_INSTALL_BRANDING') && (_INSTALL_BRANDING == 1)) {
    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data("<input type=checkbox name='ADD_LOGO' checked />Прикрепить логотип к загружаемым картинкам", "", "5");
}
//конец брендирования
echo admin_func_right_table_row_start(2);
echo "<td><div align=center>
    <input type=hidden name=MAX_FILE_SIZE value=" . $upload_size . ">
    <input type=hidden name=show value=" . $show . "><input type=hidden name=obj value=" . $obj . ">        
    <input type=hidden name=d_path value=" . $d_path . "> <br>";
echo admin_func_right_table_start(1);
for ($i = 1; $i <= $files_to_upload / 2; $i++) {
    $n = $i;
    print admin_func_right_table_row_start(2);
    echo admin_func_right_table_data("<font class=font_1>" . Dictionary::GetAdminWord(712) . " </font>" . admin_func_right_input("file", "userfile" . $n, "", "", 5), "", 2);
    echo admin_func_right_table_data("<font class=font_1>" . Dictionary::GetAdminWord(712) . " </font>" . admin_func_right_input("file", "userfile" . ($n + ($files_to_upload / 2)), "", "", 5), "", 2);
}
print admin_func_right_table_end();
print "<hr size=2 noshade>";
echo admin_func_right_input("submit", "upload", Dictionary::GetAdminWord(725), "", 1);
echo "</div>";
echo admin_func_right_table_end();
echo "</form>";


echo "<script>
    function closewin(imagePath,imageName)
    {
    AnCode = '<img src='+imagePath+' border=0>';
    var range = opener.document.frames.message.document.selection.createRange();
    range.pasteHTML(AnCode);
    range.select();
    range.execCommand();
    opener.document.frames.message.focus();
    window.close();
    }
    </script>
    ";

echo "
    <script>
    function closewin2(imagePath,imageName)
    {
    AnCode = '<a href='+imagePath+'>'+imageName+'</a>';
    var range = opener.document.frames.message.document.selection.createRange();
    range.pasteHTML(AnCode);
    range.select();
    range.execCommand();
    opener.document.frames.message.focus();
    window.close();
    }
    </script>
    ";

echo "
    <script>
    function jqGrid(AnCode){
    var range = opener.document.getElementById('{$obj}').value=AnCode;
    window.close();
    }
    function closewinsubscribe(AnCode)
    {
    var range = opener.document.forms.subscribe.fileaddress.value=AnCode;

    window.close();
    }
    function closewin3(AnCode)
    {
    var range = opener.document.forms.content.imgurl.value=AnCode;

    window.close();
    }
    function closewinbig_img(AnCode)
    {
    var range = opener.document.forms.product.imgurl.value=AnCode;

    window.close();
    }
    function closewinbig_img1(AnCode)
    {
    var range = opener.document.forms.product.imgurl1.value=AnCode;

    window.close();
    }
    function closewinbig_img2(AnCode)
    {
    var range = opener.document.forms.product.imgurl2.value=AnCode;

    window.close();
    }
    function closewinbig_img3(AnCode)
    {
    var range = opener.document.forms.product.imgurl3.value=AnCode;

    window.close();
    }
    function closewinbig_img4(AnCode)
    {
    var range = opener.document.forms.product.imgurl4.value=AnCode;

    window.close();
    }
    function closewinbig_img5(AnCode)
    {
    var range = opener.document.forms.product.imgurl5.value=AnCode;

    window.close();
    }
    function closewinsmall_img(AnCode)
    {
    var range = opener.document.forms.product.small_imgurl.value=AnCode;

    window.close();
    }
    function closewinsmall_imgurl(AnCode)
    {
    var range = opener.document.forms.product.small_imgurl.value=AnCode;

    window.close();
    }
    function closewinforeverFormWH(AnCode,width,height){
    var range = opener.document.forms.foreverForm.imgurl3_full.value=AnCode;
    var range = opener.document.forms.foreverForm.img_width.value=width;
    var range = opener.document.forms.foreverForm.img_height.value=height;
    window.close();
    }";

if (!empty($show)) {
    echo "function closewinforeverForm(AnCode)" .
    "{" .
    "
        var range = opener.document.forms.foreverForm." . $show . ".value=AnCode;
        " .
    "" .
    "  window.close();" .
    "}";
}


echo "
    function closewin4(AnCode,width,height)
    {
    var range = opener.document.forms.banner.banurl.value=AnCode;
    var range = opener.document.forms.banner.width.value=width;
    var range = opener.document.forms.banner.height.value=height;

    window.close();
    }
    </script>
    ";

include("admin_footer.php");
?>
