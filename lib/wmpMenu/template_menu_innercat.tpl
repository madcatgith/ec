
		{foreach $menuArray as $item}
			<a href="{$item.href}">
				<div class="b-catalog__item column-3 wow fadeInRight" data-wow-duration="800ms">
					<div class="img-wrap">
						<img class="responsive" src="{$item.imgurl}" alt="catalog"/>
					</div>
					<a class="more-link" href="{$item.href}"><span>{$item.title}</span></a>
				</div>
			</a>
		{/foreach}