<?php  
$GridTitle        = "Газета";
$from         = array("table" => "newspaper", "as" => "reg", "lang" => "1", "limit" => 100, "style" => "width:100%", 

                        //неязыковые поля приведенных таблиц
                        "nonlang_field"        => array (
                                "id"              => array(
                                                            "title"   => "id",
                                                            "tablestyle"   => "width: 40px;",
                                                            "colType" => "lbl"
                                                ),
                                            
                                "date_from"         => array(
                                                            "title"   => "Начало",
                                                            "tablestyle"   => "width: 65px; "
                                                            ,"colType" => "date"
                                                        ), 
                                "active"         => array(
                                                            "title"   => "Активное",
                                                            "tablestyle"   => "width: 60px; "
                                                            ,"colType" => "check"
                                                        ),                                                        
                                "date_to"         => array(
                                                            "title"   => "Конец",
                                                            "tablestyle"   => "width: 65px; "
                                                            ,"colType" => "date"
                                                        ),                                                                                                                                                            
                                                
                                "menu"      => array(
                                                "title"      => "Города",
                                                "style"      => "width: 200px;",
                                                "tablestyle" => "width: 100px; white-space:normal;",
                                                
                                                "link_table" => "newspaper_to_menu",
                                                "field"      => "title",
                                                
                                                "multy"      => true,
                                                "rules"      => true,
                                                "colType"    => "select"
                                                
                                                , "where"      => "f.view=4"
                                            ),                                                
                                "gallery"         => array(
                                                    "title"    => "Галлерея"
                                                    , "style"  => "width: 250px; "
                                                    , "tablestyle"      => "width: 60px;"
                                                    , "category" => 164
                                                    , "fields" => array( 'date_from' => 'text'
                                                                        , 'date_to' => 'text')                                                    
                                                    , "colType" => "gallery"
                                                ),                                                                                                                                                                                   
                        ),
                        
                    "multylang_field" => array(
                        "title"      => array(
                                                "title"   => "Название",
                                                "colType" => "text"
                                                , "tablestyle"      => "width: 200px;"
                                            )
                        , "pdf_href"      => array(
                                                "title"   => "Файл для скачивания",
                                                "colType" => "image"
                                                , "tablestyle"      => "width: 200px;"
                                            ) 
                        , "pdf_title"      => array(
                                                "title"   => "Название файла",
                                                "colType" => "text"
                                                , "tablestyle"      => "width: 200px;"
                                            )                                                                                          
                     ),                        
                        
                        "row_seq"            => array( "id", "active", "title", "date_from", "date_to", "menu", "gallery" )
);