    <ul class="honors-list">
        {foreach $images as $image name=i}
            <li>
                <a rel="contentGallery" href="{$image.src}">
                    <img alt="{$image.title|escape}" src="/image.php?{Image::mEncrypt('quality=90&height=129&width=259&src='|cat:$image.src)}" width="259" height="129" />
                </a>
            </li>
        {/foreach}
    </ul>
    <div class="tip">{Dictionary::GetUniqueWord(659)}</div>