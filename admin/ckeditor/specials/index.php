<?php

$no_top = "Y";
include getenv('DOCUMENT_ROOT') .'/admin/admin_top.php';

$form = new \Form\FormController;

?>
<link rel="stylesheet" type="text/css" title="text style" href="/admin/css/bootstrap.min.css" />
<script type="text/javascript" src="/admin/js/bootstrap.min.js"></script>
<div class="col-sm-12 no-padding">
    <table class="table table-hover table-striped table-specials col-sm-12">
        <tr>
            <td class="col-sm-1">Галерея</td>
            <td class="col-sm-11">
                <div class="form-group col-sm-2 no-margin">
                    <input id="galleryID" name="galleryID" type="text" class="form-control" />
                </div>
                <button class="btn btn-primary col-sm-2" type="button" onclick="newwin('/admin/mGrid/engine/editors/selectorGallery.php?name=galleryID',303,403);">Выбрать галерею</button>
                <div class="col-sm-12">
                    <button type="button" class="putSpecial btn btn-success margin-top-10" data-id="gallery">Вставить</button>
                </div>
            </td>
        </tr>
        <tr>
            <td>Видео Youtube</td>
            <td>
                <div class="col-sm-12">
                    <div class="col-sm-2 label-special">Идентификатор видео *</div>
                    <div class="col-sm-4">
                        <input name="youtube[src]" type="text" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-2 label-special">Ширина</div>
                    <div class="col-sm-4">
                        <input name="youtube[width]" type="text" class="form-control" value="650" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-2 label-special">Высота</div>
                    <div class="col-sm-4">
                        <input name="youtube[height]" type="text" class="form-control" value="315" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <button type="button" class="putSpecial btn btn-success margin-top-10" data-id="youtube">Вставить</button>
                </div>
            </td>
        </tr>
        <tr>
            <td>Карта Google Map</td>
            <td>
                <div class="col-sm-12">
                    <div class="col-sm-2 label-special">Координаты *</div>
                    <div class="col-sm-4">
                        <input id="googleCoords" name="google[coords]" type="text" class="form-control" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-2 label-special">Адрес</div>
                    <div class="col-sm-4">
                        <input id="googleAddress" name="google[address]" type="text" class="form-control" placeholder="Введите адрес для получение координат" />
                    </div>
                    <button class="btn btn-primary col-sm-2" type="button" onclick="findCoords()">Найти координаты</button>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-2 label-special">Ширина</div>
                    <div class="col-sm-4">
                        <input name="google[width]" type="text" class="form-control" value="400" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-2 label-special">Высота</div>
                    <div class="col-sm-4">
                        <input name="google[height]" type="text" class="form-control" value="400" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-2 label-special">Зум</div>
                    <div class="col-sm-4">
                        <input name="google[zoom]" type="text" class="form-control" value="10" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <button type="button" class="putSpecial btn btn-success margin-top-10" data-id="gmap">Вставить</button>
                </div>
            </td>
        </tr>
        <tr>
            <td>Конструктор форм</td>
            <td>
                <div class="col-sm-4">
                    <?=$form->actionSelect()?>
                </div>
                <div class="col-sm-8"></div>
                <div class="col-sm-12">
                    <button type="button" class="putSpecial btn btn-success margin-top-10" data-id="form">Вставить</button>
                </div>
            </td>
        </tr>
    </table>
</div>
<script>
    var newwin = function(url,width,height) {
        window.open(url,"subwindow","width="+width+",height="+height+",toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes");
    }, FireEventFromChild = function(name, val) {
        $('[id='+ name +']').val(val);
    }, findCoords = function() {
        var address = $('#googleAddress').val();
        if(!address) {
            alert('Введите адрес для поиска координат');
            return false;
        }        
        newwin('/admin/mGrid/engine/editors/editorMap.php?address='+ encodeURIComponent(address) +'&name=googleCoords', 900, 600);
    };
    
    $('.putSpecial').on('click', function() {
        
        switch($(this).data('id')) 
        {
            case 'gallery':
                if(!$('#galleryID').val()) {
                    alert('Не выбрана галерея!');
                    return false;
                }
                special = '[gallery:'+ $('#galleryID').val() +']';
                break;
            case 'youtube':
                if(!$('input[name="youtube[src]"]').val()) {
                    alert('Отсутствует идентификатор видео Youtube!');
                    return false;                    
                }
                special = '[youtube:'+ $('input[name="youtube[src]"]').val() +','+ $('input[name="youtube[width]"]').val()
                    +','+ $('input[name="youtube[height]"]').val() +']';
                break;
            case 'gmap':
                if(!$('input[name="google[coords]"]').val()) {
                    alert('Отсутствуют координаты. Введите адрес и нажмите "Найти координаты"!');
                    return false;                    
                }
                data = [];
                $('input[name^="google"]').not('input[name="google[address]"]').each(function() {
                    data.push($(this).val());
                });
                special = '[map:'+ data.join(',') +']';
                delete data;
                break;
            case 'form':
                if(!$('#formID option:selected').val()) {
                    alert('Не выбрана форма!');
                    return false;
                }
                special = '[form:'+ $('#formID option:selected').val() +']';
                break;
        }
        
        $(window.content.document.getElementById('specialModal')).find('[data-dismiss="modal"]').trigger('click');
        window.content.CKEDITOR.instances.cketextarea.insertText(special);
    });
</script>

