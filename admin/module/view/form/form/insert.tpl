<div class="col-sm-9">
    <div class="well well-small"><strong>Добавление формы</strong> <a class="btn btn-success btn-back" href="{$smarty.const.URL}">Вернуться к списку</a></div>
    {if isset($error)}<div class="alert alert-error">{$error}</div>{/if}
    {if isset($success)}<div class="alert alert-success">{$success}</div>{/if}
    <form action="{$smarty.server.REQUEST_URI}" method="post" class="form-horizontal form-offset-0" role="form">
        <div class="form-group">
            <div class="col-sm-10">
                <div class="checkbox">
                    <label for="isSend">
                        <input type="checkbox" id="isSend" value="1" name="data[0][isSend]"> Отправлять уведомление на почту?
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-10">
                <div class="checkbox">
                    <label for="hasCaptcha">
                        <input type="checkbox" id="hasCaptcha" value="1" name="data[0][hasCaptcha]"> Добавить защиту от роботов (Captcha)
                    </label>
                </div>
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
                        <label for="title{$langID}" class="col-sm-2 control-label">Название формы</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="title{$langID}" name="data[{$langID}][title]" placeholder="Название формы">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message{$langID}" class="col-sm-2 control-label">Сообщение после отправки формы</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="message{$langID}" name="data[{$langID}][message]" rows="3" placeholder="Сообщение после отправки формы"></textarea>
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>
        <button type="submit" class="btn btn-primary">Отправить</button>
    </form>
</div>