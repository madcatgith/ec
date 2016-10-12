<script fvtype="text/javascript">

    function urldecode(str)
    {
        return decodeURIComponent((str + '').replace(/\+/g, '%20'));
    }

    function urlencode(str) {

        str = (str + '').toString();

        return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');

    }

    function doLoad(image)
    {
        $.post('/admin/lib/wmpGallery/request.php', {
            'action': 'getImage'
            , 'image': urlencode(image)
            , 'gallery': {$ID}
        }, function(data)
        {
            $('#gallery').append('<li data-id="' + data.id + '" class="ui-widget-content ui-corner-tr ui-state-default"><h5 class="ui-widget-header">&nbsp;</h5><img src="/image.php?' + urldecode(data.thumbs) + '" alt="" width="96" height="72" /><a href="http://{$smarty.server.HTTP_HOST}/image.php?' + urldecode(data.src) + '" class="btnZoom ui-icon ui-icon-zoomin">Увеличить</a><a href="link/to/trash/script/when/we/have/js/off" title="Удалить" class="ui-icon ui-icon-trash">Удалить</a></li>');
            iFancy();
        }, 'json');
    }
</script>
<style>
    #gallery { float: left; width: 65%; min-height: 1px; } 
    * html #gallery { height: 12em; } /* IE6 */
    .gallery.custom-state-active { background: #eee; }
    .gallery li { float: left; width: 96px; padding: 0.4em; margin: 8px 0 0 8px; text-align: center; }
    .gallery li h5 { margin: 0 0 0.4em; cursor: move; height : 13px; }
    .gallery li a { float: right; }
    .gallery li a.ui-icon-zoomin { float: left; }
    .gallery li img { width: 100%; cursor: move; }
    #trash { float: right; width: 32%; margin-top: 8px; }
    #trash .imgHolder{
        height: 100%;
        overflow: hidden;
        position: relative;		
    }
    #trash .trashHolder{
        position: relative;
        min-height: 18em; 
        padding: 10px 10px 45px;
    }
    #trash h4 { line-height: 26px; margin: 0 0 0.4em; }
    #trash h4 .ui-icon { float: left; margin-top: 4px;}
    #trash .gallery h5 { display: none; }
    #helpersWin{
        position: absolute;
        z-index: 200;
        background: #fff;
        border: 2px solid #1C94C4;
        border-radius: 5px;
        font: 14px/20px Tahoma;
        display: none;
    }
    #helpersWin input{
        width: 250px;
        padding: 4px 6px;
        font: 14px/18px Tahoma;
        border: 1px solid #1C94C4;
        border-radius: 3px;
        margin-left: 6px;
    }
    #helpersWin form{
        margin: 0;
        padding: 0;
    }
    #helpersWin table{
        padding: 0 5px;
    }
    #helpersWin td{
        padding: 6px 0 0;
    }
