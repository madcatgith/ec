<section class="section-nav">
    <div class="container">
        {Menu::getCrumbs()}
        <h1 class="section-title">{Menu::get($langID, $menuID, 'title')}</h1>
    </div>
</section>

<main class="l-main">
    <!--<div class="container">-->
        <section class="section-promo">
            {foreach $contents as $content}
                <div class="section-promo__row">
                    <div class="section-promo__img" data-image-src="{$content.imgurl}">
                        <img class="responsive" src="{$content.imgurl}" alt="выгода для вас"/>
                    </div>
                    <div class="section-promo__article">
                        <h2 class="section-title">{$content.title}</h2>
                        <p>
                            {$content.text}
                        </p>
                    </div>
                </div>
            {/foreach}
        </section>
    <!--</div>-->
</main>