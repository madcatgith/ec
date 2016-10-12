    <section class="section-nav">
        <div class="container">
            {Menu::getCrumbs()}
            <h1 class="section-title">{Menu::get($langID, $menuID, 'title')}</h1>
        </div>
    </section>

    <main class="l-main">
        <div class="container">
            <section class="section section-news">
                <div class="b-news-list">
                    {foreach $contents as $content}
                    <div class="b-news-list__item">
                        <figure>
                            {if $content.hasImage}
                                <img class="responsive" src="{$content.imgurl}" alt="news"/>
                            {/if}
                            <figcaption>
                                <h3 class="b-news-list__title">{$content.title}</h3>
                                <div class="b-news-list__date">
                                    <hr/>
                                </div>
                                <p>
                                    {$content.announcement}
                                </p>
                                {if $content.another_page}
                                <!--<p>
                                    <a class="more-link" href="{$content.another_page}">{Dictionary::getUniqueWord(85)} →</a>
                                </p>-->
                                {/if}
                            </figcaption>
                        </figure>
                    </div>
                    {/foreach}
                </div>
                <!-- pagination-->
                {Url::pagination($page,$total,$onPage,4,'page',1)}
            </section>
        </div>
    </main>