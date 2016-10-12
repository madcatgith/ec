{if $multy}
	{foreach $options as $opt_key => $opt_item}
		{if $opt_item['selected']}
			<div class="checkboxHolder">
				<input type="checkbox" id="form_word_{$id}_{$opt_key}" name="form_word[{$id}][]" value="{$opt_item['value']}" class="checkbox in{$id}" checked="checked" />
				<label for="form_word{$id}_{$opt_key}" class="radio">{$opt_item['value']}</label>				
			</div>
		{else}
			<div class="checkboxHolder">
				<input type="checkbox" id="form_word_{$id}_{$opt_key}" name="form_word[{$id}][]" value="{$opt_item['value']}" class="checkbox in{$id}" />
				<label for="form_word_{$id}_{$opt_key}" class="radioBtn">{$opt_item['value']}</label>				
            </div>
		{/if}
	{/foreach}
{else}
	{foreach $options as $opt_key => $opt_item}
		{if $opt_item['selected']}
			<div class="radioHolder">
				<input type="radio" id="form_word[{$id}][{$opt_key}]" name="form_word[{$id}]" value="{$opt_item['value']}" class="radio in{$id}" checked="checked" />
				<label for="form_word[{$id}][{$opt_key}]" >{$opt_item['value']}</label>
			</div>
		{else}
			<div class="radioHolder">
	            <input type="radio" id="form_word[{$id}][{$opt_key}]" name="form_word[{$id}]" value="{$opt_item['value']}" class="radio in{$id}" />
	            <label for="form_word[{$id}][{$opt_key}]" >{$opt_item['value']}</label>				            
            </div>
		{/if}
	{/foreach}                          
{/if}
<script type="text/javascript">
    validate.rules["form_word[{$id}]"] = {
        required: {$required}
        {$validation}
    }; 
</script>