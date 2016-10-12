<?php

$no_html = 1;
$include = @include("../../../admin_top.php");
if(!$include or $adm_wellcome != "Y") exit;

?>
<html>
<head>
<title>Редактор галлереи</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<meta name="robots" content="noindex, nofollow">

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" title="text style" href="/admin/tags.css" />
<link href="/admin/css/ui-lightness/jquery-ui-1.7.2.custom.css" title="text style" type="text/css" rel="stylesheet">

    <script type="text/javascript">

    function newwin2(url,width,height) {
        window.open(url,"window","width="+width+",height="+height+",toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
    }

    function newwin(url,width,height){
        window.open(url,"window","width="+width+",height="+height+",toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
    }
    function newwin3(url,name, width,height) {
        window.open(url,name,"width="+width+",height="+height+",toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
    }

    var timeout         = 500;
    var closetimer        = 0;
    var ddmenuitem      = 0;

    function jsddm_open()
    {    jsddm_canceltimer();
    jsddm_close();
    ddmenuitem = $(this).find('ul').eq(0).css('visibility', 'visible');}

    function jsddm_close()
    {    if(ddmenuitem) ddmenuitem.css('visibility', 'hidden');}

    function jsddm_timer()
    {    closetimer = window.setTimeout(jsddm_close, timeout);}

    function jsddm_canceltimer()
    {    if(closetimer)
    {    window.clearTimeout(closetimer);
    closetimer = null;}}

    $('#jsddm > li').hover (jsddm_open, jsddm_timer);
//    $(document).ready(function()
//    {    $('#jsddm > li').bind('mouseover', jsddm_open);
//    $('#jsddm > li').bind('mouseout',  jsddm_timer);
//    });

    document.onclick = jsddm_close;

   </script>
<STYLE>
    .progressWrapper {
        width: 245px;
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
        width: 223px;
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
        width: 237px;
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


div#img_gallery {
        width: 582; color: #757678;
        background: #a5cd38; padding: 1px 1px 1px 1px;
        border: 1px solid #A5906B;
        position: relative; overflow: hidden;
        float:left;
        margin-left:2px;
        margin-top:-2px;
        }
#img_gallery div{
          font: normal 13px Verdana;
          background: #ffffff;
      }
.addContentBlock {
    float:right;
    width:300px;
    margin-right:2px;
    margin-top:-2px;
}
.addContentBlock .existItem {
    background-color: #f7f0b8;
    border:1px solid #A5906B;
    margin-bottom:5px;
    padding:10px;
}
.addContentBlock .newItem {
    background-color: #f7f0b8;
    border:1px solid #A5906B;
    padding:10px;
}
</STYLE>

<script type="text/javascript" src="/admin/files/swfupload.js"></script>
<script type="text/javascript" src="/admin/files/swfupload.queue.js"></script>
<script type="text/javascript" src="/admin/files/fileprogress.js"></script>
<script type="text/javascript" src="/admin/files/handlers.js"></script>

</head>
<body style='margin:0; background-color: #fcf7d5;' onload='this.focus();'>
<?

$lang_array = Lang::getLanguages();
$parent_cat = 0;
if (isset($_GET['item_id']))
    $parent_cat = $_GET['item_id'];

// Заголовок
$select_gallery = DB::Query("SELECT * FROM ?_gallery_category WHERE id=".$parent_cat." LIMIT 1");
if ($get = DB::GetArray($select_gallery)){
    $category = $get;
    echo admin_func_top('управление галереей - '.$get['title']);
    echo admin_func_sys_message($sys_message);
} else {
    die('Не выбрана галерея или галерея уже не существует.');
}


echo '<div id="img_gallery" style="">'.
     '<div style="position: relative; overflow: hidden; font-weight: bold; background: #a5cd38;">'.
         '<div style="margin: 0px 1px 0px 0px; float: left; width: 290px; position: relative; overflow: hidden;  text-align: center;">Имя</div>'.
         '<div style="margin: 0px 1px 0px 0px; float: left; width: 169px; position: relative; overflow: hidden;  text-align: center;">Изображение</div>'.
        '<div style=" float: left; width: 120px; position: relative; overflow: hidden;  text-align: center;">Действие</div>'.
     '</div>';

     $str = '';

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
                                        style="width: 150px; color: #757678; padding: 2px 0 1px 10px; border: 1px solid #D5D6D2; font: 11px Tahoma;" />';
    }else {
        // спец преобразователь, для того, чтобы убрать лишние
        $place++;
        $imgArr[$get['id']] = array();
        $imgArr[$get['id']]['start'] =
                    '<div style="width: 700px; background: #a5cd38; position: relative; overflow: hidden;" id="gallery_'.$get['id'].'" class="place_'.$place.'">'.
                    '<div style="height: 82px; margin: 1px 1px 0px 0px; padding-left:10px; float: left; width: 280px; position: relative; overflow: hidden; ">
                    <div style="float:left; margin-top:10px; " >';

        $imgArr[$get['id']]['names'] = ''.$lang_array[$get['lang_id']]['title_short'].':<input type="text" id="gallery_'.$get['id'].'_l'.$get['lang_id'].'" value="'.$get['title'].'" style="width: 150px; color: #757678; padding: 2px 0 1px 10px; border: 1px solid #D5D6D2; font: 11px Tahoma;" />';

        $imgArr[$get['id']]['end'] =
                    '</div>'.
                    '<div style="float:right; margin:30px 5px 0 0;" >
                        <input type="button" class="gallery_'.$get['id'].'" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma; width: 90px; margin: 0px 0px 0px 2px;" value="изменить">'.
                    '</div></div>'.
                    '<div style="height: 82px; margin: 1px 1px 0px 0px; float: left; width: 169px; position: relative; overflow: hidden;  text-align: center;"><img src="/image.php?' . Image::mEncrypt('height=75&ext=jpeg&src=' . $get['href']) . '" alt="'.$get['title'].'" style="padding: 3px; background: #ffffff; border: 1px solid #D5D6D2;"/></div>'.
                    '<div style="height: 82px; margin: 1px 0px 0px 0px; float: left; width: 120px; position: relative; overflow: hidden;  text-align: center;"><br/><br/>

                       <input type="button" class="gallery_'.$get['id'].'" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma; width: 70px; float: left; margin: 0px 0px 0px 2px;" value="удалить">
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


$temp_lang = '';

foreach ($lang_array as $key => $value){
    if ( is_int($key) ){
        $temp_lang .= '<div style="padding: 0px 0px 6px 0px;">
        <input type="text" class="lang_title" id="'.$key.'" style="width: 150px; color: #757678; padding: 2px 0 1px 10px; border: 1px solid #D5D6D2; font: 11px Tahoma;">
        &nbsp;&nbsp;&nbsp;Название <strong>'.$value['title_short'].'</strong></div>';
    }
}
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

            $.get("/admin/adm_ajax.php", {'action' : 'edit_title_1', 'gallery_id' : '-1', 'eng_title' : $(eng_text).attr('value'), 'rus_title' : $(rus_text).attr('value'), 'ukr_title' : $(ukr_text).attr('value'),     'id' : this_class},
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

        var lang_id = new Array();
        var title = new Array();
        $.each($('.lang_title'), function(){
            lang_id.push( $(this).attr('id') );
            title.push( $(this).attr('value') );
            $(this).val('');
        });

                    $.post("/admin/mGrid/engine/adm-ajax.php", {
                        bases: "<?=$bases; ?>", action:"insert_img"
                        , category_id: <?=$category['id']; ?>, href: $("#imgurl").val()
                        , min_href: $("#imgurl2").val()
                        , lang_id: lang_id, title: title
                    }, function(data){
                        if (data.status == 1){
                            $("#img_gallery").append(

'<div class="place_' + data.place + '" id="gallery_' + data.id + '" style="width: 600px; background: none repeat scroll 0% 0% rgb(165, 205, 56); position: relative; overflow: hidden;" >'
                +'<div style="height: 82px; margin: 1px 1px 0px 0px; padding-left: 10px; float: left; width: 280px; position: relative; overflow: hidden;" >'
                    +'<div style="float: left; "><br><div style="float:left; width:30px">Укр:</div> <input type="text" style="width: 150px; color: rgb(117, 118, 120); padding: 2px 0pt 1px 10px; border: 1px solid rgb(213, 214, 210); font: 11px Tahoma;" value="' + title[0] + '" id="gallery_'+data.id+'_l3">'
                        +'<br><div style="float:left; width:30px">Рус:</div> <input type="text" style="width: 150px; color: rgb(117, 118, 120); padding: 2px 0pt 1px 10px; border: 1px solid rgb(213, 214, 210); font: 11px Tahoma;" value="' + title[1] + '" id="gallery_'+data.id+'_l2">'
                        +'<br><div style="float:left; width:30px">Eng:</div> <input type="text" style="width: 150px; color: rgb(117, 118, 120); padding: 2px 0pt 1px 10px; border: 1px solid rgb(213, 214, 210); font: 11px Tahoma;" value="' + title[2] + '" id="gallery_'+data.id+'_l1"><br></div>'
                        +'<div style="float: right; margin: 30px 5px 0pt 0pt;">'
                        +'<input type="button" value="изменить" style="color: rgb(117, 118, 120); border: 1px solid rgb(213, 214, 210); font: 12px Tahoma; width: 90px; margin: 0px 0px 0px 2px;" class="gallery_'+data.id+'"></div></div>'
                        +'<div style="height: 82px; margin: 1px 1px 0px 0px; float: left; width: 169px; position: relative; overflow: hidden; text-align: center;">'
                            +'<img style="padding: 3px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 1px solid rgb(213, 214, 210);" alt="" src="/i.php?height=75&amp;temp_small_img_url=' + data.img_url + '"></div>'
                        +'<div style="height: 82px; margin: 1px 0px 0px; float: left; width: 120px; position: relative; overflow: hidden; text-align: center;"><br><br>'
                       +'<input type="button" value="удалить" style="color: rgb(117, 118, 120); border: 1px solid rgb(213, 214, 210); font: 12px Tahoma; width: 70px; float: left; margin: 0px 0px 0px 2px;" class="gallery_'+data.id+'">'
                       +'<input type="image" value="up" style="color: rgb(117, 118, 120); border: 1px solid rgb(213, 214, 210); font: 12px Tahoma; width: 16px; float: left; margin: 0px 0px 0px 2px; display: none;" class="gallery_'+data.id+'" src="/admin/images/arrow_up2.jpg">'
                       +'<input type="image" value="down" style="color: rgb(117, 118, 120); border: 1px solid rgb(213, 214, 210); font: 12px Tahoma; width: 16px; float: left; margin: 0px 0px 0px 2px; display: block;" class="gallery_'+data.id+'" src="/admin/images/arrow_down2.jpg">'
                    +'</div></div>'
                    );

                            $("#imgurl").val('');
                            $("#imgurl2").val('');
                            bindArrows();
                            bindUp();
                            bindDown();
                            bindDelete();
                            bindUpdate();
                        } else {
                            $("#error_upload").html('Не удалось загрузить фотографию');
                        }
                    }, "json");
        $("#load").css("visibility","hidden");
    });

});
</script>
<?

