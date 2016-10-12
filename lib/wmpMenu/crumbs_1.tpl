<div class="b-breadcrumbs hide-on-tablet">
    <ul class="b-breadcrumbs__list">
        {if count($crumbs) gt 0}
            {foreach $crumbs as $crumb}
                {if next($crumbs)}
                    <li>
                        <a href="{$crumb.href}">{$crumb.title}</a>
                    </li>
                {else}
                    <li><span>{$crumb.title}</span></li>
                {/if}
            {/foreach}
        {/if}
    </ul>
</div>
				
