<p style="font-size: 20px; font-family: Calibri, sans-serif;">Здравствуйте уважаемый(ая) <strong>{$data.name}</strong>!<br />ID Вашего заказа - <strong>{$orderID}</strong></p>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-family: Calibri, sans-serif; font-size: 14px; color: #636363; line-height: 22px;">
    <thead>
        <tr>
            <th style="border-bottom: 1px solid #DBDBDB; color: #939393; font: bold 14px/19px Calibri, sans-serif; padding: 11px 0;">{Dictionary::GetUniqueWord(461)}</th>
            <th style="border-bottom: 1px solid #DBDBDB; color: #939393; font: bold 14px/19px Calibri, sans-serif; padding: 11px 0;" width="75">{Dictionary::GetUniqueWord(240)}</th>
            <th style="border-bottom: 1px solid #DBDBDB; color: #939393; font: bold 14px/19px Calibri, sans-serif; padding: 11px 0;" width="75">{Dictionary::GetUniqueWord(269)}</th>
            <th style="border-bottom: 1px solid #DBDBDB; color: #939393; font: bold 14px/19px Calibri, sans-serif; padding: 11px 0;" width="75">{Dictionary::GetUniqueWord(79)}</th>
            <th style="border-bottom: 1px solid #DBDBDB; color: #939393; font: bold 14px/19px Calibri, sans-serif; padding: 11px 0;" width="75">{Dictionary::GetUniqueWord(202)}</th>
            <th style="border-bottom: 1px solid #DBDBDB; color: #939393; font: bold 14px/19px Calibri, sans-serif; padding: 11px 0;" width="75">{Dictionary::GetUniqueWord(274)}</th>
        </tr>
    </thead>
    <tbody>
        {assign var=total value=0}
        {foreach $items as $item}
            {assign var=product value=$products[$item.id]}
            <tr style="border-bottom: 1px solid #DBDBDB;">
                <td style="border-bottom: 1px solid #DBDBDB;">
                    {if $product->hasImage()}
                        <img style="vertical-align: middle; margin-right: 20px;" src="http://{$smarty.server.HTTP_HOST}/image.php?{Image::mEncrypt('width=64&src='|cat:$product->getImage())}" alt="{$product->getTitle()|escape}" />
                    {/if}
                    <a style="color: #6897AD; font: 15px/19px Calibri, sans-serif; text-decoration: underline;" href="http://{$smarty.server.HTTP_HOST}{$product->getUrl()}">{$product->getTitle()}</a>
                </td>
                <td style="border-bottom: 1px solid #DBDBDB; padding: 14px;">{$item.color}</td>
                <td style="border-bottom: 1px solid #DBDBDB; padding: 14px;">{$item.size}</td>
                <td style="border-bottom: 1px solid #DBDBDB; padding: 14px;">{$product->getRealPrice()} грн</td>
                <td style="border-bottom: 1px solid #DBDBDB; padding: 14px;" align="center">{$item.quantity|escape}</td>
                <td style="border-bottom: 1px solid #DBDBDB; padding: 14px;">{$product->getRealPrice() * $item.quantity} грн</td>
            </tr>
            {$total = $total + $product->getRealPrice() * $item.quantity}
        {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td style="font-size: 20px; padding: 14px;" colspan="4" align="right"><strong>{Dictionary::GetUniqueWord(275)}</strong>: {$total} грн</td>
        </tr>
    </tfoot>
</table>