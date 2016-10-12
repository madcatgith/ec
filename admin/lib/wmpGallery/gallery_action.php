<?

$include = @include_once("../../admin_top.php");
if(!$include or $adm_wellcome != "Y") exit;

$lang_array = Lang::getLanguages();
$parent_cat = 1;
if (isset($_GET['item_id']))
	$parent_cat = $_GET['item_id'];
//echo $parent_cat;
// Заголовок
$select_gallery = DB::Query("SELECT * FROM ?_gallery_category WHERE id=".$parent_cat." LIMIT 1");
if ($get = DB::GetArray($select_gallery)){
	$category = $get;
	echo admin_func_top('управление галереей '.$get['title']);
	echo admin_func_sys_message($sys_message);
} else {
	die('Не выбрана галерея или галерея уже не существует.');
}
// Кнопки
//поиск

$wmpTree = new wmpTree();
$wmpTree->BranchesSelect = wmpTree::$PreDefSql['galleryBranches'];
$wmpTree->ShowLeaves = false;
$treeBody = $wmpTree->func_items_tree("item_id", "gallery.php", "&nbsp;&nbsp;", Dictionary::GetAdminWord(231), ""
                            , "width:300px; float:left; font-size:9px;", array($category['parent']));

echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#a5cd38">
		 <tr>
		 	<td width="300" class="td_left" style="vertical-align: top; border: 1px solid #a5cd38;">
		 		<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td style="padding: 14px 4px 4px 4px; border-bottom: 2px solid #213866;">
                <form action="/admin/lib/wmpGallery/gallery.php?oper=search" method="post">';
echo Dictionary::GetAdminWord(64) ." ". admin_func_right_input("text", "search", $search, "100", "") ." ". admin_func_right_input("submit", "", Dictionary::GetAdminWord(341), "", "1");
echo '</form></td></tr><tr><td style="padding: 0;">';
echo $treeBody; // admin_func_items_tree("item_id", "gallery.php", "&nbsp;&nbsp;", Dictionary::GetAdminWord(231), ""
				//			, "width:300px; float:left; font-size:9px;", array($category['parent']));
echo '      	</td></tr></table>
			</td>
			<td style="vertical-align: top; background: #fff;">';

echo admin_func_right_table_start(0);
echo "<style>
      #img_gallery div{
      	font: normal 13px Verdana;
      	background: #ffffff;
      }
	 </style>";
echo '<div id="img_gallery" style="width: 802; color: #757678; background: #a5cd38; padding: 1px 1px 1px 1px; border: 1px solid #D5D6D2; position: relative; overflow: hidden;">'.
     '<div style="position: relative; overflow: hidden; font-weight: bold; background: #a5cd38;">'.
	 	'<div style="margin: 0px 1px 0px 0px; float: left; width: 489px; position: relative; overflow: hidden;  text-align: center;">Имя</div>'.
	 	'<div style="margin: 0px 1px 0px 0px; float: left; width: 169px; position: relative; overflow: hidden;  text-align: center;">Изображение</div>'.
		'<div style=" float: left; width: 140px; position: relative; overflow: hidden;  text-align: center;">Действие</div>'.
	 '</div>';

	 $str = '';

/*$select_photo = DB::Query("SELECT g.*, lg.title as eng
								FROM ?_gallery as g
								LEFT JOIN ?_gallery as lg ON lg.id=g.id and lg.category_id='{$category['id']}' and lg.lang_id='1'
								WHERE g.category_id='{$category['id']}' and g.lang_id='1'
								GROUP BY g.id order by g.place");*/
