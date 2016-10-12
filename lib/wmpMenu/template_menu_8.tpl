    <div class="container container-main">
        
    <div class="content content-grid">
        <h1 class="section-title">Сферы применения</h1>
<div class="b-catalog column">
	{assign var="counter" value="100"}
	{foreach $menuArray as $item}
		<div class="b-catalog__item column-3 wow fadeInRight" data-wow-duration="800ms"
                data-wow-delay="{$counter}ms">
			{$counter = $counter + 300}
            <!--<div class="img-wrap">-->
			<div>
                <!--<img class="responsive" src="/image.php?{Image::mEncrypt('width=500&height=400&src='|cat:$item.imgurl)}" alt="{$item.title}"/>-->
                <img class="responsive spheres" src="{$item.imgurl}" alt="{$item.title}"/>
            </div>
            <a class="more-link" href="{*$item.href*}"><span>{$item.title}</span></a>
        </div>
	{/foreach}
</div>
    </div>
</div>