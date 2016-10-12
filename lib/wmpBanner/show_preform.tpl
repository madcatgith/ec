<div class="section-catalog__body">
    <div class="b-catalog text-center stooltip">
        <div class="b-catalog__img">
            <div class="img-wrap">
                <img class="img-fluid" src="/pic/catalog/catalog_item.jpg" alt="ITEM"/>
                <a style="left: 57%;top: 3%;" class="b-popover__loop stooltip__link" href="#"></a>
                <a style="left: 73%;top: 32%;" class="b-popover__loop stooltip__link" href="#"></a>

                <a style="left: 64%;top: 47%;" class="b-popover__loop stooltip__link" href="#"></a>

                <a style="left: 47%;top: 64%;" class="b-popover__loop stooltip__link" href="#"></a>

                <a style="left: 20%;top: 67%;" class="b-popover__loop stooltip__link" href="#"></a>
            </div>
            {assign var="pos" value=true}
            {foreach $elements as $items}
            <div class="b-popover stooltip__text"  
            {if $pos}
                data-stooltip='{literal}{"pos": "left"}{/literal}'
                {$pos=false}
            {else}
                data-stooltip='{literal}{"pos": "right"}{/literal}'
                {$pos=true}
            {/if} 
                style="display: none;">
                <h3>{$items.title}</h3>
                <p>{$items.text}</p>
            </div>
            {/foreach}    
        </div>
    </div>
</div>
