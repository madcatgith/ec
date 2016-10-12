<section class="section-nav">
    <div class="container">
        {Menu::getCrumbs()}
        <h1 class="section-title">{Menu::get($langID, $menuID, 'title')}</h1>
    </div>
</section>
<main class="l-main">
        <div class="container">
            <section class="section section-catalog">
                <h2 class="section-title text-center">{$title}</h2>

                <div class="section-catalog__preview text-center">
                    <p>{$text}</p>
                </div>
                
                {Controller::run('iblock/preform/main')}

                <div class="section-catalog__footer">
                    <h3>Стандарты горловин</h3>
                    <ul class="b-catalog__list">
                        <li><a href="product.html">28mm PCO 1810 with stepper</a></li>
                        <li><a href="product.html">28mm PCO 1810 with stepper</a></li>
                        <li><a href="product.html">28mm PCO 1810 with stepper</a></li>
                        <li><a href="product.html">28mm PCO 1810 with stepper</a></li>
                        <li><a href="product.html">28mm PCO 1810 with stepper</a></li>
                        <li><a href="product.html">28mm PCO 1810 with stepper</a></li>
                        <li><a href="product.html">28mm PCO 1810 with stepper</a></li>
                        <li><a href="product.html">28mm PCO 1810 with stepper</a></li>
                        <li><a href="product.html">28mm PCO 1810 with stepper</a></li>
                        <li><a href="product.html">28mm PCO 1810 with stepper</a></li>
                        <li><a href="product.html">28mm PCO 1810 with stepper</a></li>
                    </ul>
                </div>
            </section>
        </div>
    </main>
