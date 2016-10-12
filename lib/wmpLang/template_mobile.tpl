<div class="b-lang-switch">
    {foreach $langArray as $lang}
        <a {if $lang.active eq 1}class="is-active"{/if} href="{$lang.href}">{$lang.title_short}</a>
    {/foreach}
</div>
