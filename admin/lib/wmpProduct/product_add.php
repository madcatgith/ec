<?php
$include = include($_SERVER['DOCUMENT_ROOT'] . '/admin/admin_top.php');

if (!$include || $adm_wellcome != 'Y') {
    exit;
}

if (isset($category_id)) {
    $parent_id = $category_id;
} else {
    $parent_id = 3;
}

include BASEPATH . 'includes.php';
require_once BASEPATH . '/admin/hierarchyhelpers/treeBuilder.php';

$dics = Dictionary::GetDicWords();

?>
<script type="text/javascript">
    function newwin2(url, width, height)
    {
        window.open(url, 'window', 'width=' + width + ',height=' + height + ',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes');
    }
</script>
<?php
$fileds      = array('isNew', 'gallery_id', 'url', 'colors', 'category_id', 'popularity', 'showInSteps', 'showInMain', 'pdf');
$fileds_lang = array('minCapacity', 'maxCapacity', 'minTorque', 'maxTorque', 'minGearRatio', 'maxGearRatio', 'multy', 'SEOTitle', 'SEODescription', 'SEOKeywords', 'announcement', 'cnc', 'active', 'title', 'price', 'strength', 'price_for', 'price_for_digit');
$sys_message = array();

if (isset($_POST['action/insert'])) {

    if (!isset($_REQUEST['category_id']) || intval($_REQUEST['category_id']) == 0) {
        $sys_message[] = Dictionary::GetAdminWord(623);
    } else {

        $ID = intval(array_shift(DB::GetArray(DB::Query("select max(id) from `?_product`")))) + 1;
        $place = 0;

        foreach (Lang::getLanguages() as $lang) {

            $lang_id = $lang['id'];
            $insertFields = array('id' => $ID, 'place' => $place, 'lang_id' => $lang['id']);

            foreach ($fileds as $val) {
                if ($val == 'colors') {
                    $insertFields[$val] = addslashes(serialize($_POST[$val]));
                } else {
                    $insertFields[$val] = mysql_real_escape_string(clearVal($_POST[$val]));
                }
            }

            foreach ($fileds_lang as $val) {
                if ($val == 'multy') {
                    $insertFields[$val] = addslashes(serialize($_POST[$val][$lang_id]));
                } else {
                    $insertFields[$val] = mysql_real_escape_string(clearVal($_POST[$val][$lang_id]));
                }
            }

            $action = DB::Query('insert into ?_product (`' . implode('`, `', array_keys($insertFields)) . '`) values ("' . implode('", "', $insertFields) . '")');

            if (!$action) {
                $sys_message[] = Dictionary::GetAdminWord(307) . " " . $title[$lang_id] . "...";
            } else {
                $sys_message[] = Dictionary::GetAdminWord(308) . " " . $title[$lang_id] . " " . Dictionary::GetAdminWord(309) . " <a href='product_edit.php?id={$ID}' style=\"color:green;font-weight:bold\">" . Dictionary::GetAdminWord(310) . "</a>.";
            }
        }

        if ($action) {

            DB::Query('update ?_product set place=place+1 where category_id=' . intval($_REQUEST['category_id']));

            $sql_linked_ids = array();

            foreach ($_REQUEST['product_ids'] as $key => $value) {
                if ($value === 'true') {
                    $sql_linked_ids[] = "('{$ID}', '{$key}', 'product')";
                }
            }

            if (count($sql_linked_ids) > 0) {
                DB::Query("INSERT into ?_tag_col (`item_id`, `link_id`, `item_table`) values " . implode(', ', $sql_linked_ids));
            }
        }
    }
}

echo admin_func_top('Добавление продуктов');
// Вывод сообщений про обработанность различных действий
echo admin_func_sys_message($sys_message);

$wmpTree = new wmpTree();
$wmpTree->BranchesSelect = wmpTree::$PreDefSql['productBranches'];
$wmpTree->ShowLeaves = false;
$parents = array();
$query = DB::Query('select id, menu_id from ?_menu where lang_id=1');

