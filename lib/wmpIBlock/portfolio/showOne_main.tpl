<!-- project-block -->
<div class="project-block">
	<div class="container">
		<div class="project-pagination">
			{if $data->get('prev_href')}
				<a href="{$data->get('prev_href')}" class="icon-arrow-left-sm"></a>
				{/if}
			{if $data->get('next_href')}
				<a href="{$data->get('next_href')}" class="icon-arrow-right-sm"></a>
			{/if}
		</div>
		<ul class="project-list">
			<li>
				<div class="project-heading">
					<h2>{$data->get('title')}</h2>
					<span class="label-category">{$data->get('category_title')}</span>
				</div>
				<div class="project-left">
					<div class="project-left-inner">	
						{if $data->get('image')}
							<div class="project-img">
								<a href="{$data->get('image')}" class="fancybox-trigger" rel="gallery-1">
									{htmlImageTag(['maxWidth' => 1142, 'src' => $data->get('image')], $data->get('title'))}
								</a>
								<a href="#" class="link-triangle-top"><span class="icon-dev"></span></a>
							</div>
						{/if}
						{if $data->get('image_2')}
							<div class="project-img">
								<a href="{$data->get('image_2')}" class="fancybox-trigger" rel="gallery-1">
									{htmlImageTag(['maxWidth' => 1142, 'src' => $data->get('image_2')], $data->get('title'))}
								</a>
								<a href="#" class="link-triangle-top"><span class="icon-dev"></span></a>
							</div>
						{/if}
						{if $data->get('image_3')}
							<div class="project-img">
								<a href="{$data->get('image_3')}" class="fancybox-trigger" rel="gallery-1">
									{htmlImageTag(['maxWidth' => 1142, 'src' => $data->get('image_3')], $data->get('title'))}
								</a>
								<a href="#" class="link-triangle-top"><span class="icon-dev"></span></a>
							</div>
						{/if}
					</div>
				</div>
				<div class="project-right">
					<div class="project-info">
						<div class="project-text">
							{$data->get('text')}
						</div>
						<div class="project-client">
							<strong>{Dictionary::getUniqueWord(43)}:</strong> {$data->get('client')}
						</div>
						{if $data->get('logo')}
							<a href="#" class="project-logo">
								{htmlImageTag(['maxWidth' => 298, 'src' => $data->get('logo')], $data->get('title'))}
							</a>
						{/if}
					</div>
					<div class="project-desc">
						{if {$data->get('features')}}
							<div class="project-icon">
								<span class="icon-settings"></span>
							</div>
							<i><strong>{Dictionary::getUniqueWord(44)}</strong> {Dictionary::getUniqueWord(45)}</i>
							<p>{$data->get('features')}</p>
						{/if}
						{if $data->get('date')|date_format:"%Y" > 1995}
							<span class="project-date">{$data->get('date')|date_format:"%B %Y"}</span>
						{/if}
						{if $data->get('url')}
							<div class="project-view">
								<a target="_blank" href="{$data->get('url')}" class="btn-green">{Dictionary::getUniqueWord(46)}</a>
							</div>
						{/if}
					</div>
				</div>
				<div class="project-buttons">
					<a href="{$data->get('portfolio_href')}" class="btn-border">
						<span class="icon-arrow"></span>
						<span>{Dictionary::getUniqueWord(47)}</span>
					</a>
					<a href="#" class="btn-border">
						<span class="icon-dev-sm"></span>
						<span>{Dictionary::getUniqueWord(49)}</span>
					</a>
				</div>
			</li>
		</ul>
	</div>
</div>
<!-- project-block end -->
