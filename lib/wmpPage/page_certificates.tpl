<section class="section-nav">
        <div class="container">
            {Menu::getCrumbs()}
            <h1 class="section-title">{Menu::get($langID,$menuID,'title')}</h1>
        </div>
</section>
<main class="l-main">
    <div class="container">
        <section class="section section-article">
            <article class="wysiwyg">
                <h3>Гарантия качества:</h3>
                <p>{Menu::get($langID,$menuID,'announce')}</p>
                {Gallery::displayGallery(1)}
            </article>
        </section>
    </div>
</main>  