while ($r = DB::GetArray($query)) {
    if (!isset($parents[$r['menu_id']])) {
        $parents[$r['menu_id']] = array();
    }
    $parents[$r['menu_id']][] = $r['id'];
}

$treeBody = $wmpTree->func_items_tree("item_id", "/admin/lib/wmpProduct/catalogue.php", "&nbsp;&nbsp;", Dictionary::GetAdminWord(231), "", "width:300px; float:left; font-size:9px;", array($parent_id), false, null, ' and id in (' . getIDs($parents, 3) . ')');
echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#a5cd38">
	<tr>
	<td width="300" class="td_left" style="vertical-align: top; border: 1px solid #a5cd38;">
	<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td style="padding: 14px 4px 4px 4px; border-bottom: 2px solid #213866;">
	<form action="/admin/lib/wmpProduct/catalogue.php?oper=search" method="post">';
echo Dictionary::GetAdminWord(64) . " " . admin_func_right_input("text", "search", $search, "100", "") . " " . admin_func_right_input("submit", "", Dictionary::GetAdminWord(341), "", "1");
echo '</form></td></tr><tr><td style="padding: 0;">';
echo $treeBody;
echo '</td></tr></table></td><td style="vertical-align: top; background: #fff;">';
echo "<form method=post action=product_add.php name=foreverForm>";

echo admin_func_right_table_start(3);
echo admin_func_right_table_row_start(2);
echo "<tr bgcolor=ffffff>";
echo "<td valign=top colspan=2>";

$categories = array();
$categoryQuery = DB::Query('select * from ?_menu where lang_id=1 order by title');

while ($r = DB::GetArray($categoryQuery)) {
    if (!isset($categories[$r['menu_id']])) {
        $categories[$r['menu_id']] = array();
    }
    $categories[$r['menu_id']][$r['id']] = $r['title'];
}

echo "<td class=tdtr>";

echo admin_func_right_table_start(6);

echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data(Dictionary::GetAdminWord(319), "", "1lt");
echo admin_func_right_table_data('<select style="width: 305px;" name="category_id"><option value="3"' . ($parent_id == 3 ? ' selcted="selected"' : '') . '>Каталог</option>' . Helpers::walkRecursive($categories, 3, 1, $parent_id) . '</select>', '', '2tr');

// echo admin_func_right_table_row_start(1);
// echo admin_func_right_table_data("Бренды:", "", "2lb");
// echo admin_func_right_table_data('<select style="width: 305px;" name="brand_id"><option value="0" selcted="selected"></option>' . Helpers::walkRecursive($categories, 17, 0, isset($_REQUEST['brand_id']) ? $_REQUEST['brand_id'] : 0) . '</select>', '', '2br');

/*
  $wmpTagTree                       = new wmpTree();
  $wmpTagTree->BranchesSelect       = wmpTree::$PreDefSql['tagBranches'];
  $wmpTagTree->LeavesSelect         = wmpTree::$PreDefSql['tagLeaves'];
  $wmpTagTree->IsCheckBoxedBranches = false;
  $wmpTagTree->IsCheckBoxedLeaves   = true;
  $wmpTagTree->IdsName              = 'product_ids';
  $wmpTagTree->ShowLeaves           = true;
  $checks                           = array(
  'checked'      => array(),
  'ignored'      => array(),
  'undetermined' => array()
  );
  $treeBody                         = $wmpTagTree->func_items_tree('item_id', '#', '&nbsp;&nbsp;', Dictionary::GetAdminWord(231), '', 'width:300px; float:left; font-size:9px; border:1px solid silver;', $checks, true, '');

  echo admin_func_right_table_row_start(1);
  echo admin_func_right_table_data("Теги:", "", "2lb");
  echo admin_func_right_table_data($treeBody, "", "1br");
 */

// echo admin_func_right_table_row_start(1);
// echo admin_func_right_table_data('В наличии', '', '2l');
// echo admin_func_right_table_data(admin_func_right_input("checkbox", "inStock", 1, '', ''), "", '2r');

#echo admin_func_right_table_row_start(1);
#echo admin_func_right_table_data('Новинка', '', '2l');
#echo admin_func_right_table_data(admin_func_right_input("checkbox", "inNew", 1, '', ''), "", '2r');

