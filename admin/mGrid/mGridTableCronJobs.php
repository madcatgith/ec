<?php 

$GridTitle        = "Автоматические обработчики";
$from         = array("table" => "cron_jobs", "as" => "dc", "limit" => 200, "nonlang" => true, "style" => "width:100%",

                    "nonlang_field" => array(
                        "id"      => array(
                                                "title"   => "id",
                                                "tablestyle" => "width: 40px;",
                                                "colType" => "lbl"
                                            ),
                                            
                        "active"     => array(
                                                "title"   => "Активный"
                                                ,"tablestyle" => "width: 70px;"
                                                ,"colType" => "check"
                                            ),                                            
                        "title"      => array(
                                                "title"   => "Название",
                                                "colType" => "text"
                                            ),  
                        "category"      => array(
                                                "title"   => "Категория",
                                                "colType" => "text"
                                            ),                          
                         "interv"         => array(
                                                    "title"    => "Интервал"
                                                    , "colType" => "text"
                                                    , "description" => " DAYS HOURS:MINUTES:SECONDS "
                                                ),                        
                         "parms"      => array(
                                                "title"   => "Параметры",
                                                "colType" => "text"
                                            ),
                                            
                         "nextdate"      => array(
                                                "title"   => "Дата следующей итерации",
                                                "colType" => "datetime"
                                            ),
                                            
                         "start"      => array(
                                                    "colType" => "html"
                                                    
                                                    ,"title"   => "Запустить"
                                                    ,"style" => "padding:1px;"
                                                    ,"editbody"      => StartExecHtml()                                                    
                                                    
                                                    ,"notsave" => true
                                                    ,"description" => ""
                                            ),                                            
                                                                                                              
                     ),
                    "row_seq"            => array("id", "active", "title", "nextdate", "interv")
);

function StartExecHtml () {
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
   
        
    <div id="refresh_list" title="Запустить" class="ui-pg-button ui-corner-all regenBtn" onmouseout="$(this).removeClass(\'ui-state-hover\')" onmouseover="$(this).addClass(\'ui-state-hover\')">
        <div class="ui-pg-div">
            <span class="ui-icon ui-icon-refresh"></span>            
        </div>
    </div>    
    <div class="genMessage" > </div>
               
    <script type="text/javascript" >       

        function OnStartAllCrons (){
            $.post("/admin/crons/cron_job.php",{key:\'needToExecForKishenia\'},function(data){
                        if( data && !data.error ){
                            alert(\'Операция выполнена успешно!\');
                        } else {
                            alert(\'Операция не выполнена!\');
                        }
                    },"json");
        }

        $(".regenBtn").live("click", function(){
            var id = $("#id_0").attr("value");
            var cat = $("#category_0").attr("value");
            var pars = $("#parms_0").attr("value");

            $.post("/admin/crons/cron_job.php",{key:\'needToExecForKishenia\', id:id, cat:cat, pars:pars },function(data){
                if( data && !data.error ){
                    alert(\'Операция выполнена успешно!\');
                } else {
                    alert(\'Операция не выполнена!\');
                }
            },"json");            
            
            return false;
        });
</script>              
    ';
}
?>