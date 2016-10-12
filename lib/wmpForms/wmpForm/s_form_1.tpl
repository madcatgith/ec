<h3 class="hBg"><?=Dictionary::GetUniqueWord(189);?></h3>
<div id="simpleForm">
	<? if(count($errors)): ?>
		<p class="errors"><?=implode('<br />', $errors);?></p>
	<? endif; ?>
	<form method="post" action="<?=$action;?>">
		<div><input type="hidden" name="orderSend" value="1"></div>
		<div class="input">
			<label for="orderFIO"><?=$data['orderFIO']['title'];?></label>
            <input id="orderFIO" name="data[orderFIO]" type="text" value="" />
        </div>
		<div class="input">
			<label for="orderPhone"><?=$data['orderPhone']['title'];?></label>
            <input id="orderPhone" name="data[orderPhone]" type="text" value="" />
        </div>
        <div class="input">
			<label for="orderEmail"><?=$data['orderEmail']['title'];?></label>
            <input id="orderEmail" name="data[orderEmail]" type="text" value="" />
        </div>
        <div class="input">
            <label for="orderCommentary" style="width: 108px; display: inline-block;"><?=$data['orderCommentary']['title'];?></label>
            <textarea id="orderCommentary" name="data[orderCommentary]" cols="50" rows="10"></textarea>
            <div class="clear"></div>
        </div>
        <div class="submit">
            <input type="submit" id="orderSend" name="send" value="<?=Dictionary::GetUniqueWord(99);?>" />
        </div>
	</form>
</div>
<script type="text/javascript">

	$reqEmail = '<?=$reqEmail;?>';

    function isEmail(string)
    {
        return (string.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1) ? true : false;
    }

    $('#orderEmail').click(function()
    {

		$(this).removeClass('required');
		
		if ($(this).val() == $reqEmail)
			$(this).val('');

    });

    $("#orderSend").click(function()
    {
    	
		if (isEmail($("#orderEmail").val()) == false) {
			$("#orderEmail").addClass("required").val($reqEmail);
			return false;
		} else {
			$("#orderForm").submit();
		}
    }); 
</script>	