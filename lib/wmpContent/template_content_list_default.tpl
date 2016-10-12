<div class="container">
<div class="content content-grid">
{foreach $contents as $content}
	<div class="b-main-news__item column-3 wow fadeInRight" data-wow-duration="800ms"
			data-wow-delay="100ms" style="margin: 50px;">
		<div class="img-wrap">
			{if $content.hasImage}
				<img class="responsive" src="{$content.imgurl}" alt="news"/>
			{/if}
		</div>
		<h4>{$content.title}</h4>
		<p class="b-main-news__preview-text">
			{$content.text}
		</p>
	</div>
{/foreach}
</div>
</div>