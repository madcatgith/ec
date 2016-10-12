<?php  
$GridTitle        = "Другие акции";
$from         = array("table" => "stocks", "as" => "reg", "lang" => "1", "limit" => 100, "style" => "width:100%", 

                        //неязыковые поля приведенных таблиц
                        "nonlang_field"        => array (
                                "id"              => array(
                                                            "title"   => "id",
                                                            "tablestyle"   => "width: 40px;",
                                                            "colType" => "lbl"
                                                ),
                                "active"         => array(
                                                            "title"   => "Активное",
                                                            "tablestyle"   => "width: 60px; "
                                                            ,"colType" => "check"
                                                        ),                                        
                                            
                                "menu"      => array(
                                                "title"      => "Города",
                                                "style"      => "width: 200px;",
                                                "tablestyle" => "width: 100px; white-space:normal;",
                                                
                                                "link_table" => "stocks_to_menu",
                                                "field"      => "title",
                                                
                                                "multy"      => true,
                                                "rules"      => true,
                                                "colType"    => "select"
                                                
                                                , "where"      => "f.view=4"
                                            ), 
                                "date_from"         => array(
                                                            "title"   => "Начало",
                                                            "tablestyle"   => "width: 65px; "
                                                            ,"colType" => "date"
                                                        ),                                            
                                 "img"      => array(
                                                "title"   => "Фото"
                                                ,"imagesizing"    => array('height', 60, 'width', 70)
                                                ,"tablestyle"   => "width: 70px; "
                                                ,"colType" => "image"
                                            ),    

                                "date_to"         => array(
                                                            "title"   => "Конец",
                                                            "tablestyle"   => "width: 65px; "
                                                            ,"colType" => "date"
                                                        ),                                                                                                                                                                                    
                                ),

                    "multylang_field" => array(
                        "title"      => array(
                                                "title"   => "Название",
                                                "colType" => "text"
                                            )  
                        , "announce"      => array(
                                                "title"   => "Анонс",
                                                "colType" => "textarea"
                                            )                                             
                        , "description"      => array(
                                                "title"   => "Описание",
                                                "colType" => "textarea"
                                            )                                                                   
                     ),
                    "row_seq"            => array( "id", "active", "date_from", "date_to", "menu", "title", "announce", "img" )
);