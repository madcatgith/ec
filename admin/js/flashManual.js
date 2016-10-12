var exeColorPicker = function(inputID, spanID){

	$(inputID).change(function(){
		($(this).val()) ? $(spanID).css('background-color','#'+$(this).val()) : $(spanID).css('background-color','#000000');
	}).change();

	$(spanID).click(function(){
		$(inputID).click();
	});

	$(inputID).ColorPicker({
		onSubmit: function(hsb, hex, rgb) {
			$(inputID).val(hex);
			$(inputID).change();
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
			$(inputID).change();
		}
	}).bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});
	
}