{if $success}
	<h3>{Dictionary::GetUniqueWord(637)}</h3>
{else}
	{if $hasErrors}
		<div class="r-error">{implode('</div><div class="r-error">', $errors)}</div>
	{/if}
	<div class="page-form">{$form}</div>
{/if}