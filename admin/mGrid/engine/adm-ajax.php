<?php

global $bases;
$bases = $_POST['bases'];
if (!isset($bases)) {
	echo 'не выбрана ни одна база данных для редактирования!';
	exit;
}

include("../../../config.php");

//Подключение конфигурационного файла
include BASEPATH."/admin/mGrid/mGridTable".$bases.".php";

switch($action){
	case "check_cnc":
		header("Content-type: text/html; charset=utf-8");

		if (array_key_exists($_POST['field'], $from["nonlang_field"])){
			// лежит в безязыковой
			$menu_query = DB::GetArray(DB::Query("SELECT id FROM ?_" .$from["table"]." WHERE {$_POST['field']}='{$_POST['value']}' LIMIT 0,1"));
		} elseif ($from["islanged"]) {
			$menu_query = DB::GetArray(DB::Query("SELECT id FROM ?_".$from["table"]." 
															WHERE {$_POST['field']}='{$_POST['value']}' 
																AND lang_id='{$_POST['lang']}'
															LIMIT 0,1"));
		} else {
			$menu_query = DB::GetArray(DB::Query("SELECT id FROM ?_".$from["table"]."_lang 
															WHERE {$_POST['field']}='{$_POST['value']}' 
																AND lang_id='{$_POST['lang']}'
															LIMIT 0,1"));
		}

//			$menu_query = DB::GetArray(DB::Query("SELECT id FROM ?_".$from["table"]." WHERE {$_POST['field']}='{$_POST['value']}' AND lang_id='{$_POST['lang']}' LIMIT 0,1"));
				
		if($menu_query['id'] && $menu_query['id'] != $_POST['item']) {
			echo json_encode(array("flag" => "1"));
		} else {
			echo json_encode(array("flag" => "2"));
		}
		break;         
        
    case "creat_gallery" :
        $name = urldecode( $_POST['name'] );
        $name = $name;
        $parent = (isset($_POST['parent'])) ? intval($_POST['parent']) : 0;
        
        $gallery_name = stripslashes(trim(htmlspecialchars($name, ENT_QUOTES)));
        if($gallery_name){
            $result = DB::Query("INSERT INTO ?_gallery_category (title, type, parent) values ('$gallery_name', 2, '$parent')");

            if ($result){
                $last_id = mysql_insert_id();
                echo json_encode(array('status' => 1, 'id' => $last_id));
            } else {
                echo json_encode(array('status' => 0));
            }
        }
        break;
    case "insert_img" : 
      
        $category_id    = intval($_POST['category_id']);
        $this_href      = trim($_POST['href']);
        
        $this_full_href = '';//$this_full_href = trim($full_href);
        if($this_href){

            $select = DB::GetArray(DB::Query("SELECT max(place) as place,category_id FROM ?_gallery WHERE category_id='$category_id' GROUP BY category_id"));
            $place = ($select['place'])? $select['place'] + 1: 1;

            $title_array = array();
            $id = array_shift(DB::GetArray(DB::Query("SELECT MAX(id) FROM ?_gallery"))) + 1;
            foreach ($_POST['lang_id'] as $key => $lang_id){
                $name = urldecode( $_POST['title'][$key] );
                $name = $name;
                        
                //$temp_name     = $_POST['title'][$key];
                //$name          = ($temp_name)? 
                //                    $temp_name : $_POST['title'][$key];
                $title_array[] = $title = stripslashes(trim(htmlspecialchars($name, ENT_QUOTES)));

                $result = DB::Query("INSERT INTO ?_gallery (id, category_id,title,href,place,lang_id) values ('$id','$category_id','$title','$this_href','$place','$lang_id')");
            }
            if($result){
                echo json_encode (array('status' => 1
                                    , 'img_url' => $this_href
                                    , 'place' => $place
                                    , 'id' => $id 
                                 )
                );              
/*
                $show_tag = '<img src="/i.php?height=75&amp;temp_small_img_url='.$this_href.'" alt="'.$title_array[0].'" style="padding: 3px; background: #ffffff; border: 1px solid #D5D6D2;"/>';

                $res = '<div class="place_'.$place.'" id="gallery_'.$id.'" style="width: 900px; background: none repeat scroll 0% 0% rgb(165, 205, 56); position: relative; overflow: hidden;">
                <div style="height: 82px; margin: 1px 1px 0px 0px; padding-left: 10px; float: left; width: 479px; position: relative; overflow: hidden;">
                    <div style="float: left; ">
                        <br><div style="float:left; width:30px">Укр:</div> <input type="text" style="width: 300px; color: rgb(117, 118, 120); padding: 2px 0pt 1px 10px; border: 1px solid rgb(213, 214, 210); font: 11px Tahoma;" value="'.$title_array[0].'" id="gallery_'.$id.'_l3">
                        <br><div style="float:left; width:30px">Рус:</div> <input type="text" style="width: 300px; color: rgb(117, 118, 120); padding: 2px 0pt 1px 10px; border: 1px solid rgb(213, 214, 210); font: 11px Tahoma;" value="'.$title_array[1].'" id="gallery_'.$id.'_l2">
                        <br><div style="float:left; width:30px">Eng:</div> <input type="text" style="width: 300px; color: rgb(117, 118, 120); padding: 2px 0pt 1px 10px; border: 1px solid rgb(213, 214, 210); font: 11px Tahoma;" value="'.$title_array[2].'" id="gallery_'.$id.'_l1"><br></div>
                        <div style="float: right; margin: 30px 20px 0pt 0pt;">
                        <input type="button" value="изменить" style="color: rgb(117, 118, 120); border: 1px solid rgb(213, 214, 210); font: 12px Tahoma; width: 90px; margin: 0px 0px 0px 2px;" class="gallery_'.$id.'"></div></div>
                        <div style="height: 82px; margin: 1px 1px 0px 0px; float: left; width: 169px; position: relative; overflow: hidden; text-align: center;">                        
                            <img style="padding: 3px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 1px solid rgb(213, 214, 210);" alt="" src="/i.php?height=75&amp;temp_small_img_url='.$this_href.'">
                        </div>
                        <div style="height: 82px; margin: 1px 0px 0px; float: left; width: 140px; position: relative; overflow: hidden; text-align: center;"><br><br>
                       <input type="button" value="удалить" style="color: rgb(117, 118, 120); border: 1px solid rgb(213, 214, 210); font: 12px Tahoma; width: 90px; float: left; margin: 0px 0px 0px 2px;" class="gallery_'.$id.'">
                       <input type="image" value="up" style="color: rgb(117, 118, 120); border: 1px solid rgb(213, 214, 210); font: 12px Tahoma; width: 16px; float: left; margin: 0px 0px 0px 2px; display: none;" class="gallery_'.$id.'" src="/admin/images/arrow_up2.jpg">
                       <input type="image" value="down" style="color: rgb(117, 118, 120); border: 1px solid rgb(213, 214, 210); font: 12px Tahoma; width: 16px; float: left; margin: 0px 0px 0px 2px; display: block;" class="gallery_'.$id.'" src="/admin/images/arrow_down2.jpg">
                    </div>
                </div>';
                echo $res;
*/
                //echo json_encode (array('status' => 1, 'res' => $res));
             } else {
                 echo json_encode (array('status' => -1));
             }
        }
        break;                        
}
