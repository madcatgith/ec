<nav class="b-footer__menu-nav">
    <ul class="inline-list">
        {foreach $menuArray as $item}
            <li><a href="{$item.href}">{$item.title}</a></li>
        {/foreach}
    </ul>
</nav>