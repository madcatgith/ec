<?php  
$GridTitle        = "Продукт недели";
echo 'ffffff';
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
                                                "tablestyle" => "width: 200px; white-space:normal;",
                                                
                                                "link_table" => "weekproduct_to_menu",
                                                "field"      => "title",
                                                
                                                "multy"      => true,
                                                "rules"      => true,
                                                "colType"    => "select"
                                                
                                                , "where"      => "f.view=4"
                                            ), 
                                "gallery"         => array(
                                                    "title"    => "Галлерея"
                                                    , "style"  => "width: 250px; "
                                                    , "category" => 45
                                                    , "fields" => array( 'menu' => 'select'
                                                                        , 'address' => 'text')                                                    
                                                    , "colType" => "gallery"
                                                ),
                                "date_from"         => array(
                                                            "title"   => "Начало",
                                                            "tablestyle"   => "width: 45px; "
                                                            ,"colType" => "date"
                                                        ),
                                "date_to"         => array(
                                                            "title"   => "Конец",
                                                            "tablestyle"   => "width: 45px; "
                                                            ,"colType" => "date"
                                                        ),                                                                                                                                                                                    
                                ),


                    "multylang_field" => array(
                        "title"      => array(
                                                "title"   => "Название",
                                                "colType" => "text"
                                            ),                        
                        "place"      => array(
                                                "title"   => "Порядок показа",
                                                "colType" => "text"
                                            ),
                    ),
                    "row_seq"            => array("id", "title", "menu", "active", "outurl", "place", "code")
);