<?
function print_header($title, $goto="")
{
		print "<SCRIPT language=JavaScript src='styles/gotourl.js'></script>";
}

//Функция: delete_print
//Назначение: вывод сообщения о подтверждении удаления
function delete_print($d_path, $filename) {
	global $path;
	$count = count($filename);
	print_header(Dictionary::GetAdminWord(734), "yes");
	print "<form name=delete action='action.php?' method=post>\n<table align=center width=600 border=0 cellspacing=1 cellpadding=3 bgcolor=#333333>
		<tr height=30 align=center>\n<td bgcolor=999999><font class=font_2 color=white><b>". Dictionary::GetAdminWord(734) ."</b></font></td></tr>\n";
	print "<tr align=center><td bgcolor=#EEEEEE><font class=font_2><b>". Dictionary::GetAdminWord(735) ."</b></font><HR size=1>\n";
	for ($i=0; @$filename[$i];$i++) {
		if (is_dir($path."/".$filename[$i])) { $type = Dictionary::GetAdminWord(736); }
		else { $type = Dictionary::GetAdminWord(712); }
		print "<font class=font_2>".$type." <b>'".$filename[$i]."'</b></font>\n<input type=hidden name='filename[".$i."]' value='".$filename[$i]."'><br>\n";
	}				
	print "</td></tr><tr align=center>\n<td bgcolor=#EEEEEE><font class=font_2>". Dictionary::GetAdminWord(737) .": ".$count."</font></td></tr><tr align=center>\n<td bgcolor=#CCCCCC><input type=hidden name=delete_cf value=\"TRUE\"><input type=submit name=exit value=". Dictionary::GetAdminWord(441) .">\n<input type=hidden name=d_path value=".$d_path.">\n<input type=hidden name=show value=".$show.">\n<input type=submit name=exit value='". Dictionary::GetAdminWord(106) ."' ONCLICK=\"MM_goToURL('parent','files.php?d_path=".$d_path."');return document.MM_returnValue\"></td></tr></table></form>";
}
//Функция: delete_files
//Назначение: Удаление файлов
function delete_all($path, $filename) {
	global $d_path,$i;
	@$file = $path."/".$filename[$i];
	if (is_dir($file)) {
		RmDirR ($file);
	}
	else {
		@unlink ($file) or die(header ("Location: files.php?d_path=".$d_path."&write=".base64_encode("". Dictionary::GetAdminWord(738) .": '".$filename[$i]."'")));	
	}
}
//Функция: RmDirR
//Назначение: удаление директорий и все, что в них находится
function RmDirR ($dir) {
	$d = dir ($dir);
	while($entry = $d->read()) {
		if ($entry != "." && $entry != "..") {
			if (Is_Dir($dir."/".$entry)) {
				RmDirR($dir."/".$entry);
			}
			else {
				UnLink ($dir."/".$entry);
			}
		}
	}
	$d->close();
	RmDir ($dir);
}
//Функция: rename_file
//Назначение: Переименование | Вывод окна для подтверждения переименоввания
function rename_file($file) {
	global $d_path,$write;
	print_header(Dictionary::GetAdminWord(739), "yes");
	if (@$write) {
		print "<table width=600  border=0 cellspacing=1 cellpadding=5 bgcolor=#000000 bordercolor=#000000 align=center>
 <tr bgcolor=#EEEEEE height=25>
   <td bgcolor=#FFFFFF width=60 nowrap><font class=font_2>". Dictionary::GetAdminWord(740) .":</font></td>
   <td bgcolor=#FFFFFF><font class=font_2 color=#FF0000><b> ".base64_decode($write)."</b></font>\n</td></tr></table>";
	}
	print "<form name=rename action=action.php? method=post>\n<table align=center width=600 border=0 cellspacing=1 cellpadding=3 bgcolor=#333333>\n<tr height=30 align=center>\n<td bgcolor=#3399CC background=images/bckg.gif>\n<font class=font_2><b>". Dictionary::GetAdminWord(739) ."</b></font></td></tr>\n<tr align=center><td bgcolor=#EEEEEE>\n";
	print "<font class=font_2>". Dictionary::GetAdminWord(741) .": <b>'".basename($file)."'</b><br><br><b>Новое имя: </b><input type=hidden name=file value='".$file."'>\n<input type=hidden name=d_path value='".$d_path."'>\n<input type=hidden name=d_path value='".$d_path."'>\n<input type=text NAME=new_name class=input_text size=18 value='".basename($file)."'><br></font></td></tr>\n<tr align=center><td bgcolor=#CCCCCC>\n<input type=image name=rename src=images/rename.gif alt=". Dictionary::GetAdminWord(742) .">\n<input type=image name=exit src=images/exit.gif alt='". Dictionary::GetAdminWord(106) ."' ONCLICK=\"MM_goToURL('parent','files.php?d_path=".$d_path."');return document.MM_returnValue\"></td></tr></table></form></body></html>";
}
//Функция: rename_cf
//Назначение: Переименование
function rename_cf($d_path ,$file, $basename, $new_name) {
	global $type2,$type3,$path;
	$new_name_1 = $path."/".$new_name; //Объединить путь и имя файла в полное имя нового файла
	if ($new_name == "") {
		header ("Location: files.php?d_path=".$d_path."&write=".base64_encode(Dictionary::GetAdminWord(743)));
		exit;
	}
	if  (preg_match("/([ ])/", $new_name)) {
		header ("Location: action.php?action=rename&filename[0]=".basename($file)."&d_path=".$d_path."&write=".base64_encode(Dictionary::GetAdminWord(744)));
		exit;
	}
	else {
		rename($file, $new_name_1) or die(header ("Location: files.php?d_path=".$d_path."&write=".base64_encode("". Dictionary::GetAdminWord(745) .":".$file)));
		header ("Location: files.php?d_path=".$d_path."&write=".base64_encode($type2." <b>'".$basename."'</b> ". Dictionary::GetAdminWord(746) ."".$type3." ". Dictionary::GetAdminWord(747) ." <b>'".$new_name."'"));
		exit;
	}
}
//Функция: edit_file
//Назначение: Окно редактирования файла
function edit_file($file, $basename, $filename) {
	global $d_path,$referer,$write,$template;
    $fh = fopen(@$file, "r") or die(header ("Location: files.php?d_path=".$d_path."&write=".base64_encode(Dictionary::GetAdminWord(748))));
	$editing = fread($fh, filesize($file));
	$editing = htmlspecialchars($editing);
	print_header(Dictionary::GetAdminWord(749), "yes");
	if (@$write) {
		print "<table width=600  border=0 cellspacing=1 cellpadding=5 bgcolor=#000000 align=center>
 <tr bgcolor=#EEEEEE height=25>
   <td bgcolor=#FFFFFF><font class=font_2 color=#FF0000><b> ".base64_decode($write)."</b></font>\n</td></tr></table><br>\n";
   }
	print "<table align=center width=600 border=0 cellspacing=1 cellpadding=3 bgcolor=#333333>\n<tr height=30 align=center><td bgcolor=#3399CC background=images/bckg.gif>\n<font class=font_2><b>". Dictionary::GetAdminWord(750) ."</b></font></td><tr align=center><td bgcolor=#EEEEEE>\n";
	print "<form name=edit action='action.php?' method=post>\n<font class=font_2>". Dictionary::GetAdminWord(712) .": <b>".$basename."</b></font></td></tr>\n<tr align=center><td bgcolor=#EEEEEE>\n<input type=hidden name=file value='".$file."'>\n<input type=hidden name=d_path value='".$d_path."'>\n<textarea name=text rows=25 cols=95 class=form>";
	if (@$template) {
		print "&lt;!DOCTYPE HTML PUBLIC &quot;-//W3C//DTD HTML 4.0 Transitional//EN&quot;&gt;
&lt;HTML&gt;
&lt;HEAD&gt;
&lt;TITLE&gt; New Document &lt;/TITLE&gt;
&lt;META NAME=&quot;Generator&quot; CONTENT=&quot;PHP-CoolFile v1.4&quot;&gt;
&lt;/HEAD&gt;

&lt;BODY&gt;

&lt;/BODY&gt;
&lt;/HTML&gt;";
	}
	else {
		print $editing;
	}
	print "</textarea></td></tr>\n<tr align=center><td bgcolor=#CCCCCC>\n<input type=image name=exit src=images/exit.gif alt='". Dictionary::GetAdminWord(106) ."' ONCLICK=\"MM_goToURL('parent','files.php?d_path=".$d_path."&write=".base64_encode(Dictionary::GetAdminWord(750))."');return document.MM_returnValue\">&nbsp;\n<input type=image name=save src=images/save.gif alt='". Dictionary::GetAdminWord(428) ."'></td>\n</tr></table></FORM>";
	fclose($fh);
}
//Функция: save_file
//Назначение: Сохранение редактируемого файла
function save_file($file, $basename, $text, $d_path) {
	$text = stripslashes($text);
	$fh = fopen($file, "w+") or die();
	$success = fwrite($fh, $text);
	fclose($fh);
	header ("Location: files.php?d_path=".$d_path."&write=".base64_encode("". Dictionary::GetAdminWord(712) ." <b>'".$basename."'</b> ". Dictionary::GetAdminWord(751) .""));
	exit;
}
//Функция: mkdir_confirm
//Назначение: Создание директории
function MakeDir($path, $new_dir) {
	global $d_path;
	$file = $path."/".$new_dir;
	if ($new_dir == "") {
		header ("Location: files.php?d_path=".$d_path."&write=".base64_encode(Dictionary::GetAdminWord(752)));
		exit;
	}
	elseif (preg_match("/([ ])/", $new_dir)) {
		header ("Location: files.php?d_path=".$d_path."&write=".base64_encode(Dictionary::GetAdminWord(753)));
		exit;
	}
	elseif (is_dir($file)) {
		header ("Location: files.php?d_path=".$d_path."&write=".base64_encode(Dictionary::GetAdminWord(754)));
		exit;
	}
	else {
		mkdir($file, 0666) or die(header ("Location: files.php?d_path=".$d_path."&write=".base64_encode(Dictionary::GetAdminWord(755))));
		header ("Location: files.php?d_path=".$d_path."&write=".base64_encode("". Dictionary::GetAdminWord(736) ." '".$new_dir."'". Dictionary::GetAdminWord(756) .""));
		exit;
	}
}

