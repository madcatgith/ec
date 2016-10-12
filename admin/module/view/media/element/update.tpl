<div class="modal fade" id="mediaElementUpdate" tabindex="-1" role="dialog" aria-labelledby="mediaAddLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                <h4 class="modal-title" id="mediaElementUpdateLabel">Изминение элемента</h4>
            </div>
            <form id="mediaElementUpdateForm" action="{$smarty.server.REQUEST_URI}" method="post" class="form-horizontal form-offset-0" role="form">
                <input type="hidden" name="elementUpdate" value="1" />
                <input type="hidden" name="data[0][id]" value="" />
                <input type="hidden" name="data[0][sort]" value="" />
                <div class="modal-body">
                    <div class="form-group">
                        <label for="mediaIDEUpdate" class="col-sm-3 control-label">Расположение</label>
                        <div class="col-sm-9">
                            <select id="mediaIDEUpdate" name="data[0][mediaID]" class="form-control">
                                {$options}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="elementFileUpdate" class="col-sm-3 control-label">Файл</label>
                        <div class="col-sm-9">
                            <input type="hidden" id="elementFileUpdate" name="data[0][source]" value="" />
                            <input type="hidden" name="data[0][type]" value="" />
                            <input type="hidden" name="data[0][isImage]" value="" />
                            <input type="hidden" name="data[0][subdir]" value="" />
                            <input type="hidden" name="data[0][width]" value="" />
                            <input type="hidden" name="data[0][height]" value="" />
                            <p>Путь к файлу - <a href="" id="mediaSource" target="_blank"></a><br />
                                <span id="sourceSizes"></span>
                            </p>
                        </div>
                    </div>
                    <ul class="nav nav-tabs langs">
                        {foreach Lang::getLanguages() as $langID => $values}
                            <li><a data-toggle="tab" href="#lang{$langID}EUpdate">{$values.title}</a></li>
                        {/foreach}
                    </ul>
                    <div class="tab-content">
                        {foreach Lang::getLanguages() as $langID => $values}
                            <div class="tab-pane" id="lang{$langID}EUpdate">
                                <div class="form-group">
                                    <label for="title{$langID}EUpdate" class="col-sm-3 control-label">Название</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="title{$langID}EUpdate" name="data[{$langID}][title]" placeholder="Название">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="text{$langID}EUpdate" class="col-sm-3 control-label">Описание</label>
                                    <div class="col-sm-9">
                                        <textarea name="data[{$langID}][text]" id="text{$langID}EUpdate" rows="3" class="form-control" placeholder="Описание"></textarea>
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