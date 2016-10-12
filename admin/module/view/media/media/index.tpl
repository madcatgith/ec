{$element->actionInsert()}
{$element->actionUpdate()}
{$self->actionInsert()}
{$self->actionUpdate()}
<div class="well well-small"><strong>Медиабиблиотека</strong> 
    <a class="btn btn-success btn-back" data-toggle="modal" data-target="#mediaAdd" href="javascript:void(0);">Добавить коллекцию</a>
    <a class="btn btn-success btn-back" data-toggle="modal" data-target="#mediaElementAdd" href="javascript:void(0);">Добавить элемент</a>
    <div class="navbar-right">
        <a class="btn btn-danger" href="javascript:void(0);" id="deleteAll">Удалить выделенные элементы</a>
    </div>
</div>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    {foreach $mediaRoot as $item}
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="mediaHeading-{$item[0].id}">
                <h4 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-{$item[0].id}" aria-controls="collapse-{$item[0].id}" aria-expanded="false">
                        {if $item[1].title}{$item[1].title}{else}Media #{$item[0].id}{/if}
                    </a>
                    <div class="navbar-right">
                        <a class="mediaEdit" data-id="{$item[0].id}" href="javascript:void(0);"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                        <a class="removeData" data-action="media/delete" data-id="{$item[0].id}" href="javascript:void(0);"><i class="glyphicon glyphicon-remove"></i> Remove</a>                    
                    </div>
                </h4>
            </div>
            <div id="collapse-{$item[0].id}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    {$element->actionIndex($item[0].id)}
                    {if !empty($structure[$item[0].id])}
                        <div class="panel-group" id="accordion-{$item[0].id}" role="tablist" aria-multiselectable="true">
                            {foreach $structure[$item[0].id] as $itemID}
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="mediaHeading-{$itemID}">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion-{$item[0].id}" href="#collapse-{$itemID}_{$item[0].id}" aria-controls="collapse-{$itemID}_{$item[0].id}" aria-expanded="false">
                                                {if $media[$itemID][1].title}{$media[$itemID][1].title}{else}Media #{$itemID}{/if}
                                            </a>
                                            <div class="navbar-right">
                                                <a class="mediaEdit" data-id="{$itemID}" href="javascript:void(0);"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                                                <a class="removeData" data-action="media/delete" data-id="{$itemID}" href="javascript:void(0);"><i class="glyphicon glyphicon-remove"></i> Remove</a>                    
                                            </div>
                                        </h4>
                                    </div>
                                    <div id="collapse-{$itemID}_{$item[0].id}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            {$element->actionIndex($itemID)}
                                        </div>
                                    </div>
                                </div>
                            {/foreach}
                        </div>
                    {/if}          
                </div>
            </div>
        </div>
    {/foreach}
</div>
<div style="overflow:hidden;margin-top:20px;">
    <div class="navbar-right">
        <a class="btn btn-danger" href="?clearCache=1">Отчистить кэш</a>
    </div>
</div>
<script>
    $(function() {
        var media = {json_encode($media)}, 
            elements = {json_encode($elements->findAll())},
            langs = {json_encode(Lang::getLanguages())};
    
        $('#accordion a.mediaEdit').on('click', function() {
            var ID = $(this).data('id'), mediaOne = media[ID];
            
            $('#mediaUpdateForm')
                .find('input[name="data[0][id]"]').val(ID).end()
                .find('select[name="data[0][parentID]"] option[value="'+ mediaOne[0].parentID +'"]').prop('selected', 'selected').end();
            
            for(lang in langs) {
                if(typeof mediaOne[langs[lang].id] !== 'undefined') {
                    $('#mediaUpdateForm')
                        .find('input[name="data['+ langs[lang].id +'][title]"]').val(mediaOne[langs[lang].id].title).end()
                        .find('textarea[name="data['+ langs[lang].id +'][text]"]').val(mediaOne[langs[lang].id].text).end();
                }
            }
            
            $('#mediaUpdate').modal('show');
        });
        
        $('#accordion a.elementEdit').on('click', function() {
            var ID = $(this).data('id'), one = elements[ID];
            
            $('#mediaElementUpdateForm')
                .find('input[name="data[0][id]"]').val(ID).end()
                .find('input[name="data[0][sort]"]').val(one[0].sort).end()
                .find('select[name="data[0][mediaID]"] option[value="'+ one[0].mediaID +'"]').prop('selected', 'selected').end()
                .find('input[name="data[0][source]"]').val(one[0].source).end()
                .find('input[name="data[0][type]"]').val(one[0].type).end()
                .find('input[name="data[0][isImage]"]').val(one[0].isImage).end()
                .find('input[name="data[0][subdir]"]').val(one[0].subdir).end()
                .find('input[name="data[0][width]"]').val(one[0].width).end()
                .find('input[name="data[0][height]"]').val(one[0].height).end();
            $('#mediaSource').text(one[0].src).attr('href', one[0].src);
            $('#sourceSizes').empty();
            if(+one[0].width > 0 && +one[0].height > 0) {
                $('#sourceSizes').text('Размер - '+ one[0].width +' x '+ one[0].height +' px');
            }
                
            for(lang in langs) {
                if(typeof one[langs[lang].id] !== 'undefined') {
                    $('#mediaElementUpdateForm')
                        .find('input[name="data['+ langs[lang].id +'][title]"]').val(one[langs[lang].id].title).end()
                        .find('textarea[name="data['+ langs[lang].id +'][text]"]').val(one[langs[lang].id].text).end();
                }
            }
            
            $('#mediaElementUpdate').modal('show');
        });
        
        $('#deleteAll').on('click', function() {
            var ids = [];
            $('input[name="deleteElements"]').each(function() {
                if($(this).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            
            if(!ids.length) {
                $('#modalInfo').find('.modal-title').text('Не выбраны элементы для удаления.').end().modal('show');
                return false;
            }

            $('#modalRemove .btn-yes').off('click').on('click', function()
            {
                $.post('/admin/module/request.php?fn=media/element/delete', {
                    uri: location.pathname,
                    ids: ids
                }, function(data) {
                    $('#modalRemove').modal('hide');
                    if (data.action) {
                        location.reload();
                    }
                }, 'json');
            });

            $('#modalRemove').modal('show');
        });
    });
</script>