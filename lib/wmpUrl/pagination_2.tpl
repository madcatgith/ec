<div id="pagination" class="type2">
    <ul>
        {if $page gt 1}
            {if $page - 1 eq 1}
                <li>
                    <a href="{$url}{$rPart}">
                        <span>&lsaquo;</span>
                    </a>
                </li>
            {else}
                <li>
                    <a href="{$url}/{$type}/{$page - 1}{$rPart}">
                        <span>&lsaquo;</span>
                    </a>
                </li>
            {/if}
        {/if}
        {if $page gt $numLink}
            <li>
                <a href="{$url}{$rPart}">
                    <span>1 - {$onPage}</span>
                </a>
            </li>
        {/if}
        {if ($page - $numLink) gt 1}
            <li>
                <a href="{$url}/{$type}/{$page - $numLink}{$rPart}">
                    <span>...</span>
                </a>
            </li>
        {/if}
        {for $loop = $loopStart; $loop <= $loopEnd; $loop++}
            {if $page eq $loop}
                <li>
                    <span class="active">
                        <span>{($loop - 1) * $onPage + 1} - {min($loop * $onPage, $total)}</span>
                    </span>
                </li>
            {else}
                {if $loop eq 1}
                    <li>
                        <a href="{$url}{$rPart}">
                            <span>1 - {$onPage}</span>
                        </a>
                    </li>
                {else}
                    <li>
                        <a href="{$url}/{$type}/{$loop}{$rPart}">
                            <span>{($loop - 1) * $onPage + 1} - {min($loop * $onPage, $total)}</span>
                        </a>
                    </li>
                {/if}
            {/if}
        {/for}
        {if $page lt ($numPages - $numLink - 1)}
            <li>
                <a href="{$url}/{$type}/{($page + $numLink)}{$rPart}" class="item">...</a>
            </li>
        {/if}
        {if $page lt ($numPages - $numLink)}
            <li>
                <a href="{$url}/{$type}/{$numPages}{$rPart}">
                    <span>{$total - $onPage} - {$total}</span>
                </a>
            </li>
        {/if}
        {if $page lt $numPages}
            <li>
                <a href="{$url}/{$type}/{$page + 1}{$rPart}">
                    <span>&rsaquo;</span>
                </a>
            </li>
        {/if}
    </ul>
</div>