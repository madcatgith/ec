<div class="col-sm-9">
    <div class="well well-small"><strong>Редактирование баннерного места (ID: {$data[0].id})</strong> <a class="btn btn-success btn-back" href="{$smarty.const.URL}">Вернуться к списку</a></div>
    {if isset($error)}<div class="alert alert-error">{$error}</div>{/if}
    {if isset($success)}<div class="alert alert-success">{$success}</div>{/if}
    <form action="{$smarty.server.REQUEST_URI}" method="post" class="form-horizontal form-offset-0" role="form">
        <input type="hidden" name="data[0][id]" value="{$data[0].id}" />
        <div class="form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-10">
                <div class="checkbox">
                    <label for="active">
                        <input type="checkbox" id="active" value="1" name="data[0][active]"{if $data[0].active} checked="checked"{/if}> Активность
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-10">
                <div class="checkbox">
                    <label for="hasFilter">
                        <input type="checkbox" id="hasFilter" value="1" name="data[0][hasFilter]"{if $data[0].hasFilter} checked="checked"{/if}> Использовать фильтр показа?
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="title" class="col-sm-2 control-label">Название</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="title" name="data[0][title]" placeholder="Название" value="{$data[0].title}">
            </div>
        </div>
        <div class="form-group">
            <label for="text" class="col-sm-2 control-label">Описание</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="text" name="data[0][text]" rows="3" placeholder="Описание">{$data[0].text}</textarea>
            </div>
        </div>  
        <button type="submit" class="btn btn-primary">Отправить</button>
    </form>
</div>