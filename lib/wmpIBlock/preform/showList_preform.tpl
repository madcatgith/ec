
<!-- services -->
<div class="services">
        <ul class="services__list">
            {$i=0}
            {foreach $data as $item}
                {$i=$i+1}
                {if ($i%2)==0}
                <li class="services__list-item" data-animation="fadeInRigth">
                {else}
                <li class="services__list-item" data-animation="fadeInLeft">    
                {/if}
                        <div class="service"> <img src="{$item->get('image')}" height="515" width="632" alt="" class="service__img">
                                <h2 class="h3 service__title">{$item->get('title')}</h2>
                                <div class="service__desc">
                                        <p>{$item->get('text')}</p>
                                </div>
                        </div>
                </li>
            {/foreach}
        </ul>
</div>
<!-- services end -->

<div class="section-catalog__footer">
    <h3>Стандарты горловин</h3>
    <ul class="b-catalog__list">
        {foreach $data as $item}
            <li><a href="product.html"></a></li>
        {/foreach}
    </ul>
</div>