<div class="input-holder radio">
	<ul class="radio-list">
		{foreach $element.value as $key => $value}
			<li>
				<label for="field{$formID}{$element.id}{$key}">
					<input type="radio" id="field{$formID}{$element.id}{$key}" name="data[{$element.id}]" value="{$value}" />
					{$value}
				</label>
			</li>
		{/foreach}
	</ul>
</div>