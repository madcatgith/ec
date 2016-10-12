<?php 

$GridTitle        = "Разделы для подписки";
$from         = array("table" => "subscribe_category", "as" => "dc", "lang" => "1", "limit" => 200, "islanged" => true, "style" => "width:100%",

                    "multylang_field" => array(
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
                        "description"      => array(
                                                "title"   => "Формат письма",
                                                "colType" => "textarea"
                                                ,"description" => "Используйте следущие спецсимволы.
                                                        <br />{city_title} - для отображения города.
                                                        <br />{category_title} - для отображения названия категории.
                                                        <br />{body} - для тела рассылки по данному разделу.
                                                        <br />{unsubscribe_category} - для подставления в ссылку адреса страницы для 
                                                        отписывания пользователя от рассылки на данный раздел.
                                                    "
                                            ),                          
                         "menu"         => array(
                                                    "title"    => "Раздел"
                                                    , "style"  => "width: 250px; "
                                                    , "toplevel" => 45
                                                    
                                                    , "colType" => "menu"
                                                    , "description" => " При необходимости автоматического сбора новостей и обновлений для 
                                                        определенного раздела - просто выбрать интересующий раздел;"
                                                ),                        
                         "handler"      => array(
                                                "title"   => "Обработчик",
                                                "colType" => "textarea"
                                            ),
                                            
                         "lastdate"      => array(
                                                "title"   => "Дата последнего пакета",
                                                "colType" => "datetime"
                                            ),
                         "position"      => array(
                                                "title"   => "Порядок показа",
                                                "colType" => "text"
                                            ),                                                                                                              
                     ),
                    "row_seq"            => array("id", "active", "title", "lastdate")                                                                        
);

?>