<?php  
$GridTitle        = "Купоны на скидку";
$from         = array("table" => "coupons", "as" => "reg", "lang" => "1", "nonlang" => true, "limit" => 100, "style" => "width:100%", 

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
                                                
                                                "link_table" => "coupons_to_menu",
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
                                                    , "category" => 138
                                                    , "fields" => array( 'date_from' => 'text'
                                                                        , 'date_to' => 'text')                                                    
                                                    , "colType" => "gallery"
                                                ),                                                                                                                                                                                   
                        ),
                        "row_seq"            => array( "id", "active", "date_from", "date_to", "menu", "gallery" )
);