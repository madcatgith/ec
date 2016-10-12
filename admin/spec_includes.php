<?php

$no_top = "Y";
include getenv('DOCUMENT_ROOT') .'/admin/admin_top.php';

$field = filter_input(INPUT_GET, 'field');

echo admin_func_sys_message($sys_message);

// Заголовок
echo admin_func_top(Dictionary::GetAdminWord(938));
echo "<table cellspacing=1 cellpadding=2 border=0 width=\"100%\" bgcolor=627080>";
echo admin_func_right_table_row_start("");
echo admin_func_right_table_data( Dictionary::GetAdminWord(1167), "", "");
echo admin_func_right_table_data( Dictionary::GetAdminWord(939) . Dictionary::GetAdminWord(1150), "100%", "");

# Фото галерея
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data("<a href=\"javascript:void(0);\" onclick=\"replaceTarget('[gallery:{id},1]', 'galleryID')\">Вставить галерею</a>", "", 2);
echo admin_func_right_table_data('<input type="text" id="galleryID" style="width:30px;" />' . "&nbsp;<a onclick=\"newwin('/admin/mGrid/engine/editors/selectorGallery.php?name=galleryID',303,403); return false;\" href=\"/admin/mGrid/engine/editors/selectorGallery.php?name=galleryID\" class=\"blue\">Выбрать галерею</a>", "100%", "2i");

# Вставка видео
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data('<a href="javascript:void(0);" onclick="replaceTarget(\'[youtube:{id}]\', \'videoYT\')">Вставить видео YouTube</a>', "", 2);
echo admin_func_right_table_data('<input type="text" id="videoYT" style="width:300px;" />', "100%", "2i");

# Карта
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data('<a href="javascript:void(0);" onclick="replaceTarget(\'[map:{id}]\', \'coords\')">Вставить Карту</a>', "", 2);
echo admin_func_right_table_data('<input type="text" id="address" name="address" placeholder="Введите адрес" style="width:200px;" />&nbsp;&nbsp;<input type="text" id="coords" style="width:150px;" />&nbsp;<a href="#" onclick="javascript:findCoords();">Найти координаты</a>', "100%", "2i");

# Конструктор форм
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data('<a href="javascript:void(0);" onclick="replaceTarget(\'[form:{id}]\', \'formID\')">Вставить конструктор форм</a>', "", 2);

$forms = \Form\FormModel::model()->findAll();
$select = '<select id="formID"><option value="">Выберите форму</option>';
foreach ($forms as $form) {
    $select .= '<option value="'. $form[0]['id'] .'">'. $form[1]['title'] .'</option>';
}
$select .= '</select>';

echo admin_func_right_table_data($select, "100%", "2i");

echo "<script>
    function newwin(url,width,height){
        window.open(url,\"subwindow\",\"width=\"+width+\",height=\"+height+\",toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes\");
    }

function closewinwidesearch()  {
  var s='';
  var count=0;
  for(var i=0; i<formwidesearch.selectwidesearch.options.length; i++)
  {
    if(formwidesearch.selectwidesearch.options[i].selected)
    {
      s+=','+formwidesearch.selectwidesearch.options[i].value;
      count++;
    }
  }
  if(s.substr(0,1)==',')  {
    s=s.slice(1);
  }

  if(count > 0)  {
    var AnCode = '{php \$pre_ws_menu_ids=\"'+ s +'\"; include \"search_wide.php\"; php}';

    var TempStrText = opener.document.forms.". $field .".value;
    if(TempStrText != '') opener.document.forms.". $field .".value = TempStrText + '\\n' + AnCode;
    else opener.document.forms.". $field .".value = AnCode;

    window.close();
  }
}

function htmlspecialchars(text)   {
   var chars = Array(\"&amp;\", \"&lt;\", \"&gt;\", \"&quot;\", \"'\");
   var replacements = Array(\"&\", \"<\", \">\", '\"', \"'\");
   for (var i=0; i<chars.length; i++)
   {
       var re = new RegExp(chars[i], \"gi\");
       if(re.test(text))
       {
           text = text.replace(re, replacements[i]);
       }
   }
   return text;
}
</script>";
?>
<script>
    var replaceTarget = function(where, obj)
    {
        if(!$('#'+ obj).val()) {
            return false;
        }

        closewin(where.replace("{id}", $('#'+ obj).val()));
    },
    FireEventFromChild = function(name, val)
    {
        $('[id='+ name +']').val(val);
    },
    closewin = function(AnCode)
    {
        if(typeof opener.CKEDITOR === 'object') {
            opener.CKEDITOR.instances.cketextarea.insertText(AnCode);
        } else {
            var TempStrText = opener.$("[name='<?=$field?>']").val();
            if(TempStrText != '') {
                opener.$("[name='<?=$field?>']").val(TempStrText + '\n' + AnCode);
            } else {
                opener.$("[name='<?=$field?>']").val(AnCode);
            }
        }
        window.close();
    }, findCoords = function()
    {
        var address = $('#address').val();
        if(!address) {
            return false;
        }
        
        newwin('/admin/mGrid/engine/editors/editorMap.php?address='+ address +'&name=coords', 900, 600);
    };
</script>
