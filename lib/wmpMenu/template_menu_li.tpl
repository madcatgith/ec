{foreach $menuArray as $menu}
	<li {if Menu::isActive($menu.id)}class="active"{/if}><a href="{$menu.href}" title="{$menu.title|escape}">{$menu.title}</a></li>
{/foreach}