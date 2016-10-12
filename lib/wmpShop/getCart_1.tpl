<div class="cart-table">
    <table>
        <tbody>
            {foreach $data as $item}
                <tr>
                    <td class="product-image">
                        <img src="{Image::mEncrypt('height=80&maxWidth=80&src='|cat:$item.info->getImage())|filesSmallGenerate}" {Image::mEncrypt('height=80&maxWidth=80&src='|cat:$item.info->getImage())|htmlSizes} alt="{$item.info->getTitle()|escape}" />	
                    </td>
                    <td>
                        <h4>{$item.info->getTitle()}</h4>
                        {if $item.info->has('color')}
                            <p>{Dictionary::GetUniqueWord(80)}: {$item.info->getColorAttribute($item.info->get('color'), 'title')}</p>
                        {/if}
                        {if !$item.info->get('noPrice')}<b><span class="price"><span>{$item.info->getPrice(0, false)}</span>{$item.info->getPriceZero()}</span> {Dictionary::GetUniqueWord(52)} {if $item.info->get('price_for')}<em>/ {$item.info->get('price_for')}</em>{/if}</b>{/if}	
                        
                    </td>
                    <td class="product-select">
                        {if !$item.info->has('color')}
                            <div class="wrap-select">
                                <select data-id="{$item.info->getID()}" data-action="changeQuantity" name="amount[{$item.info->getID()}]" data-hint="{Dictionary::GetUniqueWord(79)}">
                                    <option></option>
                                    {foreach getForeachByDigit($item.info->getDigitForeach()) as $row name=i}
                                        <option value="{$row}"{if $row eq $item.amount} selected="selected"{/if}>{$row}</option>
                                    {/foreach}
                                </select>
                            </div>           
                        {/if}
                    </td>
                    <td class="product-summary">
                        {if $item.summary}<span class="price"><span>{afterPoint($item.summary, 0, 0)}</span>{getPriceZero($item.summary)}</span>{Dictionary::GetUniqueWord(52)}{/if}
                    </td>
                    <td><a href="javascript:void(0);" data-id="{$item.info->getID()}" class="icon-cross deleteFromCart"></a></td>
                </tr>
            {/foreach}
        </tbody>
        <tfoot>
            <tr class="no-border total">
                <td><strong>{Dictionary::GetUniqueWord(55)}:</strong></td>
                <td colspan="2"><span class="price"><span>{afterPoint($shop->getTotalAmount(), 0, 0)}</span>{getPriceZero($shop->getTotalAmount())}</span>{Dictionary::GetUniqueWord(52)}</td>
            </tr>
        </tfoot>
    </table>
</div>