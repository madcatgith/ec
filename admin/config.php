<?

// Modified by Shkodenko V. Taras 24/10/2006
if ($no_enter == _UP_LICENSE)
    $files_folder = "";

//Начальная директория
// $host_1 = _BASE_URL.'/'.$files_folder; // полные пути включая доменное имя
$host_1 = $files_folder; // краткие пути к файлам лучше для переносимости
$path_1 = $_SERVER['DOCUMENT_ROOT'] . $files_folder;

$fcid_ululu = @substr($d_path, 1);
$select     = DB::Query("select title from `?_file_cats` where id=$fcid_ululu ");

if ($fcid_ululu != 'gallery' && ($d_path == "" or @array_shift(DB::GetArray($select)) == "") and ($add_yes55 != "1")) {

    $select = DB::Query("select min(id) from `?_file_cats` ");
    $d_path = "/" . @array_shift(DB::GetArray($select));

    if ($d_path == "/") {
        echo "<center><b><br><br><font color=red>" . Dictionary::GetAdminWord(469) . "<br> " . Dictionary::GetAdminWord(470) . " <a href=file_cats_add.php?show=$show>file_cats_add.php</a>";
        exit;
    }
}
$fcid_ululu = @substr($d_path, 1);


//Количество файлов, доступных для загрузки на сервер
//(четное число, например: 4, 6, 8 ...)
$files_to_upload = 4;