<script type="text/javascript" src="/admin/mGrid/engine/js/ui.dialog.js"></script>
<script type="text/javascript" src="/admin/mGrid/engine/js/ui.tabs.js"></script>
<script type="text/javascript" src="/admin/mGrid/engine/js/ui.datepicker-ru.js"></script>
<script type="text/javascript" src="/js/timepicker.js"></script>

<script type="text/javascript" src="/admin/js/colorpicker.js"></script>
<script type="text/javascript" >
    var plugsExecs = new Array();
</script>

<link type="text/css" href="/admin/colorpicker.css" title="text style" rel="stylesheet" />
<?php

//$lang_query     = DB::Query("SELECT * FROM ?_lang WHERE active='1' ORDER BY place");
$filter_content = array();
$tmpForm        = '';
$postFilterHref = '';

$prePostData	= array('var arter = new Array()', 'var str = ""');
$postData       = array("table:'".$bases."'", "oper:action", "id:id");
$jQueryInsertTd = array();
$jQueryUpdate   = array();

include BASEPATH."/admin/mGrid/engine/mGridForm.php";

//error_reporting(E_ALL);

	$psi = 0;
	//Р Р…Р ВµРЎРЏР В·РЎвЂ№Р С”Р С•Р Р†РЎвЂ№Р Вµ Р С—Р С•Р В»РЎРЏ
	$tabs   = array();
	if(count($from["nonlang_field"]) > 0){
		$tabs[0] = array();
		$tabs[0]['els'] = array();
		$i = $j = $s = 0;
		$id = 0;

		foreach($from["nonlang_field"] as $key => $value){
			if ($value['colType'] == "html") {
				$tabs[0]['els'][]      = '<div style="width: 100%; position: relative;">'.createElement($id, $key, $value, $from).'</div>';
				continue;
			}

			$tabs[0]['els'][]      = '<div style="width: 100%; position: relative; overflow: hidden;">'.createElement($id, $key, $value, $from).'</div>';

			if (isset ($value['multy']) && $value['multy']){
				$prePostData[] = 'arter.push("")';

				if ($value['colType'] == 'check'){
					$prePostData[] =
						"$('#{$key}_{$id} :input:checked').each(function(i) {
							arter[{$psi}] = arter[{$psi}] + ',' + this.value;
	     				})";
				} elseif ($value['colType'] == 'select'){
					$prePostData[] =
						"$('#{$key}_{$id} option:selected').each(function(i) {
							arter[{$psi}] = arter[{$psi}] + ',' + this.value;
	     				})";
				}
	     		$postData[] = "'data[{$id}][{$key}]' : arter[{$psi}]";
				$psi ++;
			} elseif($value['colType'] == 'check'){
				$postData[]               = "'data[{$id}][{$key}]' : $('#{$key}_{$id}').attr('checked') ? '1' : '0'";
			} else {
				$postData[]               = "'data[{$id}][{$key}]' : $('#{$key}_{$id}').val()";
			}
			$i++;
		}

		if (isset($from["OwnerCode"]) && $from["OwnerCode"])
			$postData[] = "'data[OwnerCode]' : ".$from["OwnerCode"];
	}

	//Р С?Р Р…Р С•Р С–Р С•РЎРЏР В·РЎвЂ№РЎвЂЎР Р…РЎвЂ№Р Вµ Р С—Р С•Р В»РЎРЏ
	if(count($from["multylang_field"]) > 0){
        $lang_array = Lang::getLanguages();
    	//Р С—РЎР‚Р С•Р В±Р ВµР В¶Р С”Р В° Р С—Р С• РЎРЏР В·РЎвЂ№Р С”Р С•Р Р†РЎвЂ№Р С? Р С—Р С•Р В»РЎРЏР С?
		foreach ($lang_array as $id => $lang ){
			if (!is_int($id)) continue;
				$tabs[$id] = array();
				$tabs[$id]['li'] = $lang['title'];
				//$tabs[$id]['li'] = '<li lang="'.$id.'" ><a href="#tabs-'.$id.'" >'.$title.'</a></li>';
				$tabs[$id]['els'] = array();
				$i = $j = $s = 0;
				foreach($from["multylang_field"] as $key => $value){
					$tabs[$id]['els'][]      = '<div style="width: 100%; position: relative; overflow: hidden;">'.createElement($id, $key, $value, $from).'</div>';

					if (isset ($value['multy']) && $value['multy']){
						$prePostData[] = 'arter.push("")';

						if ($value['colType'] == 'check'){
							$prePostData[] =
								"$('#{$key}_{$id} :input:checked').each(function(i) {
									arter[{$psi}] = arter[{$psi}] + ',' + this.value;
			     				})";
						}elseif ($value['colType'] == 'select'){
							$prePostData[] =
								"$('#{$key}_{$id} option:selected').each(function(i) {
									arter[{$psi}] = arter[{$psi}] + ',' + this.value;
			     				})";
						}
			     		$postData[] = "'data[{$id}][{$key}]' : arter[{$psi}]";
						$psi ++;
					} elseif($value['colType'] == 'check'){
						$postData[]               = "'data[{$id}][{$key}]' : $('#{$key}_{$id}').attr('checked') ? '1' : '0'";
					} else {
						$postData[]               = "'data[{$id}][{$key}]' : $('#{$key}_{$id}').val()";
					}
					$i++;
				}
				if (isset($from["OwnerCode"]) && $from["OwnerCode"])
					$postData[] = "'data[OwnerCode]' : ".$from["OwnerCode"];
		}
	}

	function GenEditingTabForm ($tabs){
		$str = '<div id="tabs" style="vertical-align:top; width:100%;" >';
		//РЎР‚Р С‘РЎРѓРЎС“Р ВµР С? Р С”Р С•РЎР‚Р ВµРЎв‚¬Р С”Р С‘ РЎвЂљР В°Р В±Р С•Р Р†
		$tabs_li = '';
		foreach ($tabs as $key => $value){
			if ($key == 0) continue;
			$tabs_li .= '<li lang="'.$key.'" ><a href="#tabs-'.$key.'" >'.$value['li'].'</a></li>';
		}
		$str .= '<ul class="lang_tabs" >'.$tabs_li.'</ul>';

		//РЎР‚Р С‘РЎРѓРЎС“Р ВµР С? РЎРѓР С•Р Т‘Р ВµРЎР‚Р В¶Р С‘Р С?Р С•Р Вµ РЎвЂљР В°Р В±Р С•Р Р†
		$tabs_cont = '';
		foreach ($tabs as $key => $value){
			if ($key == 0) continue;
			$tabs_cont .= '<div id="tabs-'.$key.'" style="border: 1px solid #FBD850; background: #FFFFFF url(mGrid/engine/css/img/ui-bg_glass_65_ffffff_1x400.png) repeat-x scroll 50% 50%;"><form action="" method="POST" class="fieldlist_lang" >';
			if (count($value['els']) > 5){
				$tabs_cont_ar = array();
				for ($i = 0; $i < count ($value['els']); $i++)
					$tabs_cont_ar[$i%2] .= $value['els'][$i];
				$tabs_cont .= '<div style="float: left; width:48%;">'.$tabs_cont_ar[0].'</div>
							   <div style="float: right; width:48%;">'.$tabs_cont_ar[1].'</div>
							   <div class="clear"></div>';
			}else {
				$tabs_cont .= implode('', $value['els']);
			}
			$tabs_cont .='</form></div>';
		}

		$str .= $tabs_cont;
		$str .= '</div>';
		return $str;
	}

	function GenEditingNonlangForm ($value){
		//РЎР‚Р С‘РЎРѓРЎС“Р ВµР С? РЎРѓР С•Р Т‘Р ВµРЎР‚Р В¶Р С‘Р С?Р С•Р Вµ РЎвЂљР В°Р В±Р С•Р Р†
		$tabs_cont = '<div id="tabs-0" style="padding:10px; border-right: 1px solid #a1bbd3; background: #ebeff1 url(mGrid/engine/css/img/backblue.png) repeat-y scroll 100% 100%;">
		<form action="" method="POST" class="fieldlist_lang" >';
		if (count($value['els']) > 5){
			$tabs_cont_ar = array();
			for ($i = 0; $i < count ($value['els']); $i++)
				$tabs_cont_ar[$i%2] .= $value['els'][$i];
			$tabs_cont .= '<div style="float: left; width:48%;">'.$tabs_cont_ar[0].'</div>
						   <div style="float: right; width:48%;">'.$tabs_cont_ar[1].'</div>
						   <div class="clear"></div>';
		}else {
			$tabs_cont .= implode('', $value['els']);
		}
		$tabs_cont .='</form></div>';

		$str .= $tabs_cont;
		return $str;
	}

