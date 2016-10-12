<script type="text/javascript"> 
	var validate = {
	    highlight : errorField,
		unhighlight : successField,
		errorPlacement : errorPlacement,
		errorPlacementFix : errorPlacement,
		errorElement : 'label',
		errorClass : 'fError',
		messages : {
		},
		rules : {
		}
	};
</script>
{if isset($hasErrors)}
	<div class="alert alert-error">
	    {foreach $errorsMessage as $error}
	    	<div>{$error}</div>
	    {/foreach}
	</div>
{/if}
<form id="f{$form_id}" class="form-horizontal f{$form_id}" action="{$action}" method="post" enctype="multipart/form-data" >
	<div class="hidden">
    	<input type="hidden" name="form_id" value="{$form_id}" />
    	<input type="hidden" name="is_form_order" value="1" />
	</div>
	{foreach $form_items as $key => $item}
		<div class="control-group">
			<label class="control-label" for="form_word_{$key}">{if $item.requred}<em>*</em>{/if}<span>{$item.string}</span></label>
			<div class="controls">
				<input type="hidden" name="label[{$key}]" value="{$item.string}" />
					{$item.html}
				</div>
			</div>
		</tr>
	{/foreach}
	<div class="control-group">
		<div class="controls">
		    <div><em class="required">*</em>{Dictionary::GetUniqueWord(432)}</div><br />
		    {$subbtns}
		</div>
	</div>
</form>
<script type="text/javascript">$("#f{$form_id}").validate(validate);</script>