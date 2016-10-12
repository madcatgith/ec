<a href="#popup{$form.id}" class="fancybox btn btn-green">{$form.title}</a>
<div class="popup-holder">
	<div id="popup{$form.id}" class="form-feedback">
	    {if isset($success)}<p class="formSuccess">{$form.message}</p>{/if}
	    {if isset($errors)}<div class="formError">{implode('</div><div class="formError">', $errors)}</div>{/if}
	    <form method="post" action="{$smarty.server.REQUEST_URI}" enctype="multipart/form-data" id="forms{$form.id}">
	        <h4>{$form.title}</h4>
	        <div class="form-rows">
	            <input type="hidden" name="formID" value="{$formID}" />
	            {wmp_sessid_input()}
	            {foreach $elements as $element}
	                <div class="form-row">
	                	<label for="field{$formID}{$element.id}">{$element.title}</label>
	                    <div class="wrap-input {$element.type}">
	                        {$element.tpl}
	                    </div>
	                </div>
	            {/foreach}
	        </div>
	        <div class="form-rows_btn">
	        	<input type="submit" class="btn btn-green" value="{Dictionary::GetUniqueWord(40)}" />														
	        </div>
	    </form>
	</div>
</div>