<div class="galleryList">
    {foreach $categories as $key => $c}
        <div class="galleryOne">
            <h4>{$c}</h4>
            {assign var=first value=array_shift($images[$key])}
            <div class="image-holder">
                <a href="{$first.src}" rel="category{$key}" class="fancybox-gallery">
                    <img alt="{$first.title|escape}" src="/image.php?{Image::mEncrypt('height=240&width=240&src='|cat:$first.src)}" />
                </a>
            </div>
            <div class="other-photos" id="category-{$key}">
                {foreach $images[$key] as $image}
                    <a href="{$image.src}" rel="category{$key}" class="fancybox-gallery">
                        <img alt="{$image.title|escape}" src="/image.php?{Image::mEncrypt('height=50&width=50&src='|cat:$image.src)}" />
                    </a>
                {/foreach}
            </div>
        </div>
    {/foreach}    
</div>
<div class="tip">{Dictionary::GetUniqueWord(658)}</div>
