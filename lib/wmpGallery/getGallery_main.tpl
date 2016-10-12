{assign var=images value=array_chunk($images, 6, true)}
<h2>Фото та відео галерея</h2>
<div class="main-photo">
	<ul id="mainPhoto">
		{foreach $images as $chunk}
			<li>
				{foreach $chunk as $image}
					<div class="main-photo_one">
						<a href="{filesSmallGenerate(['maxWidth' => 800, 'src' => $image.src], false)}" class="fancybox">
							{htmlImageTag(['width' => 83, 'height' => 76, 'src' => $image.src], $image.title)}
						</a>
					</div>
				{/foreach}
			</li>
		{/foreach}
	</ul>
</div>