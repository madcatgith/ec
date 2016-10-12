<?php 

$GridTitle        = "Форматы подписки";
$from         = array("table" => "subscribe_format", "as" => "dc", "lang" => "1", "limit" => 20, "islanged" => true, "style" => "width:100%",

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
                                                ,"description" => "Используйте следущие спецсимволы.
                                                        <br />{date} - дата отправки.
                                                     "                                                
                                            ),  
                        "description"      => array(
                                                "title"   => "Формат письма",
                                                "colType" => "textarea"
                                                ,"description" => "Используйте следущие спецсимволы.
                                                        <br />{body} - тело рассылки.
                                                        <br />{username} - ФИО пользователя.
                                                        <br />{email} - email пользователя.                                                        
                                                        <br />{city} - город пользователя.
                                                        <br />{lang} - язык пользователя.
                                                        <br />{unsubscribe_all} - для подставления в ссылку адреса страницы для 
                                                        отписывания пользователя от рассылки в целом.
                                                    "
                                            ),                          
                     ),
                    "row_seq"            => array("id", "active", "title", "description")                                                                        
);

?>