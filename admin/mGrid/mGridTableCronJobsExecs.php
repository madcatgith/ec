<?php 

$GridTitle        = "Статистика выполнения автоматических обработчиков";
$from         = array("table" => "cron_jobs_res", "as" => "dc", "limit" => 200, "nonlang" => true, "style" => "width:100%",

                    "nonlang_field" => array(
                        "id"      => array(
                                                "title"   => "id",
                                                "tablestyle" => "width: 40px;",
                                                "colType" => "lbl"
                                            ),                                                                                       
                        "ip"      => array(
                                                "title"   => "Адрес запроса на обработку"
                                                ,"tablestyle" => "width: 150px;"
                                                , "colType" => "text"
                                            ),  
                        "operdate"      => array(
                                                "title"   => "Дата исполнения"
                                                ,"tablestyle" => "width: 150px;"
                                                ,"colType" => "datetime"
                                            ),
                        "oper"     => array(
                                                "title"   => "Операции"
                                                ,"tablestyle" => "width: 300px;"
                                                ,"colType" => "textarea"
                                            ),                                                                      
                         "status"         => array(
                                                    "title"    => "Статус"
                                                    ,"tablestyle" => "width: 70px;"
                                                    , "colType" => "text"
                                                ),
                         ),
                    "row_seq"            => array("id", "ip", "operdate", "oper", 'status')
); 
?>