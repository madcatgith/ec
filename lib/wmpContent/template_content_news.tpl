<section class="section-nav">
        <div class="container">
            {Menu::getCrumbs()}
            <h1 class="section-title">{$title|escape}</h1>
        </div>
</section>
    
<main class="l-main">
        <div class="container">
            <section class="section section-article">
                <article class="wysiwyg">
                    <p>
                        <img class="responsive" src="{$imgurl|escape}" alt="post"/>
                    </p>
                    {$text}
                </article>
                <div class="row-btn text-center">
                    <a class="btn btn-flat m-red m-wide" href="{Menu::get($langID,$menuID,"href")}">{Dictionary::getUniqueWord(73)}</a>
                </div>
            </section>
        </div>
    </main>

<!-- main-->
<section class="section-content-preview">
    <div class="container">
        <img style="margin-top: -1px" src="/media/pic/content-compressor.jpg" alt="preview" class="responsive"/>
    </div>
</section>

<main class="l-main">
    <div class="container">
        <!-- breadcrumbs-->
        <div class="b-breadcrumbs hide-on-tablet">
            <ul class="b-breadcrumbs__list">
                <li><a href="/">Главная</a></li>
                <li><a href="news.html">Статьи</a></li>
                <li><span>Как правильно выбрать</span></li>
            </ul>
        </div>
        <!-- content -->
        <div class="content">
            <h2 class="section-title">
                {$title|escape}
                <span class="section-title__label">21 июня 2016, 12:12</span>
            </h2>
            <div class="wysiwyg">
                <p>
                    Lorem ipsum dolor sit amet, ad odio consequuntur qui, ex accusam electram rationibus est, eum ut alii natum. Hinc omittam ad sea, homero voluptua pericula vel eu. Ei deserunt voluptatum pro. Verear molestiae consequat ne est, mea ei dolorum ceteros, an quod etiam sententiae has.
                </p>
                <p>
                    Quis nonumes pericula ea sea, ne dicat labore voluptua vel, nulla munere eloquentiam id ius. Ad has eripuit detracto epicurei. Porro mazim ei sit, animal integre quaerendum ei nec, porro gloriatur ius no. Vim at zril instructior mediocritatem, forensibus philosophia id pro.
                </p>
                <p>
                    Meis consetetur an duo, ne clita altera feugait sed. Vero graeci utroque te vix. In partiendo repudiandae vituperatoribus ius. Cu tota mandamus mnesarchum nam, tincidunt intellegebat pro no. An legimus aliquando complectitur sit.
                </p>
                <p>
                    Nam blandit pertinax eu. Id feugiat perpetua periculis vim, suscipit pertinax sensibus eum et, diceret maiestatis vituperatoribus sed cu. Quot accumsan eum ad, vis te commune albucius delicatissimi, ut eam veri debet. Has cu nonumy utamur corpora. Mei alii graece omittantur id, alienum molestie neglegentur eu nam, ea has similique intellegam. Usu alienum recusabo similique no, velit democritum voluptaria pri ea, ex pro dicit repudiare.
                </p>
                <p>
                    Pro ex fabulas insolens platonem, oblique euismod et quo, case dicit minimum duo ea. Case dolorum omnesque vis ad. Affert intellegat appellantur an sed, vel falli putent ei. Ad omnis utinam debitis has, vim no justo noster, quod dico aliquid ei cum. Ei quo labore dignissim. Has amet nulla scaevola cu. Debitis deseruisse quo et, ignota facilisi deserunt vim an.
                </p>
                <!-- widget slider-->
                <div class="wysiwyg-slider m-fullscreen">
                    <div class="wysiwyg-slider__container">
                        <a class="js-toggle-fullscreen" href="#"></a>
                        <div class="wysiwyg-slider__list">
                            <img class="responsive" src="pic/slider/content.jpg" alt="full preview"/>
                            <img class="responsive" src="pic/slider/content.jpg" alt="full preview"/>
                            <img class="responsive" src="pic/slider/content.jpg" alt="full preview"/>
                            <img class="responsive" src="pic/slider/content.jpg" alt="full preview"/>
                        </div>
                        <ul class="wysiwyg-slider__thumbs text-center">
                            <a href="#"><img src="pic/thumbs/content-slider/item_1.png" alt="thumb"/></a>
                            <a href="#"><img src="pic/thumbs/content-slider/item_2.png" alt="thumb"/></a>
                            <a href="#"><img src="pic/thumbs/content-slider/item_3.png" alt="thumb"/></a>
                            <a href="#"><img src="pic/thumbs/content-slider/item_4.png" alt="thumb"/></a>
                        </ul>
                    </div>
                </div>
                <!-- ./widget slider-->
                <h3>Что говорит Илон Маск?</h3>
                <p>
                    Lorem ipsum dolor sit amet, ad odio consequuntur qui, ex accusam electram rationibus est, eum ut alii natum. Hinc omittam ad sea, homero voluptua pericula vel eu. Ei deserunt voluptatum pro. Verear molestiae consequat ne est, mea ei dolorum ceteros, an quod etiam sententiae has.
                </p>
                <p>
                    Meis consetetur an duo, ne clita altera feugait sed. Vero graeci utroque te vix. In partiendo repudiandae vituperatoribus ius. Cu tota mandamus mnesarchum nam, tincidunt intellegebat pro no. An legimus aliquando complectitur sit.
                </p>
                <p>
                    Quis nonumes pericula ea sea, ne dicat labore voluptua vel, nulla munere eloquentiam id ius. Ad has eripuit detracto epicurei. Porro mazim ei sit, animal integre quaerendum ei nec, porro gloriatur ius no. Vim at zril instructior mediocritatem, forensibus philosophia id pro.
                </p>

                <!-- wysiwyg-blockquote-->
                <div class="wysiwyg-blockquote left halfwidth">
                    <div class="img-wrap">
                        <img width="161" src="pic/author.png" alt="author"/>
                    </div>
                    <blockquote>
                        <p class="blockquote">
                            Failure is an option here. If things are not failing, you are not innovating enough
                        </p>
                        <p class="author">Elon Musk</p>
                    </blockquote>
                </div>
                <p>
                    Lorem ipsum dolor sit amet, ad odio consequuntur qui, ex accusam electram rationibus est, eum ut alii natum. Hinc omittam ad sea, homero voluptua pericula vel eu. Ei deserunt voluptatum pro. Verear molestiae consequat ne est, mea ei dolorum ceteros, an quod etiam sententiae has.
                </p>
                <p>
                    Meis consetetur an duo, ne clita altera feugait sed. Vero graeci utroque te vix. In partiendo repudiandae vituperatoribus ius. Cu tota mandamus mnesarchum nam, tincidunt intellegebat pro no. An legimus aliquando complectitur sit.
                </p>
                <p>
                    Quis nonumes pericula ea sea, ne dicat labore voluptua vel, nulla munere eloquentiam id ius. Ad has eripuit detracto epicurei. Porro mazim ei sit, animal integre quaerendum ei nec, porro gloriatur ius no. Vim at zril instructior mediocritatem, forensibus philosophia id pro.
                </p>
                <!-- -->
                <p>
                    Lorem ipsum dolor sit amet, ad odio consequuntur qui, ex accusam electram rationibus est, eum ut alii natum. Hinc omittam ad sea, homero voluptua pericula vel eu. Ei deserunt voluptatum pro. Verear molestiae consequat ne est, mea ei dolorum ceteros, an quod etiam sententiae has.
                </p>
                <p>
                    Meis consetetur an duo, ne clita altera feugait sed. Vero graeci utroque te vix. In partiendo repudiandae vituperatoribus ius. Cu tota mandamus mnesarchum nam, tincidunt intellegebat pro no. An legimus aliquando complectitur sit.
                </p>
                <p>
                    Quis nonumes pericula ea sea, ne dicat labore voluptua vel, nulla munere eloquentiam id ius. Ad has eripuit detracto epicurei. Porro mazim ei sit, animal integre quaerendum ei nec, porro gloriatur ius no. Vim at zril instructior mediocritatem, forensibus philosophia id pro.
                </p>
            </div>
            <div class="b-share">
                    <span class="b-share__label">
                        Поделиться
                    </span>
                <ul class="b-share__soc-list inline-list">
                    <li><a target="_blank" href="#">
                            <svg class="svg-icon-fb">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-fb"></use>
                            </svg>
                        </a></li>
                    <li><a target="_blank" href="#">
                            <svg class="svg-icon-vk">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-vk"></use>
                            </svg>
                        </a></li>
                    <li><a target="_blank" href="#">
                            <svg class="svg-icon-twitter">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-twitter"></use>
                            </svg>
                        </a></li>
                    <li><a target="_blank" href="#">
                            <svg class="svg-icon-insta">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-insta"></use>
                            </svg>
                        </a></li>
                </ul>
            </div>
        </div>
    </div>
</main>