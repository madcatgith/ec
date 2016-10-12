<select id="field{$formID}{$element.id}" name="data[{$element.id}]"{if $element.isMulty} multiple="multiple"{/if}>
	{foreach $element.value as $key => $value}
		<option value="{$value|escape}">{$value}</option>
	{/foreach}
</select>