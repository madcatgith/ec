<div id="navbar">
	<ul class="nav sf-js-enabled sf-shadow">
		{foreach from=$menu key=parent_id item=mitem}
			<li>
        		<a href="javascript:void(0);">{$mitem['title']}</a>
        		{if isset($mitem['subs']) && count($mitem['subs']) }
					<ul>
						{foreach from=$mitem['subs'] item=sub}
							{if $sub['url'] eq '#'}
								<li class="active"><a class="active" href="#">{$sub['title']}</a></li> 
								{if isset($sub['subs']) && count($sub['subs'])}
									{foreach from=$sub['subs'] item=sub2}
										<li><a href="/admin/{$sub2['url']}?wide=1">{$sub2['title']}</a></li> 
									{/foreach}
								{/if}
							{else}
								<li><a href="/admin/{$sub['url']}?wide=1">{$sub['title']}</a></li> 
							{/if}
						{/foreach}
					</ul>
				{/if}
      		</li>
		{/foreach}
	</ul>
</div>