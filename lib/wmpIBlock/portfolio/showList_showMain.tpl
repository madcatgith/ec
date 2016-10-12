<!-- main-block -->
<div class="main-block">
	<div class="container">
		<!-- main-list -->
		<ul class="main-list">
			{foreach $data as $item name=foo}
				<li>
					<div class="main-left">
						<div class="main-title">
							<span class="number-small">{$smarty.foreach.foo.index + 1}</span>
							<h2><a href="{$item->get('href')}">{$item->get('title')}</a></h2>
						</div>
						<div class="main-info">
							<span class="label-category">{$item->get('category_title')}</span>
							{if $item->get('announce')}
								<i>{$item->get('announce')}</i>
							{/if}
							<a href="{$item->get('href')}" class="main-logo">
								{htmlImageTag(['maxWidth' => 185, 'src' => $item->get('logo')], $item->get('title'))}
							</a>
						</div>
					</div>
					<div class="main-right">
						<div class="main-img-big">
							{htmlImageTag(['maxWidth' => 468, 'src' => $item->get('image_main')], $item->get('title'))}
							<a href="{$item->get('href')}" class="link-triangle"><span class="icon-dev"></span></a>
						</div>
						<div class="main-aside">
							{if $item->get('image_main_preview')}
								<div class="main-img-small">
									{htmlImageTag(['maxWidth' => 300, 'src' => $item->get('image_main_preview')], $item->get('title'))}
								</div>
							{/if}
							{if $item->get('features')}
								<div class="main-desc">
									<div class="main-icon">
										<span class="icon-settings"></span>
									</div>
									<i><strong>{Dictionary::getUniqueWord(44)}</strong> {Dictionary::getUniqueWord(45)}</i>
									<p>{$item->get('features')}</p>
								</div>
							{/if}
						</div>
					</div>
				</li>
			{/foreach}
		</ul>
		<!-- main-list end -->	
		<!-- main-bottom -->
		<div class="main-bottom">
			<i><a href="{$data[0]->get('portfolio_href')}">{Dictionary::getUniqueWord(50)}...</a></i>
		</div>
		<!-- main-bottom end -->
	</div>
</div>
<!-- main-block end -->
