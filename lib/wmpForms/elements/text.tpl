<input
    {if $element.validation=='email'}
        type="email"
    {elseif $element.validation=='number'}
        type="number"
    {elseif $element.validation=='hidden'}
        type="hidden"
        class="hidden"        
    {else}
        type="text"
    {/if}    
    id="field{$formID}{$element.id}" 
    name="data[{$element.id}]" 
    value="{$element.value}" 
    placeholder="{$element.placeholder}"
    {if $element.validation} 
        data-validation="{$element.validation}"
    {/if} 
    {if $element.isRequired}
        required="true"
    {/if}  
/>