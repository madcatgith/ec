<div class="well well-small"><strong>Список елементов баннера (ID: {$bannerID})</strong> 
    <a class="btn btn-success btn-back" href="{$smarty.const.URL}">Вернуться к списку баннеров</a>
    <a class="btn btn-success btn-back" href="{$smarty.const.URL}/{$bannerID}/element/insert">Добавить элемент</a>
</div>
<table class="table table-hover table-bordered table-elements">
    <thead>
        <tr>
            <th width="10">#</th>       
            <th width="150">Название елемента</th>
            {if $banner[0].hasFilter}
                <th width="500">Фильтр</th>
            {/if}
            <th width="40">Языки</th>
            <th width="100">Действия</th>
        </tr>
    </thead>
    <tbody>
        {foreach $data as $d}
            <tr data-id="{$d[0].id}">
                <td class="id">{$d[0].id}</td>
                <td width="150">{$d[1].title}</td>
                {if $banner[0].hasFilter}
                    <td>
                        {if !empty($d[0].filter)}
                            <ul>
                                {foreach $d[0].filter as $type => $filter}
                                    <li>Тип "{$types[$type]}": 
                                        {if $type eq 'menuType'}
                                            {$filter}
                                        {else}
                                            <ul>
                                                {foreach $filter as $f}
                                                    <li><i>Меню</i>: {$f.title};<br/> <i>Элементы</i>: {$f.values}</li>
                                                {/foreach}                                                
                                            </ul>
                                        {/if}
                                    </li>
                                {/foreach}
                            </ul>
                        {/if}
                    </td>
                {/if}
                <td class="langs">
                    {foreach Lang::getLanguages() as $langID => $lang}
                        {if isset($d[$langID]) && $d[$langID].active}
                            <a href="{$smarty.const.URL}/{$bannerID}/element/{$d[0].id}#lang{$langID}">{$lang.title_short}</a>
                        {elseif isset($d[$langID])}
                            <span>{$lang.title_short}</span>
                        {/if}
                    {/foreach}
                </td>
                <td>
                    <a href="{$smarty.const.URL}/{$bannerID}/element/{$d[0].id}"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                    <a class="removeData" data-action="banner/element/delete" data-id="{$d[0].id}" href="javascript:void(0);"><i class="glyphicon glyphicon-remove"></i> Remove</a>
                    <span class="glyphicon glyphicon-resize-vertical"></span>
                </td>
            </tr>
        {/foreach}
    </tbody>
</table>