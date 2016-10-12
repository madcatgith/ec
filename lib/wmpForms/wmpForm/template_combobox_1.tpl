{if $multy}
    <select size='{$size}' multiple="multiple" id="form_word_{$id}" name="form_word[{$id}][]" class="select-sity combo multy in{$id} {$class}">
		{foreach $options as $opt_key => $opt_item}
		    {if $opt_item['selected']}
		        <option title="{$opt_item['value']}" value="{$opt_item['value']}" title="{$opt_item['value']}" selected="selected" >{$opt_item['value']}</option>
		    {else}
		        <option title="{$opt_item['value']}" value="{$opt_item['value']}" title="{$opt_item['value']}" >{$opt_item['value']}</option>
		    {/if}
		{/foreach}
    </select >
	{if $hasDescription}
		<div class="fieldHint">
			<div class="block-error">
				<span>{$description}</span>
			</div>
		</div>
	{/if}    
	<script type="text/javascript">
	    validate.rules["form_word[{$id}][]"] = {
	        required: {$required}
	        {$validation}
	    }; 
	</script>            
{else}
	<select name="form_word[{$id}]" id="form_word_{$id}" class="combo mono in{$id} {$class} select-sity">
	    {foreach $options as $opt_key => $opt_item}
	        {if $opt_item['selected']}
	            <option icon="/img/20x20.png" value="{$opt_item['value']}" selected="selected" >{$opt_item['value']}</option>
	        {else}
	            <option icon="/img/20x20.png" value="{$opt_item['value']}" >{$opt_item['value']}</option>
	        {/if}
	    {/foreach}
    </select>
	{if $hasDescription}
		<div class="fieldHint">
			<div class="block-error">
				<span>{$description}</span>
			</div>
		</div>
	{/if}    
	<script type="text/javascript">
	    validate.rules["form_word[{$id}]"] = {
	        required: {$required}
	        {$validation}
	    }; 
	</script>                        
{/if}
{if isset($feedbackField) and $feedbackField eq true}
	<input type="hidden" name="feedback" value="{$id}" />
{/if}