</style>
<div class="demo ui-widget ui-helper-clearfix">
    <ul id="gallery" class="gallery ui-helper-reset ui-helper-clearfix">
        {foreach $gallery as $image}
            <li data-id="{$image.id}" class="ui-widget-content ui-corner-tr ui-state-default">
                <h5 class="ui-widget-header">{if $image.title}{$image.title}{else}&nbsp;{/if}</h5>
                {if $image.type eq 0}
                    <img src="/image.php?{Image::mEncrypt('height=72&fill=ffffff&width=96&ext=jpg&src='|cat:$image.href)}" alt="{$image.title|escape}" width="96" height="72" />
                    <a href="http://{$smarty.server.HTTP_HOST}/image.php?{Image::mEncrypt('height=288&src='|cat:$image.href)}" class="btnZoom ui-icon ui-icon-zoomin">Увеличить</a>
                {elseif $image.type eq 1}
                    <img src="{$image.href}" alt="{$image.title|escape}" width="96" height="72" />
                    <a href="{$image.href}" class="btnZoom ui-icon ui-icon-zoomin">Увеличить</a>
                {/if}
                <a href="link/to/trash/script/when/we/have/js/off" title="Удалить" class="ui-icon ui-icon-trash">Удалить</a>
            </li>				
        {/foreach}  
    </ul>
    <div id="trash" class="ui-widget-content ui-state-default">
        <div class="trashHolder">
            <h4 class="ui-widget-header"><span class="ui-icon ui-icon-trash">Корзина</span> Корзина</h4>
            <div class="imgHolder"></div>
            <div style="text-align: right; width: 96%; position: absolute; bottom: 8px;">
                <button id="clearBasket" type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text">Очистить</span></button>
            </div>
        </div>
    </div>
    <div id="helpersWin">
        <form action="javascript:void()" method="post">
            <table>
                {foreach Lang::getLanguages() as $lang}
                    <tr>
                        <td align="right">Alt для <b>{$lang.title}</b>:</td> 
                        <td><input class="title" type="text" data-id="{$lang.id}" name="title[{$lang.id}]" id="title_{$lang.id}" /></td>
                    </tr>
                {/foreach}
            </table>
            <div style="text-align: center; padding: 6px 0;">
                <button id="saveTitle" type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text">Сохранить</span></button>
            </div>
        </form>
    </div>
    <div class="fieldset flash rh" style="clear: left; padding: 6px; position: relative;" id="fsUploadProgress"></div>
    <div style="clear: left; padding: 8px; position: relative;">
        <span id="spanButtonPlaceholder"></span> 
        <button id="loadImage" type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text">Загрузить с ПК</span></button>
        <button id="cancelLoadImage" onclick="cancelQueue(upload);" disabled="disabled" type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text">Отмена загрузки</span></button>
        <span style="margin-top: -8px; color: #000000; display: inline-block; font-size: 30px; line-height: 30px; vertical-align: middle;">|</span>
        <button id="addImage" type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text">Добавить из файлов</span></button>
        <span style="margin-top: -8px; color: #000000; display: inline-block; font-size: 30px; line-height: 30px; vertical-align: middle;">|</span>
        <button id="addYoutube" type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text">Добавить видео (Youtube)</span></button>
        <span style="margin-top: -8px; color: #000000; display: inline-block; font-size: 30px; line-height: 30px; vertical-align: middle;">|</span>
        <button id="savePlace" type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text">Сохранить позиции</span></button>
    </div>
</div>
<div id="youtube-form" title="Добавить видео (Youtube)">
    <form style="margin: 0;" action="javascript:void(0);">
        <label for="hrefY">Ссылка на видео (http://www.youtube.com/watch?v=NKyJrZX6jSU)</label><br />
        <input style="width: 405px; margin: 4px 0 10px;" type="text" name="href" id="hrefY" class="text ui-widget-content ui-corner-all" /><br />
        <div id="youtube-tabs">
            <ul>
                {foreach Lang::getLanguages() as $k => $lang}
                    <li><a href="#youtube-tabs-{$k}">{$lang.title}</a></li>
                    {/foreach}
            </ul>
            {foreach Lang::getLanguages() as $k => $lang}
                <div id="youtube-tabs-{$k}" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                <label for="yti{$k}">Название ролика</label><br />
                                <input style="width: 100%; margin: 4px 0 10px;" type="text" name="langs[{$k}][title]" id="yti{$k}" value="" class="text ui-widget-content ui-corner-all" /><br />                            
                            <td>
                        </tr>
                    </table>
                </div>
            {/foreach}
        </div>
    </form>
</div>            
<script type="text/javascript">
    $(function() {
        // there's the gallery and the trash
        var $gallery = $("#gallery"),
                $trash = $("#trash"),
                $trashHolder = $('.imgHolder', $trash);

        $gallery.sortable();

        // image deletion function
        var recycle_icon = "<a href='link/to/recycle/script/when/we/have/js/off' title='Восстановить' class='ui-icon ui-icon-refresh'>Восстановить</a>";
        function deleteImage($item) {
            $item.fadeOut(function() {
                var $list = $("ul", $trashHolder).length ?
                        $("ul", $trash) :
                        $("<ul class='gallery ui-helper-reset'/>").appendTo($trashHolder);

                $item.find("a.ui-icon-trash").remove();
                $item.append(recycle_icon).appendTo($list).fadeIn(function() {
                    $item
                            .animate({
                                width: "48px"
                            })
                            .find("img")
                            .animate({
                                height: "36px"
                            });
                });
            });
        }

        // image recycle function
        var trash_icon = "<a href='link/to/trash/script/when/we/have/js/off' title='Удалить' class='ui-icon ui-icon-trash'>Удалить</a>";
        function recycleImage($item) {
            $item.fadeOut(function() {
                $item
                        .find("a.ui-icon-refresh")
                        .remove()
                        .end()
                        .css("width", "96px")
                        .append(trash_icon)
                        .find("img")
                        .css("height", "72px")
                        .end()
                        .appendTo($gallery)
                        .fadeIn();
            });
        }

        // resolve the icons behavior with event delegation
        $("ul.gallery > li").live('click', function(event) {
            var $item = $(this),
                    $target = $(event.target);

            if ($target.is("a.ui-icon-trash")) {
                deleteImage($item);
            } else if ($target.is("a.ui-icon-refresh")) {
                recycleImage($item);
            }

            return false;
        });
    });
</script>
<script type="text/javascript" src="/admin/files/swfupload.js"></script>
<script type="text/javascript" src="/admin/files/swfupload.queue.js"></script>
<script type="text/javascript" src="/admin/files/fileprogress.js"></script>
<script type="text/javascript" src="/admin/files/handlers.js"></script>
<style type="text/css">
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
        z-index: 10;
    }
    #loadImage{
        position: relative;
        z-index: 1;
    }
