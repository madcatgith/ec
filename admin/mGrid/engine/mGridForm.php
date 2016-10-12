<?php

function jQueryTableUpdate($id, $i, $options)
{
    $result = '';
    if (!isset($options['multy']) || !$options['multy']) {
        switch ($options['colType']) {
            case 'select':
                $result = "thisTr.eq('{$i}').html($('#{$id} :selected').text()) ;\n";
                break;
            case 'check':
                $result = "thisTr.eq('{$i}').html('<ins class=\"' + (($('#{$id}').attr('checked')) ? 'checked' : 'unchecked') + '\" />') ;\n";
                break;
            case 'color':
                $result = " thisTr.eq('{$i}').css( 'background-color', '#'+$('#{$id}').val()) ;\n
					thisTr.eq('{$i}').find('span').html($('#{$id}').val());\n";
                break;
            default:
                $result = "thisTr.eq('{$i}').html($('#{$id}').val()) ;\n";
                break;
        }
    } else {
        switch ($options['colType']) {
            case 'select':
                $result = "	var str = '';
					var comma = '';
					$('#{$id} :selected').each(function(i){
					str = str + comma + $(this).text();
					comma = ', ';
					});
					thisTr.eq('{$i}').html(str);\n";
                break;
            case 'check':
                $result = "	var str = '';
					var comma = '';
					$('#{$id} :input:checked').each(function(i){
					str = str + comma + $(\"label[for='\" + $(this).attr('id') + \"']\").text();
					comma = ', ';
					});
					thisTr.eq('{$i}').html(str);\n";
                break;
        }
    }
    return $result;
}

function passwordType($langId, $field, $colParent, $options)
{
    return '<input type="password" style="font-size:11px; margin-bottom: 2px; width:95%;" name="' . $field . '[' . $langId . ']" id="' . $field . '_' . $langId . '" class="text ui-widget-content ui-corner-all" />';
}

function checkType($langId, $field, $colParent, $options)
{
    return '<input type="checkbox" value="1" name="' . $field . '[' . $langId . ']" id="' . $field . '_' . $langId . '" class="FormElement"/>';
}

function checkMultyType($langId, $field, $colParent, $options)
{
    $whereDictCode = ($options['OwnerCode']) ?
            " and owner_code=" . ($options['OwnerCode']) . " " : "";

    if ($options['nonlang']) {
        $langer = "";
    } else {
        global $lang;
        $langer = ($langId == 0) ?
                "and lang_id='{$lang}'" :
                "and lang_id='{$langId}'";
    }

    $rulesCol = ($options['rulesCol']) ?
            $options['rulesCol'] : "title";

    $select = "SELECT
		id,
		{$rulesCol} as resf
		FROM
		?_{$field}
		WHERE
		1=1
		{$langer}
		{$whereDictCode}
		ORDER BY
		resf";

    $tempSelect .= '<div id="' . $field . '_' . $langId . '" class="checker">';
    $selectQuery = DB::Query($select);
    while ($item        = DB::GetRow($selectQuery)) {
        $tempSelect .= '<span class="checkblock">
			<input type="checkbox" value="' . $item['id'] . '" name="' . $field . '[' . $langId . '][' . $item['id'] . ']" id="' . $field . '_' . $langId . '_' . $item['id'] . '" class="FormElement"/>
			<label for="' . $field . '_' . $langId . '_' . $item['id'] . '">' . reprepear($item['resf'], 'input') . '</label>
			</span>';
    }
    return $tempSelect . "<span style='clear:both;'>&nbsp;</span></div>";
}

function cncType($langId, $field, $options, $from)
{

    global $lang, $bases;

    $tpl = new Template;

    $tpl->assign('lang', $lang);
    $tpl->assign('bases', $bases);
    $tpl->assign('langId', $langId);
    $tpl->assign('field', $field);
    $tpl->assign('options', $options);

    return $tpl->fetch(dirname(__FILE__) . '/templates/cnc.tpl');
}

function colorType($langId, $field)
{
    return '<input name="' . $field . '[' . $langId . ']" id="' . $field . '_' . $langId . '" type="text" value="" class="pgColor" style="font: 11px Tahoma;" />
		<script type="text/javascript">
		$("#' . $field . '_' . $langId . '").ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
		$("#' . $field . '_' . $langId . '").val(hex);
		},
		onBeforeShow: function () {    $(this).ColorPickerSetColor(this.value);  }
		}).bind("keyup", function(){  $(this).ColorPickerSetColor(this.value);});
		</script>';
}

function constType($langId, $field, $options, $from)
{
    return '<input type="hidden" name="' . $field . '[' . $langId . ']" id="' . $field . '_' . $langId . '" value="' . $options['default'] . '" />';
}

