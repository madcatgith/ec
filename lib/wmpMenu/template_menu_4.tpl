{foreach $menuArray as $item}
<div class="b-faq__item column-4">
    <ul class="b-faq__item-list">
		<li><a class="is-active" href="{$item.href}">{$item.title}</a></li>
		{Menu::getTreeByTemplate(Lang::getID(), $item.id, '6')}
    </ul>
</div>
{/foreach}