if ($from['islanged']){
	echo '<div id="dialog" style="display: none;" ><div id="resultHolder" style="text-align: center;"></div>';
	echo GenEditingTabForm($tabs);
	echo '</div>';
} elseif ($from['nonlang']){
    echo '<div id="dialog" style="display: none;" ><div id="resultHolder" style="text-align: center;"></div>';
    echo GenEditingNonlangForm($tabs[0]);
    echo '</div>';
} else {
	// Р С—РЎР‚Р С•Р Р†Р ВµРЎР‚РЎРЏР ВµР С?, Р ВµРЎРѓРЎвЂљРЎРЉ Р В»Р С‘ РЎС“ Р Р…Р В°РЎРѓ РЎвЂ¦Р С•РЎвЂљРЎРЏ Р В±РЎвЂ№ Р С•Р Т‘Р С‘Р Р… РЎРЏР В·РЎвЂ№Р С”Р С•Р Р†Р С•Р в„–
	//echo GenEditingForm($tabs);
	echo '<div id="dialog" style="display: none;" ><div id="resultHolder" style="text-align: center;"></div>';
	echo
	'<table style="width:100%;" >
		<tr><td rowspan="2" style="vertical-align:top; width:50%;">'.GenEditingNonlangForm($tabs[0]).'</td></tr>
		<tr><td style="vertical-align:top; width:50%;" >'
			.GenEditingTabForm($tabs)
		.'</td></tr>
	</table>';
	echo '</div>';
}
global $lang;

