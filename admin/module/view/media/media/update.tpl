<div class="modal fade" id="mediaUpdate" tabindex="-1" role="dialog" aria-labelledby="mediaAddLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                <h4 class="modal-title" id="mediaLabelUpdate">Редактирование коллекции</h4>
            </div>
            <form id="mediaUpdateForm" action="{$smarty.server.REQUEST_URI}" method="post" class="form-horizontal form-offset-0" role="form">
                <input type="hidden" name="mediaUpdate" value="1" />
                <input type="hidden" name="data[0][id]" value="0" />
                <div class="modal-body">
                    <div class="form-group">
                        <label for="parentIDUpdate" class="col-sm-3 control-label">Расположение</label>
                        <div class="col-sm-9">
                            <select id="parentIDUpdate" name="data[0][parentID]" class="form-control">
                                <option value="0">Верхний уровень</option>
                                {$options}
                            </select>
                        </div>
                    </div>
                    <ul class="nav nav-tabs langs">
                        {foreach Lang::getLanguages() as $langID => $values}
                            <li><a data-toggle="tab" href="#lang{$langID}Update">{$values.title}</a></li>
                        {/foreach}
                    </ul>
                    <div class="tab-content">
                        {foreach Lang::getLanguages() as $langID => $values}
                            <div class="tab-pane" id="lang{$langID}Update">
                                <div class="form-group">
                                    <label for="title{$langID}Update" class="col-sm-3 control-label">Название</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="title{$langID}Update" name="data[{$langID}][title]" placeholder="Название">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="text{$langID}Update" class="col-sm-3 control-label">Описание</label>
                                    <div class="col-sm-9">
                                        <textarea name="data[{$langID}][text]" id="text{$langID}Update" rows="3" class="form-control" placeholder="Описание"></textarea>
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