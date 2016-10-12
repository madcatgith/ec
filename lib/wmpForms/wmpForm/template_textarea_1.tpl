<textarea id="form_word_{$id}" name="form_word[{$id}]" class="input-xlarge in{$id}">{$selected}</textarea>
<script type="text/javascript">
    validate.rules["form_word[{$id}]"] = {
        required : {$required}
        {$validation}
    }; 
</script>