{if $images|@count>1}
{foreach $images as $image}
    <div>
        <a href="#">
            <img width="" src="/image.php?{Image::mEncrypt('width=80&height=80&src='|cat:$image.src)}" data-big-image="{$image.src}" alt="product"/>
        </a>
    </div>
{/foreach}
{/if}
