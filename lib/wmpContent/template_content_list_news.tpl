<!-- main-->
<main class="l-main">
    <div class="container">
        <!-- breadcrumbs-->
        {Menu::getCrumbs()}
        <!-- content -->
        <div class="content no-pt b-news-content">
            <h2 class="section-title">{Menu::get($langID, $menuID, 'title')}</h2>
            <div class="b-news-list">
                {foreach $contents as $content}
                <div class="b-news-list__item">
                    <div class="b-news-list__item-img" data-src="/image.php?{Image::mEncrypt('width=574&height=324&src='|cat:$content.imgurl)}">
                        <img src="/image.php?{Image::mEncrypt('width=400&height=200&src='|cat:$content.imgurl)}" alt="news"/>
                    </div>
                    <div class="b-news-list__description">
                        <h3 class="b-news-list__title">
                            {$content.title}
                        </h3>
                        <div class="b-news-list__date">{$content.date}</div>
                        <div class="b-news-list__content">
                           <p>{$content.announcement}</p>
                        </div>
                        <div class="b-news-list__footer">
                            <!--<div class="b-news-list__stat">-->
                            <!--<span class="b-news-list__stat-item">-->
                            <!--<i class="icon-view pull-left"></i>-->
                            <!--1203-->
                            <!--</span>-->
                            <!--<span class="b-news-list__stat-item">-->
                            <!--<i class="icon-comment pull-left"></i>-->
                            <!--1203-->
                            <!--</span>-->
                            <!--</div>-->
                            <a href="{$content.href}" class="b-news-list__more">{Dictionary::getUniqueWord(72)}</a>
                        </div>
                    </div>
                </div>
                {/foreach}
            </div>
            <!-- pagination-->
            {Url::pagination($page,$total,$onPage,4,'page',1)}
        </div>
    </div>
</main>