//Функция: mkfile_confirm
//Назначение: Создание файла
function MakeFile($path, $d_path, $new_file) {
	global $temp;
    $file = $path."/".$new_file;
    $filename = basename($file);
	if ($new_file == "") {
		header ("Location: files.php?d_path=".$d_path."&write=".base64_encode(Dictionary::GetAdminWord(757)));
		exit;
	}
	elseif (preg_match("/([ ])/", $new_file)) {
		header ("Location: files.php?d_path=".$d_path."&write=".base64_encode(Dictionary::GetAdminWord(758)));
		exit;
	}
	elseif (file_exists($file)) {
		header ("Location: files.php?d_path=".$d_path."&write=".base64_encode(Dictionary::GetAdminWord(759)));
		exit;
	}
	else {
		$fh = fopen($file, "a") or die(header ("Location: files.php?d_path=".$d_path."&write=".base64_encode(Dictionary::GetAdminWord(760))));
		fclose($fh);
		if (@$temp == "create") {
			$print = "template=true&";
		}
		header ("Location: action.php?d_path=".$d_path."&filename[0]=".$new_file."&action=edit&".@$print."write=".base64_encode(Dictionary::GetAdminWord(761)));
		exit;
	}
}
//Функция: copy_files
//Назначение: Копирование файлов в указанную папку
function copy_files($path, $path_1, $files, $new_path) {
	global $d_path;
	$n_files = explode("|", $files);
	for ($i=0; @$n_files[$i];$i++) {
		@$file = $path."/".$n_files[$i];
		@$copy_file = $path_1.$new_path."/".$n_files[$i];
		@copy ($file, $copy_file) or die(header ("Location: files.php?d_path=".$d_path."&write=".base64_encode(Dictionary::GetAdminWord(762))));
	}
	header ("Location: files.php?d_path=".$d_path."&write=".base64_encode("". Dictionary::GetAdminWord(763) .": '".$new_path."'"));
	exit;
}

