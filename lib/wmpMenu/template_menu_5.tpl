{foreach $menuArray as $item}
	{if Menu::isActive($item.id)}
		{$item.title}
	{/if}
{/foreach}