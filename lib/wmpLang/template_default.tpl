<div class="b-lang-switch">
    <ul class="b-lang-switch__list inline-list">
        {foreach $langArray as $lang}
            <li><a {if $lang.active eq 1}class="is-active"{/if} href="{$lang.href}">{$lang.http_accept_language}</a></li>
        {/foreach}
    </ul>
    <a class="b-menu-icon js-toggle-mobile-menu" href="#"><i class="icon-menu"></i></a>
</div>