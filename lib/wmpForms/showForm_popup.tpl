<div class="popup">
<a href="#" id="close"><img src="/media/ico/close.png"/></a>
<h3>{$form.title}</h3>
<form method="post" action="{$smarty.server.REQUEST_URI}" enctype="multipart/form-data" id="forms{$form.id}" class="section-callback__wrap">
	<input type="hidden" name="formID" value="{$formID}" />
    {wmp_sessid_input()}
	<div class="section-callback__row first">
		{foreach $elements as $element}
			{if $element.type neq 'textarea'}
				{if $element.validation!='hidden'}
				<div class="row">
					<label for="field{$formID}{$element.id}">{$element.title} {if $element.isRequired}<span class="req">*</span>{/if}</label>
					{$element.tpl}
				</div>
				{else}
					{$element.tpl}
				{/if}
			{/if}
		{/foreach}
	</div>
	<div class="section-callback__row">
		{foreach $elements as $element}
			{if $element.type eq 'textarea'}
				<div class="row">
					<label for="field{$formID}{$element.id}">{$element.title} {if $element.isRequired}<span class="req">*</span>{/if}</label>
					{$element.tpl}
				</div>
			{/if}
		{/foreach}
    </div>
	<div class="section-callback__row--btn">
		<input type="submit" value="{Dictionary::GetUniqueWord(69)}"  class="btn btn-submit">
    </div>
</form>
</div>
{if isset($success) or isset($errors)}
	<script>
		window.location.hash="order";
	</script>
{/if}
{if isset($success)}
	<p class="formSuccess" style="color: green; font-weight: 700; margin-top: 15px;">
		{$form.message}
	</p>
{/if}
{if isset($errors)}
	<div class="formError" style="color: red; font-weight: 700; margin-top: 15px;">
		{implode('</div><div class="formError">', $errors)}
	</div>
{/if}