<input type="text" id="form_word_{$id}" name="form_word[{$id}]" value="{$selected}" maxlength="{$maxlength}" class="input-xlarge in{$id}" />
<script type="text/javascript">
	validate.rules["form_word[{$id}]"] = {
		required : {$required}
		{$validation}
	}; 
</script>
