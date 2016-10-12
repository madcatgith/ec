<?php
$GridTitle        = "Тип генерируемого файла импорта";

$from         = array("table" => "bill_import_files", "as" => "ds", "style" => "width:100%", "lang" => 1, "nonlang" => true,
                        "nonlang_field" => array(
                            "id"      => array(
                                                        "title"   => "id",
                                                        "tablestyle"   => "width: 40px;",
                                                        "style"   => "width: 40px;",
                                                        "colType" => "lbl"
                                                    )
                            , "title"      => array(
                                                    "title"   => "Название",
                                                    "colType" => "text"
                                                )
                            , "file_name"      => array(
                                                    "title"   => "Файл",
                                                    "colType" => "text"
                                                )                                                
                            , "active"      => array(
                                                    "title"   => "Акт.",
                                                    "colType" => "check"
                                                )                     
                                                
                            , "description"      => array(
                                                    "title"   => "Описание",
                                                    "colType" => "textarea"
                                                )  
                                                /*                                                                                              
                            , "date"              => array(
                                                            "title"   => "Дата генерации",    
                                                            "style"   => "width: 100px;",
                                                            "tablestyle"   => "width: 120px;",                                                        
                                                            "colType" => "date"
                                                            ,"description" => ""
                                            ),    
                                            */                                                                                                                                    
                           , "gen_files"      => array(
                                                    "colType" => "html"
                                                    
                                                    , "title"   => "Сгенерированные файлы"
                                                    , "style" => "width: 255px; border:1px solid SlateGray; padding:1px;"
                                                    
                                                    , "editbody"      => GetEditFilesHtml()
                                                    , "tablebody"      => 'GenTableFileHtml'                                                    
                                                    
                                                    , "notsave" => true
                                                    , "description" => ""
                                            ),  
                                            
                            "regen"      => array(
                                                    "colType" => "html"
                                                    
                                                    ,"title"   => "Генерировать"
                                                    ,"style" => "padding:1px;"
                                                    ,"editbody"      => GenEditFileHtml()                                                    
                                                    
                                                    ,"notsave" => true
                                                    ,"description" => ""
                                            ),                                            
                                                                                       
                        )
                        ,"row_seq"            => array("id", 'active', "title", "file_name", 'description', "gen_files")
                );   
  
// в редакторе вывод списка файлов
function GetEditFilesHtml () {
    return '
    <style>
        .rh{
            position: relative;
            overflow: hidden;
            font-size:8px;
        }
        .corner{
            position: absolute;
            width: 6px;
            height: 6px;
            z-index: 2;
        }
        .row_holder{
            background: #fff;    
            width: 256px;
        }
        .load {
            background : url("'._BASE_URL.'/img/bg-ajax-loader.gif") no-repeat 0 0;
            height:20px;
        }
    </style>
                
    <div class="rh row_holder">
        <div class="fileList load"> </div>
    </div>
              
<script type="text/javascript" >        
        var updateGenFilesBill = function(){
                    var mask = $("#file_name_0").attr("value");
                
                    $(".fileList").addClass("load").html("");            
                    $.post("'._BASE_URL.'/admin/mGrid/engine/mGridEdit.php",{oper:\'updateGenFilesBill\', id:id, mask:mask},function(data){
                        $(".fileList").html("");
                        var str = "";
                        $.each(data, function(key, value) {
                            str += "<div style=\'width:45%; float:left;\'>" + value.name + "</div>";
                            str += "<div style=\'width:45%; float:right;\'>" + value.date + "</div>";
                        });
                        $(".fileList").removeClass("load").append(str);
                    },"json");            
                };        
                
        plugsExecs.push(updateGenFilesBill);                
</script>              
    ';
}  

// в гриде вывод даты последней генерации
function GenTableFileHtml ($wholeRow, $rowId, $key){
    $mask = $wholeRow['file_name'];
    
    $count = 0;
        
    $max_dt = 0;
    $dir = opendir(BASEPATH."admin/import/res");    
    while (($file = readdir($dir)) !== false) {
        if (strpos($file, $mask) === 0 ){
            $count++;
            $tmp = filemtime(BASEPATH."admin/import/res/".$file);
            if ($max_dt < $tmp){
                $max_dt = $tmp;
            }
        }
    }
    
    if ($count == 0)
        return '- - -';
    else
        return 'Всего файлов : ' . $count . ' Последняя дата : ' . date ("d.m.Y H:i:s", $max_dt);
}

function GenEditFileHtml () {
    return '
    <style>
        .regenBtn {
            border-style: solid; border-width: 1px; width:17px; float:left;
            width:17px;            
        }
        .refrList {
            background : url("'._BASE_URL.'/admin/mGrid/engine/css/ui-lightness/images/ui-icons_ef8c08_256x240.png") no-repeat -64px -80px ;
            display:block;
            heigh:15px;
            width:15px;            
        }
        .genMessage {
            float:left;
            color:green;
            font-style:italic;
            width:150px;
            margin-left:20px;
        }
    </style> 
        
    <div id="refresh_list" title="Генерировать файлы" class="ui-pg-button ui-corner-all regenBtn" onmouseout="$(this).removeClass(\'ui-state-hover\')" onmouseover="$(this).addClass(\'ui-state-hover\')">
        <div class="ui-pg-div">
            <span class="ui-icon ui-icon-refresh"></span>            
        </div>
    </div>    
    <div class="genMessage" > </div>
               
<script type="text/javascript" >        
        var genFileBill = function(){
            $(".genMessage").html("");            
        };        
                
        plugsExecs.push(genFileBill);
        
        $(".regenBtn").live("click", function(){
            var mask = $("#file_name_0").attr("value");            
               
            $(".genMessage").html("");            
            $.post("'._BASE_URL.'/admin/mGrid/engine/mGridEdit.php",{oper:\'genFileBill\', id:id, mask:mask},function(data){
                 var str = data;
                 $(".genMessage").append(str);
                 updateGenFilesBill();
            },"json"); 
            return false;
        });
</script>              
    ';
}  
   
?>