$def_lang_id = ($from['lang']) ? $from['lang'] : 0;
$lang = (isset($_GET['lang'])) ? $_GET['lang'] : $def_lang_id;

$i = 0;
foreach($from["row_seq"] as $val){
	$jQueryInsertTd[]    = "<td title=\"\" style=\"\" role=\"gridcell\">&nbsp;</td>";

	if (array_key_exists($val, $from["multylang_field"]))
		$jQueryTableUpdate[] = jQueryTableUpdate("{$val}_{$lang}", $i, $from["multylang_field"][$val]);
	else
		$jQueryTableUpdate[] = jQueryTableUpdate("{$val}_0", $i, $from["nonlang_field"][$val]);

	$i++;
}

$jQueryInsertTr = '<tr class=\"ui-widget-content jqgrow\" onMouseOver=\"$(this).addClass(\'ui-state-hover\')\" onMouseOut=\"$(this).removeClass(\'ui-state-hover\')\" role=\"row\" on></tr>';

?>
<div id="map_dialog" title="Р В Р ВµР Т‘Р В°Р С”РЎвЂљР С•РЎР‚ Р С”Р В°РЎР‚РЎвЂљ">
</div>

<div id="confirm_dialog" title="Р Р€Р Т‘Р В°Р В»Р С‘РЎвЂљРЎРЉ Р В·Р В°Р С—Р С‘РЎРѓРЎРЉ?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Р вЂќР ВµР в„–РЎРѓРЎвЂљР Р†Р С‘РЎвЂљР ВµР В»РЎРЉР Р…Р С• РЎвЂ¦Р С•РЎвЂљР С‘РЎвЂљР Вµ РЎС“Р Т‘Р В°Р В»Р С‘РЎвЂљРЎРЉ Р В·Р В°Р С—Р С‘РЎРѓРЎРЉ?</p>
</div>

<div id="info_dialog" title="Р РЋР С—РЎР‚Р В°Р Р†Р С”Р В°">
	<b>Р СџР С•Р В»Р Вµ:</b> <span id="info_fieldname" > </span><br />
	<b>Р СњР В°Р В·Р Р…Р В°РЎвЂЎР ВµР Р…Р С‘Р Вµ:</b><br /><span id="info_fielddescr" > </span>
</div>

<script type="text/javascript">

var action = '';
var id = 0;
var tempDataHolder = new Array();

insertRow = function(id){
	tr = $("<?=$jQueryInsertTr;?>").attr("id", id).append('<?=implode("",$jQueryInsertTd);?>');
	tr.append('<td title="Р вЂќР ВµР в„–РЎРѓРЎвЂљР Р†Р С‘РЎРЏ" style="" class="delete_row" role="gridcell"><div id="del_row" class="ui-pg-div" style="text-align: center;"><span class="ui-icon ui-icon-trash" style="cursor: pointer; margin: 0 auto;"></span></div></td>');
	$("#mGridTable").append(tr);
}