#echo admin_func_right_table_row_start(1);
#echo admin_func_right_table_data('На главную', '', '2l');
#echo admin_func_right_table_data(admin_func_right_input("checkbox", "main", 1, '', ''), "", '2r');

#echo admin_func_right_table_row_start(1);
#echo admin_func_right_table_data('На главную категории', '', '2l');
#echo admin_func_right_table_data(admin_func_right_input("checkbox", "subMain", 1, '', ''), "", '2r');

#echo admin_func_right_table_row_start(1);
#echo admin_func_right_table_data('Распродажа', '', '2l');
#echo admin_func_right_table_data(admin_func_right_input("checkbox", "isSale", 1, '', ''), "", '2r');

#echo admin_func_right_table_row_start(1);
#echo admin_func_right_table_data('Популярный', '', '2l');
#echo admin_func_right_table_data(admin_func_right_input("checkbox", "isPopular", 1, '', ''), "", '2r');

#echo admin_func_right_table_row_start(2);
#echo admin_func_right_table_data('А может мне подойдет это?', "", "1l");
#echo admin_func_right_table_data(admin_func_right_input("", "need", '', 250, 3), "", "2r");

#echo admin_func_right_table_row_start(1);
#echo admin_func_right_table_data(Dictionary::GetAdminWord(325), "", "1l");
#echo admin_func_right_table_data(admin_func_right_input("", "price", '', 150, 3) . ' <i>грн</i>', "", "2r");

#echo admin_func_right_table_row_start(1);
#echo admin_func_right_table_data("Новая цена", "", "1lb");
#echo admin_func_right_table_data(admin_func_right_input("", "new_price", '', 150, 3) . ' <i>грн</i>', "", "2br");

/*$popularity = '<select name="popularity">';
foreach (range(1, 5) as $popular) {
    $popularity .= '<option value="'. $popular .'">'. $popular .'</option>';
}
$popularity .= '</select>';*/

/*echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data("Популярность", "", "1lb");
echo admin_func_right_table_data($popularity, "", "2br");*/

/*foreach(array(
    'url' => 'Картинка главная'
) as $id => $file) {
    echo admin_func_right_table_row_start(1);
    echo admin_func_right_table_data($file, '', "2l");
    echo admin_func_right_table_data(admin_func_right_input('', $id, '', 305, 3, '', array('id' => $id)) . "&nbsp;" . admin_func_right_input("submit", "", "Обзор", "", "onClick=\"newwin2('/admin/files.php?show=jqGrid&amp;obj={$id}',720,520); return false;\""), "", "2r");
}*/

/*echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data("Слайдер", "", "1lb");
echo admin_func_right_table_data(Helpers::getGallerySelect('gallery_id', $gallery_id), "", "2br");*/

/*
if(in_array((filter_input(INPUT_GET, 'category_id') ?: filter_input(INPUT_POST, 'category_id')), Menu::getChildrenIDs(1, 14))) {
    foreach(range(1, 6) as $row) {
        echo admin_func_right_table_row_start(1);
        echo admin_func_right_table_data('Цвет #'. $row .': ', '', "2l");  
        echo admin_func_right_table_data(
            admin_func_right_input('', 'colors['. $row .'][title]', '', 100, 3, '', array('placeholder' => 'Название цвета')) . '&nbsp;' .
            admin_func_right_input('', 'colors['. $row .'][rgb]', '', 100, 3, '', array('placeholder' => 'RGB код цвета')) . '&nbsp;' .
            admin_func_right_input('', 'colors['. $row .'][image]', '', 150, 3, '', array('id' => 'colors_image_' . $row, 'placeholder' => 'Картинка кофемашины')) . '&nbsp;' .
            admin_func_right_input("submit", "", "Обзор", "", "onClick=\"newwin2('/admin/files.php?show=jqGrid&amp;obj=colors_image_{$row}',720,520); return false;\"")
        , "", "2r");
    }
    
    echo admin_func_right_table_row_start(1);
    echo admin_func_right_table_data("Показывать на главной", "", '2l');
    echo admin_func_right_table_data(admin_func_right_input("checkbox", "showInMain", 1, '', '', '', array('id' => 'showInMain')), "", '2r');
}

echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data("Показывать на страницу 'Кофемашина бесплатно'", "", '2lb');
echo admin_func_right_table_data(admin_func_right_input("checkbox", "showInSteps", 1, '', '', '', array('id' => 'showInSteps')), "", '2br');

echo admin_func_right_table_row_start(1);
echo admin_func_right_table_data("Новинка", "", '2lb');
echo admin_func_right_table_data(admin_func_right_input("checkbox", "isNew", 1, '', '', '', array('id' => 'isNew')), "", '2br');
*/

