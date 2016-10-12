{if $data|@count > 0}   
<table class="hide-on-small fullwidth">
                    <thead>
                        <tr>
                            <th>Мощность (KW)</th>
                            <th>Типоразмер</th>
                            <th>Полюса</th>
                            <th>Тип крепления двигателя</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $data as $item}
                        <tr>  
                            <td>{$item->get('min_pow')}</td>
                            <td>{$item->get('type_min')}</td>
                             <td>{$item->get('polus')}</td>
                            <td>{$item->get('engine_type_t')}</td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
                        
        <table class="hide-on-small fullwidth">
                    <thead>
                        <tr>
                            <th>Тип двигателя</th>
                            <th>Количество фаз</th>
                            <th>Исполнение</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $data as $item2}
                        <tr>
                            <td>{$item2->get('engine_type')}</td>
                            <td>{$item2->get('phase_count')}</td>
                             <td>{$item2->get('execution')}</td>
                        </tr>
                        {/foreach}
                    </tbody>
         </table>
{/if}                    