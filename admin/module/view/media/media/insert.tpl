<div class="modal fade" id="mediaAdd" tabindex="-1" role="dialog" aria-labelledby="mediaAddLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                <h4 class="modal-title" id="mediaLabel">Добавление коллекции</h4>
            </div>
            <form id="mediaAddForm" action="{$smarty.server.REQUEST_URI}" method="post" class="form-horizontal form-offset-0" role="form">
                <input type="hidden" name="mediaInsert" value="1" />
                <div class="modal-body">
                    <div class="form-group">
                        <label for="parentID" class="col-sm-3 control-label">Расположение</label>
                        <div class="col-sm-9">
                            <select id="parentID" name="data[0][parentID]" class="form-control">
                                <option value="0">Верхний уровень</option>
                                {$options}
                            </select>
                        </div>
                    </div>
                    <ul class="nav nav-tabs langs">
                        {foreach Lang::getLanguages() as $langID => $values}
                            <li><a data-toggle="tab" href="#lang{$langID}">{$values.title}</a></li>
                        {/foreach}
                    </ul>
                    <div class="tab-content">
                        {foreach Lang::getLanguages() as $langID => $values}
                            <div class="tab-pane" id="lang{$langID}">
                                <div class="form-group">
                                    <label for="title{$langID}" class="col-sm-3 control-label">Название</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="title{$langID}" name="data[{$langID}][title]" placeholder="Название">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="text{$langID}" class="col-sm-3 control-label">Описание</label>
                                    <div class="col-sm-9">
                                        <textarea name="data[{$langID}][text]" id="text{$langID}" rows="3" class="form-control" placeholder="Описание"></textarea>
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