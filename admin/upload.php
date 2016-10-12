<?

// Modified by Shkodenko V. Taras 13/09/2006
//
$no_html = 1;
$include = @include("admin_top.php");
if ($include && $adm_wellcome == "Y") {


    //
// convert $HTTP_POST_VARS to $_POST
// convert $HTTP_POST_FILES to $_FILES
    if (version_compare(phpversion(), "4.1.0") == -1) {
        foreach ($HTTP_POST_VARS as $k => $v) {
            if (isset($HTTP_POST_VARS[$k]))
                $_POST[$k] = $HTTP_POST_VARS[$v];
        }
        for ($i = 1; $i <= $files_to_upload; $i++) {
            foreach ($HTTP_POST_FILES['userfile' . $i] as $k => $v) {
                if (isset($HTTP_POST_FILES['userfile' . $i][$k]))
                    $_FILES['userfile' . $i][$k] = $HTTP_POST_FILES['userfile' . $i][$v];
            }
        }
    }
//

    include("config.php");

    $path  = $path_1 . $d_path;
    $x     = "";
    $count = 0;
    for ($i = 1; $i <= $files_to_upload; $i++) {
        $t_tmp_filename = $_FILES['userfile' . $i]['tmp_name'];
        $t_new_filename = $_FILES['userfile' . $i]['name'];
        $t_new_filesize = $_FILES['userfile' . $i]['size'];
        $t_upfile_error = $_FILES['userfile' . $i]['error'];

        $upload = $path . "/" . $t_new_filename;
        if ($t_new_filename != '' and $t_new_filename != 'none') {
            // пытаемся загрузить файл
            if (@move_uploaded_file($t_tmp_filename, $upload)) {
                $x .= "<br>Файл: " . $t_new_filename . " загружен.";
                chmod($upload, 0644);
                //брендирование
                if (defined('_INSTALL_BRANDING') && (_INSTALL_BRANDING == 1)) {
                    if ($_POST['ADD_LOGO'])
                        if (preg_match("/\.jpg|\.jpeg|\.gif|\.png/i", $t_new_filename)) {
                            include_once "branding/Image_Toolbox.class.php";
                            $myimage = new Image_Toolbox($upload);
                            $myimage->addImage("branding/logo24.png");
                            $myimage->blend("right -10", "bottom -5", IMAGE_TOOLBOX_BLEND_COPY);
                            $myimage->save($upload);
                            $x .= "<br>Логотип прикреплен.";
                        } else {
                            $x .= "<br>Не графический файл.";
                        }
                }
                //конец брендирования			
            } else {
                // 1 - размер загруженного файла превышает размер установленный параметром upload_max_filesize в php.ini
                if ($t_upfile_error == 1) {
                    $x .= '<br>Ошибка ' . $t_upfile_error . ' при загрузке файла: ' . $t_tmp_filename . ' в папку: ' . $upload . ' ! ' .
                            '<br>Размер загруженного файла ' . $t_new_filesize . ' превышает размер установленный параметром ' .
                            'upload_max_filesize в php.ini' . "\n";
                }
                // 2 - размер загруженного файла превышает размер установленный параметром MAX_FILE_SIZE в HTML форме.
                if ($t_upfile_error == 2) {
                    $x .= '<br>Ошибка ' . $t_upfile_error . ' при загрузке файла: ' . $t_tmp_filename . ' в папку: ' . $upload . ' !' .
                            '<br>Размер загруженного файла ' . $t_new_filesize . ' превышает размер установленный параметром ' .
                            'MAX_FILE_SIZE (' . $_POST['MAX_FILE_SIZE'] . ' байт) в HTML форме.' . "\n";
                }
                // 3 - загружена только часть файла
                if ($t_upfile_error == 3) {
                    $x .= '<br>Ошибка ' . $t_upfile_error . ' при загрузке файла: ' . $t_tmp_filename . ' в папку: ' . $upload . ' !' .
                            '<br>Загружена только часть файла !' . "\n";
                }
                // 4 - файл не был загружен (Пользователь в форме указал неверный путь к файлу).
                if ($t_upfile_error == 4) {
                    $x .= '<br>Ошибка ' . $t_upfile_error . ' при загрузке файла: ' . $t_tmp_filename . ' в папку: ' . $upload . ' !' .
                            '<br>Пользователем был указан неверный путь к файлу !' . "\n";
                } else {
                    $x .= '<br>Ошибка ' . $t_upfile_error . ' при загрузке файла: ' . $t_tmp_filename . ' в папку: ' . $upload . ' !' . "\n";
                }
            }
        } else {
            $count++;
        }
    }

    if ($count == $files_to_upload) {
        header("Location: files.php?d_path=" . $d_path . "&obj=" . $obj . "&show=" . $show . "&write=" . base64_encode(Dictionary::GetAdminWord(1093)));
        exit;
    } else {
        $x = substr_replace($x, "", 0, 4);
        header("Location: files.php?d_path=" . $d_path . "&obj=" . $obj . "&show=" . $show . "&write=" . base64_encode($x));
        exit;
    }
}
?>