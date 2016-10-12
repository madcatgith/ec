{foreach $menuArray as $item}
	{if Menu::isActive($item.id)}
		{$item.fileSrc}
	{/if}
{/foreach}