updateRow = function(id){
	thisTr = $("#mGridTable [id=" + id + "]").css({"background":"#72BEFF","border":"1px solid #FF6D48"}).find("td");
	<?=implode("", $jQueryTableUpdate);?>
}

clearForm = function(){
	$('.fieldlist, .fieldlist_lang').find(':radio, :checkbox')
	.removeAttr('checked');

	$('.fieldlist, .fieldlist_lang').find(':text, :password, :file, textarea')
	.val('')
	.removeAttr('selected');

	$('.fieldlist, .fieldlist_lang').find('select option').attr("selected","");
	$('#resultHolder').html('');
	$(".imgLoad").hide();
	$(".cncHolder").html('');
    $(".mapHolder").html('');
	tempDataHolder = new Array();
}

selectFillData = function(select, val){
	$(select).find("option[value='"+val.values+"']").attr("selected", "selected");
	$(select).change();
}

$("#search_dialog").dialog({
	bgiframe: true,
	resizable: false,
	autoOpen: false,
	height: 'auto',
	width: 900,
	modal: true,
	overlay: {
		backgroundColor: '#000',
		opacity: 0.5
	},
	buttons: {
		"Р РЋР В±РЎР‚Р С•РЎРѓР С‘РЎвЂљРЎРЉ": function() {
			location.href = '<?=$postFilterHref;?>';
		},
		"Р СџРЎР‚Р С‘Р С?Р ВµР Р…Р С‘РЎвЂљРЎРЉ": function() {
			$("#filterForm").submit();
		}
	}
});

$(".lblinfo").live("click", function(){
	var name = $(this).prev().html();
	var desc = $(this).prev().attr('title');
	$("#info_dialog #info_fieldname").html(name);
	$("#info_dialog #info_fielddescr").html(desc);
	$("#info_dialog").dialog("open");
});

$("#info_dialog").dialog({
	bgiframe: true,
	resizable: true,
	autoOpen: false,
	width:  'auto',
	height: 'auto',
	modal: true,
	overlay: {
		backgroundColor: 'red',
		opacity: 0.6
	},
	buttons: {
		"Р С›РЎвЂљР С?Р ВµР Р…Р В°": function() {
			$(this).dialog("close");
		}
	}
});

$("#del_row").live("click", function(){
	id = $(this).parents("tr").attr("id");
	$("#confirm_dialog").dialog("open");
});

$("#confirm_dialog").dialog({
	bgiframe: true,
	resizable: false,
	autoOpen: false,
	height:180,
	modal: true,
	overlay: {
		backgroundColor: '#000',
		opacity: 0.5
	},
	buttons: {
	"Р С›РЎвЂљР С?Р ВµР Р…Р В°": function() {
		$(this).dialog("close");
	},
	"Р Р€Р Т‘Р В°Р В»Р С‘РЎвЂљРЎРЉ": function() {
		$.post("/admin/mGrid/engine/mGridEdit.php",{<?="table:'".$bases."', oper:'del', id:id";?>},function(data){
			if(data.flag){
				$("#mGridTable tr[id=" + id + "]").remove();
			}
		},"json");
		$(this).dialog("close");
	}
	}
});

$("#tabs").tabs().attr("class","").addClass("ui-tabs").css("padding","0").find("ul:first").attr("class","ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all");