function dateType($langId, $field, $options, $from)
{
    $style = $options['style'];
    return '<input type="text" style="font-size:11px; margin-bottom: 2px; ' . $style . '" name="' . $field . '[' . $langId . ']" id="' . $field . '_' . $langId . '" class="text ui-widget-content ui-corner-all" /><script type="text/javascript">$("#' . $field . '_' . $langId . '").datepicker({showOn: "button", dateFormat: "yy-mm-dd", buttonImage: "/admin/images/calendar.gif", buttonImageOnly: true});</script>';
}

function datetimeType($langId, $field, $options, $from)
{
    $style = $options['style'];
    return '<input type="text" style="font-size:11px; margin-bottom: 2px; ' . $style . '" name="' . $field . '[' . $langId . ']" id="' . $field . '_' . $langId . '" class="text ui-widget-content ui-corner-all" /><script type="text/javascript">$("#' . $field . '_' . $langId . '").datepicker({regional:"ru", minDate: 0, dateFormat: "yy-mm-dd", showOn: "button", buttonImage: "/admin/images/calendar.gif", buttonImageOnly: true, duration:"", showTime: true, constrainInput: false, stepMinutes: 1, stepHours: 1, time24h: true});</script>';
}

function galleryType($langId, $field, $options, $from)
{
    global $lang, $bases;

    $style = $options['style'];
    $plag  = 'val1="";';
    foreach ($options['fields'] as $key => $val) {
        if ($val == 'text') {
            $plag .= "$('#dialog [name^=\"" . $key . "\"]').each(function(){
				var v = $(this).val();
				if (v){  val1 += ' ' + v; return false;   }
				});";
        } elseif ($val == 'select') {
            $plag .= "$('#dialog [name^=\"" . $key . "\"] option:selected').each(function(){
				var v = $(this).text();
				if (v){  val1 += ' ' + v; return false;   }
				});";
        } elseif ($val == 'value') {
            $plag .= "val1 += ' " . $key . "';";
        }
    }

    return '
		<input type="text" style="font-size:11px; margin-bottom: 2px; ' . $style . '" name="' . $field . '[' . $langId . ']" id="' . $field . '_' . $langId . '" class="galHolder ui-widget-content ui-corner-all" />
		<img class="waitFor_' . $field . '" src="/admin/mGrid/engine/css/img/ajax-loader_red.gif" style="display:none;" />
		<a style="float:left; font-size: 11px; text-decoration: none; color:DodgerBlue;" href="#" class="galSelectBtn' . $field . '" >Выбор галлереи</a>
		<!---<a style="float:right; font-size: 11px; text-decoration: none; color:DodgerBlue;" href="#" class="galBtn' . $field . '" >Редактор галлереи</a>--->
		<script type="text/javascript">
		function FireEventFromChild (name, val){
		$("[name="+name+"]").attr("value", val);
		}

		var isIn = false;
		$(".galSelectBtn' . $field . '").click(function(){
		var val1=$("#' . $field . '_' . $langId . '").val();
		newwin(\'/admin/mGrid/engine/editors/selectorGallery.php?name=' . $field . '[' . $langId . ']&item_id=\'+val1,303,403);
		return false;
		});
		$(".galBtn' . $field . '").click(function(){
		if (isIn) return;
		isIn = true;
		var val1=$("#' . $field . '_' . $langId . '").val();
		//не задана галерея, поэтому в начале создаем её
		if (!val1 || val1 == 0) {
		$(".waitFor_' . $field . '").show();
		' . $plag . '
		//val1 = escape(val1);
		$.post("/admin/mGrid/engine/adm-ajax.php", {bases: "' . $bases . '", action:"creat_gallery", parent: "' . $options['category'] . '", needId:"1", name: val1},
		function(data){
		if (data.status == 1){
		$("#' . $field . '_' . $langId . '").val(data.id);

		val1 = "item_id=" + data.id;
		window.open(\'/admin/mGrid/engine/editors/editorGallery.php?\' + val1 + \'&name=' . $field . '_' . $langId . '\'
		,\'Galleryeditor\',\'resizable=yes,scrollbars=1,width=950,height=600,left=30,top=30\');

		$(".waitFor_' . $field . '").hide();
		isIn = false;
		} else {
		alert("Возникла ошибка! Галлерея не была создана.")
		isIn = false;
		}
		}, "json");

		} else {
		val1 = "item_id=" + val1;
		window.open(\'/admin/mGrid/engine/editors/editorGallery.php?bases=' . $bases . '&\' + val1 + \'&name=' . $field . '_' . $langId . '\'
		,\'Galleryeditor\',\'resizable=yes,scrollbars=1,width=950,height=600,left=30,top=30\');
		isIn = false;
		}

		return false;
		});
		</script>
		';
}

function htmlType($langId, $field, $options, $from)
{
    return '<div style="' . $options['style'] . '" >
		' . $options['editbody']($langId) . '
		</div>';
}

function imageType($langId, $field)
{
    return '<input type="text" style="font-size:11px; margin-bottom: 2px; width:80%;" name="' . $field . '[' . $langId . ']" id="' . $field . '_' . $langId . '" class="text ui-widget-content ui-corner-all" /><input type="button" maxlength="250" onclick="newwin2(\'/admin/files.php?show=jqGrid&amp;obj=' . $field . '_' . $langId . '\',720,520); return false;" value="Обзор" style="font-size:11px; margin-left: 2px;" class="text ui-widget-content ui-corner-all" />';
}

function lblType($langId, $field)
{
    return '<input type="text" style="background:#DADAD5; border:1px solid darkgray; font-style:italic; font-size:11px; margin-bottom: 0px;" name="' . $field . '[' . $langId . ']" id="' . $field . '_' . $langId . '" class="text ui-widget-content ui-corner-all" readonly=true />';
}

function mapType($langId, $field, $options, $from)
{
    $style = $options['style'];
    $plag  = 'var comma = "";';
    foreach ($options['fields'] as $key => $val) {
        if ($val == 'text') {
            $plag .= "$('#dialog [name^=\"" . $key . "\"]').each(function(){
				var v = $(this).val();
				if (v){
				val1 += comma + v;
				comma= ',';
				return false;
				}
				});";
        } elseif ($val == 'select') {
            $plag .= "$('#dialog [name^=\"" . $key . "\"] option:selected').each(function(){
				var v = $(this).text();
				if (v){
				val1 += comma + v;
				comma= ',';
				return false;
				}
				});";
        } elseif ($val == 'value') {
            $plag .= "val1 += comma +' " . $key . "';
				comma = ',';
				";
        }
    }
    return '
		<input type="text" style="font-size:11px; margin-bottom: 2px; ' . $style . '" name="' . $field . '[' . $langId . ']" id="' . $field . '_' . $langId . '" class="mapHolder ui-widget-content ui-corner-all" />
		<a style="float:right; font-size: 11px; text-decoration: none; color:DodgerBlue;" href="#" class="mapBtn' . $field . '" >Редактор карты</a>
		<script type="text/javascript">
		$(".mapBtn' . $field . '").click(function(){
		
			var val1 = $("#' . $field . '_' . $langId . '").val();
		
			if (val1) {
				val1 = "coords=" + val1;
			} else {
				' . $plag . '
				val1 = "address=" + val1;
			}

			window.open(\'/admin/mGrid/engine/editors/editorMap.php?\' + val1 + \'&name=' . $field . '_' . $langId . '\',\'Mapeditor\',\'resizable=yes,width=900,height=600,left=30,top=30\');

		});
		</script>
		';
}

