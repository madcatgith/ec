<div class="captcha">
	<label for="form_word_{$id}"><img id="captcha" src="/captcha/captcha.php?name=form_word[{$id}]&{time()}" alt="captcha" /></label>
	<a onclick="RefreshCaptcha(); return false;" href="javascript:void(0);"><img alt="refresh" src="/captcha/refresh.png" /></a>         
	<input type="text" id="form_word_{$id}" name="form_word[{$id}]" class="input-mini in{$id}" />
</div>
<script type="text/javascript">
    
    function RefreshCaptcha()
    { 
        $('#captcha').attr('src', '/captcha/captcha.php?name=form_word[{$id}]&' + Math.random());
        $('#form_word_{$id}').val('');
    }

    validate.rules["form_word[{$id}]"] = {
        required: true
        , remote: "/captcha/check.php"
    };
    
    validate.messages["form_word[{$id}]"] = "{Dictionary::GetWord(10078)}";         

</script>