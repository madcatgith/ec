{if $data|@count > 0}       
<table>
    <thead>
        <tr>
            <th>{Dictionary::GetUniqueWord(81)}</th>
            <th>{Dictionary::GetUniqueWord(82)}</th>
            <th>{Dictionary::GetUniqueWord(83)}</th>
        </tr>
    </thead>
    <tbody>
        {foreach $data as $item}
        <tr>  
            <td>{$item->get('weight')}</td>
            <td>{$item->get('length_p')}</td>
            <td>{$item->get('quantity')}</td>
        <tr>
        {/foreach}
    </tbody>
</table>
{/if}                    