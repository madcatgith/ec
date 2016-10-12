{if Registry::get('isLogin')}
	<script type="text/javascript"> 
		var validate = {
	        highlight: errorField,
			unhighlight: successField,
			errorPlacement: errorPlacement,
			errorPlacementFix: errorPlacement,
			errorElement: 'label',
			errorClass: 'fError',
			rules : {
			}
		};
	</script>
	<div id="form{$form_id}" class="modal hide fade popUpForm">
		<form id="f{$form_id}" class="form-post f{$form_id}" action="{$action}" method="post" enctype="multipart/form-data" >
		    <div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    			<h3>{$formInfo.title}</h3>
		    </div>
		    <div class="modal-body">
				<fieldset>
					<div class="hidden">
    					<input type="hidden" name="form_id" value="{$form_id}" />
    					<input type="hidden" name="is_form_order" value="1" />
				    </div>
				    {if isset($hasErrors)}
	    				<div class="alert alert-error">
	    					{foreach $errorsMessage as $error}
	    						<div class="block-error">{$error}</div>
	    					{/foreach}
	    				</div>
				    {/if}
					{foreach $form_items as $key => $item}
						{if ! empty($item.html)}
							{if strlen($item.row.size)}
								<div class="form-inline">
									<div class="control-group {$item.row.size}">
										<label class="control-label" for="form_word_{$key}">{$item.string}{if $item.requred} <em>*</em>{/if}</label>
										<div class="controls">
											<input type="hidden" name="label[{$key}]" value="{$item.string}" />
										    <div class="edcont ed{$key}">{$item.html}</div>
										</div>
									</div>
								</div>
							{else}
								<div class="control-group allLength {$item.type}">
									<label class="control-label" for="form_word_{$key}">{$item.string}{if $item.requred} <em>*</em>{/if}</label>
									<div class="controls">
											<input type="hidden" name="label[{$key}]" value="{$item.string}" />
									        <div class="edcont ed{$key}">{$item.html}</div>
									</div>
								</div>
							{/if}
						{else}
						    <div class="alert alert-info">{$item.string}</div>
					    {/if}
					{/foreach}
			    </fieldset>
		    </div>
		    <div class="modal-footer">
	    		<p class="policeAndTerm"><em class="req">*</em> {Dictionary::GetUniqueWord(432)}</p>
    			<button aria-hidden="true" data-dismiss="modal" class="btn">{Dictionary::GetUniqueWord(173)}</button>
    			<button type="submit" class="btn btn-inverse">{Dictionary::GetUniqueWord(99)}</button>
		    </div>
	    </form>
	</div>
	<script type="text/javascript">
		$("#f{$form_id}").validate(validate);
	</script>
{else}
	<div id="form{$form_id}" class="modal hide fade">
		<div class="modal-header">
    		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    		<h3>{$formInfo.title}</h3>
		</div>
		<div class="modal-body"><p class="text-error form-error">{Dictionary::GetUniqueWord(490)}</p></div>
		<div class="modal-footer">
    		<button aria-hidden="true" data-dismiss="modal" class="btn">{Dictionary::GetUniqueWord(173)}</button>
		</div>
	</div>
{/if}