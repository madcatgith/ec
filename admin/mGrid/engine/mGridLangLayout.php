<?

function GetLangControl() {

    global $lang;

	$pars         = preg_replace('/&lang=[0-9]*/i', '', $_SERVER['QUERY_STRING']);
	$lang_control = "<div id='langs_change' align='center' style='white-space: nowrap; margin-left:20px;'>";
	$lang_array   = Lang::getLanguages();

	foreach ($lang_array as $key => $value) {
		if (is_int($key))
			$lang_control .= lang_buton($key, $value['title_short'], $pars, $key == $lang);
	}
	$lang_control .="</div>";

	return $lang_control;
}

function lang_buton($lang, $name, $path, $active) {
	$active = ($active) ? "active" : "";
	return "<div id='lang_{$name}' class='langselect {$active}' style='cursor: default; float:left; margin-left:10px;'>
					<a href='".$_SERVER['PHP_SELF']."?".$path."&lang=".$lang."'>| {$name} |</a>
			</div>";
}
?>