//Функция: display_directory
//Назначение: вывод списка всех директорий вложенных в корневую папку
function display_directory($dir1, $folder_location, $init_depth, $d_path) {
	@$dir .= $dir1;
	$dh = opendir($dir);
	print "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	while ($file = readdir($dh)) {
		// Элементы каталогов "." и ".." не выводятся.
		if ( ($file != ".") && ($file != "..") ) {
			$depth = explode("/", $dir);
			$current_depth = sizeof($depth);
			$tab_depth = $current_depth - $init_depth;
			$file = $dir."/".$file;
		if ( is_dir($file) ) { // Вычислить отступ
			print "<tr valign=\"top\">";
			$x = 0;
			while ( $x < ($tab_depth * 1) ) {
				print "<td><img src=images/vl.gif width=17 height=22 border=0></td>";
				$x++;
			}
			print "<td valign=top><img src=images/vl.gif width=17 height=22 border=0></td><td valign=top><A onmouseover=\"popup('".$d_path."/".basename($file)."')\" onmouseout=kill() href=\"JavaScript: EDITRPOP('".$d_path."/".basename($file)."')\" onMouseOver=\"self.status=\''; return true;\">\n<img src=".$folder_location." border=0></a></td><td nowrap><a onmouseover=\"popup('".$d_path."/".basename($file)."')\" onmouseout=kill() href=\"JavaScript: EDITRPOP('".$d_path."/".basename($file)."')\" onMouseOver=\"self.status=\''; return true;\"><font class=font_2> ".basename($file)."</font></a></td></tr>\n";
			// Рекурсивный вызов функции display_directory()
			display_directory($file, $folder_location, $init_depth, $d_path."/".basename($file));
			}
		}
	}
	closedir($dh);
}


