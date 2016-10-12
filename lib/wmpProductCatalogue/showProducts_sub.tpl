{*Menu::getTreeByTemplate($langID, Url::get('menuID'), 'innercat')*}
{assign var=productsList value=array_chunk($products, 3, true)}
{assign var="counter" value="100"}
                {foreach $productsList as $products}
                    {foreach $products as $product}
                    <a href="{$product->getUrl()}">
				<div class="b-catalog__item column-3 wow fadeInRight" data-wow-duration="800ms"
								 data-wow-delay="{$counter}ms">
					{$counter = $counter + 100}
					<div class="img-wrap">
						<img class="responsive" src="{Image::mEncrypt('height=253&src='|cat:$product->getImage())|filesSmallGenerate}" alt="catalog"/>
					</div>
					<a class="more-link" href="{$product->getUrl()}"><span>{$product->getTitle()}</span></a>
				</div>
			</a>
                    {/foreach}            
                {/foreach}
