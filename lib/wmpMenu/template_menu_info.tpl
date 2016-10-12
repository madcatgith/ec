
    <div class="content b-faq column">
        {foreach $menuArray as $items}
                <div class="b-faq__item column-4">
                    
                    <ul class="b-faq__item-list">
                        {$subs=Menu::getChilds($langID,$items.id)}
                        {foreach $subs as $item}
                        <li><a href="{$item.href}">{$item.title}</a></li>
                        {/foreach}
                    </ul>
                </div>
        {/foreach}                
</div>