function menuType($langId, $field, $options, $from)
{
    global $lang, $bases;

    $style = $options['style'];

    return '
		<input type="text" style="font-size:11px; margin-bottom: 2px; ' . $style . '" name="' . $field . '[' . $langId . ']" id="' . $field . '_' . $langId . '" class="menuHolder ui-widget-content ui-corner-all" />
		<a style="float:right; font-size: 11px; text-decoration: none; color:DodgerBlue;" href="#" class="menuSelectBtn' . $field . '" >Редактор меню</a>
		<script type="text/javascript">
		function FireEventFromChild (name, val){
		$("[name^="+name+"]").attr("value", val);
		}

		var isIn = false;
		$(".menuSelectBtn' . $field . '").click(function(){
		var val1=$("#' . $field . '_' . $langId . '").val();
		newwin(\'/admin/mGrid/engine/editors/selectorMenu.php?name=' . $field . '&item_id=\'+val1,303,403);
		return false;
		});
		</script>
		';
}

function selectType($langId, $field, $rulesField, $colParent, $options)
{

    $tempSelect = "";
    $connField  = (isset($options['connectParentField'])) ?
            'connP="' . ($options['connectParentField']) . '_' . $langId . '"' : '';

    $ownerCode = (isset($options['OwnerCode'])) ?
            'owner="' . ($options['OwnerCode']) . '"' : '';

    if ($colParent) {
        $tempSelect .= '<select  name="' . $field . '[' . $langId . ']" id="' . $field . '_' . $langId . '" ' . $ownerCode . ' ' . $connField . ' parent="' . $colParent . '_' . $langId . '" style="font-size:11px;' . $options['style'] . '" class="text ui-widget-content ui-corner-all"><option value="0" class="default">&nbsp;</option></select><img src="/admin/images/load.gif" alt="load" style="padding-left: 5px; vertical-align:middle; display:none;" class="imgLoad"/>';
    } else {

        $multiple   = (!isset($options['multy']) && !$options['multy']) ? "" : "multiple=true size=8";
        $tempSelect = '<select  name="' . $field . '[' . $langId . ']" id="' . $field . '_' . $langId . '" ' . $ownerCode . ' ' . $connField . ' ' . $multiple . ' style="font-size:11px;' . $options['style'] . '" class="text ui-widget-content ui-corner-all"><option value="0">&nbsp;</option>';

        if ($options['multy']) {
            if ($options['nonlang']) {
                $langer = "";
            } else {
                global $lang;
                $langer = ($langId == 0) ?
                        "and f.lang_id='{$lang}'" :
                        "and f.lang_id='{$langId}'";
            }
            $whereDictCode = ($options['OwnerCode']) ?
                    " and owner_code=" . ($options['OwnerCode']) . " " : "";

            $whereClause = ($options['where']) ?
                    " and " . ($options['where']) . " " : "";

            $rulesCol = ($options['rulesCol']) ?
                    $options['rulesCol'] : "title";

            if(isset($options['outfield_lang'])) {
                $fieldSQL = $field .'_lang';
            } else {
                $fieldSQL = $field;
            }
            
            $SQL = "SELECT
				f.id,
                                f.*,
				f.{$rulesCol} as outres
				FROM
				?_{$fieldSQL} as f
				WHERE
				1=1
				{$langer}
				{$whereDictCode}
				{$whereClause}
				ORDER BY
				outres";
        } else {
            global $lang;
            $langId = ($langId == 0) ? $lang : $langId;

            $whereClause = ($options['where']) ?
                    " and " . ($options['where']) . " " : "";

            if ($options['nonlang']) {

                if (is_array($options['outfield'])) {
                    $str = "concat(f." . implode(",'. ', f.", $options['outfield']) . ")";
                } else
                    $str = "f.{$options['outfield']} ";

                $orderby = (isset($options['orderby'])) ? "ORDER BY " . $options['orderby'] : "ORDER BY outres";

                $SQL = "SELECT f.id, {$str} as outres
					FROM ?_{$options['table']} as f
					WHERE 1=1
					{$whereClause}
					{$orderby}";
            } elseif ($options['islanged']) {
                if (is_array($options['outfield'])) {
                    $str = "concat(f." . implode(",'. ', f.", $options['outfield']) . ")";
                } else
                    $str = "f.{$options['outfield']} ";

                $orderby = (isset($options['orderby'])) ? "ORDER BY " . $options['orderby'] : "ORDER BY outres";

                $SQL = "SELECT f.id, {$str} as outres
					FROM ?_{$options['table']} as f
					WHERE f.lang_id='{$langId}'
					{$whereClause}
					{$orderby}";
            } else {
                //языковая версия
                $out_field = (isset($options['outfield'])) ?
                        "f.{$options['outfield']}" :
                        "f_lang.{$options['outfield_lang']}";

                if (isset($options["connField"])) {
                    $SQL = "SELECT f.id, f.*, {$out_field} as outres
						FROM ?_{$options['table']} as f
						LEFT JOIN ?_{$options['table']}_lang as f_lang
						ON f_lang.id = f.id AND f_lang.lang_id='{$langId}'
						WHERE 1=1
						{$whereClause}
						ORDER BY outres";
                } else {
                    $SQL = "SELECT f.id, f.*, {$out_field} as outres
						FROM ?_{$options['table']}_lang as f_lang
						LEFT JOIN ?_{$options['table']} as f
						ON f_lang.id = f.id
						WHERE f_lang.lang_id='{$langId}'
						{$whereClause}
						ORDER BY outres";
                }
            }
        }

        $selectQuery = DB::Query($SQL);

        if (isset($options['render'])) {
            $tempSelect .= $options['render']($selectQuery);
        } else {
            while ($item = DB::GetRow($selectQuery)) {
                $tempSelect .= '<option value="' . $item['id'] . '">' . reprepear($item['outres'], 'input') . '</option>';
            }
            if (isset($options['data'])) {
                foreach ($options['data'] as $key => $val) {
                    $tempSelect .= '<option value="' . $key . '">' . $val . '</option>';
                }
            }
        }

        $tempSelect .= "</select>";
    }

    if (isset($options['addable']) && $options['addable'])
        $tempSelect .= '&nbsp;&nbsp;&nbsp;<input type="text" style="font-size:11px; margin-bottom: 2px; width:100px;" name="' . $field . '_tr_[' . $langId . ']" id="' . $field . '_tr_' . $langId . '" ownercode="' . $options['OwnerCode'] . '" class="text ui-widget-content ui-corner-all" />
			<input type="button" class="text ui-widget-content ui-corner-all" style="font-size: 11px; margin-left: 2px;" value="добавить"
			onclick="addnewword(\'' . $field . '_tr_' . $langId . '\', \'' . $field . '_' . $langId . '\'); return false;" maxlength="250"/>';

    return $tempSelect;
}

function textType($langId, $field)
{
    return '<input type="text" style="font-size:11px; margin-bottom: 2px; width:95%;" name="' . $field . '[' . $langId . ']" id="' . $field . '_' . $langId . '" class="text ui-widget-content ui-corner-all" />';
}

function textareaType($langId, $field)
{
	$style = 'style="float:right; margin-right:5px; font-size: 11px; text-decoration: none; color:DodgerBlue;"';
    return '<a '. $style .' href="javascript:void(window.open(\'/admin/ckeditor/?name='. $field .'_'. $langId .'\', \'CKEditor\', \'resizable=yes,width=\'+screen.width+\',height=\'+screen.height+\',left=30,top=30\'))" class="blue">Html редактор</a>
		<textarea style="font-size:11px; margin-bottom: 2px; width:95%; height: 60px;" name="' . $field . '[' . $langId . ']" id="' . $field . '_' . $langId . '" class="text ui-widget-content ui-corner-all" /></textarea>';
}

function createElement($langId, $field, $options, $from)
{
    $thisFunction = $options['colType'] . 'Type';

    if ($options['colType'] == "check") {
        if (isset($options['multy']) && $options['multy']) {
            $thisFunction = $options['colType'] . 'MultyType';
            $result       = createLabel($langId, $field, $options['title'] . ":", $options);
            return $result . $thisFunction($langId, $field, false, $options);
        } else {
            $result = createLabel($langId, $field, $options['title'], $options, "display:inline; font-size:11px; vertical-align:top;");
            return $thisFunction($langId, $field, false, $options) . $result;
        }
    } elseif ($options['colType'] == "cnc") {
        $result = createLabel($langId, $field, $options['title'] . ":");
        return $result . $thisFunction($langId, $field, $options, $from);
    } elseif ($options['colType'] == "html") {
        $result = createLabel($langId, $field, $options['title'] . ":");
        return $result . $thisFunction($langId, $field, $options, $from);
    } elseif ($options['colType'] == "date") {
        $result = createLabel($langId, $field, $options['title'] . ":");
        return $result . $thisFunction($langId, $field, $options, $from);
    } elseif ($options['colType'] == "datetime") {
        $result = createLabel($langId, $field, $options['title'] . ":");
        return $result . $thisFunction($langId, $field, $options, $from);
    } elseif ($options['colType'] == "password") {
        $result = createLabel($langId, $field, $options['title'] . ":");
        return $result . $thisFunction($langId, $field, $options, $from);
    } elseif ($options['colType'] == "map") {
        $result = createLabel($langId, $field, $options['title'] . ":");
        return $result . $thisFunction($langId, $field, $options, $from);
    } elseif ($options['colType'] == "const") {
        return $thisFunction($langId, $field, $options, $from);
    } elseif ($options['colType'] == "gallery") {
        $result = createLabel($langId, $field, $options['title'] . ":");
        return $result . $thisFunction($langId, $field, $options, $from);
    } elseif ($options['colType'] == "menu") {
        $result = createLabel($langId, $field, $options['title'] . ":");
        return $result . $thisFunction($langId, $field, $options, $from);
    } else {
        $result = createLabel($langId, $field, $options['title'] . ":", $options);

        if (function_exists($thisFunction)) {
            return $result . $thisFunction($langId, $field, ( isset($options['rulesField']) ? $options['rulesField'] : "dic_" . $field)
                            , ( isset($options['colParent']) ? $options['colParent'] : false)
                            , $options
            );
        } else {
            return '';
        }
    }
}

function createLabel($langId, $field, $title, $options, $style = " margin-right: 10px; display:block; font-size:11px;")
{
    $title = str_replace('<br/>', '&nbsp;', $title);
    return '<div style="' . $style . '" ><label for="' . $field . '_' . $langId . '" title="' . $options["description"] . '" >' . $title . '</label><span class="lblinfo" href="#" >[ ? ]</span></div>';
}

?>
