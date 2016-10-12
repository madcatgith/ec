<?

$GridTitle = "Виды залога";
$from      = array(
	"table" => "loan_calc"
	, "as" => "lc"
	, "lang" => "1"
	, "style" => "width:100%",
	"nonlang_field" => array(
		"id" => array(
			"title"      => "id",
			"style"      => "width: 40px;",
			"tablestyle" => "width: 40px;",
			"colType"    => "lbl"
		),
        "tax" => array(
            "title"      => "Коэф. макс стоимости %",    
            "style"      => "width: 100px;",
            "tablestyle" => "width: 160px;",                                                        
            "colType"    => "text"
        ),
        "active"  	=> array(
			"title"   => "Активность",
			"colType" => "check",
			"tablestyle" => "width: 120px;"
		)
    ),
    "multylang_field" => array(
	    "title" => array(
	        "title"        => "Выводимое название",
	        "colType"      => "text"
	        ,"description" => "Всегда выводимый краткий тект. 'Lady Gaga 4ever'"
	    )
    ),
	"row_seq" => array("id", "tax", "title", "active")
);