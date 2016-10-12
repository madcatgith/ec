<div class="info-container">
    <div class="advices">
        <h2 class="heading-def">{$title}</h2>
    </div>
    <div class="advices-box">
        {assign var=tagsAll value=array_chunk($tags, 4, true)}
        <div class="advices-table advices-all">
            <table>
                {foreach $tagsAll as $tag}
                    <tr>
                        {foreach $tag as $k => $v}
                            <td><a href="{$v.href}">{$v.title}</a></td>
                        {/foreach}
                    </tr>
                {/foreach}
            </table>
        </div>
    </div>
</div>