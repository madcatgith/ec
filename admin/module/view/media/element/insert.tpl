{if $error}<div class="alert alert-error">{$error}</div>{/if}
<div class="modal fade" id="mediaElementAdd" tabindex="-1" role="dialog" aria-labelledby="mediaAddLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                <h4 class="modal-title" id="mediaElementInsertLabel">Добавление элемента</h4>
            </div>
            <form id="mediaElementAddForm" action="{$smarty.server.REQUEST_URI}" method="post" class="form-horizontal form-offset-0" role="form" enctype="multipart/form-data">
                <input type="hidden" name="elementInsert" value="1" />
                <div class="modal-body">
                    <div class="form-group">
                        <label for="mediaIDEInsert" class="col-sm-3 control-label">Расположение</label>
                        <div class="col-sm-9">
                            <select id="mediaIDEInsert" name="data[0][mediaID]" class="form-control">
                                {$options}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="elementFileInsert" class="col-sm-3 control-label">Файл</label>
                        <div class="col-sm-9">
                            <input type="file" id="elementFileInsert" name="mediaSource" />
                        </div>
                    </div>
                    <ul class="nav nav-tabs langs">
                        {foreach Lang::getLanguages() as $langID => $values}
                            <li><a data-toggle="tab" href="#lang{$langID}EInsert">{$values.title}</a></li>
                        {/foreach}
                    </ul>
                    <div class="tab-content">
                        {foreach Lang::getLanguages() as $langID => $values}
                            <div class="tab-pane" id="lang{$langID}EInsert">
                                <div class="form-group">
                                    <label for="title{$langID}EInsert" class="col-sm-3 control-label">Название</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="title{$langID}EInsert" name="data[{$langID}][title]" placeholder="Название">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="text{$langID}EInsert" class="col-sm-3 control-label">Описание</label>
                                    <div class="col-sm-9">
                                        <textarea name="data[{$langID}][text]" id="text{$langID}EInsert" rows="3" class="form-control" placeholder="Описание"></textarea>
                                    </div>
                                </div>
                            </div>
                        {/foreach}
                    </div>          
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>