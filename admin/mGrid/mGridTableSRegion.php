<?

$GridTitle = "Регоны";
$from      = array(
	"table"   => "support_region"
	, "as"    => "sr"
	, "lang"  => "1"
	, "style" => "width:100%"
	, "nonlang_field" => array (
                        
            "id" => array(
                                "title"   => "id",
                                "style"   => "width: 40px;",
                                "tablestyle"   => "width: 40px;",
                                "colType" => "lbl"
                    ), 
            		"place" => array(
                                "title"   => "Позиция в списке",
                                "style"   => "width: 80px;",
                                "tablestyle"   => "width: 140px;",
                                "colType" => "text"
                    ) 
                ),               
                  
                "multylang_field" => array(

                
                    "name"      => array(
                                            "title"   => "Название",
                                            "colType" => "text",
                                        )
                                       
                ),
                "row_seq"            => array("id", "name", "place")
        );