$("#dialog").dialog({
	bgiframe: true,
	resizable: true,
	autoOpen: false,
	height: 'auto',
	width: 900,
	modal: true,
	buttons: {
	"Р С›РЎвЂљР С?Р ВµР Р…Р В°": function() {
		$(this).dialog('close');
	},
	"Р С›Р С”": function() {
		<?=implode('; ', $prePostData);?>;
		$.post('/admin/mGrid/engine/mGridEdit.php', {<?=implode(',', $postData);?>},function(data){
			if(action == 'add' && data.flag){
				$('#resultHolder').html('<span style="color: #18c42c; font-weight: bold;">Р вЂ”Р В°Р С—Р С‘РЎРѓРЎРЉ РЎС“РЎРѓР С—Р ВµРЎв‚¬Р Р…Р С• Р Т‘Р С•Р В±Р В°Р Р†Р В»Р ВµР Р…Р В°.</span>');
				id = data.id;

				$("#dialog #tabs ul>li").each(function(index){
					$('.fieldlist_lang input#id_' + $(this).attr("lang")).attr('value', id);
				})

				$('.fieldlist_lang input#id_0').attr('value', id);

				insertRow(id);
				updateRow(id);
			}else if(action == 'edit' && data.flag){
				$('#resultHolder').html('<span style="color: #18c42c; font-weight: bold;">Р вЂ”Р В°Р С—Р С‘РЎРѓРЎРЉ РЎС“РЎРѓР С—Р ВµРЎв‚¬Р Р…Р С• Р С•РЎвЂљРЎР‚Р ВµР Т‘Р В°Р С”РЎвЂљР С‘РЎР‚Р С•Р Р†Р В°Р Р…Р В°.</span>');
				updateRow(id);
			}else if(!data.flag || data.flag == 'undefined'){
				if(action == 'add'){
					$('#resultHolder').html('<span style="color: 3f02222; font-weight: bold;">Р С›РЎв‚¬Р С‘Р В±Р С”Р В° Р С—РЎР‚Р С‘ Р Т‘Р С•Р В±Р В°Р Р†Р В»Р ВµР Р…Р С‘Р С‘.</span>');
				}else if(action == 'edit'){
					$('#resultHolder').html('<span style="color: 3f02222; font-weight: bold;">Р С›РЎв‚¬Р С‘Р В±Р С”Р В° Р С—РЎР‚Р С‘ РЎР‚Р ВµР Т‘Р В°Р С”РЎвЂљР С‘РЎР‚Р С•Р Р†Р В°Р Р…Р С‘Р С‘.</span>');
				}
			}
		}, "json");
	}
	},
	close: function() {
		clearForm();
	}
}).css("padding","0");

$('#add_list').live("click", function(){
		$('#ui-dialog-title-dialog').text("Р вЂќР С•Р В±Р В°Р Р†Р С‘РЎвЂљРЎРЉ Р Р…Р С•Р Р†РЎС“РЎР‹ Р В·Р В°Р С—Р С‘РЎРѓРЎРЉ");
		action = 'add';
		$('#dialog').dialog('open');
});

$('.fieldlist_lang select').change(function(){
	sVal = $(this).val();
	var obj  = $('.fieldlist_lang select[parent=' + $(this).attr('id') + ']');
	if(obj!= null && obj.attr("id") != undefined  && sVal >= 0){
		var img = obj.next("img");
		var oId = obj.attr("id");
		if (img != null)
			img.show();
		$.post('/admin/mGrid/engine/mGridFillData.php',{oper:'select', name:oId, value:sVal, parent:obj.attr("parent"), table:'<?=$bases?>', id:id},function(data){
			obj.html('<option class="default" value="0">&nbsp;</option>');
			if (tempDataHolder[id] != null) {
				$.each(data.options, function(key, value) {
					if(key == tempDataHolder[id][oId] && key != null){
						obj.append(new Option(value, key, true));
					}else{
						obj.append(new Option(value, key));
					}
				});

				/*
				$.each(data.options, function(key, value) {
					if(value == tempDataHolder[id][oId] && value != null){
						obj.append(new Option(value, key, true));
					}else{
						obj.append(new Option(value, key));
					}
				});
				*/
			} else {
				$.each(data.options, function(key, value) {
					obj.append(new Option(value, key));
				});
			}
			obj.change();
			img.hide();
		},"json");
	}else if(obj.attr("id") != 'undefined'){

	}
});

