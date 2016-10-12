 <nav class="b-main-nav">
                <ul class="b-main-nav__list inline-list">
                    {foreach $menuArray as $menu}
                        <li><a href="{$menu.href}" title="{$menu.title|escape}">{$menu.title}</a></li>
                    {/foreach}
                </ul>
            </nav>