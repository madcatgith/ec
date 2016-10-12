<section class="section-nav">
    <div class="container">
        {Menu::getCrumbs()}
        <h1 class="section-title">{Menu::get($langID, $menuID, 'title')}</h1>
    </div>
</section>
<main class="l-main">
    <div class="container">
        <section class="section section-catalog">
            {Controller::run('catalog/main')}
        </section>
    </div>
</main>