//Определение разрешений
function display_perms( $mode ) {
	if(($mode & 0xC000) === 0xC000) // Сокет домена
	$type = "s";
	elseif(($mode & 0x4000) === 0x4000) // Директория
	$type = "d";
	elseif(($mode & 0xA000) === 0xA000) // Символическая ссылка
	$type = "l";
	elseif(($mode & 0x8000) === 0x8000) // Регулярный файл
	$type = "-";
	elseif(($mode & 0x6000) === 0x6000) // Специальный файл БЛОК
	$type = "b";
	elseif(($mode & 0x2000) === 0x2000) // Специальный файл Символ
	$type = "c";
	elseif(($mode & 0x1000) === 0x1000) // PIPE
	$type = "p";
	else // Неизвестный
	$type = "?";

	$owner['read'] = ($mode & 00400) ? "r" : "-";
	$owner['write'] = ($mode & 00200) ? "w" : "-";
	$owner['execute'] = ($mode & 00100) ? "x" : "-";
	$group['read'] = ($mode & 00040) ? "r" : "-";
	$group['write'] = ($mode & 00020) ? "w" : "-";
	$group['execute'] = ($mode & 00010) ? "x" : "-";
	$world['read'] = ($mode & 00004) ? "r" : "-";
	$world['write'] = ($mode & 00002) ? "w" : "-";
	$world['execute'] = ($mode & 00001) ? "x" : "-";

	if( $mode & 0x800 )
	$owner['execute'] = ($owner[execute]=="x") ? "s" : "S";
	if( $mode & 0x400 )
	$group['execute'] = ($group[execute]=="x") ? "s" : "S";
	if( $mode & 0x200 )
	$world['execute'] = ($world[execute]=="x") ? "t" : "T";

	$return = $type.$owner['read'].$owner['write'].$owner['execute'].$group['read'].$group['write'].$group['execute'].$world['read'].$world['write'].$world['execute'];

	return $return;
}
//Функция, заменяющая элементы ".." и "."
function TruePath ($path) {
   $patharray = explode ("/", $path);
   $path="";$count=count($patharray);
   for ($i=0;$i<$count; $i++){
      if ( $patharray[$i] == "." || $patharray[$i]== ".." || ( (($i+1) < $count) && ($patharray[$i+1]== "..")) ) {
      }
      else {
         $path .= $patharray[$i]."/";
      }
   }
   $path = substr($path,0,-1);
   return ($path);
}

