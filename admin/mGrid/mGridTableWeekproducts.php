<?php  
$GridTitle        = "Продукт недели";
$from         = array("table" => "weekproduct", "as" => "reg", "lang" => "1", "limit" => 100, "style" => "width:100%", 

                        //неязыковые поля приведенных таблиц
                        "nonlang_field"        => array (
                                "id"              => array(
                                                            "title"   => "id",
                                                            "tablestyle"   => "width: 40px;",
                                                            "colType" => "lbl"
                                                ),
                                "active"         => array(
                                                            "title"   => "Активное",
                                                            "tablestyle"   => "width: 45px; "
                                                            ,"colType" => "check"
                                                        ),                                        
                                            
                                "menu"      => array(
                                                "title"      => "Города",
                                                "style"      => "width: 200px;",
                                                "tablestyle" => "white-space:normal;",
                                                
                                                "link_table" => "weekproduct_to_menu",
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
                                "gallery"         => array(
                                                    "title"    => "Галлерея"
                                                    , "style"  => "width: 250px; "
                                                    , "tablestyle"      => "width: 60px;"
                                                    , "category" => 130
                                                    , "fields" => array( 'title' => 'text'
                                                                        , 'date_from' => 'text')                                                    
                                                    , "colType" => "gallery"
                                                ),

                                "date_to"         => array(
                                                            "title"   => "Конец",
                                                            "tablestyle"   => "width: 65px; "
                                                            ,"colType" => "date"
                                                        ),                                                                                                                                                                                    
                                ),


                    "multylang_field" => array(
                        "title"      => array(
                                                "title"   => "Название"
                                                , "tablestyle"   => "width: 200px; "
                                                , "colType" => "text"
                                            )                        
                     ),
                    "row_seq"            => array("id", "active", "date_from", "date_to", "title", "menu", "gallery")
);