$select_photo = DB::Query("SELECT g.*
								FROM ?_gallery as g
                                WHERE g.category_id = '$parent_cat'
								ORDER BY g.place");
$imgArr = array();
$place = 0;
// пробегаемся по списку изображений и строим верх
while($get = DB::GetArray($select_photo)){
    // уже выбиралась фотка с таким именем
    if (array_key_exists($get['id'], $imgArr)){
        $imgArr[$get['id']]['names'] .= '<br/><div style="float:left; width:30px">'.$lang_array[$get['lang_id']]['title_short'].':</div>'
                                        .'<input type="text" id="gallery_'.$get['id'].'_l'.$get['lang_id'].'" value="'.$get['title'].'"
                                        style="width: 300px; color: #757678; padding: 2px 0 1px 10px; border: 1px solid #D5D6D2; font: 11px Tahoma;" />';
    }else {
        $place++;
        $imgArr[$get['id']] = array();
        $imgArr[$get['id']]['start'] =
                    '<div style="width: 900px; background: #a5cd38; position: relative; overflow: hidden;" id="gallery_'.$get['id'].'" class="place_'.$place.'">'.
                    '<div style="height: 82px; margin: 1px 1px 0px 0px; padding-left:10px; float: left; width: 479px; position: relative; overflow: hidden; ">
                    <div style="float:left; margin-top:10px; " >';

        $imgArr[$get['id']]['names'] = ''.$lang_array[$get['lang_id']]['title_short'].':<input type="text" id="gallery_'.$get['id'].'_l'.$get['lang_id'].'" value="'.$get['title'].'" style="width: 300px; color: #757678; padding: 2px 0 1px 10px; border: 1px solid #D5D6D2; font: 11px Tahoma;" />';

        $imgArr[$get['id']]['end'] =
                    '</div>'.
                    '<div style="float:right; margin:30px 20px 0 0;" >
                        <input type="button" class="gallery_'.$get['id'].'" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma; width: 90px; margin: 0px 0px 0px 2px;" value="изменить">'.
                    '</div></div>'.
                    '<div style="height: 82px; margin: 1px 1px 0px 0px; float: left; width: 169px; position: relative; overflow: hidden;  text-align: center;"><img src="/image.php?' . Image::mEncrypt('height=75&ext=jpeg&src=' . $get['href']) . '" alt="'.$get['title'].'" style="padding: 3px; background: #ffffff; border: 1px solid #D5D6D2;"/></div>'.
                    '<div style="height: 82px; margin: 1px 0px 0px 0px; float: left; width: 140px; position: relative; overflow: hidden;  text-align: center;"><br/><br/>
                       <input type="button" class="gallery_'.$get['id'].'" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma; width: 90px; float: left; margin: 0px 0px 0px 2px;" value="удалить">
                       <input type="image" src="/admin/images/arrow_up2.jpg" class="gallery_'.$get['id'].'" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma; width: 16px; float: left; margin: 0px 0px 0px 2px;" value="up">
                       <input type="image" src="/admin/images/arrow_down2.jpg" class="gallery_'.$get['id'].'" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma; width: 16px; float: left; margin: 0px 0px 0px 2px;" value="down">
                    </div>'.
                    '</div>';

    }
}
$res = '';
foreach ($imgArr as $value){
    $res .= implode ($value);
};
echo $res."</div>";

echo '<div style="padding: 10px; position: relative; overflow: hidden; color: red;" id="error"></div>';

$temp_lang = '';

foreach ($lang_array as $key => $value){
	if ( is_int($key) ){
		$temp_lang .= '<div style="padding: 0px 0px 6px 0px;">
		<input type="text" class="lang_title" id="'.$key.'" style="width: 300px; color: #757678; padding: 2px 0 1px 10px; border: 1px solid #D5D6D2; font: 11px Tahoma;">
		&nbsp;&nbsp;&nbsp;Название фотографии <strong>'.$value['title_short'].'</strong></div>';
	}
}
/*
while($get_lang = DB::GetArray($select_lang)){
	$temp_lang .= '<div style="padding: 0px 0px 6px 0px;">
	<input type="text" class="lang_title" id="'.$get_lang['id'].'" style="width: 300px; color: #757678; padding: 2px 0 1px 10px; border: 1px solid #D5D6D2; font: 11px Tahoma;">
	&nbsp;&nbsp;&nbsp;Название фотографии <strong>'.$get_lang['title_short'].'</strong></div>';
}
*/

/*
$select_lang = DB::Query("SELECT id, title_short FROM ?_lang where active=1");
$temp_lang = '';
while($get_lang = DB::GetArray($select_lang)){
	$temp_lang .= '<div style="padding: 0px 0px 6px 0px;">
	<input type="text" class="lang_title" id="'.$get_lang['id'].'" style="width: 300px; color: #757678; padding: 2px 0 1px 10px; border: 1px solid #D5D6D2; font: 11px Tahoma;">
	&nbsp;&nbsp;&nbsp;Название фотографии <strong>'.$get_lang['title_short'].'</strong></div>';
}
*/

?>
<script type="text/javascript">


$(document).ready(function(){

	$("input[value='up']:visible:first").css('display','none');
	$("input[value='down']:visible:last").css('display','none');

	var bindArrows = function(){
			$("input[value='up']").css('display','block');
			$("input[value='up']:first").css('display','none');
			$("input[value='down']").css('display','block');
			$("input[value='down']:last").css('display','none');
	}
	// Удалить
	var bindDelete = function() {
		$("input[value='удалить']").click(function(){
				var del_var = $(this).attr('class');
				//$.get("/admin/adm_ajax.php", {'action' : 'delete_photo', 'gallery_id' : '<?=$category['id'];?>', 'photo_id' : del_var, 'place_id' : $("div #"+del_var).attr('class')},
				$.get("/admin/adm_ajax.php", {'action' : 'delete_photo', 'gallery_id' : '<?=$category['id'];?>', 'photo_id' : del_var, 'place_id' : $("div #"+del_var).attr('class')},
				  function(data){
				    if(data){
				    	$('#'+del_var).fadeOut('slow').remove();
				    	bindArrows();
				    }else{
				    	$("#error").html('Не удалось удалить галерею ' + del_var);
				    }
				});
		});
	}

		// Плейс +1
	var bindUp = function() {
		$("input[value='up']").click(function(){
			var temp = "#" + $(this).attr('class');
			var place = $(temp).attr('class').match('[0-9]+');
            place = place[0];
			var temp_class = 'temp_class_replace_value_bla_bla_bla';
			replacemant = parseInt(place) - 1;
			$.get("/admin/adm_ajax.php", {'action' : 'replace', 'gallery_id' : '<?=$category['id'];?>', 'first_photo' : $('.place_'+place).attr('id'), 'last_photo' : $('.place_'+replacemant).attr('id'), 'first_place' : place, 'last_place' : replacemant },
				function(data){
					if(data && data.success){
						$(".place_"+place).insertBefore($(".place_"+replacemant));
                        $(".place_"+place).removeClass("place_"+place).addClass(temp_class);
                        $(".place_"+replacemant).addClass("place_"+place).removeClass("place_"+replacemant);
                        $("."+temp_class).addClass("place_"+replacemant).removeClass(temp_class);
						bindArrows();
					}else{
						$("#error").html('Не удалось изменить место');
					}
			},'json');
		});
	}

		// Плейс -1
	var bindDown = function() {
		$("input[value='down']").click(function(){
			var temp = "#" + $(this).attr('class');
			var place = $(temp).attr('class').match('[0-9]+');
            place = place[0];
			var temp_class = 'temp_class_replace_value_bla_bla_bla';
			replacemant = parseInt(place) + 1;
			$.get("/admin/adm_ajax.php", {'action' : 'replace', 'gallery_id' : '<?=$category['id'];?>', 'first_photo' : $('.place_'+place).attr('id'), 'last_photo' : $('.place_'+replacemant).attr('id'), 'first_place' : place, 'last_place' : replacemant },
				function(data){
					if(data && data.success){
						$(".place_"+replacemant).insertBefore($(".place_"+place));
						$(".place_"+place).removeClass("place_"+place).addClass(temp_class);
						$(".place_"+replacemant).addClass("place_"+place).removeClass("place_"+replacemant);
						$("."+temp_class).addClass("place_"+replacemant).removeClass(temp_class);
						bindArrows();
					}else{
						$("#error").html('Не удалось изменить место');
					}
			},'json');
		});
	}

	var bindUpdate = function(){
		$("input[value='изменить']").click(function(){
			var this_class = $(this).attr('class');

            var eng_text   = '#' + this_class + '_l3';
            var rus_text   = '#' + this_class + '_l1';
            var ukr_text   = '#' + this_class + '_l2';

			$.get("/admin/adm_ajax.php", {'action' : 'edit_title_1', 'gallery_id' : '-1', 'eng_title' : $(eng_text).attr('value'), 'rus_title' : $(rus_text).attr('value'), 'ukr_title' : $(ukr_text).attr('value'), 	'id' : this_class},
				function(data){
					if(data){

					}else{
						$("#error").html('Не удалось изменить место');
					}
			});

		});
	}

	bindUp();
	bindDown();
	bindDelete();
	bindUpdate();

	$('#add_gallery #upload').click(function(){
		$("#load").css("visibility","visible");
		lang_id = '';
		title   = '';
		$.each($('.lang_title'), function(){
      		lang_id += '&lang_id[]='+$(this).attr('id');
      		title   += '&title[]='+$(this).attr('value');
      		$(this).val('');
    	});
		string = "action=insert_img" + lang_id + title +"&category_id=<?=$category['id'];?>&href=" + $("#imgurl").val() + '&min_href=' + $("#imgurl2").val();
		$.ajax({
				type: "GET",
				url: "/admin/adm_ajax.php",
 				data: string,
 				cache: false,
				success: function(data){
			    	$("#img_gallery").append(data);
		    		$("#imgurl").val('');
		    		$("#imgurl2").val('');
					bindArrows();
					bindUp();
					bindDown();
					bindDelete();
					bindUpdate();
				},
				error: function(data){
					if(!data)$("#error_upload").html('Не удалось загрузить фотографию');
				}
			});
		$("#load").css("visibility","hidden");
	});

});
</script>
<?
//echo '<div>Максимальна ширина фотографии 430px для "Реализованых проектов", "Наши работы", "Наши Клиенты".<br/>Для остального контента максимальная ширина 1024px<div>';
/*
Показ всех фото из общей папки
*/

echo '<div>Форма для загрузки фотографии в галлерею.<div>';

echo '<div style="padding: 10px; position: relative; overflow: hidden;" id="add_gallery">';
	echo $temp_lang;

	echo '<div style="clear: both;">';
	echo '<div style="width: 310px; float: left;"><input type="text" name="imgurl" id="imgurl" style="width: 300px; color: #757678; padding: 2px 0 1px 10px; border: 1px solid #D5D6D2; font: 11px Tahoma;"></div>';

	echo '<div style="width: 230px; float: left;"><input type="button" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma;" value="Выбрать" onclick="newwin2(\'/admin/files.php?show=jqGrid&amp;obj=imgurl\',720,520); return false; return false;">
	<span>из загруженных фотографий</span></div>';
	echo '</div>';
//	echo '<div style="clear: both; padding: 6px 0px 0px 0px;">';
//	echo '<span>Миниатюрная фотография 75px x 75px</span>';
//	echo '<div style="width: 310px; float: left;"><input type="text" name="imgurl2" id="imgurl2" style="width: 300px; color: #757678; padding: 2px 0 1px 10px; border: 1px solid #D5D6D2; font: 11px Tahoma;"></div>';
//	echo '<div style="width: 100px; float: left;"><input type="button" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma;" value="Выбрать" onclick="newwin2(\'files.php?show=ajaximgurl2\',720,520); return false;"></div>';
//	echo '</div>';
	echo '<div style="width: 100px; float: left; clear: left; padding: 6px 0px 0px 0px;"><input type="button" id="upload" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma;" value="Загрузить"></div>';
	echo '<div id="load" style="width: 16px; float: left; padding: 8px 0px 0px 0px; margin-left: 19px; visibility: hidden;"><img src="/admin/images/spinner.gif" alt=""/></div>';
echo '</div>';
echo '<div style="padding: 10px; position: relative; overflow: hidden; color: red;" id="error_upload"></div>';

//include("admin_footer.php"); ?>


<script type="text/javascript" src="/admin/files/swfupload.js"></script>
<script type="text/javascript" src="/admin/files/swfupload.queue.js"></script>
<script type="text/javascript" src="/admin/files/fileprogress.js"></script>
<STYLE>
	.progressWrapper {
		width: 345px;
		overflow: hidden;
		float: left;
	}

	.progressContainer {
		margin: 5px;
		padding: 4px;
		border: solid 1px #E8E8E8;
		background-color: #F7F7F7;
		overflow: hidden;
	}
	/* Message */
	.message {
		margin: 1em 0;
		padding: 10px 20px;
		border: solid 1px #FFDD99;
		background-color: #FFFFCC;
		overflow: hidden;
	}
	/* Error */
	.red {
		border: solid 1px #B50000;
		background-color: #FFEBEB;
	}

	/* Current */
	.green {
		border: solid 1px #DDF0DD;
		background-color: #EBFFEB;
	}

	/* Complete */
	.blue {
		border: solid 1px #CEE2F2;
		background-color: #F0F5FF;
	}

	.progressName {
		font-size: 8pt;
		font-weight: 700;
		color: #555;
		width: 323px;
		height: 14px;
		text-align: left;
		white-space: nowrap;
		overflow: hidden;
	}

	.progressBarInProgress,
	.progressBarComplete,
	.progressBarError {
		font-size: 0;
		width: 0%;
		height: 2px;
		background-color: blue;
		margin-top: 2px;
	}

	.progressBarComplete {
		width: 100%;
		background-color: green;
		visibility: hidden;
	}

	.progressBarError {
		width: 100%;
		background-color: red;
		visibility: hidden;
	}

	.progressBarStatus {
		margin-top: 2px;
		width: 337px;
		font-size: 7pt;
		font-family: Arial;
		text-align: left;
		white-space: nowrap;
	}

	a.progressCancel {
		font-size: 0;
		display: block;
		height: 14px;
		width: 14px;
		background-image: url(/admin/files/cancelbutton.gif);
		background-repeat: no-repeat;
		background-position: -14px 0px;
		float: right;
	}

	a.progressCancel:hover {
		background-position: 0px 0px;
	}
	.rh{
		position: relative;
		overflow: hidden;
	}
	.swfupload{
		position: absolute;
		top: 0px;
		left: 0px;
	}
</style>
<script type="text/javascript" src="/admin/files/handlers.js"></script>
<div style="">
	<div style="font: bold 12px Verdana; padding: 3px 3px 3px 3px;">После загрузки нажать кнопку обновить.</div>
	<div class="rh">
		<span id="spanButtonPlaceholder"></span>
		<input id="buttonFlash" type="button" value="Загрузить" />
		<input id="btnCancel" type="button" value="Отменить загрузку" onclick="cancelQueue(upload);" disabled="disabled" />
		<input id="btnRefresh" type="button" value="Обновить"/>
	</div>
	<div class="fieldset flash rh" style="width: 700px; margin-bottom: 40px;" id="fsUploadProgress"></div>
</div>
<script type="text/javascript">
    <? $files_photo = '/gallery';  ?>
	var upload = new SWFUpload({
		// Backend Settings
		upload_url: "/admin/ajax-upload.php",
		post_params: {
						"PHPSESSID" : "<?=session_id();?>",
						"path"		: "<?=$files_photo?>",
						"gallery"   : "<?=$parent_cat?>"
                        ,"uniq"      : "1"
					 },

		// File Upload Settings
		file_size_limit : "102400",	// 100MB
		file_types : "*.*",
		file_types_description : "All Files",
		file_upload_limit : "0",
		file_queue_limit : "0",

		// Event Handler Settings (all my handlers are in the Handler.js file)
		file_dialog_start_handler : fileDialogStart,
		file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadComplete,

		// Button Settings
		button_placeholder_id : "spanButtonPlaceholder",
		button_width: 61,
		button_height: 22,
		button_cursor : SWFUpload.CURSOR.HAND,
        button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,

		// Flash Settings
		flash_url : "/admin/files/swfupload.swf",


		custom_settings : {
			progressTarget : "fsUploadProgress",
			cancelButtonId : "btnCancel"
		},

		// Debug Settings
		debug: false
	});
	tempButton = $("#buttonFlash");
	$(".swfupload").css({ "width" : $(tempButton).width() + 16 , "height" : $(tempButton).height() + 6});
	$("#btnRefresh").click(function(){
		location.reload(true);
	});
</script>
<?include(BASEPATH."admin/admin_footer.php");?>