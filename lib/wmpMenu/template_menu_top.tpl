
<nav class="b-main-nav">
    <ul class="b-main-nav__list inline-list">
        <li>
            <a href="index.html">
                <svg class="svg-icon-home">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-home"></use>
                </svg>
            </a>
        </li>
        {foreach $menuArray as $menu}
            <li><a href="{$menu.href}" title="{$menu.title|escape}">{$menu.title}</a></li>
        {/foreach}
    </ul>
</nav>
