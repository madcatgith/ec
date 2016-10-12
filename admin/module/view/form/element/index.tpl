<div class="well well-small"><strong>Список елементов формы (ID: {$formID})</strong> 
    <a class="btn btn-success btn-back" href="{$smarty.const.URL}">Вернуться к списку форм</a>
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            Добавить елемент <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            {foreach $types as $kType => $vType}
                <li><a href="{$smarty.const.URL}/{$formID}/element/insert/{$kType}">{$vType}</a></li>
            {/foreach}
        </ul>
    </div>
</div>
<table class="table table-hover table-bordered table-elements">
    <thead>
        <tr>
            <th width="10">#</th>            
            <th width="80">Тип элемента</th>
            <th>Название елемента</th>
            <th>Значение</th>
            <th width="40">Языки</th>
            <th width="150">Действия</th>
        </tr>
    </thead>
    <tbody>
        {foreach $data as $d}
            <tr data-id="{$d[0].id}">
                <td class="id">{$d[0].id}</td>
                <td>{$types[$d[0].type]}</td>
                <td>{$d[0].langed_title}</td>
                <td>{$d[0].langed_value}</td>
                <td class="langs">
                    {foreach Lang::getLanguages() as $langID => $lang}
                        {if isset($d[$langID]) && $d[$langID].isActive}
                            <a href="{$smarty.const.URL}/{$formID}/element/{$d[0].id}#lang{$langID}">{$lang.title_short}</a>
                        {elseif isset($d[$langID])}
                            <span>{$lang.title_short}</span>
                        {/if}
                    {/foreach}
                </td>
                <td>
                    <a href="{$smarty.const.URL}/{$formID}/element/{$d[0].id}"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                    <a class="removeData" data-action="form/element/delete" data-id="{$d[0].id}" href="javascript:void(0);"><i class="glyphicon glyphicon-remove"></i> Remove</a>
                    <span class="glyphicon glyphicon-resize-vertical"></span>
                </td>
            </tr>
        {/foreach}
    </tbody>
</table>