//Функция, выводящая форму для изменения разрешений файла или папки
function print_chmod($file) {
	global $d_path;
	if (is_dir($file)) { $type = "Папка"; }
	if (is_file($file)) { $type = "Файл"; }
	$perms = display_perms(fileperms($file));
	$length = strlen($perms);
	$owner_r = "";
	$owner_w = "";
	$owner_x = "";
	$group_r = "";
	$group_w = "";
	$group_x = "";
	$world_r = "";
	$world_w = "";
	$world_x = "";
	if ($perms[1] == "r") { $owner_r = " checked"; }
	if ($perms[2] == "w") { $owner_w = " checked"; }
	if ($perms[3] == "x") { $owner_x = " checked"; }
	if ($perms[4] == "r") { $group_r = " checked"; }
	if ($perms[5] == "w") { $group_w = " checked"; }
	if ($perms[6] == "x") { $group_x = " checked"; }
	if ($perms[7] == "r") { $world_r = " checked"; }
	if ($perms[8] == "w") { $world_w = " checked"; }
	if ($perms[9] == "x") { $world_x = " checked"; }
	print_header(Dictionary::GetAdminWord(764), "yes");
	print "<form action=action.php method=post>\n<input type=hidden name=file value='".$file."'>
  <input type=hidden name=d_path value='".$d_path."'>
  <input type=hidden name=action value=chmod>
  <input type=hidden name='owner[3]' value=no_error>
  <input type=hidden name='group[3]' value=no_error>
  <input type=hidden name='world[3]' value=no_error>
 <table align=center width=560 border=0 cellspacing=1 cellpadding=3 bgcolor=#333333>
  <tr height=30 align=center>
   <td bgcolor=#3399CC background=images/bckg.gif><font class=font_2><b>". Dictionary::GetAdminWord(764) ."</b></font></td>
  </tr><tr align=center>
   <td bgcolor=#EEEEEE><font class=font_2><b>".$type.": ".basename($file)." </b></font></td></tr>
  <tr><td bgcolor=#EEEEEE>
   <table align=center width=300 border=0 cellspacing=0 cellpadding=5 bgcolor=#EEEEEE>
    <tr><td><font class=font_2><b>owner</b><br><br>
     <input type=checkbox NAME=owner[0] value=4".$owner_r.">Read<br>
     <input type=checkbox NAME=owner[1] value=2".$owner_w.">Write<br>
     <input type=checkbox NAME=owner[2] value=1".$owner_x.">Execute</font></td>
    <td><font class=font_2><b>group</b><br><br>
     <input type=checkbox NAME=group[0] value=4".$group_r.">Read<br>
     <input type=checkbox NAME=group[1] value=2".$group_w.">Write<br>
     <input type=checkbox NAME=group[2] value=1".$group_x.">Execute</font></td>
    <td><font class=font_2><b>other</b><br><br>
     <input type=checkbox NAME=world[0] value=4".$world_r.">Read<br>
     <input type=checkbox NAME=world[1] value=2".$world_w.">Write<br>
     <input type=checkbox NAME=world[2] value=1".$world_x.">Execute</font></td>
   </tr></table>
  </td></tr><tr align=center><td bgcolor=#CCCCCC>
   <input type=image name=exit src=images/exit.gif alt='". Dictionary::GetAdminWord(106) ."' ONCLICK=\"MM_goToURL('parent','files.php?d_path=".$d_path."');return document.MM_returnValue\">&nbsp;
  <input type=image name=chmod src=images/save_chmod.gif alt='". Dictionary::GetAdminWord(428) ."'>
 </td></tr></table></FORM>";
}

//Функция, изменяющия разрешения для файла или папки
function chmod_file($file, $owner, $group, $world) {
	global $d_path;
	if (!isset($owner[0])) { $owner[0] = 0; }
	if (!isset($owner[1])) { $owner[1] = 0; }
	if (!isset($owner[2])) { $owner[2] = 0; }
	if (!isset($group[0])) { $group[0] = 0; }
	if (!isset($group[1])) { $group[1] = 0; }
	if (!isset($group[2])) { $group[2] = 0; }
	if (!isset($world[0])) { $world[0] = 0; }
	if (!isset($world[1])) { $world[1] = 0; }
	if (!isset($world[2])) { $world[2] = 0; }
	$sum_owner = $owner[0] + $owner[1] + $owner[2];
	$sum_group = $group[0] + $group[1] + $group[2];
	$sum_world = $world[0] + $world[1] + $world[2];
	$sum_chmod = "0".$sum_owner.$sum_group.$sum_world;
	if (@chmod($file, $sum_chmod)) {
		header ("Location: files.php?d_path=".$d_path."&write=".base64_encode("". Dictionary::GetAdminWord(766) ." '".basename($file)."' ". Dictionary::GetAdminWord(765) .""));
		exit;
	}
	else {
		header ("Location: files.php?d_path=".$d_path."&write=".base64_encode("". Dictionary::GetAdminWord(767) ." '".basename($file)."'"));
		exit;
	}
}

function file_size($file_size) {
	if ($file_size >= 1073741824) {
	$file_size = round($file_size / 1073741824 * 100) / 100 . " G";
	}
	elseif ($file_size >= 1048576) {
	$file_size = round($file_size / 1048576 * 100) / 100 . " M";
	}
	elseif ($file_size >= 1024) {
	$file_size = round($file_size / 1024 * 100) / 100 . " K";
	}
	else {
	$file_size = $file_size . "";
	}
	return $file_size;
}

//функция, определяющая размер папки в байтах
function dirsize($dir) {
	$dh = opendir($dir);
	$size = 0;
	while (($file = readdir($dh)) !== false)
		if ($file != "." and $file != "..") {
			$path = $dir."/".$file;
			if (is_dir($path)) $size += dirsize($path);
			elseif (is_file($path)) $size += filesize($path);
		}
	closedir($dh);
	return $size;
}
?>