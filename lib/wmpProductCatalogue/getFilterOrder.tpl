<div class="sort">
    <ul class="sort-list">
        {foreach $orders as $orderID => $order}
            <li{if $current eq $orderID} class="active"{/if}><a href="?sort={$orderID}">{Dictionary::GetUniqueWord($order.dic)}</a></li>
        {/foreach}
		<li class="show-card">
			<div class="hide-block active">{Dictionary::getUniqueWord(105)}</div> 
			<div class="show-all hide">{Dictionary::getUniqueWord(112)}</div> 
		</li>
    </ul>
</div>