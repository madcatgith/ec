<section class="section section-partners text-center">
        <h3 class="section-title">{Dictionary::GetUniqueWord(84)}</h3>
        <div class="container">
            <div class="b-partner-slider js-partner-slider">
                {foreach $contents as $content}
                    {if $content.hasImage}
                        <a href="{Url::setUrl(['lang'=>$langID,'menu'=>5])}"><img class="img-fluid" src="/image.php?{Image::mEncrypt('width=215&height=120&src='|cat:$content.imgurl)}" alt="obolon"/></a>
                    {/if}
                {/foreach}
            </div>
        </div>
    </section>
