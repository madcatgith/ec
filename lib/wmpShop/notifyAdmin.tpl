<p style="font-size: 20px;"><strong>Новый заказ #{$orderID}!</strong></p><br />
<b>{Dictionary::GetUniqueWord(63)}:</b> {$info.name}<br />
<b>{Dictionary::GetUniqueWord(64)}:</b> {$info.email}<br />
<b>{Dictionary::GetUniqueWord(65)}:</b> {$info.phone}<br />
<b>{Dictionary::GetUniqueWord(66)}:</b> {$info.city}<br />
{if $info.comment}<b>{Dictionary::GetUniqueWord(67)}:</b> {$info.comment}<br />{/if}
<b>{Dictionary::GetUniqueWord(108)}:</b> {$info.deliveryType}<br />
{*<b>{Dictionary::GetUniqueWord(68)}:</b> {if $info.deliveryType}Да{else}Нет{/if}<br />*}
<br />
<table width="100%" border="1">
    <tr>
        <td>ID</td>
        <td>Название</td>
        <td>Цена</td>
        <td>Количество</td>
        <td>Сума</td>
    </tr>
    {foreach $data as $productID => $product}
        <tr>      
            <td>{$productID}</td>
            <td>
                {$product.info->getTitle()}
                {if $product.info->get('color')}
                    (Цвет: {$product.info->getColorAttribute($product.info->get('color'), 'title')})
                {/if}
            </td>
            <td>{if $product.info->getPrice()}{$product.info->getPrice()} грн. {if $product.info->get('price_for')}/ {$product.info->get('price_for')}{/if}{/if}</td>
            <td>{$product.amount}</td>
            <td>{$product.summary}</td>    
        </tr>
    {/foreach}
    <tr>
        <td colspan="4" style="text-align: right;">Общая сума:</td>
        <td>{$shop->getTotalAmount()} грн.</td>
    </tr>
</table>