$('tr.jqgrow td:not(td.delete_row)').live("dblclick", function() {

	id = $(this).parent('tr').attr('id');

	if(id != 'undefined'){
		$.post('/admin/mGrid/engine/mGridFillData.php', {oper:'fillData',table:'<?=$bases?>',id:id}, function(data){
			tempDataHolder[id] = new Array();
			$.each(data, function(i, val) {
				$.each(val, function(i2, val2) {
					if (typeof(val2) == "object"){
						if (val2.multy == 1){
						//РЎРЊРЎвЂљР С• Р С?РЎС“Р В»РЎРЉРЎвЂљР С‘Р Р†РЎвЂ№Р В±Р С•РЎР‚ Р С‘ Р Р…РЎС“Р В¶Р Р…Р С• Р В°Р С”Р С”РЎС“РЎР‚Р В°РЎвЂљР Р…Р С• РЎР‚Р В°Р В·Р В»Р С•Р В¶Р С‘РЎвЂљРЎРЉ Р В·Р Р…Р В°РЎвЂЎР ВµР Р…Р С‘РЎРЏ
							if (val2.type == "check"){
								$.each(val2.values, function(i3, val3){
									tId = '#' + i2 + '_' + i + '_' + i3;
									opt = $(tId)[0];
									if (opt == null)
										return 0;
									opt.checked = true;

									tempDataHolder[id][i2 + '_' + i + '_' + i3] = i3;
								});
							}else{
								//РЎРЊРЎвЂљР С• РЎРѓР ВµР В»Р ВµР С”РЎвЂљ
								tId = '#' + i2 + '_' + i;
								opt = $(tId)[0];
								if (opt == null)
									return 0;
								$.each(val2.values, function(i3, val3){
									$(opt).find('[value='+ i3 +']').attr('selected', 'selected');

									tempDataHolder[id][i2 + '_' + i + '_' + i3] = i3;
								});
							}
						} else {
							tId = '#' + i2 + '_' + i;
							opt = $(tId)[0];
							if (opt == null)
								return 0;

							switch(val2.type){
								case 'select':
									tempDataHolder[id][i2 + '_' + i] = val2.values;
									selectFillData(opt, val2);
								break;
							}
						}
					} else {
						tId = '#' + i2 + '_' + i;
						opt = $(tId)[0];
						if (opt == null)
							return 0;
						tag = opt.tagName.toLowerCase();
						tempDataHolder[id][i2 + '_' + i] = val2;
						switch(tag){
							case 'textarea':
							opt.value = val2;
							break;
							case 'input':
							switch(opt.type.toLowerCase()){
								case 'text':
									opt.value = val2;
									break;
								case 'checkbox':
									opt.value = '1';
									if (val2 == '1')
										opt.checked = true;
									else
										opt.checked = false;
									break;
							}
							break;/*
							case 'select':
							if(val2){
								selectFillData(opt, val2);
							}
							break; */
						}
					}
				});
			});
			action = 'edit';
			$('#ui-dialog-title-dialog').text("Р В Р ВµР Т‘Р В°Р С”РЎвЂљР С‘РЎР‚Р С•Р Р†Р В°РЎвЂљРЎРЉ [ " + $(".ui-jqgrid-title").html() + " ]. " + id + ". " + data[<?=$lang?>].title);
			$('#dialog').dialog('open');
            //РЎвЂљР ВµР С—Р ВµРЎР‚РЎРЉ РЎвЂ Р ВµР С—Р В»РЎРЏР ВµР С? Р Р†РЎРѓР Вµ Р С—РЎР‚Р С‘Р Р†РЎРЏР В·Р В°Р Р…Р Р…РЎвЂ№Р Вµ Р С•Р В±РЎР‚Р В°Р В±Р С•РЎвЂљРЎвЂЎР С‘Р С”Р С‘
            for(var i=0; i<plugsExecs.length; i++){
                 plugsExecs[i]();
            }
		}, "json");
	}
});

$('tr.jqgrow:not(.clicked) td:not(.delete_row)').live("click", function() {
	$(this).parent().addClass ('clicked');
});

$('tr.jqgrow.clicked td:not(.delete_row)').live("click", function() {
	$(this).parent().removeClass ('clicked');
});

$('th.ui-state-default.ui-th-column.sel:not(.clicked) ').live("click", function() {
	var  index = $('th.ui-state-default.ui-th-column.sel').index(this);
	$('tr.ui-jqgrid-labels').find('th:eq('+ index +')').addClass ('clicked');;
	$('tr.jqgrow').find('td:eq('+ index +')').addClass ('clicked');;
	//$(this).parent().removeClass ('clicked');
});


$('th.ui-state-default.ui-th-column.sel.clicked ').live("click", function() {
	var  index = $('th.ui-state-default.ui-th-column.sel').index(this);
	$('tr.ui-jqgrid-labels').find('th:eq('+ index +')').removeClass ('clicked');;
	$('tr.jqgrow').find('td:eq('+ index +')').removeClass ('clicked');;
	//$(this).parent().removeClass ('clicked');
});
</script>
