<input type="hidden" name="d_path[{$id}]" value="{$value}" />
<input type="hidden" name="filescounter[]" value="{$id}" />
<input type="hidden" name="form_word[{$id}]" value="Файл " />
<input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
<input type="file" size="22" name="userfile{$id}" class="files in{$id} {$class}" />
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