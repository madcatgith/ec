<!-- portfolio-block -->
<div class="portfolio-block">
	<div class="container">
		<ul class="portfolio-list">
			{foreach $data as $item}
				<li>
					<a href="{$item->get('href')}" class="link-wrap">
						<span class="portfolio-img">
							 <img src="/image.php?{Image::mEncrypt('width=246&height=246&src='|cat:$item->get('preview'))}" alt="{$item->get('title')|escape}" {Image::mEncrypt('width=246&height=246&src='|cat:$item->get('preview'))|htmlSizesSmall} />
						</span>
						<span class="portfolio-desc">
							<strong class="portfolio-name">{$item->get('title')}</strong>
							<span class="label-category">{$item->get('category_title')}</span>
						</span>
					</a>
				</li>
			{/foreach}
		</ul>
	</div>
</div>
{*if $instance->getRowCount() gt $instance->getOnPage()}
    {Url::pagination($instance->getPage(), $instance->getRowCount(), $instance->getOnPage(), 4, 'page', 1)}
{/if*}
<div class="info-block">
	<div class="container">
	<!-- service-block -->
		{Controller::run('iblock/contacts/main')}
	</div>
</div>
{Controller::run('blocks/consultation')}