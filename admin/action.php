<?
// Modified by Shkodenko V. Taras 27/07/2006
$no_html = 1;
$include = @include("admin_top.php");
if($include && $adm_wellcome == "Y")
{

include("config.php");
include("functions.php");
@$path = $path_1.$d_path;

if (!@$file) {
		@$file = $path."/".$filename[0];
}

$basename = basename($file); //Выделяем из полного имени файла, собственное имя файла
$isdir = is_dir($file); //Проверяем, является ли файл директорией


if ($delete == "TRUE") {
	if (!@$filename)
	{ //Если переменная не существует, вывести сообщение об ошибке
		header ("Location: files.php?d_path=".$d_path."&obj=" . $obj . "&show=".$show."&write=".base64_encode(Dictionary::GetAdminWord(214)));
		exit;
	}
	else
	{ //Если переменная существует, вывести окно для подтверждения удаления
		@delete_print($d_path, $filename);
	}
}
if ($delete_cf == "TRUE") {
	for ($i=0; @$filename[$i]; $i++) {
			@delete_all($path, $filename); //удаление директорий и файлов
	}
	header ("Location: files.php?d_path=".$d_path."&obj=" . $obj . "&show=".$show."&write=".base64_encode(Dictionary::GetAdminWord(215)));
	exit;
}
if (@$action == "rename" ) {
	@rename_file($file); //вывод окна с формой для переименования
}
if (@$rename_x == TRUE) {
	if ($isdir == TRUE) {
		$type2 = "Папка";
		$type3 = "а";
	}
	if ($isdir == FALSE)  {
		$type2 = "Файл";
		$type3 = "";
	}
	rename_cf($d_path, $file, $basename, $new_name); //переименование файлов или папок
}
if (@$action == "edit") {
	edit_file($file, $basename, @$filename); //вывод формы для редактирования файлов
}
if (@$save_x == TRUE) {
	save_file($file, $basename, $text, $d_path); //сохранение редактируемого файла
}
if (@$action == "mkdir") {
	MakeDir($path, $new_dir); //создание директории
}
if (@$action == "mkfile") {
	MakeFile($path, $d_path, $new_file); //создание файла
}
if (@$copy_tree_x == TRUE) {
	if (@!$filename) { //Если переменная не существует, вывести сообщение об ошибке
	header ("Location: files.php?d_path=".$d_path."&obj=" . $obj . "&show=".$show."&write=".base64_encode(Dictionary::GetAdminWord(216)));
	exit;
	}
	else {
		for ($i=0; $i < count($filename); $i++) {
			if (is_dir($path."/".$filename[$i])) {
				header ("Location: files.php?d_path=".$d_path."&obj=" . $obj . "&show=".$show."&write=".base64_encode(Dictionary::GetAdminWord(217)));
				exit;
			}
		}
	$files = implode("|", $filename); //Собрать массив в сторку
	print_header(Dictionary::GetAdminWord(218), "yes");
	echo '<div class="dek" id="dek"></div>'.
         '<script type="text/javascript" src="styles/popup.js"></script>'."\n".
         '<script type="text/javascript" src="styles/rpop.js"></script>'."\n";
	include("menu.php"); //Вставить файл menu.php
	}
}
if (@$action == "copy") {
	@copy_files($path, $path_1, $files, $new_path); //Копирование файлов
}
if (@$action == "print_chmod") {
	print_chmod($file);
}
if (@$action == "chmod") {
	chmod_file($file, $owner, $group, $world);
}

}