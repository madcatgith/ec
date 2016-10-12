<div class="well well-small"><strong>Список баннерных мест</strong> <a class="btn btn-success btn-back" href="{$smarty.const.URL}/insert">Добавить баннерное место</a></div>
<table class="table table-hover table-bordered table-custom">
    <thead>
        <tr>
            <th width="10">#</th>            
            <th>Название</th>
            <th>Описание</th>
            <th width="200">Действия</th>
        </tr>
    </thead>
    <tbody>
        {foreach $data as $d}
            <tr>
                <td class="id">{$d[0].id}</td>
                <td>{$d[0].title}</td>
                <td>{$d[0].text}</td>
                <td>
                    <a href="{$smarty.const.URL}/{$d[0].id}/element"><i class="glyphicon glyphicon-list-alt"></i> Elements</a>
                    <a href="{$smarty.const.URL}/{$d[0].id}"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                    <a class="removeData" data-action="banner/delete" data-id="{$d[0].id}" href="javascript:void(0);"><i class="glyphicon glyphicon-remove"></i> Remove</a>
                </td>
            </tr>
        {/foreach}
    </tbody>
</table>