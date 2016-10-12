<a href="#" class="cart">
    <i class="icon-cart"></i>
    <span>{Dictionary::GetUniqueWord(56)} (<b>{count($data)}</b>)</span>
    <i class="arrow-select"></i>
</a>
<div class="drop-cart">
    <div class="drop-cart-inner">
        <div class="drop-cart-inner-scroll nano">
            <div class="nano-content">
                <div class="nano-container">
                    <table>
                        <tbody>
                            {foreach $data as $item}
                                <tr>
                                    <td>
                                        <img src="{Image::mEncrypt('height=56&maxWidth=98&src='|cat:$item.info->getImage())|filesSmallGenerate}" {Image::mEncrypt('height=56&maxWidth=98&src='|cat:$item.info->getImage())|htmlSizes} alt="{$item.info->getTitle()|escape}" />	
                                    </td>
                                    <td>
                                        <h4>{$item.info->getTitle()}</h4>
                                        {if $item.info->has('color')}
                                            <p>{Dictionary::GetUniqueWord(80)}: {$item.info->getColorAttribute($item.info->get('color'), 'title')}</p>
                                        {else}
                                            <p>({$item.amount} {Dictionary::GetUniqueWord(79)})</p>
										{/if}
										{if !$item.info->get('noPrice')}<b><span class="price"><span>{$item.info->getPrice(0, false)}</span>{$item.info->getPriceZero()}</span> {Dictionary::GetUniqueWord(52)} {if $item.info->get('price_for')}<em>/ {$item.info->get('price_for')}</em>{/if}</b>{/if}	
                                    </td>
                                    <td><a href="javascript:void(0);" data-id="{$item.info->getID()}" class="icon-cross deleteFromMiniCart"></a></td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
		<div class="without-scroll">
			<div class="car-small-price">
				<div class="car-small-price-title">{Dictionary::GetUniqueWord(55)}:</div>
				<div class="car-small-price-price"><span class="price"><span>{afterPoint($shop->getTotalAmount(), 0, 0)}</span>{getPriceZero($shop->getTotalAmount())}</span>{Dictionary::GetUniqueWord(52)}</div>
			</div>
			<div class="buttons">
				<a href="{$link}" class="btn-green">{Dictionary::GetUniqueWord(54)}</a>
			</div>
		</div>
    </div>
</div>