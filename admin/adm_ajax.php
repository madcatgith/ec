<?

include $_SERVER["DOCUMENT_ROOT"] . "/config.php";

header("Content-type: text/html; charset=utf-8");

foreach (array($_GET, $_POST) as $arr) {
    extract($arr, EXTR_OVERWRITE);
}

switch ($_REQUEST['action']) {
    case "check_cnc":

        switch ($_POST['type']) {
            case 'status':
                $menu_query = DB::GetArray(DB::Query("SELECT id FROM ?_event_status WHERE cnc='{$_POST['name']}' AND lang_id='{$_POST['lang']}' LIMIT 0,1"));
                if ($menu_query['id'] && $menu_query['id'] != $_POST['id']) {
                    echo json_encode(array("flag" => "1"));
                } else {
                    echo json_encode(array("flag" => "2"));
                }
                break;
            case 'EventCity':
            case 'city':
                $menu_query = DB::GetArray(DB::Query("SELECT id FROM ?_event_city WHERE cnc='{$_POST['name']}' AND lang_id='{$_POST['lang']}' LIMIT 0,1"));

                if ($menu_query['id'] && $menu_query['id'] != $_POST['menu']) {
                    echo json_encode(array("flag" => "1"));
                } else {
                    echo json_encode(array("flag" => "2"));
                }
                break;
            case 'tags':
                $menu_query = DB::GetArray(DB::Query("SELECT id FROM ?_event_tags WHERE cnc='{$_POST['name']}' AND lang_id='{$_POST['lang']}' LIMIT 0,1"));
                if ($menu_query['id'] && $menu_query['id'] != $_POST['id']) {
                    echo json_encode(array("flag" => "1"));
                } else {
                    echo json_encode(array("flag" => "2"));
                }
                break;
            case 'category':
                $menu_query = DB::GetArray(DB::Query("SELECT id FROM ?_event_category WHERE category_id='{$_POST['category_id']}' AND cnc='{$_POST['name']}' AND lang_id='{$_POST['lang']}' LIMIT 0,1"));
                if ($menu_query['id'] && $menu_query['id'] != $_POST['id']) {
                    echo json_encode(array("flag" => "1"));
                } else {
                    echo json_encode(array("flag" => "2"));
                }
                break;
            case 'event':
                $menu_query = DB::GetArray(DB::Query("SELECT id FROM ?_event WHERE cnc='{$_POST['name']}' AND lang_id='{$_POST['lang']}' LIMIT 0,1"));
                if ($menu_query['id'] && $menu_query['id'] != $_POST['id']) {
                    echo json_encode(array("flag" => "1"));
                } else {
                    echo json_encode(array("flag" => "2"));
                }
                break;
            case 'menu':
                $menu_query = DB::GetArray(DB::Query("SELECT id FROM ?_menu WHERE menu_id='{$_POST['menu_id']}' AND cnc='{$_POST['name']}' AND lang_id='{$_POST['lang']}' LIMIT 0,1"));
                if ($menu_query['id'] && $menu_query['id'] != $_POST['menu']) {
                    echo json_encode(array("flag" => "1"));
                } else {
                    echo json_encode(array("flag" => "2"));
                }
                break;

            case 'content':
                $menu_query = DB::GetArray(DB::Query("SELECT id FROM ?_content WHERE menu_id='$_POST[menu_id]' AND cnc='$_POST[name]' AND lang_id='$_POST[lang]' LIMIT 0,1"));

                if ($menu_query[id] && $menu_query[id] != $_POST[menu]) {
                    echo json_encode(array("flag" => "1"));
                } else {
                    echo json_encode(array("flag" => "2"));
                }

                break;
        }
        break;
    case "check_cnc":
        switch ($_POST['type']) {
            case 'menu':
                $menu_query = DB::GetArray(DB::Query("SELECT id FROM ?_menu WHERE menu_id='{$_POST['menu_id']}' AND cnc='{$_POST['name']}' AND lang_id='{$_POST['lang']}' LIMIT 0,1"));
                if ($menu_query['id'] && $menu_query['id'] != $_POST['menu']) {
                    echo json_encode(array("flag" => "1"));
                } else {
                    echo json_encode(array("flag" => "2"));
                }
                break;

            case 'content':
                $menu_query = DB::GetArray(DB::Query("SELECT id FROM ?_content WHERE menu_id='$_POST[menu_id]' AND cnc='$_POST[name]' AND lang_id='$_POST[lang]' LIMIT 0,1"));
                if ($menu_query[id] && $menu_query[id] != $_POST[menu]) {
                    echo json_encode(array("flag" => "1"));
                } else {
                    echo json_encode(array("flag" => "2"));
                }
                break;
        }
        break;

    case "creat_category" :

        $parent = (isset($parent)) ? intval($parent) : 0;

        $gallery_name = stripslashes(trim(htmlspecialchars($name, ENT_QUOTES)));
        if ($gallery_name) {
            $result = DB::Query("INSERT INTO ?_gallery_category (title, type, parent) values ('$gallery_name', 1, '$parent')");
            if ($result) {
                $last_id = mysql_insert_id();
                echo '<tr id="gallery_' . $last_id . '" class="class_1"><td style="padding: 2px 0px 2px 10px;"><a href="/admin/lib/wmpGallery/gallery.php?item_id=' . $last_id . '" style="color: #757678; font: 11px Tahoma;">' . $gallery_name . '</a></td><td><input type="button" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma;" value="удалить" class="gallery_' . $last_id . '"></td></tr>';
            }
        }
        break;
    case "creat_gallery" :
        $temp_name    = $name;
        $parent       = (isset($_GET['parent'])) ? intval($_GET['parent']) : 0;
        $gallery_name = stripslashes(trim(htmlspecialchars($name, ENT_QUOTES)));

        if ($gallery_name) {
            $result = DB::Query("INSERT INTO ?_gallery_category (title, type, parent) values ('$gallery_name', 2, '$parent')");

            if ($result) {
                $last_id = mysql_insert_id();
                if (isset($_GET['prod'])) {
                    echo "<script language=\"JavaScript\">$('#photogalary1').load('/admin/gallery_product.php'); </script>";
                }
                else
                    echo '<tr id="gallery_' . $last_id . '" class="class_1"><td style="padding: 2px 0px 2px 10px;"><a href="/admin/lib/wmpGallery/controller.php?action=show&id=' . $last_id . '" style="color: #757678; font: 11px Tahoma;">' . $gallery_name . '</a></td><td><input type="button" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma;" value="удалить" class="gallery_' . $last_id . '"></td></tr>';
            }
        }
        break;
    case "delete_category" :
        if ($gallery_id) {
            preg_match('/[0-9]+/', $gallery_id, $result);
            $id = $result[0];
            DB::Query("DELETE FROM ?_gallery_category where id='$id'");
            DB::Query("DELETE FROM ?_gallery_category where parent='$id'");

            DB::Query("DELETE FROM ?_gallery where category_id='$id'");
            echo "success";
        }
        break;
    case "insert_img" :
        $category_id    = stripslashes(trim($category_id));
        $this_href      = trim($_GET['href']);
        $this_full_href = '';

        if ($this_href) {

            $select = DB::GetArray(DB::Query("SELECT max(place) as place,category_id FROM ?_gallery WHERE category_id='$category_id' GROUP BY category_id"));
            $place  = ($select['place']) ? $select['place'] + 1 : 1;

            $title_array = array();
            $id          = array_shift(DB::GetArray(DB::Query("SELECT MAX(id) FROM ?_gallery"))) + 1;

            foreach ($_GET['lang_id'] as $key => $lang_id) {
                $temp_name     = $_GET['title'][$key];
                $name          = ($temp_name) ? $temp_name : $_GET['title'][$key];
                $title_array[] = $title         = stripslashes(trim(htmlspecialchars($name, ENT_QUOTES)));

                $result = DB::Query("INSERT INTO ?_gallery (id, category_id,title,href,place,lang_id) values ('$id','$category_id','$title','$this_href','$place','$lang_id')");
            }

            if ($result) {

                $show_tag = '<img src="/image.php?' . Image::mEncrypt('hsrc=' . $this_href) . '" alt="' . $title_array[0] . '" style="padding: 3px; background: #ffffff; border: 1px solid #D5D6D2;"/>';

                echo '<div class="place_' . $place . '" id="gallery_' . $id . '" style="width: 900px; background: none repeat scroll 0% 0% rgb(165, 205, 56); position: relative; overflow: hidden;">
                <div style="height: 82px; margin: 1px 1px 0px 0px; padding-left: 10px; float: left; width: 479px; position: relative; overflow: hidden;">
                    <div style="float: left; ">
                        <br><div style="float:left; width:30px">Укр:</div> <input type="text" style="width: 300px; color: rgb(117, 118, 120); padding: 2px 0pt 1px 10px; border: 1px solid rgb(213, 214, 210); font: 11px Tahoma;" value="' . $title_array[0] . '" id="gallery_' . $id . '_l3">
                        <br><div style="float:left; width:30px">Рус:</div> <input type="text" style="width: 300px; color: rgb(117, 118, 120); padding: 2px 0pt 1px 10px; border: 1px solid rgb(213, 214, 210); font: 11px Tahoma;" value="' . $title_array[1] . '" id="gallery_' . $id . '_l2">
                        <br><div style="float:left; width:30px">Eng:</div> <input type="text" style="width: 300px; color: rgb(117, 118, 120); padding: 2px 0pt 1px 10px; border: 1px solid rgb(213, 214, 210); font: 11px Tahoma;" value="' . $title_array[2] . '" id="gallery_' . $id . '_l1"><br></div>
                        <div style="float: right; margin: 30px 20px 0pt 0pt;">
                        <input type="button" value="изменить" style="color: rgb(117, 118, 120); border: 1px solid rgb(213, 214, 210); font: 12px Tahoma; width: 90px; margin: 0px 0px 0px 2px;" class="gallery_' . $id . '"></div></div>
                        <div style="height: 82px; margin: 1px 1px 0px 0px; float: left; width: 169px; position: relative; overflow: hidden; text-align: center;">                        
                            <img style="padding: 3px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 1px solid rgb(213, 214, 210);" alt="" src="/image.php?' . Image::mEncrypt('hsrc=' . $this_href) . '">
                        </div>
                        <div style="height: 82px; margin: 1px 0px 0px; float: left; width: 140px; position: relative; overflow: hidden; text-align: center;"><br><br>
                       <input type="button" value="удалить" style="color: rgb(117, 118, 120); border: 1px solid rgb(213, 214, 210); font: 12px Tahoma; width: 90px; float: left; margin: 0px 0px 0px 2px;" class="gallery_' . $id . '">
                       <input type="image" value="up" style="color: rgb(117, 118, 120); border: 1px solid rgb(213, 214, 210); font: 12px Tahoma; width: 16px; float: left; margin: 0px 0px 0px 2px; display: none;" class="gallery_' . $id . '" src="/admin/images/arrow_up2.jpg">
                       <input type="image" value="down" style="color: rgb(117, 118, 120); border: 1px solid rgb(213, 214, 210); font: 12px Tahoma; width: 16px; float: left; margin: 0px 0px 0px 2px; display: block;" class="gallery_' . $id . '" src="/admin/images/arrow_down2.jpg">
                    </div>
                </div>';
            }
        }
        break;
    case "edit_title":
        preg_match('/gallery_([0-9]*)/usi', $id, $result);
        $id             = $result[1];
        $temp_rus_title = $rus_title;
        $rus_title      = ($temp_rus_title) ? $temp_rus_title : $rus_title;
        $rus_title      = stripslashes(trim(htmlspecialchars($rus_title, ENT_QUOTES)));

        DB::Query("UPDATE ?_gallery SET title='$rus_title' where lang_id='1' AND id='$id' AND category_id=$gallery_id");
        echo "success";
        break;
    case "delete_photo" :
        if ($photo_id) {
            preg_match('/gallery_([0-9]*)/usi', $photo_id, $result);
            $id    = $result[1];
            preg_match('/place_([0-9]*)/usi', $place_id, $result);
            $place = $result[1];
            DB::Query("DELETE FROM ?_gallery WHERE id=$id AND category_id=$gallery_id");
            DB::Query("UPDATE ?_gallery SET place=place-1 where place>$place AND category_id=$gallery_id");
            echo "success";
        }
        break;
    case "replace" :
        $gallery_id = intval($_GET['gallery_id']);
        if ($gallery_id && $first_photo && $last_photo) {
            preg_match('/[0-9]+/', $first_photo, $result);
            $first_id = $result[0];
            preg_match('/[0-9]+/', $last_photo, $result);
            $last_id  = $result[0];
            $first    = DB::Query("UPDATE ?_gallery SET place=$first_place where id=$last_id AND category_id=$gallery_id");
            $last     = DB::Query("UPDATE ?_gallery SET place=$last_place where id=$first_id AND category_id=$gallery_id");
            if ($first && $last)
                echo json_encode(array('success' => true));
            return;
        }
        json_encode(array('success' => '1'));
        return;
        break;
    case "edit_title_1":
        // каструбатый код, который не учитывавет локализаций и чисто хардкод
        preg_match('/gallery_([0-9]*)/usi', $_GET['id'], $result);
        $id = $result[1];

        $rus = $_GET['rus_title'];
        $rus = ConvertHtmlToAlt($rus);
        $eng = $_GET['eng_title'];
        $eng = ConvertHtmlToAlt($eng);
        $ukr = $_GET['ukr_title'];
        $ukr = ConvertHtmlToAlt($ukr);

        DB::Query("UPDATE ?_gallery SET title='$rus' where lang_id='1' AND id='$id'");
        DB::Query("UPDATE ?_gallery SET title='$eng' where lang_id='3' AND id='$id'");
        DB::Query("UPDATE ?_gallery SET title='$ukr' where lang_id='2' AND id='$id'");
        echo "success";
        break;
}
