<?

$GridTitle = "Карта проектов";
$from      = array(
	"table"   => "project_map"
	, "as"    => "pm"
	, "lang"  => "1"
	, "style" => "width:100%"
	, "nonlang_field" => array (
                        
                            "id"      => array(
                                                        "title"   => "id",
                                                        "style"   => "width: 40px;",
                                                        "tablestyle"   => "width: 40px;",
                                                        "colType" => "lbl"
                                            ),
                                            
                                           
                             "region_id" => array(
                                                "title" => "Регион",
                                                "style" => "width: 195px",
                                                "colType" => "select",
                                                "table" => "map_region_lang",
                                                "outfield" => "name",
                                                "islanged" => "true",
                                                "rules" => "true",
                                                "field" => "id",
                                            ),

                                           
                            "phone"      => array(
                                                        "title"   => "Телефон",
                                                        "style"   => "width: 200x;",
                                                        "tablestyle"   => "width: 200px;",
                                                        "colType" => "text"
                                            ),
                                            
                            "fax"      => array(
                                                        "title"   => "Факс",
                                                        "style"   => "width: 200px;",
                                                        "tablestyle"   => "width: 200px;",
                                                        "colType" => "text"
                                            ),
                                            
                            "email"      => array(
                                                        "title"   => "Email",
                                                        "style"   => "width: 200px;",
                                                        "tablestyle"   => "width: 200px;",
                                                        "colType" => "text"
                                            ),
                                            
                         "active"         => array(
                                                    "title"   => "Активный",
                                                    "tablestyle"   => "width: 100px; text-align: center;"
                                                    ,"colType" => "check"
                                                ),   
                        
                        ),
                          
                        "multylang_field" => array(

                        
                            "name"      => array(
                                                    "title"   => "Название",
                                                    "colType" => "text",
                                                ),
                                                
                            "address"      => array(
                                                    "title"   => "Адрес",
                                                    "colType" => "text",
                                                ),
                                               
                        ),
                        "row_seq"            => array("id", "region_id", "name", "address", "active")
                );
