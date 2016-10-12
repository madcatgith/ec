<?php

$include = @include("admin_top.php");
if (!$include or $adm_wellcome != "Y")
    exit;

// Преобразование кавычек из формы в БД
$step = "form_to_db";
include "admin_functions_post_quotes.php";

if ($edit_one != "") {

    $does_it_exist = DB::Query("select * from `?_title_config` where id ='1'");
    $fields        = array('viewTitle', 'viewTitleMenu', 'viewTitleContent', 'viewTitleProduct', 'viewTitleIBlock');
    $values        = array('id' => 1);

    foreach ($fields as $field) {
        $values[$field] = mysql_real_escape_string(clearVal($_REQUEST[$field]));
    }

    if (mysql_num_rows($does_it_exist) == "") {

        $edit_config = DB::Query("insert into `?_title_config` (`" . implode('`, `', array_keys($values)) . "`) values ('" . implode("', '", $values) . "')");

        if (!$edit_config) {
            $sys_message[] = Dictionary::GetAdminWord(498);
        } else {
            $sys_message[] = Dictionary::GetAdminWord(499);
        }
    } else {

        $updateFields = array();
        
        foreach ($values as $key => $value) {
            $updateFields[] = '`' . $key . '`="' . $value . '"';
        }
        
        $edit_config = DB::Query("update `?_title_config` set " . implode(', ', $updateFields) . "  where id ='1'");

        if (!$edit_config) {
            $sys_message[] = Dictionary::GetAdminWord(498);
        } else {
            $sys_message[] = Dictionary::GetAdminWord(499);
        }
    }
}
$select           = DB::Query("select * from `?_title_config` where id='1' ");
$get              = DB::GetArray($select);
$viewTitle        = $get['viewTitle'];
$viewTitleMenu    = $get['viewTitleMenu'];
$viewTitleContent = $get['viewTitleContent'];
$viewTitleProduct = $get['viewTitleProduct'];
$viewTitleIBlock  = $get['viewTitleIBlock'];
// Заголовок
echo admin_func_top(Dictionary::GetAdminWord(500));

// Вывод сообщений про обработанность различных действий
echo admin_func_sys_message($sys_message);


echo admin_func_right_table_start(2);
echo "<form method=post action=ConfigTitle.php name=foreverForm>";
echo "$formauthorization";
echo "<input type=hidden name=edit_one value=1>";

// Вылавливаем из БД информацию

echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data('Титул главной страницы сайта', "", "1lt");
echo admin_func_right_table_data(admin_func_right_input("", "viewTitle", $viewTitle, 425, 3), "", "2tr");

echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data('Титул меню', "", "1l");
echo admin_func_right_table_data(admin_func_right_input("", "viewTitleMenu", $viewTitleMenu, 425, 3), "", "1r");

echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data('Титул контент', "", "1l");
echo admin_func_right_table_data(admin_func_right_input("", "viewTitleContent", $viewTitleContent, 425, 3), "", "2r");

echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data('Титул продукт', "", "1l");
echo admin_func_right_table_data(admin_func_right_input("", "viewTitleProduct", $viewTitleProduct, 425, 3), "", "2r");

echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data('Титул инфоблоков', "", "1l");
echo admin_func_right_table_data(admin_func_right_input("", "viewTitleIBlock", $viewTitleIBlock, 425, 3), "", "2r");

echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data(admin_func_right_input("submit", "", Dictionary::GetAdminWord(462), "", 1), "", "7");
echo "</form>";
echo admin_func_right_table_end();
echo admin_func_right_table_end();

echo "<div class='' style=''><ul>";
echo "<li>%s - title общий <br/></li>";
echo "<li>%m - title меню <br/></li>";
echo "<li>%c - title статьи <br/></li>";
echo "<li>%p - title продукты <br/></li>";
echo "<li>%b - title инфоблоков <br/></li>";
echo "</ul></div>";
echo "<style>body {background:none repeat scroll 0 0 #FEF8E0;} </stele>";
echo "
	<script language=\"JavaScript\">
	function newwin2(url,width,height) {
	window.open(url, 'window', 'width='+width+',height='+height+',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes');
	}
	</script>";


include("admin_footer.php");
?>