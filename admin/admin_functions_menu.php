<?

///////////////////////////////////////////////
//                                           //
//  Данный файл содержит функции левого меню //
//                                           //
///////////////////////////////////////////////

// Вывод левого менюидного большого блока (начало)
function admin_func_menu_block_start($name, $img, $color) {
  $temp_echo  = "<table cellspacing=0 cellpadding=0 border=0 width=\"100%\">";
  //$temp_echo .= "<tr><td colspan=2><IMG src=\"p.gif\" width=20 height=20 border=1></td>";
  //$temp_echo .= "<th rowspan=2 width=33><img src=\"". $img ."\" hspace=0 vspace=0 border=0></th>";
  $temp_echo .= "<tr class=yprav><td class=\"top_2\">&nbsp; ". $name .":</td>";
  //$temp_echo .= "<td width=8><img src=\"g/v1.gif\" hspace=0 vspace=0 border=0></td>";
  $temp_echo .= "<tr><td colspan=2><IMG src=\"p.gif\" width=1 height=1 border=0></td>";
  $temp_echo .= "</table>";
  $temp_echo .= "<table cellspacing=0 cellpadding=0 border=0 bgcolor=". $color ." width=\"100%\">";
  $temp_echo .= "<tr><td align=left><div class=ul>";

  return $temp_echo;
}

// Вывод левого менюидного большого блока (конь и ец)
function admin_func_menu_block_end() {
  $temp_echo  = "</div></td>";
  $temp_echo .= "</table>";

  return $temp_echo;
}

// Вывод пунктов меню
function admin_func_menu_block_list($name,$url, $class='') {
  global $hrefauthorization;

  if(strstr($url,"?")) $url .= "&$hrefauthorization";
  else $url .= "?$hrefauthorization";

  $class = "class='{$class}'";
  
  $temp_echo  = "<li {$class} ><nobr><a class='menu' href=\"/admin/". $url ."\">". $name ."</a></nobr></li>";

  return $temp_echo;
}

function mGridTable($name) {
	return 'mGrid/mGridBases.php?db=' . $name;
}