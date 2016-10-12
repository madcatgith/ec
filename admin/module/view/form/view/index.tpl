<div class="well well-small"><strong>Просмотр записей формы (ID: {$formID})</strong> <a class="btn btn-success btn-back" href="{$smarty.const.URL}">Вернуться к списку</a></div>
{foreach $records as $record}
    <div class="panel panel-default panel-view">
        <div class="panel-heading">Запись #{$record.id} ({$record.date}) | {Lang::get($record.langID, 'title_short')} | IP: {$record.ip} | Country: {$record.country} <a class="glyphicon glyphicon-remove removeData" data-action="form/view/delete" data-id="{$record.id}" href="javascript:void(0);"></a></div>
        <div class="panel-body">
            {foreach $record.fields as $field}
                <div><strong>{$field.title}:</strong> {$field.value}</div>
            {/foreach}
        </div>
    </div>
{/foreach}