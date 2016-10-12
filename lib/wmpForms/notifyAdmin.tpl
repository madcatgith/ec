<h3>Заполнена форма - {$form.title} (ID: {$form.id})</h3><br />

<b>Дата и время:</b> {$recordInfo.date} <br />
<b>IP:</b> {$recordInfo.ip} <br />
<b>Страна:</b> {$recordInfo.country} <br /><br />

{foreach $data as $item}
    <b>{$item.title}:</b> {$item.value} <br />
{/foreach}