foreach (Lang::getLanguages() as $lang) {

    $lang_id = $lang["id"];
    $temp_in_title = '<input type="text" name="title[' . $lang_id . ']" id="title_' . $lang_id . '" value="" style="width: 335px;" />';
    $temp_cnc = '<input type="text"   id="cnc_' . $lang_id . '" name="cnc[' . $lang_id . ']" value="" style="width: 335px;">';
    $temp_cnc .= '<input type="button" id="cnc_gen_' . $lang_id . '" class="' . $lang_id . '" value="Генерировать ЧПУ">';
    $temp_cnc .= '<div style="width: 335px; height: 15px; font-size: 12px; padding: 2px 0 2px 0;" id="alert_' . $lang_id . '"></div>';

    echo admin_func_right_table_row_start(3);
    echo admin_func_right_table_data("<font color=white>[{$lang['title']}]</font>", "", '2lb');
    echo admin_func_right_table_data(admin_func_right_input("checkbox", "active[{$lang_id}]", 1, '', '', '', array('id' => 'lang' . $lang_id)) . '<label for="lang' . $lang_id . '" style="color: #fff;">' . Dictionary::GetAdminWord(240) . '</label>', "", '2br');

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data(Dictionary::GetAdminWord(178), "", "1l");
    echo admin_func_right_table_data($temp_in_title, "", "2r");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data("ЧПУ", "", "1lb");
    echo admin_func_right_table_data($temp_cnc, "", "2br");
    ?>
    <script type="text/javascript">
        $(document).ready(function () {

            $("#cnc_gen_<?= $lang_id; ?>").click(function () {
                if ($.trim($('#title_<?= $lang_id; ?>').val()).length) {
                    $.post('/admin/request.php?fn=generate/uri', {
                        string: $('#title_<?= $lang_id; ?>').val()
                    }, function (data)
                    {
                        $('#cnc_<?= $lang_id; ?>').val(my_replace(alphabet, data.string));
                        $('#alert_<?= $lang_id; ?>').html('<span style="color: #18c42c;">ЧПУ успешно сгенерирован</span>');
                    }, 'json');
                } else {
                    $('#alert_<?= $lang_id; ?>').html('<span style="color: 3f02222;">Введите название и нажмите генерировать</div>');
                }
            });
        });
    </script>
    <?php
    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data("SEOTitle", "", "1l");
    echo admin_func_right_table_data(admin_func_right_input("", "SEOTitle[{$lang_id}]", '', 335, 3), "", "2r");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data("SEODescription", "", "1l");
    echo admin_func_right_table_data(admin_func_right_input("", "SEODescription[{$lang_id}]", '', 335, 3), "", "2r");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data("SEOKeywords", "", "1lb");
    echo admin_func_right_table_data(admin_func_right_input("", "SEOKeywords[{$lang_id}]", '', 335, 3), "", "2br");
	
/*	echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data( "Мощность (Kw) минимальная:", "", "1l");
    echo admin_func_right_table_data( admin_func_right_input( "", "minCapacity[{$lang_id}]", '', 335, array('id' => 'minCapacity')) , "", "2r");		

	echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data( "Мощность (Kw) максимальная:", "", "1l");
    echo admin_func_right_table_data( admin_func_right_input( "", "maxCapacity[{$lang_id}]", '', 335, array('id' => 'maxCapacity')) , "", "2r");		
	
	echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data( "Крутящий момент (Nm) минимальный:", "", "1l");
    echo admin_func_right_table_data( admin_func_right_input( "", "minTorque[{$lang_id}]", '', 335, array('id' => 'minTorque')) , "", "2r");		

	echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data( "Крутящий момент (Nm) максимальный:", "", "1l");
    echo admin_func_right_table_data( admin_func_right_input( "", "maxTorque[{$lang_id}]", '', 335, array('id' => 'maxTorque')) , "", "2r");		
	
	echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data( "Передаточное отношение минимальное:", "", "1l");
    echo admin_func_right_table_data( admin_func_right_input( "", "minGearRatio[{$lang_id}]", '', 335, array('id' => 'minGearRatio')) , "", "2r");		

	echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data( "Передаточное отношение максимальное:", "", "1l");
    echo admin_func_right_table_data( admin_func_right_input( "", "maxGearRatio[{$lang_id}]", '', 335, array('id' => 'maxGearRatio')) , "", "2r");	*/	
	
	/*
    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data( "Цена", "", "1l");
    echo admin_func_right_table_data( admin_func_right_input( "", "price[{$lang_id}]", '', 335, 3) , "", "2r");		

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data( "Количество единиц", "", "2l");
    echo admin_func_right_table_data( admin_func_right_input( "", "price_for[$lang_id]", '', 120, 3) .' '. admin_func_right_input( "", "price_for_digit[$lang_id]", '', 120, 3) .' [цифры]', "", "2r");
    
    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data("Крепкость кофе", "", "2lb");
    $field8 = "<select class=\"jselect\" name=\"strength[$lang_id]\"><option value=\"0\"></option>";
    foreach (range(1,5) as $k) {
        if ($strength[$lang_id] == $k) {
            $field8 .= "<option value=\"". $k ."\" selected=\"selected\">". $k ."</option>";
        } else {
            $field8 .= "<option value=\"". $k ."\">". $k ."</option>";
        }
    }
    $field8 .= "<select>";
    echo admin_func_right_table_data($field8, "", "2br");
	*/
    # multy[$langID][0] = array(0 => '', 1 => '', 2 => ''); 
    $multy[$lang_id] = array();

    $multyFields = '<div class="multyFields" data-lang="'. $lang_id .'">';
    foreach($multy[$lang_id] as $key => $row)
    {
        $multyFields .= '<div data-row="'. $key .'">';
        foreach($row as $key2 => $v) 
        {
            if($key2 === 0) {
                $multyFields .= '<input name="multy['. $lang_id .']['. $key .']['. $key2 .']" id="multy_'. $lang_id .'_'. $key .'_'. $key2 .'" type="text" value="'. $v .'" /><input type="submit" value="Обзор" class="file_chooser" />';
            } else {
                $multyFields .= '<select name="multy['. $lang_id .']['. $key .']['. $key2 .']"><option value="0"></option>';
                
                foreach($dics[$lang_id] as $dic) {
                    $multyFields .= '<option value="'. $dic['id'] .'" '. ($dic['id'] == $v ? ' selected="selected"' : '') .'>'. $dic['title'] .'</option>';
                }
                
                $multyFields .= '</select>';
            }
        }
        $multyFields .= '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>';
    }
    $multyFields .= '</div>';

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data($multyFields, "", "7lr");

    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data('<a href="javascript:void(0);" class="addRow" data-lang="' . $lang_id . '">Добавить</a><a href="javascript:void(0);" class="delRow" data-lang="' . $lang_id . '">Удалить</a>', "", "7lr");

    # multy end	
    
    echo admin_func_right_table_row_start(2);
    echo admin_func_right_table_data("Анонс <a href=\"#\" onclick=\"window.open('/admin/ckeditor/?name=announcement{$lang_id}', 'CKEditor', 'resizable=yes,width='+screen.width+',height='+screen.height+',left=30,top=30');\">Html редактор</a>", "", "1lb");
    echo admin_func_right_table_data("<textarea style=\"width:335px;height:100px;\" name=\"announcement[{$lang_id}]\" id=\"announcement{$lang_id}\">". $announcement[$lang_id] ."</textarea>", "", "2br");
}

echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data(admin_func_right_input("submit", "action/insert", 'Добавить', "", 1), "", 7);
echo admin_func_right_table_end();
echo "</form>";
?>
<style>
    .addRow, .delRow {
        display:inline-block;
        float:left;
        margin:7px 10px 7px 0;
        text-decoration:none;
        padding:4px 8px;
    }
    .delRow {
        background:#fbca05;
        color:#373636;
    }
    .delRow:hover {
        background:#ffd422;
        color:#373636;
    }
    .addRow {
        background:#007c2e;
        color:#fff;
    }
    .addRow:hover {
        background:#00c94b;
        color:#fff;
    }
    .move {
        display:inline-block;
        font-size:12px;
        padding:0 4px;
        margin:0 0 0 4px;
        border:1px solid #007c2e;
        color:#007c2e;
        text-decoration:none;
        height:18px;
    }
    .ui-icon.ui-icon-arrowthick-2-n-s {
        margin-bottom:-5px;
        display:inline-block;
        margin-left:4px;
        cursor:pointer;
    }
    .multyFields div *:first-child {
        width:255px;
    }
    .multyFields div select {border:1px solid #ccc; width: 400px; height: 30px; overflow: hidden;}
    .multyFields select option {width: 400px;}
</style>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $('#photogalary').click(function ()
        {
            $('#photogalary1').css({
                'display': 'block'
            });
            $('#photogalary1').load('/admin/lib/wmpGallery/gallery_action_prod.php', {
                'parentID': 0,
                'name': $('#title_1').val(),
                'ids': $('input[name=galleryID]').val()
            });
        });
        $('#photogalary_add').click(function ()
        {
            $('input[name=galleryID]').attr('value', $('#gall').val());
        });

        var dictionary = <?=json_encode($dics)?>;
        
        $.fn.insertOptions = function(data, langID, valueField, titleField) {
            $(this).append(_c('option').attr('value', 0));
            for(i in data[langID]) {
                $(this).append(_c('option').attr('value', data[langID][i][valueField]).html(data[langID][i][titleField]));
            }
            return $(this);
        };
        
        $('.multyFields').each(function ()
        {
            $(this).sortable({
                handle: '.ui-icon',
                cancel: ".ui-jqgrid-labels",
                update: function (event, ui)
                {
                    updateNames($(ui.item).closest('.multyFields').data('lang'));
                }
            });
        });

        $('.addRow').click(function() 
        {
            var l = $(this).data('lang');

            $('.multyFields[data-lang="'+ l +'"]').append(
                _c('div').append(_c('input')).append(_c('input').attr({
                    'type': 'submit',
                    'value': 'Обзор',
                    'class': 'file_chooser'
                })).append(_c('select').insertOptions(dictionary, l, 'id', 'title')).append(_c('span').addClass('ui-icon ui-icon-arrowthick-2-n-s'))
            );

            updateNames(l);
        });

        $('.delRow').click(function ()
        {
            var l = $(this).data('lang');

            if ($('.multyFields[data-lang="' + l + '"] div').size() > 1) {
                $('.multyFields[data-lang="' + l + '"] div:last').remove();
            }
        });

        $('.multyFields').on('click', '.file_chooser', function() {
            newwin2('/admin/files.php?show=jqGrid&obj='+ $(this).prev().attr('id'), 720, 520);
            return false;
        });

        updateNames = function(l) 
        {
            $('.multyFields[data-lang="'+ l +'"] div').each(function(i) 
            {
                $(this).data('row', i).find('*').not('span, option').each(function(ii)
                {
                    $(this).attr({'name': 'multy['+ l +']['+ i +']['+ ii +']', 'id': 'multy_'+ l +'_'+ i +'_'+ ii});
                });
            });		
        },
        _c = function (a) {return $(document.createElement(a))};
    });
</script>
<div id='photogalary1' style='left:20%; position:fixed; top:0; width:990px; padding: 10px; height:600px;overflow:scroll; display: none;background:none repeat scroll 0 0 #fff; border: 1px solid red;'></div>
<?
include("admin_footer.php");
