<div class="info-container" id="advices">
    <div class="advices">
        <h2 class="heading-def">{$title}</h2>
    </div>
    <div class="advices-box">
        {foreach $category as $cat => $value}
            <div class="advices-table">
                <table>
                    <tr>
                        <td class="heading" colspan="2">{$value}</td>
                    </tr>
                    {assign var=tag value=array_chunk($tags[$cat], 2, true)}
                    {foreach $tag as $t}
                        <tr>
                            {foreach $t as $k => $v}
                                <td><a href="{$v.href}">{$v.title}</a></td>
                                {/foreach}
                        </tr>
                    {/foreach}
                </table>
            </div>
        {/foreach}
    </div>
</div>