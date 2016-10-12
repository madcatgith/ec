{foreach $elements as $items}
	<section class="section-promo valign-wrapper hide-on-tablet"
			style="
				background-color: rgba(255, 255, 255, 0.65);
				position: relative;
				overflow: hidden;
			">
		<div class="container valign-wrapper--justify">
			<div class="section-promo__logo">
				<img class="responsive" data-2x="../../images/common/{$logo}@2x.png" src="{Config::get('logo')}" alt="{Config::get('title')|escape}"/>
			</div>
			<h1 class="section-promo__title">{Config::get('postal')}</h1>
		</div>
		<div
			style="
				position: absolute;
				top: 0;
				right: 0;
				left: 0;
				bottom: 0;
				z-index: -1;
			">
			<video class="section-promo valign-wrapper hide-on-tablet" loop="true" autoplay muted="true"
				style="
					min-height: 100%;
					min-width: 100%;
					width: auto;
					height: auto;
				">
				<source src="{$items.image}" type="video/mp4">
			</video>
		</div>
	</section>
{/foreach}