</style>
<script type="text/javascript">


    var upload = new SWFUpload({
        // Flash Settings
        flash_url: "/admin/files/swfupload.swf",
        // Backend Settings
        upload_url: "/admin/lib/wmpGallery/request.php",
        button_image_url: '/admin/lib/wmpGallery/blank.png',
        post_params: {
            "PHPSESSID": "{session_id()}",
            "gallery": '{$ID}',
            'action': 'load'
        },
        // File Upload Settings

        file_size_limit: "102400", // 100MB
        file_types: "*.*",
        file_types_description: "All Files",
        file_upload_limit: "0",
        file_queue_limit: "0",
        // Event Handler Settings (all my handlers are in the Handler.js file)
        file_dialog_start_handler: fileDialogStart,
        file_queued_handler: fileQueued,
        file_queue_error_handler: fileQueueError,
        file_dialog_complete_handler: fileDialogComplete,
        upload_start_handler: uploadStart,
        upload_progress_handler: uploadProgress,
        upload_error_handler: uploadError,
        upload_success_handler: uploadSuccess,
        upload_complete_handler: function uploadComplete(file) {

            try {

                doLoad(file.name)

                //  I want the next upload to continue automatically so I'll call startUpload here
                if (this.getStats().files_queued === 0) {
                    document.getElementById(this.customSettings.cancelButtonId).disabled = true;
                } else {
                    this.startUpload();
                }

            } catch (ex) {
                this.debug(ex);
            }

        },
        // Button Settings
        button_placeholder_id: "spanButtonPlaceholder",
        button_width: 61,
        button_height: 22,
        button_cursor: SWFUpload.CURSOR.HAND,
        button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
        custom_settings: {
            progressTarget: "fsUploadProgress",
            cancelButtonId: "cancelLoadImage"
        }
    });

    $(".swfupload").css({
        "width": $('#loadImage').width()
        , "height": $('#loadImage').height()
        , 'top': $('#loadImage').position().top
        , 'left': $('#loadImage').position().left
    });

    iFancy = function()
    {
        $(".btnZoom").fancybox({
            openEffect: 'none'
            , closeEffect: 'none'
            , prevEffect: 'none'
            , nextEffect: 'none'
            , type: 'image'
        });
    };

    iFancy();

    $('#clearBasket').click(function()
    {

        var ids = [];

        $('#trash .gallery li').each(function()
        {
            ids.push($(this).data('id'));
        });

        $.post('/admin/lib/wmpGallery/request.php', {
            'action': 'clearBasket'
            , 'ids': ids
            , 'gallery': {$ID}
        }, function(data)
        {
            $('#trash .gallery').remove();
        }, 'json');

        delete ids;

    });

    $('#savePlace').click(function()
    {
        var ids = [];

        $('#gallery li').each(function()
        {
            ids.push({
                'id': $(this).data('id')
                , 'place': $('#gallery li').index(this) + 1
            });
        });

        $.post('/admin/lib/wmpGallery/request.php', {
            'action': 'sort'
            , 'ids': ids
            , 'gallery': {$ID}
        }, function(data)
        {

        }, 'json');

        delete ids;

    });


    addImagesToGallery = function(images)
    {

        var prImages = [];

        $.each(images, function(i, val)
        {
            prImages.push(urldecode(val));
        });

        $.post('/admin/lib/wmpGallery/request.php', {
            'action': 'addImages'
            , 'images': prImages
            , 'gallery': {$ID}
        }, function($data)
        {
            $.each($data, function(i, data)
            {
                $('#gallery').append('<li data-id="' + data.id + '" class="ui-widget-content ui-corner-tr ui-state-default"><h5 class="ui-widget-header">&nbsp;</h5><img src="/image.php?' + urldecode(data.thumbs) + '" alt="" width="96" height="72" /><a href="http://{$smarty.server.HTTP_HOST}/image.php?' + urldecode(data.src) + '" class="btnZoom ui-icon ui-icon-zoomin">Увеличить</a><a href="link/to/trash/script/when/we/have/js/off" title="Удалить" class="ui-icon ui-icon-trash">Удалить</a></li>');
            });

            iFancy();

        }, 'json');

        delete prImages

    };

    $('#addImage').click(function()
    {
        newwin2('/admin/files.php?show=jqGallery&callback=addImagesToGallery', 720, 520);
    });


    $('#gallery li').live('dblclick', function()
    {

        var self = $(this);

        $.post('/admin/lib/wmpGallery/request.php', {
            'action': 'getImageByID'
            , 'id': $(this).data('id')
            , 'gallery': {$ID}
        }, function(data)
        {

            $('#helpersWin').find('input').val('');

            if (data.lang)
                $.each(data.lang, function(i, val)
                {
                    $('#title_' + i).val(urldecode(val['title']));
                });

            $('#helpersWin').show().css({
                'left': self.position().left + 8
                , 'top': self.position().top + 8
            }).data('id', self.data('id'));

        }, 'json');

        delete self;

    });

    $('#saveTitle').click(function()
    {

        var titles = [];

        $('#helpersWin .title').each(function()
        {
            titles.push({
                'id': $(this).data('id')
                , 'title': urlencode($(this).val())
            });
        });

        $('#gallery li[data-id=' + $('#helpersWin').data('id') + ']').find('h5.ui-widget-header').text($('#title_{Lang::getID()}').val());

        $.post('/admin/lib/wmpGallery/request.php', {
            'action': 'saveLangs'
            , 'id': $('#helpersWin').data('id')
            , 'titles': titles
        }, function(data)
        {
            $('#helpersWin').hide();
        }, 'json');

        delete titles;

    });

    $('#addYoutube').click(function()
    {
        $("#youtube-form").dialog("open");
    });

    $("#youtube-form").dialog({
        autoOpen: false,
        width: 430,
        modal: true,
        buttons: {
            "Добавить": function()
            {
                $.post('/admin/lib/wmpGallery/request.php', {
                    action: 'addYoutube',
                    gallery: {$ID},
                    data: $(this).find('form').serialize()
                }, function(data)
                {
                    $.each(data, function(i, image)
                    {
                        $('#gallery').append('<li data-id="' + image.id + '" class="ui-widget-content ui-corner-tr ui-state-default"><h5 class="ui-widget-header">&nbsp;</h5><img src="' + image.thumbs + '" alt="" width="96" height="72" /><a href="' + image.src + '" class="btnZoom ui-icon ui-icon-zoomin">Увеличить</a><a href="link/to/trash/script/when/we/have/js/off" title="Удалить" class="ui-icon ui-icon-trash">Удалить</a></li>');
                    });
                    iFancy();
                    $(this).dialog("close");
                }, 'json');
            },
            "Закрыть": function()
            {
                $(this).dialog("close");
            }
        },
        close: function()
        {
            $(this).find('input[type=text]. textarea').val('');
        }
    });

    $("#youtube-tabs").tabs();
</script>