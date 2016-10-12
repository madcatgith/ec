<?php

	///////////////////////////////////////////////
	//                                           //
	//       Данный файл содержит функции,       //
	//    связанные с выборкой данных из БД      //
	//                                           //
	///////////////////////////////////////////////

	/*  ******   новые методы    ******  */
	function admin_set_menu_cnc($lang)
	{

		$menu_array = array();
		$menu_query = DB::Query("SELECT id, cnc, menu_id, place, title, lang_id, active
		FROM ?_menu
		ORDER BY menu_id, lang_id, place, title
		");

		while($get  = DB::GetArray($menu_query)){
			$id = $get['id'];
			if (array_key_exists($id, $menu_array)){
				$menu_array[$id]['lang'][$get['lang_id']]['title'] = $get['title'];
				$menu_array[$id]['lang'][$get['lang_id']]['active'] = $get['active'];
			}else {
				$menu_array[$id]['menu_id']  = $get['menu_id'];
				$menu_array[$id]['place']    = $get['place'];
				$menu_array[$id]['title']    = $get['title'];

				$menu_array[$id]['lang'][$get['lang_id']]['title'] = $get['title'];
				$menu_array[$id]['lang'][$get['lang_id']]['active'] = $get['active'];

				//получение уровня вложенности меню на котором мы сейчас находимся
				$menu_array[$id]['lvl']      = ($menu_array[$get['menu_id']]['lvl']) ?
				$menu_array[$get['menu_id']]['lvl'] + 1
				: 1 ;
			}

			if ($lang == $get['lang_id']){
				$menu_array[$id]['title']    = $get['title'];
			}
		}

		return $menu_array;
	}

	// Вывод дерева менюидов
	function admin_func_submenus_tree($url, $url_edit, $items_array, $subitems) {

		$tmp        = "";
		$lang_array = Lang::getLanguages();

		foreach ($subitems as $key => $value) {
			if (is_int($key)) {
				$title = strip_tags(ConvertAltToHtml($value['title']));
				foreach ($value['lang'] as $lkey => $lvalue){
					if ($lvalue['active'])
						$title .= '<a href="'.$url_edit.$key.'#'.$lang_array[$lkey]['title_short'].'" class="lang active" >(' . $lang_array[$lkey]['title_short'] . ')</a>';
					else
						$title .= '<a href="'.$url_edit.$key.'#'.$lang_array[$lkey]['title_short'].'" class="lang inactive" >(' . $lang_array[$lkey]['title_short'] . ')</a>';
				}

				$item = $items_array[$key];
				$tmp.="<li id=\"phtml_".$key."\" menu_id=\"".$key."\" ><a href='".$url.$key."' ><ins> </ins>".$title."</a>"
				.admin_func_submenus_tree($url, $url_edit, $items_array, $item["submenu"])."</li>\n";
			}
		}
		if ($tmp)
			return "<ul >".$tmp."</ul>";
		else
			return "";
	}

	// Вывод дерева менюидов
	function admin_func_menu_tree($name_name, $url, $repeating, $main_page_name, $java, $view, $menu_ids, $ischeckboxed = false) {
		global $menu_array, $max_menus_level, $default_lang, $hrefauthorization;

		$menu_array = admin_set_menu_cnc(1);
		foreach ($menu_array as $key => $value){
			if (is_int($key))
				$menu_array[$value["menu_id"]]["submenu"][$key] = $value;
		}

		if(!empty($url)) {
			if(strstr($url, "?")) {
				$url .= "&$hrefauthorization&". $name_name ."=";
				$url_edit = "menu_edit.php?&$hrefauthorization&id=";
			} else {
				$url .= "?$hrefauthorization&". $name_name ."=";
				$url_edit = "menu_edit.php?&$hrefauthorization&id=";
			}
		}


		//формируем красивое уникальное имя для дерева.
		$treeview_id = 'menu'.uniqid();

		$temp_echo .= "<div id='{$treeview_id}' class='treeview' style='{$view}; display:none;'><ul>";

		//если не был задан $menu_id значит верхний уровень
		$isactive = (!$menu_id && !isset($_POST['search']))? "class='active'" : "";
		if ($ischeckboxed){
			$temp_echo .=  "<li id=\"phtml_all\" menu_id=\"all\" class='open' ><a href=\"{$url}\" ><ins> </ins>Все</a><ul>";
		}
		$temp_echo .=  "<li id=\"phtml_0\" menu_id=\"0\" class='open' ><a href=\"{$url}\" {$isactive} ><ins> </ins>Главная страница</a><br /><ul>";

		$lang_array = Lang::getLanguages();

		foreach ($menu_array as $key => $value){
			if (isset($value['menu_id']) && $value['menu_id'] == 0){
				$title = strip_tags(ConvertAltToHtml($value['title']));
				foreach ($value['lang'] as $lkey => $lvalue){
					if ($lvalue['active'])
						$title .= '<a href="'.$url_edit.$key.'#'.$lang_array[$lkey]['title_short'].'" class="lang active" >(' . $lang_array[$lkey]['title_short'] . ')</a>';
					else
						$title .= '<a href="'.$url_edit.$key.'#'.$lang_array[$lkey]['title_short'].'" class="lang inactive" >(' . $lang_array[$lkey]['title_short'] . ')</a>';
				}
				$temp_echo.="<li id=\"phtml_".$key."\" menu_id=\"".$key."\" ><a href='".$url.$key."' ><ins> </ins>".$title."</a><br />\n".
				admin_func_submenus_tree($url, $url_edit, $menu_array, $value["submenu"])."</li>";
			}
		}
		//запускаем скрипт по показыванию дерева и открытия ветвей где находиться текущий элемент
		$temp_echo.= '</ul></li>';
		if ($ischeckboxed){
			$temp_echo .= '</ul></li>';
		}
		$temp_echo .= (isset($_POST['search'])) ? "<li id=\"phtml_0\"><a href=\"#\" class=\"active\">Поиск:</a><br /></li>" : '';

		if ($ischeckboxed){
			$them = "ui : { theme_name : \"checkbox\" }, plugins : {  checkbox : { three_state : false }  },";

			$highlight_handler = "";
			foreach ($menu_ids['checked'] as $value){
				$highlight_handler .= "
				$(\"#phtml_".$value." a:first\").addClass(\"checked\")
				.parent().parents(\"li.closed\").removeClass(\"closed\").addClass(\"open\");
				var input_name = \"content_ids[".$value."]\";
				$(\"#banner\").append (\"<input type='hidden' id='\"+ input_name +\"' name='\"+ input_name +\"' value='true' />\");
				";
			}

			foreach ($menu_ids['ignored'] as $value){
				$highlight_handler .= "
				$(\"#phtml_".$value." a:first\").addClass(\"ignored\")
				.parent().parents(\"li.closed\").removeClass(\"closed\").addClass(\"open\");
				var input_name = \"content_ids[".$value."]\";
				$(\"#banner\").append (\"<input type='hidden' id='\"+ input_name +\"' name='\"+ input_name +\"' value='ignored' />\");
				";
			}

			foreach ($menu_ids['undetermined'] as $value){
				$highlight_handler .= "
				$(\"#phtml_".$value." a:first\").addClass(\"undetermined\")
				.parent().parents(\"li.closed\").removeClass(\"closed\").addClass(\"open\");
				var input_name = \"content_ids[".$value."]\";
				$(\"#banner\").append (\"<input type='hidden' id='\"+ input_name +\"' name='\"+ input_name +\"' value='undetermined' />\");
				";
			}

			if (count($menu_ids) == 0){
				$highlight_handler .= "
				$(\"#banner\").append (\"<input type='hidden' id='content_ids[all]' name='content_ids[all]' value='true' />\");
				$(\"#phtml_all a:first\").addClass(\"checked\");
				";
			}
			$click_handler = "
			, onchange : function (node) {

			var node = $(node).find(\"a:first\");
			var input_selector = \"#content_ids\\\\[\"+ node.parent().attr(\"menu_id\") +\"\\\\]\";

			var inp = $(\"#banner \" + input_selector);
			if(inp.length == 0){
			var in_name = \"content_ids[\"+ node.parent().attr(\"menu_id\") +\"]\";
			$(\"#banner\").append (\"<input type='hidden' id='\" + in_name + \"' name='\"+ in_name +\"' />\");
			inp = $(\"#banner \" + input_selector);
			}

			if (inp.attr('value') == 'false' || inp.attr('value') == ''){
			node.removeClass(\"ignored\");
			node.removeClass(\"unchecked\");
			node.removeClass(\"undetermined\");
			node.addClass(\"checked\");
			inp.attr({ value: 'true' });
			} else if(inp.attr('value') == 'true') {
			node.removeClass(\"checked\");
			node.removeClass(\"unchecked\");
			node.addClass(\"undetermined\");
			inp.attr({ value: 'undetermined' });
			} else if (inp.attr('value') == 'undetermined'){
			node.removeClass(\"unchecked\");
			node.removeClass(\"checked\");
			node.removeClass(\"undetermined\");
			node.addClass(\"ignored\");
			inp.attr({ value: 'ignored' });
			} else {
			node.removeClass(\"checked\");
			node.removeClass(\"ignored\");
			node.addClass(\"unchecked\");
			inp.attr({ value: 'false' });
			}
			}
			";

		} else {
			$them = "ui : { theme_name : \"apple\" },";

			$highlight_handler = "";
			foreach ($menu_ids as $value){
				$highlight_handler .= "
				$(\"#phtml_".$value." a:first\").addClass(\"active\")
				.parents(\"li.closed\").removeClass(\"closed\").addClass(\"open\");
				";
			}

			$click_handler = "
			, onselect : function (node, item) {
			//var addr = $(node).find(\"a:first\").attr(\"href\");
			//document.location.href = addr;
			".$java."
			breal(node);
			return true;
			}
			";
		}

		return $temp_echo.'</div>'
		."<script type=\"text/javascript\">
		$(function () {
		$(\"#".$treeview_id."\").tree({
		".$them.
		"callback : {
		onload : function () {
		$(\"#".$treeview_id."\").slideDown();
		".$highlight_handler."
		}
		".$click_handler."
		}
		});
		});
		</script>";
	}

	/*  ******   старые методы по обработке    ******  */
	function get_admin_billing_factura_propis($itogo_k_oplate, $lang_id, $currency_id) {
		$global_lang_id = $lang_id;

		$get_currency = DB::GetArray(DB::Query("select * from `?_billing_currency` where id=$currency_id and lang_id=$lang_id and active=1 "));

		$itogo_k_oplate = str_replace(".", ",", $itogo_k_oplate);

		$temp = @explode(",", $itogo_k_oplate);
		$banknota = $temp[0];
		$kopey = $temp[1];
		if(strlen($kopey) == 1) $kopey = $kopey * 10;

		$kopeyka_array[1]  = $get_currency[title_full_kopeyka_1];
		$kopeyka_array[2]  = $get_currency[title_full_kopeyka_2];
		$kopeyka_array[3]  = Dictionary::GetWord(9003);
		$kopeyka_array[4]  = Dictionary::GetWord(9004);
		$kopeyka_array[5]  = Dictionary::GetWord(9005);
		$kopeyka_array[6]  = Dictionary::GetWord(9006);
		$kopeyka_array[7]  = Dictionary::GetWord(9007);
		$kopeyka_array[8]  = Dictionary::GetWord(9008);
		$kopeyka_array[9]  = Dictionary::GetWord(9009);
		$kopeyka_array[10] = Dictionary::GetWord(9010);
		$kopeyka_array[11] = Dictionary::GetWord(9011);
		$kopeyka_array[12] = Dictionary::GetWord(9012);
		$kopeyka_array[13] = Dictionary::GetWord(9013);
		$kopeyka_array[14] = Dictionary::GetWord(9014);
		$kopeyka_array[15] = Dictionary::GetWord(9015);
		$kopeyka_array[16] = Dictionary::GetWord(9016);
		$kopeyka_array[17] = Dictionary::GetWord(9017);
		$kopeyka_array[18] = Dictionary::GetWord(9018);
		$kopeyka_array[19] = Dictionary::GetWord(9019);
		$kopeyka_array[20] = Dictionary::GetWord(9020);
		$kopeyka_array[30] = Dictionary::GetWord(9030);
		$kopeyka_array[40] = Dictionary::GetWord(9040);
		$kopeyka_array[50] = Dictionary::GetWord(9050);
		$kopeyka_array[60] = Dictionary::GetWord(9060);
		$kopeyka_array[70] = Dictionary::GetWord(9070);
		$kopeyka_array[80] = Dictionary::GetWord(9080);
		$kopeyka_array[90] = Dictionary::GetWord(9090);

		$kopey_rev = " ". strrev($kopey);
		if(!empty($kopey))
		{
			$temp = $kopey_rev[2];
			if(!empty($temp))
			{
				$temp = $temp * 10;
				$kopey_propis[] = $kopeyka_array[$temp];
			}

			$temp = $kopey_rev[1];
			if(!empty($temp))
			{
				$temp = $temp;
				$kopey_propis[] = $kopeyka_array[$temp];
			}
		}
		else $kopey_propis[] = "00";

		$kopey_propis[] = $get_currency[title_short_kopeyka];
		$kopey_propis = @implode(" ", $kopey_propis);

		$banknotaka_array[1]  = $get_currency[title_full_banknota_1];
		$banknotaka_array[2]  = $get_currency[title_full_banknota_2];
		$banknotaka_array[3]  = Dictionary::GetWord(9003);
		$banknotaka_array[4]  = Dictionary::GetWord(9004);
		$banknotaka_array[5]  = Dictionary::GetWord(9005);
		$banknotaka_array[6]  = Dictionary::GetWord(9006);
		$banknotaka_array[7]  = Dictionary::GetWord(9007);
		$banknotaka_array[8]  = Dictionary::GetWord(9008);
		$banknotaka_array[9]  = Dictionary::GetWord(9009);
		$banknotaka_array[10] = Dictionary::GetWord(9010);
		$banknotaka_array[11] = Dictionary::GetWord(9011);
		$banknotaka_array[12] = Dictionary::GetWord(9012);
		$banknotaka_array[13] = Dictionary::GetWord(9013);
		$banknotaka_array[14] = Dictionary::GetWord(9014);
		$banknotaka_array[15] = Dictionary::GetWord(9015);
		$banknotaka_array[16] = Dictionary::GetWord(9016);
		$banknotaka_array[17] = Dictionary::GetWord(9017);
		$banknotaka_array[18] = Dictionary::GetWord(9018);
		$banknotaka_array[19] = Dictionary::GetWord(9019);
		$banknotaka_array[20] = Dictionary::GetWord(9020);
		$banknotaka_array[30] = Dictionary::GetWord(9030);
		$banknotaka_array[40] = Dictionary::GetWord(9040);
		$banknotaka_array[50] = Dictionary::GetWord(9050);
		$banknotaka_array[60] = Dictionary::GetWord(9060);
		$banknotaka_array[70] = Dictionary::GetWord(9070);
		$banknotaka_array[80] = Dictionary::GetWord(9080);
		$banknotaka_array[90] = Dictionary::GetWord(9090);
		$banknotaka_array[100]  = Dictionary::GetWord(9100);
		$banknotaka_array[200]  = Dictionary::GetWord(9200);
		$banknotaka_array[300]  = Dictionary::GetWord(9300);
		$banknotaka_array[400]  = Dictionary::GetWord(9400);
		$banknotaka_array[500]  = Dictionary::GetWord(9500);
		$banknotaka_array[600]  = Dictionary::GetWord(9600);
		$banknotaka_array[700]  = Dictionary::GetWord(9700);
		$banknotaka_array[800]  = Dictionary::GetWord(9800);
		$banknotaka_array[900]  = Dictionary::GetWord(9900);

		$tysjacha_array[1]  = Dictionary::GetWord(8001);
		$tysjacha_array[2]  = Dictionary::GetWord(8002);
		$tysjacha_array[3]  = Dictionary::GetWord(9003);
		$tysjacha_array[4]  = Dictionary::GetWord(9004);
		$tysjacha_array[5]  = Dictionary::GetWord(9005);
		$tysjacha_array[6]  = Dictionary::GetWord(9006);
		$tysjacha_array[7]  = Dictionary::GetWord(9007);
		$tysjacha_array[8]  = Dictionary::GetWord(9008);
		$tysjacha_array[9]  = Dictionary::GetWord(9009);
		$tysjacha_array[10] = Dictionary::GetWord(9010);
		$tysjacha_array[11] = Dictionary::GetWord(9011);
		$tysjacha_array[12] = Dictionary::GetWord(9012);
		$tysjacha_array[13] = Dictionary::GetWord(9013);
		$tysjacha_array[14] = Dictionary::GetWord(9014);
		$tysjacha_array[15] = Dictionary::GetWord(9015);
		$tysjacha_array[16] = Dictionary::GetWord(9016);
		$tysjacha_array[17] = Dictionary::GetWord(9017);
		$tysjacha_array[18] = Dictionary::GetWord(9018);
		$tysjacha_array[19] = Dictionary::GetWord(9019);
		$tysjacha_array[20] = Dictionary::GetWord(9020);
		$tysjacha_array[30] = Dictionary::GetWord(9030);
		$tysjacha_array[40] = Dictionary::GetWord(9040);
		$tysjacha_array[50] = Dictionary::GetWord(9050);
		$tysjacha_array[60] = Dictionary::GetWord(9060);
		$tysjacha_array[70] = Dictionary::GetWord(9070);
		$tysjacha_array[80] = Dictionary::GetWord(9080);
		$tysjacha_array[90] = Dictionary::GetWord(9090);
		$tysjacha_array[100]  = Dictionary::GetWord(9100);
		$tysjacha_array[200]  = Dictionary::GetWord(9200);
		$tysjacha_array[300]  = Dictionary::GetWord(9300);
		$tysjacha_array[400]  = Dictionary::GetWord(9400);
		$tysjacha_array[500]  = Dictionary::GetWord(9500);
		$tysjacha_array[600]  = Dictionary::GetWord(9600);
		$tysjacha_array[700]  = Dictionary::GetWord(9700);
		$tysjacha_array[800]  = Dictionary::GetWord(9800);
		$tysjacha_array[900]  = Dictionary::GetWord(9900);

		$million_array[1]  = Dictionary::GetWord(9001);
		$million_array[2]  = Dictionary::GetWord(9002);
		$million_array[3]  = Dictionary::GetWord(9003);
		$million_array[4]  = Dictionary::GetWord(9004);
		$million_array[5]  = Dictionary::GetWord(9005);
		$million_array[6]  = Dictionary::GetWord(9006);
		$million_array[7]  = Dictionary::GetWord(9007);
		$million_array[8]  = Dictionary::GetWord(9008);
		$million_array[9]  = Dictionary::GetWord(9009);
		$million_array[10] = Dictionary::GetWord(9010);
		$million_array[11] = Dictionary::GetWord(9011);
		$million_array[12] = Dictionary::GetWord(9012);
		$million_array[13] = Dictionary::GetWord(9013);
		$million_array[14] = Dictionary::GetWord(9014);
		$million_array[15] = Dictionary::GetWord(9015);
		$million_array[16] = Dictionary::GetWord(9016);
		$million_array[17] = Dictionary::GetWord(9017);
		$million_array[18] = Dictionary::GetWord(9018);
		$million_array[19] = Dictionary::GetWord(9019);
		$million_array[20] = Dictionary::GetWord(9020);
		$million_array[30] = Dictionary::GetWord(9030);
		$million_array[40] = Dictionary::GetWord(9040);
		$million_array[50] = Dictionary::GetWord(9050);
		$million_array[60] = Dictionary::GetWord(9060);
		$million_array[70] = Dictionary::GetWord(9070);
		$million_array[80] = Dictionary::GetWord(9080);
		$million_array[90] = Dictionary::GetWord(9090);
		$million_array[100]  = Dictionary::GetWord(9100);
		$million_array[200]  = Dictionary::GetWord(9200);
		$million_array[300]  = Dictionary::GetWord(9300);
		$million_array[400]  = Dictionary::GetWord(9400);
		$million_array[500]  = Dictionary::GetWord(9500);
		$million_array[600]  = Dictionary::GetWord(9600);
		$million_array[700]  = Dictionary::GetWord(9700);
		$million_array[800]  = Dictionary::GetWord(9800);
		$million_array[900]  = Dictionary::GetWord(9900);

		$milliard_array[1]  = Dictionary::GetWord(9001);
		$milliard_array[2]  = Dictionary::GetWord(9002);
		$milliard_array[3]  = Dictionary::GetWord(9003);
		$milliard_array[4]  = Dictionary::GetWord(9004);
		$milliard_array[5]  = Dictionary::GetWord(9005);
		$milliard_array[6]  = Dictionary::GetWord(9006);
		$milliard_array[7]  = Dictionary::GetWord(9007);
		$milliard_array[8]  = Dictionary::GetWord(9008);
		$milliard_array[9]  = Dictionary::GetWord(9009);
		$milliard_array[10] = Dictionary::GetWord(9010);
		$milliard_array[11] = Dictionary::GetWord(9011);
		$milliard_array[12] = Dictionary::GetWord(9012);
		$milliard_array[13] = Dictionary::GetWord(9013);
		$milliard_array[14] = Dictionary::GetWord(9014);
		$milliard_array[15] = Dictionary::GetWord(9015);
		$milliard_array[16] = Dictionary::GetWord(9016);
		$milliard_array[17] = Dictionary::GetWord(9017);
		$milliard_array[18] = Dictionary::GetWord(9018);
		$milliard_array[19] = Dictionary::GetWord(9019);
		$milliard_array[20] = Dictionary::GetWord(9020);
		$milliard_array[30] = Dictionary::GetWord(9030);
		$milliard_array[40] = Dictionary::GetWord(9040);
		$milliard_array[50] = Dictionary::GetWord(9050);
		$milliard_array[60] = Dictionary::GetWord(9060);
		$milliard_array[70] = Dictionary::GetWord(9070);
		$milliard_array[80] = Dictionary::GetWord(9080);
		$milliard_array[90] = Dictionary::GetWord(9090);
		$milliard_array[100]  = Dictionary::GetWord(9100);
		$milliard_array[200]  = Dictionary::GetWord(9200);
		$milliard_array[300]  = Dictionary::GetWord(9300);
		$milliard_array[400]  = Dictionary::GetWord(9400);
		$milliard_array[500]  = Dictionary::GetWord(9500);
		$milliard_array[600]  = Dictionary::GetWord(9600);
		$milliard_array[700]  = Dictionary::GetWord(9700);
		$milliard_array[800]  = Dictionary::GetWord(9800);
		$milliard_array[900]  = Dictionary::GetWord(9900);

		$banknota_rev = strrev($banknota);
		$banknota_rev = " ". $banknota_rev;
		unset($banknota_propis);

		$temp = $banknota_rev[12];
		if(!empty($temp))
		{
			$temp = $temp * 100;
			$banknota_propis[] = $milliard_array[$temp];
		}

		$temp = $banknota_rev[11] . $banknota_rev[10];
		$temp = $temp * 1;

		if($temp <= 20)
		{
			$banknota_propis[] = $milliard_array[$temp];
		}
		else
		{
			$temp = $banknota_rev[11];
			if(!empty($temp))
			{
				$temp = $temp * 10;
				$banknota_propis[] = $milliard_array[$temp];
			}

			$temp = $banknota_rev[10];
			if(!empty($temp))
			{
				$temp = $temp;
				$banknota_propis[] = $milliard_array[$temp];
			}
		}

		$temp_val = $banknota_rev[12] . $banknota_rev[11] . $banknota_rev[10];
		$temp_val = $temp_val * 1;
		$temp_val2 = $banknota_rev[11] . $banknota_rev[10];
		$temp_val2 = $temp_val2 * 1;
		if(!empty($temp_val))
		{
			$last_digit = $banknota_rev[10];
			if($temp_val2 >= 5 and $temp_val2 <= 20) $banknota_propis[] = Dictionary::GetWord(705);
			elseif($last_digit == 1) $banknota_propis[] = Dictionary::GetWord(706);
			elseif($last_digit == 2 or $last_digit == 3 or $last_digit == 4) $banknota_propis[] = Dictionary::GetWord(707);
			else $banknota_propis[] = Dictionary::GetWord(705);
		}

		$temp = $banknota_rev[9];
		if(!empty($temp))
		{
			$temp = $temp * 100;
			$banknota_propis[] = $million_array[$temp];
		}


		$temp = $banknota_rev[8] . $banknota_rev[7];
		$temp = $temp * 1;

		if($temp <= 20)
		{
			$banknota_propis[] = $million_array[$temp];
		}
		else
		{
			$temp = $banknota_rev[8];
			if(!empty($temp))
			{
				$temp = $temp * 10;
				$banknota_propis[] = $million_array[$temp];
			}

			$temp = $banknota_rev[7];
			if(!empty($temp))
			{
				$temp = $temp;
				$banknota_propis[] = $million_array[$temp];
			}
		}

		$temp_val = $banknota_rev[9] . $banknota_rev[8] . $banknota_rev[7];
		$temp_val = $temp_val * 1;
		$temp_val2 = $banknota_rev[8] . $banknota_rev[7];
		$temp_val2 = $temp_val2 * 1;
		if(!empty($temp_val))
		{
			$last_digit = $banknota_rev[7];
			if($temp_val2 >= 5 and $temp_val2 <= 20) $banknota_propis[] = Dictionary::GetWord(708);
			elseif($last_digit == 1) $banknota_propis[] = Dictionary::GetWord(709);
			elseif($last_digit == 2 or $last_digit == 3 or $last_digit == 4) $banknota_propis[] = Dictionary::GetWord(710);
			else $banknota_propis[] = Dictionary::GetWord(708);
		}

		$temp = $banknota_rev[6];
		if(!empty($temp))
		{
			$temp = $temp * 100;
			$banknota_propis[] = $tysjacha_array[$temp];
		}


		$temp = $banknota_rev[5] . $banknota_rev[4];
		$temp = $temp * 1;

		if($temp <= 20)
		{
			$banknota_propis[] = $tysjacha_array[$temp];
		}
		else
		{
			$temp = $banknota_rev[5];
			if(!empty($temp))
			{
				$temp = $temp * 10;
				$banknota_propis[] = $tysjacha_array[$temp];
			}

			$temp = $banknota_rev[4];
			if(!empty($temp))
			{
				$temp = $temp;
				$banknota_propis[] = $tysjacha_array[$temp];
			}
		}

		$temp_val = $banknota_rev[6] . $banknota_rev[5] . $banknota_rev[4];
		$temp_val2 = $banknota_rev[5] . $banknota_rev[4];
		$temp_val = $temp_val * 1;
		$temp_val2 = $temp_val2 * 1;
		if(!empty($temp_val))
		{
			$last_digit = $banknota_rev[4];
			if($temp_val2 >= 5 and $temp_val2 <= 20) $banknota_propis[] = Dictionary::GetWord(711);
			elseif($last_digit == 1) $banknota_propis[] = Dictionary::GetWord(712);
			elseif($last_digit == 2 or $last_digit == 3 or $last_digit == 4) $banknota_propis[] = Dictionary::GetWord(713);
			else $banknota_propis[] = Dictionary::GetWord(711);
		}

		$temp = $banknota_rev[3];
		if(!empty($temp))
		{
			$temp = $temp * 100;
			$banknota_propis[] = $banknotaka_array[$temp];
		}

		$temp = $banknota_rev[2] . $banknota_rev[1];
		$temp = $temp * 1;

		if($temp <= 20)
		{
			$banknota_propis[] = $banknotaka_array[$temp];
		}
		else
		{
			$temp = $banknota_rev[2];
			if(!empty($temp))
			{
				$temp = $temp * 10;
				$banknota_propis[] = $banknotaka_array[$temp];
			}

			$temp = $banknota_rev[1];
			if(!empty($temp))
			{
				$temp = $temp;
				$banknota_propis[] = $banknotaka_array[$temp];
			}
		}

		$temp_val = $banknota_rev[2] . $banknota_rev[1];
		$temp_val = $temp_val * 1;
		$banknota_propis[] = $get_currency[title_short_banknota];

		$banknota_propis = @implode(" ", $banknota_propis);
		//$banknota_propis = substr($banknota_propis, 1);

		$propis = $banknota_propis ." ". $kopey_propis;

		return $propis;
	}

	// Вывод дерева категорий юзеров
	function admin_func_db_user_category_tree_select($exept_category_id, $name_name, $url, $repeating, $main_page_name, $java, $view) {
		global $max_menus_level, $default_lang, $hrefauthorization, ${$name_name};

		if(!empty($exept_category_id)) $where_plus = "and id!='$exept_category_id'";

		if(!empty($url))
		{
			if(strstr($url,"?")) $url .= "&$hrefauthorization&". $name_name ."=";
			else $url .= "?$hrefauthorization&". $name_name ."=";
		}

		if(!empty($java)) $java = " onChange='javascript: document.location=this.value'";
		if(!empty($main_page_name))
		{
			unset($selected);
			if(empty(${$name_name})) $selected = "selected";

			$main_page_name = "<option value=\"". $url ."0\"". $selected .">". $main_page_name;
		}

		if(empty($view)) $view = " style=\"font-size:9px\"";

		$temp_echo .= "<select name=\"". $name_name ."\"". $java . $view .">";
		$temp_echo .= $main_page_name;

		$max_level = Registry::get('maxMenuLevel');;
		unset($eval_array);
		for($i=$max_level;$i>=1;$i--)
		{
			$next = $i + 1;
			unset($temp_plus);
			if($i != $max_level) $temp_plus = $eval_array[$next];
			$eval_array[$i] = "\n".
			"\$select_category". $i ." = DB::Query(\"select * from `?_user_category` where category_id=\$pre". $i ."_id and lang_id=$default_lang $where_plus order by place,id \");\n".
			"\n".
			"\n".
			"while(\$get_category". $i ." = DB::GetArray(\$select_category". $i ."))\n".
			"{\n".
			"  \$temp = \"\$get_category". $i ."[id]\";\n".
			"  unset(\$selected);\n".
			"  if(\$temp == \${\$name_name}) \$selected = \"selected\";\n".
			"  \n".
			"  \$temp_echo .= \"<option value=\\\"". $url ."\$temp\\\" \$selected>". str_repeat($repeating, $i) ."\";\n".
			"  \$temp_echo .= \"\$get_category". $i ."[title]\";\n".
			"  \$pre". ($i+1) ."_id = \$get_category". $i ."[id];\n".
			"  $temp_plus\n".
			"}";
		}

		$pre1_id = 0;
		eval($eval_array[1]);
		unset($eval_array);

		$temp_echo .= "</select>";

		return $temp_echo;
	}

	// Вывод дерева менюидов
	function admin_func_menu_tree_select($exept_menu_id, $name_name, $url, $repeating, $main_page_name, $java, $view) {
		global $max_menus_level, $default_lang, $hrefauthorization, ${$name_name};

		if(!empty($exept_menu_id)) $where_plus = "and id!='$exept_menu_id'";

		if(!empty($url))
		{
			if(strstr($url,"?")) $url .= "&$hrefauthorization&". $name_name ."=";
			else $url .= "?$hrefauthorization&". $name_name ."=";
		}

		if(empty($java)) unset($java);
		if(!empty($java)) $java = " onChange='javascript: document.location=this.value'";
		if(!empty($main_page_name))
		{
			unset($selected);
			if(empty(${$name_name})) $selected = "selected";

			$main_page_name = "<option value=\"". $url ."0\"". $selected .">". $main_page_name;
		}

		if(empty($view)) $view = " style=\"font-size:9px\"";

		$temp_echo .= "<select name=". $name_name . $java . $view .">";
		$temp_echo .= $main_page_name;

		$max_level = Registry::get('maxMenuLevel');
		$defLang = Registry::get('defAdmin');
		
		unset($eval_array);
		for($i=$max_level;$i>=1;$i--)
		{
			$next = $i + 1;
			unset($temp_plus);
			if($i != $max_level) $temp_plus = $eval_array[$next];
			$eval_array[$i] = "\n".
			"\$select_menu". $i ." = DB::Query(\"select * from `?_menu` where menu_id=\$pre". $i ."_id and lang_id=$defLang $where_plus order by place,id \");\n".
			"\n".
			"\n".
			"while(\$get_menu". $i ." = DB::GetArray(\$select_menu". $i ."))\n".
			"{\n".
			"  \$temp = \"\$get_menu". $i ."[id]\";\n".
			"  unset(\$selected);\n".
			"  if(\$temp == \${\$name_name}) \$selected = \"selected\";\n".
			"  \n".
			"  \$temp_echo .= \"<option value=\\\"". $url ."\$temp\\\" \$selected>". str_repeat($repeating, $i) ."\";\n".
			"  \$temp_echo .= strip_tags(ConvertAltToHtml(\$get_menu". $i ."['title']));\n".
			"  \$pre". ($i+1) ."_id = \$get_menu". $i ."[id];\n".
			"  $temp_plus\n".
			"}";
		}

		$pre1_id = 0;
		eval($eval_array[1]);
		unset($eval_array);

		$temp_echo .= "</select>";

		return $temp_echo;
	}

	/*
	// Вывод дерева менюидов и контентов
	function admin_func_db_menu_and_content_tree_select($exept_menu_id, $exept_content_id, $name_name, $multiple, $size, $repeating, $repeating_content, $main_page_name, $all_name, $view, $addition)
	{
	// $exept_menu_id     - кроме id этого меню
	// $exept_content_id  - кроме id этого контента
	// $name_name         - name переменной
	// $multiple          - 1 - multiple, 0 - НЕ multiple
	// $size              - размер select'а: кол-во строк
	// $repeating         - повторяющиеся кульбабки * уровень менюида перед каждым менюидом
	// $repeating_content - мулька, вставляемая перед контентом
	// $main_page_name    - название menu_id с нулевым уровнем
	// $all_name          - название всего
	// $view              - вид
	// $addition          - на всякий случай

	global $max_menus_level, $default_lang, $hrefauthorization, ${$name_name};

	if(!empty($exept_menu_id))    $where_plus_menu    = "and id!='$exept_menu_id'";
	if(!empty($exept_content_id)) $where_plus_content = "and id!='$exept_content_id'";
	if(!empty($size)) $size = " size=\"$size\"";
	if(!empty($multiple))
	{
	$multiple = " multiple";
	$name_name_use = $name_name. "[]";
	}
	if(empty($view)) $view = " style=\"font-size:9px\"";

	$select_view = "<select name=". $name_name_use . $view . $size . $multiple . $addition .">";

	if(!empty($main_page_name))
	{
	unset($selected);
	if(!empty($multiple))
	{
	if(@in_array(${$name_name})) $selected = "selected";
	}
	else
	{
	if(${$name_name} == "0") $selected = "selected";
	}

	$main_page_name = "<option value=\"0\"". $selected .">". $main_page_name;
	}


	$temp_echo .= "";
	$temp_echo .= $main_page_name;

	$max_level = Registry::get('maxMenuLevel');;
	unset($eval_array);
	for($i=$max_level;$i>=1;$i--)
	{
	$next = $i + 1;
	unset($temp_plus);
	if($i != $max_level) $temp_plus = $eval_array[$next];
	$eval_array[$i] = "\n".
	"\$select_menu". $i ." = DB::Query(\"select * from `?_menu` where menu_id=\$pre". $i ."_id and lang_id=$default_lang $where_plus order by place,id \");\n".
	"\n".
	"\n".
	"while(\$get_menu". $i ." = DB::GetArray(\$select_menu". $i ."))\n".
	"{\n".
	"  \$temp = \"\$get_menu". $i ."[id]\";\n".
	"  unset(\$selected);\n".
	"  if(\$temp == \${\$name_name}) \$selected = \"selected\";\n".
	"  \n".
	"  \$temp_echo .= \"<option value=\\\"". $url ."\$temp\\\" \$selected>". str_repeat($repeating, $i) ."\";\n".
	"  \$temp_echo .= \"\$get_menu". $i ."[title]\";\n".
	"  \$pre". ($i+1) ."_id = \$get_menu". $i ."[id];\n".
	"  $temp_plus\n".
	"}";
	}

	$pre1_id = 0;
	eval($eval_array[1]);
	unset($eval_array);

	$temp_echo .= "</select>";

	return $temp_echo;
	}
	*/

	// Вывод дерева карт
	function admin_func_map_tree_select($exept_map_id, $name_name, $url, $repeating, $main_page_name, $java, $view) {
		global $max_menus_level, $default_lang, $hrefauthorization, ${$name_name}, $coords;

		if(!empty($exept_map_id)) $where_plus = "and id!='$exept_map_id'";

		if(!empty($url))
		{
			if(strstr($url,"?")) $url .= "&$hrefauthorization&". $name_name ."=";
			else $url .= "?$hrefauthorization&". $name_name ."=";
		}

		if($java == 1)
		{
			$java = " onChange='javascript: document.location=this.value'";

			$option_value = $url. "\$temp";
		}
		elseif($java == 3)
		{
			$java = " onChange='javascript: closewin(this.value)'";

			$option_value = htmlspecialchars("<?php \\\$start_map_id=\"\$temp\"; include \"map.php\"; php?>");
		}
		elseif($java == 2)
		{
			$java = " onChange='javascript: selectMap()'";

			$option_value = "\$temp";

			$temp_echo .= "<script language=\"JavaScript\">";



			$temp_echo .= "function put_xy()".
			"{";

			$select_langs = DB::Query("select * from `?_lang` where active=1");
			while($get_lang = DB::GetArray($select_langs))
			{
				$lang_id = $get_lang[id];

				$temp_echo .= "  ".
				"  var old_coords = '$coords[$lang_id]';".
				"  ".
				"  tmpArray = new Array();".
				"  coordsArray = new Array();".
				"  myRe=/(\d+)/g;".
				"  ".
				"  jj=0;".
				"  while(tmpArray = myRe.exec(old_coords)) coordsArray[jj++] = parseInt(tmpArray[0]);".
				"  ".
				"  for(i=0; i<coordsArray.length; )".
				"  {".
				"    var PointLeft = (coordsArray[i++] - 2);".
				"    var PointTop  = (coordsArray[i++] - 3);".
				"    document.all.item(\"TD2coords". $lang_id ."\").innerHTML += '<img src=\"map_point.gif\" style=\"position: absolute; top:'+ PointTop +'px; left:'+ PointLeft +'px;\" align=top>';".
				"  }".
				"  ".
				"  if(old_coords != '')".
				"  {".
				"    document.all.item(\"crearField". $lang_id ."\").innerHTML = '&nbsp;&nbsp;&nbsp;<a style=\"color: blue; cursor: hand; text-decoration: underline;\" onClick=\"clear_xy". $lang_id ."();\">очистить область (точки)</a>';".
				"  }";
			}
			$temp_echo .= "}";


			$select_langs = DB::Query("select * from `?_lang` where active=1");
			while($get_lang = DB::GetArray($select_langs))
			{
				$lang_id = $get_lang[id];

				$temp_echo .= "function clear_xy". $lang_id ."()".
				"{".
				"  ".
				"  document.foreverForm.coords". $lang_id .".value = '';".
				"  ".
				"  var mapID = document.all.item(\"map_id\").value;".
				"  document.all.item(\"TD2coords". $lang_id ."\").innerHTML = '<img src=\"/i.php?map_id='+ mapID +'&lang_id=$lang_id\" style=\"position: absolute;top:0px; left:0px;\" onmousedown=\"get_xy". $lang_id ."();\">';".
				"  document.all.item(\"TD2coords". $lang_id ."\").innerHTML = '<img src=\"/i.php?map_id='+ mapID +'&lang_id=$lang_id\" style=\"position: relative;top:0px; left:0px;\" onmousedown=\"get_xy". $lang_id ."();\">';".
				"  document.all.item(\"crearField". $lang_id ."\").innerHTML = '';".
				"}";

				$temp_echo .= "function get_xy". $lang_id ."()".
				"{".
				"  curr_x = event.x;".
				"  curr_y = event.y;".
				"  if (document.foreverForm.coords". $lang_id .".value) document.foreverForm.coords". $lang_id .".value += ',';".
				"  ".
				"  var PointTop  = (curr_y - 2);".
				"  var PointLeft = (curr_x - 3);".
				"  ".
				"  document.foreverForm.coords". $lang_id .".value += curr_x +','+ curr_y;".
				"  document.all.item(\"TD2coords". $lang_id ."\").innerHTML += '<img src=\"map_point.gif\" style=\"position: absolute; top:'+ PointTop +'px; left:'+ PointLeft +'px;\" align=top>';".
				"  document.all.item(\"crearField". $lang_id ."\").innerHTML = '&nbsp;&nbsp;&nbsp;<a style=\"color: blue; cursor: hand; text-decoration: underline;\" onClick=\"clear_xy". $lang_id ."();\">очистить область (точки)</a>';".
				"}";
			}



			$temp_echo .= "function selectMap()".
			"{".
			"  var mapID = document.all.item(\"map_id\").value;".
			"  if(mapID > 0)".
			"  {";



			$select_langs = DB::Query("select * from `?_lang` where active=1");
			while($get_lang = DB::GetArray($select_langs))
			{
				$lang_id = $get_lang[id];

				$temp_echo .= "  document.all.item(\"TD1another_page". $lang_id ."\").style.display = '';";
				$temp_echo .= "  document.all.item(\"TD2another_page". $lang_id ."\").style.display = '';";
				$temp_echo .= "  document.all.item(\"TD1coords". $lang_id ."\").style.display = '';";
				$temp_echo .= "  document.all.item(\"TD2coords". $lang_id ."\").style.display = '';";
				$temp_echo .= "  document.all.item(\"TD2coords". $lang_id ."\").innerHTML = '<img src=\"/i.php?map_id='+ mapID +'&lang_id=$lang_id\" style=\"position: absolute;top:0px; left:0px;\" onmousedown=\"get_xy". $lang_id ."();\">';";
				$temp_echo .= "  document.all.item(\"TD2coords". $lang_id ."\").innerHTML = '<img src=\"/i.php?map_id='+ mapID +'&lang_id=$lang_id\" style=\"position: relative;top:0px; left:0px;\" onmousedown=\"get_xy". $lang_id ."();\">';";
				$temp_echo .= "  document.all.item(\"TD1text_short". $lang_id ."\").style.display = '';";
				$temp_echo .= "  document.all.item(\"TD2text_short". $lang_id ."\").style.display = '';";
				$temp_echo .= "  document.all.item(\"TD1text". $lang_id ."\").style.display = '';";
				$temp_echo .= "  document.all.item(\"TD2text". $lang_id ."\").style.display = '';";
				$temp_echo .= "  document.all.item(\"TD3text". $lang_id ."\").style.display = '';";
			}



			$temp_echo .= "  }".
			"  else".
			"  {";



			$select_langs = DB::Query("select * from `?_lang` where active=1");
			while($get_lang = DB::GetArray($select_langs))
			{
				$lang_id = $get_lang[id];

				$temp_echo .= "  document.all.item(\"TD1another_page". $lang_id ."\").style.display = 'none';";
				$temp_echo .= "  document.all.item(\"TD2another_page". $lang_id ."\").style.display = 'none';";
				$temp_echo .= "  document.all.item(\"TD1coords". $lang_id ."\").style.display = 'none';";
				$temp_echo .= "  document.all.item(\"TD2coords". $lang_id ."\").style.display = 'none';";
				$temp_echo .= "  document.all.item(\"TD1text_short". $lang_id ."\").style.display = 'none';";
				$temp_echo .= "  document.all.item(\"TD2text_short". $lang_id ."\").style.display = 'none';";
				$temp_echo .= "  document.all.item(\"TD1text". $lang_id ."\").style.display = 'none';";
				$temp_echo .= "  document.all.item(\"TD2text". $lang_id ."\").style.display = 'none';";
				$temp_echo .= "  document.all.item(\"TD3text". $lang_id ."\").style.display = 'none';";
			}



			$temp_echo .= "  }".
			"}".
			"</script>";
		}
		if(!empty($main_page_name))
		{
			unset($selected);
			if(empty(${$name_name})) $selected = "selected";

			$main_page_name = "<option value=\"". $url ."0\"". $selected .">". $main_page_name;
		}

		if(empty($view)) $view = " style=\"font-size:9px\"";

		$temp_echo .= "<select name=". $name_name . $java . $view .">";
		$temp_echo .= $main_page_name;

		$max_level = Registry::get('maxMenuLevel');;
		unset($eval_array);
		for($i=$max_level;$i>=1;$i--)
		{
			$next = $i + 1;
			unset($temp_plus);
			if($i != $max_level) $temp_plus = $eval_array[$next];
			$eval_array[$i] = "\n".
			"\$select_map". $i ." = DB::Query(\"select id,title from `?_map` where imgurl!='' and map_id=\$pre". $i ."_id and lang_id=$default_lang $where_plus order by place,id \");\n".
			"\n".
			"\n".
			"while(\$get_map". $i ." = DB::GetArray(\$select_map". $i ."))\n".
			"{\n".
			"  \$temp = \"\$get_map". $i ."[id]\";\n".
			"  unset(\$selected);\n".
			"  if(\$temp == \${\$name_name}) \$selected = \"selected\";\n".
			"  \n".
			"  \$temp_echo .= \"<option value=\\\"$option_value\\\" \$selected>". str_repeat($repeating, $i) ."\";\n".
			"  \$temp_echo .= \"\$get_map". $i ."[title]\";\n".
			"  \$pre". ($i+1) ."_id = \$get_map". $i ."[id];\n".
			"  $temp_plus\n".
			"}";
		}

		$pre1_id = 0;
		eval($eval_array[1]);
		unset($eval_array);

		$temp_echo .= "</select>";

		return $temp_echo;
	}

	// Вывод дерева категорий продуктов
	function admin_func_product_tree_select($exept_product_category_id, $name_name, $url, $repeating, $main_page_name, $java, $view) {
		global $max_menus_level, $default_lang, $default_lang_admin, $hrefauthorization, ${$name_name}, $coords;

		if(!empty($exept_product_category_id)) $where_plus = "and id!='$exept_product_category_id'";

		if(!empty($url))
		{
			if(strstr($url,"?")) $url .= "&$hrefauthorization&". $name_name ."=";
			else $url .= "?$hrefauthorization&". $name_name ."=";
		}

		if ($java == 1) {
			$java = " onChange='javascript: document.location=this.value'";

			$option_value = $url. "\$temp";
		} elseif($java == 3) {
			$java         = " onChange='javascript: closewin(this.value)'";
			$option_value = htmlspecialchars("{php echo ProductCatalogue::getCatalogue(Lang::getID(), \$temp, 1, array('sqlLimit' => 9)); php}");
		}

		if(!empty($main_page_name))
		{
			unset($selected);
			if(empty(${$name_name})) $selected = "selected";

			$main_page_name = "<option value=\"". $url ."0\"". $selected .">". $main_page_name;
		}

		// Вид валюты по-умолчанию
		$default_currency_str = @array_shift(DB::GetArray(DB::Query("select `title_short_banknota` from `?_billing_currency` where `default`=1 and `lang_id`=$default_lang_admin ")));

		if(empty($view)) $view_str = " style=\"font-size:9px\"";

		$temp_echo .= "<select name=". $name_name . $java . $view_str .">";
		$temp_echo .= $main_page_name;

		$max_level = Registry::get('maxMenuLevel');;
		unset($eval_array);
		for($i=$max_level;$i>=1;$i--)
		{
			$next = $i + 1;
			unset($temp_plus);
			if($i != $max_level) $temp_plus = $eval_array[$next];
			$eval_array[$i] = "\n".
			"\$select_product_category". $i ." = DB::Query(\"select id,title from `?_product_category` where category_id=\$pre". $i ."_id and lang_id=$default_lang $where_plus order by place,id \");\n".
			"\n".
			"\n".
			"while(\$get_product_category". $i ." = DB::GetArray(\$select_product_category". $i ."))\n".
			"{\n".
			"  if(\$view == \"full\")\n".
			"  {\n".
			"    \$select_product = DB::Query(\"select id,title,price,price_for_what from `?_product` where category_id=\$get_product_category". $i ."[id] and lang_id=$default_lang order by place,id \");\n".
			"    while(\$get_product = DB::GetArray(\$select_product))\n".
			"    {\n".
			"      unset(\$selected);\n".
			"      if(\$get_product[id] == \${\$name_name}) \$selected = \"selected\";\n".
			"      \n".
			"      \$temp_echo .= \"<option value=\\\"\$get_product[id]\\\" \$selected>". str_repeat($repeating, $i) ."\";\n".
			"      \$temp_echo .= \"\$get_product_category". $i ."[title] - \$get_product[title] (\$get_product[price] $default_currency_str \$get_product[price_for_what])\";\n".
			"    }\n".
			"  }\n".
			"  else\n".
			"  {\n".
			"    \$temp = \"\$get_product_category". $i ."[id]\";\n".
			"    unset(\$selected);\n".
			"    if(\$temp == \${\$name_name}) \$selected = \"selected\";\n".
			"    \n".
			"    \$temp_echo .= \"<option value=\\\"$option_value\\\" \$selected>". str_repeat($repeating, $i) ."\";\n".
			"    \$temp_echo .= \"\$get_product_category". $i ."[title]\";\n".
			"  }\n".
			"  \$pre". ($i+1) ."_id = \$get_product_category". $i ."[id];\n".
			"  $temp_plus\n".
			"}";
		}

		$pre1_id = 0;
		eval($eval_array[1]);
		unset($eval_array);

		$temp_echo .= "</select>";

		return $temp_echo;
	}

?>