echo '
<div class="addContentBlock" style="" >
    <div class="existItem">
        <div style="font-weight:bold;" >Форма загрузки фотографии в галлерею.</div>
        <div style="padding: 10px; position: relative; overflow: hidden;" id="add_gallery">';
    echo $temp_lang;

echo '
            <div style="clear: both;"></div>
            <div style="width: 166px; float: left;">
                <input type="text" name="imgurl" id="imgurl" style="width: 150px; color: #757678; padding: 2px 0 1px 10px; border: 1px solid #D5D6D2; font: 11px Tahoma;">
            </div>
            <div style="width: 50px; float: left;">
                <input type="button" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma;" value="Выбрать" onclick="newwin3(\'/admin/files.php?show=jqGrid&amp;obj=imgurl\',\'selectwn\',720,520); return false;" title="из загруженных фотографий" >
            </div>
            <div style="width: 100px; float: left; clear: left; padding: 6px 0px 0px 0px;">
                <input type="button" id="upload" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma;" value="Готово" />
            </div>
            <div id="load" style="width: 16px; float: left; padding: 8px 0px 0px 0px; margin-left: 19px; visibility: hidden;">
                <img src="/admin/images/spinner.gif" alt=""/>
            </div>
        </div>
        <div style="padding: 10px; position: relative; overflow: hidden; color: red;" id="error_upload">
        </div>
        <div style="padding: 10px; position: relative; overflow: hidden; color: red;" id="error"></div>
    </div>';
?>
    <div class="newItem">
        <div style="font: bold 12px Verdana; padding: 3px 3px 3px 3px;">После загрузки нажать кнопку обновить.
        </div>
        <div class="rh">
            <span id="spanButtonPlaceholder"></span>
            <input id="buttonFlash" type="button" value="Загрузить" />
            <input id="btnCancel" type="button" value="Отменить загрузку" onclick="cancelQueue(upload);" disabled="disabled" />
            <input id="btnRefresh" type="button" value="Обновить"/>
        </div>
        <div class="fieldset flash rh" style="width: 250px; margin-bottom: 40px;" id="fsUploadProgress">
        </div>
    </div>
</div>
<script type="text/javascript">
    <? $files_photo = '/gallery';  ?>
    var upload = new SWFUpload({
        // Backend Settings
        upload_url: "/admin/ajax-upload.php",
        post_params: {
                        "PHPSESSID" : "<?=session_id();?>",
                        "path"        : "<?=$files_photo?>",
                        "uniq"        : true,
                        "gallery"   : "<?=$parent_cat?>"
                     },

        // File Upload Settings
        file_size_limit : "102400",    // 100MB
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

</body>
</html>