<div class="b-benefit-promo text-center">
{foreach $elements as $items}
    <div class="b-benefit-promo__item">
        <figure>
            <div class="img-wrap">
                <i class="{$items.href}"></i>
            </div>
            <figcaption>
                <h3 class="b-benefit-promo__title">{$items.title}</h3>
                <p>{$items.text}</p>
            </figcaption>
        </figure>
    </div>
{/foreach}
</div>
