{foreach $element.value as $key => $value}
	<label for="field{$formID}{$element.id}{$key}">
		<input type="checkbox" id="field{$formID}{$element.id}{$key}" name="data[{$element.id}][]" value="{$value}" />
		{$value}
	</label>
{/foreach}