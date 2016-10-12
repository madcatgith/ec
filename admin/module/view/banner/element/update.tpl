<div class="col-sm-9">
    <div class="well well-small"><strong>Добавление элемента баннера (ID: {$data[0].bannerID})</strong> <a class="btn btn-success btn-back" href="{$smarty.const.URL}/{$data[0].bannerID}/element">Вернуться к списку елементов</a></div>
    {if isset($error)}<div class="alert alert-error">{$error}</div>{/if}
    {if isset($success)}<div class="alert alert-success">{$success}</div>{/if}
    <form action="{$smarty.server.REQUEST_URI}" method="post" class="form-horizontal form-offset-0" role="form">
        <input type="hidden" name="data[0][id]" value="{$data[0].id}" />
        <input type="hidden" name="data[0][bannerID]" value="{$data[0].bannerID}" />
        <input type="hidden" name="data[0][sort]" value="{$data[0].sort}" />
        <div class="form-group">
            <div class="col-md-12">
                <label class="col-sm-3 control-label" for="image">Картинка</label>
                <div class="col-sm-9 input-group">
                    <input class="form-control" id="image" name="data[0][image]" placeholder="Картинка" type="text" value="{$data[0].image}">
                    <div class="input-group-btn">
                        <a style="margin-left: 0;" href="javascript:void(0);" onclick="newwin2('/admin/files.php?show=jqGrid&amp;obj=image', 720, 520); return false;" class="btn btn-default">Обзор</a>
                    </div>
                </div>
            </div>
        </div>
        {if $banner[0].hasFilter}
            <div class="form-group">
                <div style="display:none;" id="filterTpl">
                    <div class="input-group col-sm-12 filterHolder">
                        <div class="type col-sm-3">
                            <select name="data[0][filter][X][type]" class="form-control type-select" placeholder="Тип фильтра">
                                <option value="">Тип фильтра</option>
                                    {foreach $types as $kType => $vType}
                                        <option value="{$kType}">{$vType}</option>
                                    {/foreach}
                            </select> 
                        </div>
                        <div class="menuType col-sm-3">
                            <select name="data[0][filter][X][menu][]" class="form-control" multiple="multiple"></select>
                        </div>
                        <div class="contentType col-sm-3">
                            <select name="data[0][filter][X][content][]" class="form-control col-sm-3" multiple="multiple"></select>
                        </div>
                        <div class="productType col-sm-3">
                            <select name="data[0][filter][X][product][]" class="form-control col-sm-3" multiple="multiple"></select>
                        </div>
                        <div class="iblockType col-sm-3">
                            <select name="data[0][filter][X][iblock][]" class="form-control col-sm-3" multiple="multiple"></select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="col-sm-3 control-label" for="filter">Фильтр показа</label>
                    <div class="col-sm-9 else-holder" id="filter">
                        {assign var=cf value=count($data[0].filter)}
                        {if $cf}
                            {foreach $data[0].filter as $type => $menuIDs name=i}
                                <div class="input-group col-sm-12 filterHolder updateFilter" data-filter="{$smarty.foreach.i.index}">
                                    <div class="type col-sm-3">
                                        <select name="data[0][filter][{$smarty.foreach.i.index}][type]" class="form-control type-select" placeholder="Тип фильтра">
                                            <option value="">Тип фильтра</option>
                                            {foreach $types as $kType => $vType}
                                                <option value="{$kType}"{if $kType eq $type} selected="selected"{/if}>{$vType}</option>
                                            {/foreach}
                                        </select> 
                                    </div>
                                    <div class="menuType col-sm-3 active">
                                        <select name="data[0][filter][{$smarty.foreach.i.index}][menu][]" class="form-control" multiple="multiple">
                                            {foreach $menu->actionSelect(0) as $mData}
                                                <option value="{$mData.id}"{if isset($menuIDs[$mData.id])} selected="selected"{/if}>{$mData.title}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                    <div class="contentType col-sm-3{if $type eq 'contentType'} active{/if}">
                                        <select name="data[0][filter][{$smarty.foreach.i.index}][content][]" class="form-control col-sm-3" multiple="multiple">
                                            {if $type eq 'contentType'}
                                                {assign var=cData value=$content->actionSelect(0)}
                                                {foreach $menuIDs as $menuID => $menuData}
                                                    {foreach $cData[$menuID] as $cValue}
                                                        <option value="{$cValue.id}"{if in_array($cValue.id, $menuData)} selected="selected"{/if}>{$cValue.title}</option>
                                                    {/foreach}
                                                {/foreach}
                                            {/if}
                                        </select>
                                    </div>
                                    <div class="productType col-sm-3{if $type eq 'productType'} active{/if}">
                                        <select name="data[0][filter][{$smarty.foreach.i.index}][product][]" class="form-control col-sm-3" multiple="multiple"></select>
                                    </div>
                                    <div class="iblockType col-sm-3{if $type eq 'iblockType'} active{/if}">
                                        <select name="data[0][filter][{$smarty.foreach.i.index}][iblock][]" class="form-control col-sm-3" multiple="multiple">
                                            {if $type eq 'iblockType'}
                                                {assign var=ibData value=$iblock->findInMenu(0)}
                                                {foreach $menuIDs as $menuID => $menuData}
                                                    {foreach $ibData[$menuID] as $cValue}
                                                        <option value="{$cValue.id}"{if in_array($cValue.id, $menuData)} selected="selected"{/if}>{$cValue.title}</option>
                                                    {/foreach}
                                                {/foreach}
                                            {/if}                                        
                                        </select>
                                    </div>
                                </div>                                
                            {/foreach}
                        {/if}
                        <div class="input-group col-sm-12 filterHolder" data-filter="{$cf}">
                            <div class="type col-sm-3">
                                <select name="data[0][filter][{$cf}][type]" class="form-control type-select" placeholder="Тип фильтра">
                                    <option value="">Тип фильтра</option>
                                    {foreach $types as $kType => $vType}
                                        <option value="{$kType}">{$vType}</option>
                                    {/foreach}
                                </select> 
                            </div>
                            <div class="menuType col-sm-3">
                                <select name="data[0][filter][{$cf}][menu][]" class="form-control" multiple="multiple"></select>
                            </div>
                            <div class="contentType col-sm-3">
                                <select name="data[0][filter][{$cf}][content][]" class="form-control col-sm-3" multiple="multiple"></select>
                            </div>
                            <div class="productType col-sm-3">
                                <select name="data[0][filter][{$cf}][product][]" class="form-control col-sm-3" multiple="multiple"></select>
                            </div>
                            <div class="iblockType col-sm-3">
                                <select name="data[0][filter][{$cf}][iblock][]" class="form-control col-sm-3" multiple="multiple"></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9 else-holder">
                        <button type="button" class="btn btn-default btn-ok" id="filterAdd" disabled="disabled">Добавить фильтр</button>
                    </div>
                </div>
            </div>
        {/if}
        <ul class="nav nav-tabs langs">
            {foreach Lang::getLanguages() as $langID => $values}
                <li><a data-toggle="tab" href="#lang{$langID}">{$values.title}</a></li>
            {/foreach}
        </ul>
        <div class="tab-content">
            {foreach Lang::getLanguages() as $langID => $values}
                <div class="tab-pane" id="lang{$langID}">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                            <div class="checkbox">
                                <label for="active{$langID}">
                                    <input type="checkbox" id="active{$langID}" value="1" name="data[{$langID}][active]"{if $data[$langID].active} checked="checked"{/if}> Активность
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="href{$langID}" class="col-sm-3 control-label">Ссылка</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="href{$langID}" name="data[{$langID}][href]" placeholder="Ссылка" value="{$data[$langID].href}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title{$langID}" class="col-sm-3 control-label">Название</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="title{$langID}" name="data[{$langID}][title]" placeholder="Название" value="{$data[$langID].title}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="text{$langID}" class="col-sm-3 control-label">Текст</label>
                        <div class="col-sm-9">
                            <textarea name="data[{$langID}][text]" id="text{$langID}" rows="6" class="form-control">{$data[$langID].text}</textarea>
                            <button onclick="window.open('/admin/ckeditor/?name=text{$langID}', 'CKEditor', 'resizable=yes,width=1200,height=600,left=30,top=30');" class="btn btn-default col-md-12" type="button">HTML редактор</button>
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>
        <button type="submit" class="btn btn-primary">Отправить</button>
    </form>
</div>
<script type="text/javascript">
    var data = {
        'menuType': {$menu->actionSelect()},
        'contentType': {$content->actionSelect()},
        'iblockType': {$iblock->findInMenu()}, 
        'productType': {
        }
    };
</script>