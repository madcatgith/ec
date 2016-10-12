<link href="/admin/orders/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="/admin/orders/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="/admin/orders/bootstrap/css/fuelux.min.css" rel="stylesheet">
<link href="/admin/orders/bootstrap/css/fuelux-responsive.min.css" rel="stylesheet">
<link href="/admin/orders/bootstrap/css/datepicker.css" rel="stylesheet">
<script src="/admin/orders/bootstrap/js/jquery-1.10.2.min.js"></script>
<script src="/admin/orders/bootstrap/js/bootstrap.min.js"></script>
<script src="/admin/orders/bootstrap/js/bootstrap-datepicker.js"></script>
<script src="/admin/orders/bootstrap/js/orders.js"></script>
<script type="text/javascript">
    var paymentMethods = {$paymentMethods|json_encode};
</script>
<div class="fuelux">
    <h3>Заказы</h3>
    <table class="table table-bordered datagrid datagrid-stretch-header table-grid">
        <thead>
            <tr>
                <th colspan="13">
                    <span class="datagrid-header-title"><strong>Применить к выделенным:</strong></span>
                    <button class="btn" type="button" title="Новый заказ" data-range="all" data-value="0" data-action="newOrder">
                        <i class="icon icon-minus"></i> Новый заказ
                    </button>
                    <button class="btn btn-info" type="button" title="Подтвержден" data-range="all" data-value="1" data-action="confirmed">
                        <i class="icon icon-white icon-ok"></i> Подтвержден
                    </button>
                    <button class="btn btn-inverse" type="button btn-info" title="Доставляется / Вывозится" data-range="all" data-value="3" data-action="execution">
                              <i class="icon icon-white icon-shopping-cart"></i> Доставляется / Вывозится
                    </button>
                    <button class="btn btn-success" type="button" title="Выполнен" data-range="all" data-value="4" data-action="delivered">
                        <i class="icon icon-white icon-check"></i> Выполнен
                    </button>
                    <button data-action="remove" data-range="all" title="Удалить" type="button" class="btn btn-danger">
                        <i class="icon icon-white icon-remove"></i> Удалить
                    </button>
                </th>
            </tr>                
            <form action="/admin/orders/" method="get">
                <input type="hidden" value="" name="{$orders->getOnPage()}" />     
                <tr>
                    <th class="middle"><button id="collapse" type="button" class="btn btn-small"><i class="icon icon-plus">&nbsp;</i></button></th>
                    <th class="middle"><input data-checkgroup="all" type="checkbox" /></th>
                    {getTH('id', '#', 'width="35"')}
                    {getTH('name', 'ФИО')}
                    {getTH('phone', 'Телефон')}
                    {*getTH('country', 'Страна')*}
                    {getTH('city', 'Город')}
                    {*getTH('address', 'Адрес доставки')}
                    {getTH('paymentMethod', 'Тип оплаты')}
                    {getTH('paymentStatus', 'Статус оплаты')*}
                    {getTH('deliveryType', 'Тип доставки')}
                    {getTH('deliveryStatus', 'Статус заказа')}
                    {getTH('orderCreated', 'Дата заказа')}
                    {*getTH('orderDelivered', 'Дата выполнения')*}
                    {getTH('total', 'Сумма')}
                    <th>Действия</th>
                </tr>
                <tr>
                    <td class="filters" width="36">&nbsp;</td>
                    <td class="filters" width="13">&nbsp;</td>
                    <td class="filters" width="70">{getFilters('id', 'text', 'input-mini')}</td>
                    <td class="filters">{getFilters('name', 'text', 'input-medium')}</td>
                    <td class="filters">{getFilters('phone', 'text', 'input-medium')}</td>
                    {*<td class="filters">{getFilters('country', 'text', 'input-medium')}</td>*}
                    <td class="filters">{getFilters('city', 'text', 'input-medium')}</td>
                    {*<td class="filters">{getFilters('address', 'text', 'input-medium')}</td>
                    <td class="filters" width="150" style="text-align: center;">{getFilters('paymentMethod', 'select', $paymentMethods)}</td>
                    <td class="filters" width="122" style="text-align: center;">{getFilters('paymentStatus', 'select', $paymentStatus)}</td>*}
                    <td class="filters" width="150" style="text-align: center;">{getFilters('deliveryMethod', 'select', $deliveryMethods)}</td>
                    <td class="filters" width="150" style="text-align: center;">{getFilters('deliveryStatus', 'select', $deliveryStatus)}</td>
                    <td class="filters" width="117">{getFilters('orderCreated', 'date')}</td>
                    {*<td class="filters" width="117">{getFilters('orderDelivered', 'date')}</td>*}
                    <td class="filters" width="120">{getFilters('total', 'integer')}</td>
                    <td class="filters" width="88" style="text-align: center; vertical-align: middle;">
                        <button type="submit" class="btn btn-primary">
                            <i class="icon icon-white icon-ok"></i>
                        </button>
                    </td>
                </tr>
            </form>
        </thead>
        <tbody>
            <form action="{$smarty.server.REQUEST_URI}" method="get">
                {foreach $orders->getRows() as $order name=index}
                    <tr class="infoBorder nr statusBG{$order.deliveryStatus}">
                        <td>
                            <button type="button" data-link="{$order.id}" class="btn btn-small"><i class="icon icon-plus">&nbsp;</i></button>
                        </td>
                        <td>
                            <input type="checkbox" name="checkgroup[order][{$order.id}]" data-value="{$order.id}" />
                        </td>
                        <td>{$order.id}</td>
                        <td>{$order.name}</td>
                        <td>{$order.phone}</td>
                        {*<td>{$order.country}</td>*}
                        <td>{$order.city}</td>
                        {*<td>{$order.address}</td>
                        <td>{$paymentMethods[$order.paymentMethod]}</td>
                        <td>{$paymentStatus[$order.paymentStatus]}</td>*}
                        <td>{if $order.deliveryType}{$deliveryMethods[$order.deliveryType]}{else}-{/if}</td>
                        <td class="filters2">
                            <select name="deliveryStatus" data-id="{$order.id}" class="input-medium">
                                {foreach $deliveryStatus as $key => $status}
                                    <option value="{$key}"{if $key eq $order.deliveryStatus} selected="selected"{/if}>{$status}</option>
                                {/foreach}
                            </select>
                        </td>                
                        <td>{date('Y.m.d H:i', strtotime($order.date))}</td>
                        {*<td>
                            {if $order.orderDelivered neq '0000-00-00 00:00:00'}
                                {date('Y.m.d H:i', strtotime($order.orderDelivered))}
                            {else}
                                &nbsp;
                            {/if}
                        </td>*}
                        <td>{$order.amount} UAH</td>
                        <td class="filters3" style="text-align: center;">
                            {*if $order.deliveryStatus eq 4}
                                {getTimerBadge($order.timerSecond)}
                            {else}
                                {getTimer($order.timerSecond, $order.timerDate, $order.isRunning, $order.id)}
                            {/if*}
                            <button data-value="{$order.id}" data-action="remove" data-range="one" title="Удалить" type="button" class="btn btn-danger">
                                <i class="icon icon-white icon-remove"></i>
                            </button>
                        </td>
                    </tr>
                    <tr style="display: none;" data-link="{$order.id}" class="hiddenBoder statusBG{$order.deliveryStatus}" >
                        <td colspan="3" style="padding: 2px 0 0; text-align: left; vertical-align: middle;">
                            &nbsp;
                        </td>
                        <td colspan="12" style="padding: 0;">
                            <table style="margin: 0; border: 0 none; width: 100%;">
                                <thead>
                                    <tr class="statusBG{$order.deliveryStatus}">
                                        <td style="border: 0 none;" width="12">
                                            <input data-checkgroup="inner" type="checkbox" />
                                        </td>
                                        <th width="35">#</th>
                                        <th>Название</th>
                                        <th>Цена</th>
                                        <th width="56">Ко-во</th>
                                        <th>Общая</th>
                                        <th width="325">Комментарий</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach $order.items as $item name=itemIndex}
                                        <tr class="nr">
                                            <td style="border-left: 0 none; text-align: center;" width="13">
                                                <input name="checkgroup[cart][{$item.info->get('id')}]" type="checkbox" data-value="{$item.info->get('id')}" />
                                            </td>
                                            <td>{$item.info->get('id')}</td>
                                            <td>
                                                {if $item.info->getImage()}<img src="{Image::mEncrypt('height=90&maxWidth=90&src='|cat:$item.info->getImage())|filesSmallGenerate}" {Image::mEncrypt('height=90&maxWidth=90&src='|cat:$item.info->getImage())|htmlSizes} alt="{$item.info->get('title')|escape}" />{/if}
                                                {$item.info->get('title')}
                                            </td>
                                            <td>{$item.info->getPrice()}{if $item.info->get('price_for')}<br />/ {$item.info->get('price_for')}{/if}</td>
                                            <td>{$item.amount}</td>
                                            <td>{if $item.info->get('price_for_digit')}{$item.amount / $item.info->get('price_for_digit') * $item.info->getPrice()}{else}{$item.amount * $item.info->getPrice()}{/if}</td>
                                            {if $smarty.foreach.itemIndex.iteration eq 1}
                                                <td class="bg{$smarty.foreach.index.iteration%2}" rowspan="{count($order.items)}">{if $order.comment}{$order.comment|nl2br}{else}&nbsp;{/if}</td>
                                            {/if}
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        </td>
                    </tr>
                {/foreach}
            </form>
        </tbody>   
        <tfoot class="table table-bordered datagrid datagrid-stretch-footer">
            <tr class="infoBorder">
                <th colspan="15">
                    <form action="{$smarty.server.REQUEST_URI}" method="get">                      
                        <div style="visibility: visible;" class="datagrid-footer-left">
                            <div class="grid-controls">
                                <span>
                                    <span class="grid-start">{$orders->getOffset() + 1}</span> -
                                    <span class="grid-end">
                                        {if $orders->getOffset() + $orders->getOnPage() lt $orders->getNumRows()}
                                            {$orders->getOffset() + $orders->getOnPage()}
                                        {else}
                                            {$orders->getNumRows()}
                                        {/if}
                                    </span> из
                                    <span class="grid-count">{$orders->getNumRows()} {declension($orders->getNumRows(), ['запись', 'записи', 'записей'])}</span>
                                </span>
                                <div data-resize="auto" class="select grid-pagesize">
                                    <select name="onPage" id="onPage">
                                        {foreach [10, 20, 30, 50, 75, 100] as $val}
                                            <option{if $orders->getOnPage() eq $val} selected="selected"{/if} value="{$val}">{$val}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <span>Записей на страницу</span>
                            </div>
                        </div>
                        <div style="visibility: visible;" class="datagrid-footer-right">
                            <div class="grid-pager">
                                {pagination($page + 1, $orders->getNumRows(), $orders->getOnPage())}
                            </div>
                        </div>                                      
                    </form>
                </th>
            </tr>
        </tfoot>
    </table>
</div>                    