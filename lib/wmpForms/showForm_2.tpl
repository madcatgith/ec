{if isset($success)}<p class="formSuccess">{$form.message}</p>{/if}
{if isset($errors)}<div class="formError">{implode('</div><div class="formError">', $errors)}</div>{/if}
<h3 class="section-title">{$form.title}</h3>
<form method="post" action="{$smarty.server.REQUEST_URI}" enctype="multipart/form-data" id="forms{$form.id}" class="section-callback__wrap">
	<input type="hidden" name="formID" value="{$formID}" />
    {wmp_sessid_input()}
	<div class="section-callback__row">
		{foreach $elements as $element}                   
                            
				
                                <div class="row">
                                        {if $element.title neq "Каптча"}                                    
					<label for="field{$formID}{$element.id}">{$element.title} {if $element.isRequired}<span class="req">*</span>{/if}</label>
                                        {/if} 
					{$element.tpl}                                        
				</div>
                                       
			
		{/foreach}
		<input type="submit" value="{Dictionary::GetUniqueWord(69)}"  class="btn btn-submit" >
	</div>
</form>