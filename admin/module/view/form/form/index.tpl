<div class="well well-small"><strong>Список форм</strong> <a class="btn btn-success btn-back" href="{$smarty.const.URL}/insert">Добавить форму</a></div>
<table class="table table-hover table-bordered table-custom">
    <thead>
        <tr>
            <th width="10">#</th>            
            <th>Название формы</th>
            <th width="70">Оповещение E-mail</th>
            <th width="70">Защита от роботов</th>
            <th>Сообщение после отправки</th>
            <th width="40">Языки</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        {foreach $data as $d}
            <tr>
                <td class="id">{$d[0].id}</td>
                <td>{$d[0].langed_title}</td>
                <td><input type="checkbox" disabled {if $d[0].isSend} checked="checked"{/if} /></td>
                <td><input type="checkbox" disabled {if $d[0].hasCaptcha} checked="checked"{/if} /></td>
                <td>{$d[0].langed_message}</td>
                <td class="langs">
                    {foreach Lang::getLanguages() as $langID => $lang}
                        {if isset($d[$langID])}
                            <a href="{$smarty.const.URL}/{$d[0].id}#lang{$langID}">{$lang.title_short}</a>
                        {/if}
                    {/foreach}
                </td>
                <td>
                    <a href="{$smarty.const.URL}/{$d[0].id}/view"><i class="glyphicon glyphicon-eye-open"></i> View</a>
                    <a href="{$smarty.const.URL}/{$d[0].id}/element"><i class="glyphicon glyphicon-list-alt"></i> Elements</a>
                    <a href="{$smarty.const.URL}/{$d[0].id}"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                    <a class="removeData" data-action="form/delete" data-id="{$d[0].id}" href="javascript:void(0);"><i class="glyphicon glyphicon-remove"></i> Remove</a>
                </td>
            </tr>
        {/foreach}
    </tbody>
</table>