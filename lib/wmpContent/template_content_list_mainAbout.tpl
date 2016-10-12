<section class="section-promo">
    {foreach $contents as $content}
        <div class="section-promo__row">
            <div class="section-promo__img" data-image-src="{$content.imgurl}">
                <img class="responsive" src="{$content.imgurl}" alt="выгода для вас"/>
            </div>
            <div class="section-promo__article">
                <h2 class="section-title">{$content.title}</h2>
                <p>
                    {$content.announcement}
                </p>
                <p>
                    <a class="btn btn-fix" href="{Url::setUrl(['lang'=>$langID,'menu'=>11])}">{Dictionary::GetUniqueWord(86)}</a>
                </p>
            </div>
        </div>
    {/foreach}
</section>
