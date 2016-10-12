{assign var=productsList value=array_chunk($products, 3, true)}
{Banner::show(1, 'preform')}
<div class="section-catalog__footer">
    <h3>{Dictionary::getUniqueWord(74)}</h3>
    <ul class="b-catalog__list">
        {foreach $productsList as $products}
            {foreach $products as $product}
                <li><a href="{$product->getUrl()}">{$product->getTitle()}</a></li>
            {/foreach}
        {/foreach}
    </ul>
</div>                
