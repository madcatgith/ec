{foreach $data as $item}
<div class="b-footer__contact">
<div class="b-footer__contact-item">
    <h3 class="b-footer__contact-title">{Dictionary::getUniqueWord(75)}:</h3>
    <address>
        <p>
            Тел.: {foreach $item->get('tels') as $tels}{$tels}{/foreach}
        </p>
        <p>
            Моб.: {$item->get('mob')}
        </p>
        <p>
            Факс: {$item->get('skype')}
        </p>
        <p>
            E-mail: <a href="mailto:{$item->get('email')}">{$item->get('email')}</a>.
        </p>
    </address>
</div>
<div class="b-footer__contact-item">
    <h3 class="b-footer__contact-title">{Dictionary::getUniqueWord(76)}:</h3>
    <address>
        <p>
            {$item->get('address')} <br/>
            <a href="#">{Dictionary::getUniqueWord(77)}</a>
        </p>
    </address>
</div>
</div>
{/foreach}