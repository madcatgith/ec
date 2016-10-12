<input type="text" style="font-size:11px; margin-bottom: 2px; width:80%;" name="{$field}[{$langId}]" id="{$field}_{$langId}" class="text ui-widget-content ui-corner-all" />
<input type="button" value="Генерировать ЧПУ" id="{$field}_gen_{$langId}" class="text ui-widget-content ui-corner-all" />
<input type="button" value="Проверить ЧПУ" id="{$field}_check_{$langId}" style="margin-left: 2px;" class="text ui-widget-content ui-corner-all" /><br />
<div id="{$field}_alert_{$langId}" class="cncHolder" style="padding: 2px 0pt; width: 100%; height: 14px; font-size: 11px;"></div>
<script type="text/javascript">

    $("#{$field}_gen_{$langId}").click(function()
    {

        var val1 = '';

        {foreach $options.fields as $key => $val}
            {if $val eq 'text'}
                $('#dialog [name^="{$key}"]').each(function()
                { 
                    var v = $(this).val(); 
                    if (v) {  
                        val1 += ' ' + v; 
                        return false;
                    }
                });
            {elseif $val eq 'select'}
                $('#dialog [name^="{$key}"] option:selected').each(function()
                { 
                    var v = $(this).text(); 
                    if (v){ 
                        val1 += ' ' + v; 
                        return false;   
                    }
                });
            {elseif $val eq 'value'}
                    val1 += ' {$key}';
            {/if}
        {/foreach}

        if (val1 === '') {
            $("#{$field}_alert_{$langId}").html('<span style="color: 3f02222;">Введите название меню и нажмите генерировать</span>');
        } else {

            // $("#{$field}_{$langId}").val(my_replace(alphabet, val1));
            
            $.post('/admin/request.php?fn=generate/uri', {
                string: val1
            }, function(data)
            {
                $("#{$field}_{$langId}").val(my_replace(alphabet, data.string));
                $("#{$field}_alert_{$langId}").html('<span style="color: #18c42c;">ЧПУ успешно сгенерирован</span>');
            }, 'json');            
        }
    });

    $("#{$field}_check_{$langId}").click(function()
    {

        var my_string = $("#{$field}_{$langId}").val();

        if (my_string === '') {
            $("#{$field}_alert_{$langId}").html('<span style="color: #f02222;">Поле ЧПУ пустое, сгенерируйте или введите свой</span>');
        } else {

            var currlang = $(".ui-tabs-selected.ui-state-active").attr("lang");
            
            $.post("/admin/mGrid/engine/adm-ajax.php", {
                action : "check_cnc", 
                bases: "{$bases}", 
                field : "{$field}", 
                item: id, 
                value: my_string, 
                lang: currlang
            }, function(data)
            {
		if(data.flag == 2) {
                    $("#{$field}_alert_{$langId}").html('<span style="color: #18c42c;">данный ЧПУ удовлетворяет требованиям<span>');
		}else if(data.flag == 1) {
                    $("#{$field}_alert_{$langId}").html('<span style="color: #f02222;">такой ЧПУ уже существует</span>');
		}else{
                    $("#{$field}_alert_{$langId}").html('<span style="color: #f02222;">Ошибка проверки ЧПУ</span>');
		}
            }, "json");
        }
    });
</script>