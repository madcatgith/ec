<div class="b-pagination text-center">
    <ul class="b-pagination__list">
        {if $page gt 1}
            {if $page - 1 eq 1}
                <li><a class="b-pagination__btn prev" href="{$url}{$rPart}"><i class="icon-arrow-left"></i></a></li>
            {else}
                <li><a class="b-pagination__btn prev" href="{$url}/{$type}/{$page - 1}{$rPart}"><i class="icon-arrow-left"></i></a></li>
            {/if}
        {/if}
        {if $page gt $numLink}
            <li><a href="{$url}{$rPart}">1</a></li>
        {/if}
        {if ($page - $numLink) gt 1}
            <li><a>...</a></li>
        {/if}
        {for $loop = $loopStart; $loop <= $loopEnd; $loop++}
            {if $page eq $loop}
                <li><a class="is-active">{$loop}</a></li>
            {else}
                {if $loop eq 1}
                    <li><a href="{$url}{$rPart}">{$loop}</a></li>
                {else}
                    <li><a href="{$url}/{$type}/{$loop}{$rPart}">{$loop}</a></li>
                {/if}
            {/if}
        {/for}
        {if $page lt ($numPages - $numLink - 1)}
            <li><a href="#">...</a></li>
        {/if}
        {if $page lt ($numPages - $numLink)}
            <li><a href="{$url}/{$type}/{$numPages}{$rPart}">{$numPages}</a></li>
        {/if}
        {if $page lt $numPages}
            <li><a class="b-pagination__btn next" href="{$url}/{$type}/{$page + 1}{$rPart}"><i class="icon-arrow-right"></i></a></li>
        {/if}
    </ul>
</div>