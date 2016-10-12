<!-- section slider-->
<section class="section-slider">
	<div class="b-slider-control">
		<a class="slider-trigger-prev slick-arrow" href="#" style="display: block;">prev</a>
		<a class="slider-trigger-next slick-arrow" href="#" style="display: block;">next</a>
	</div>
	<div class="main-slider">
		{foreach $data as $item}
			<div class="item">
				<img src="{$item->get('logo')}" alt="slide"/>
				<div class="description">
					<h3 class="section-title">{$item->get('title')}</h3>
					<p>
						{$item->get('announce')}
					</p>
					<p>
						{$item->get('text')}
					</p>
				</div>
			</div>
		{/foreach}
	</div>
	<div class="section-slider__content container">
		<div class="content-grid__aside">
			<div class="slide-description">
			</div>
			<div class="b-slider-control">
				<a class="slider-trigger-prev" href="#" style="display: inline-block;">prev</a>
				<a class="slider-trigger-next" href="#" style="display: inline-block;">next</a>
			</div>
		</div>
	</div>
</section>