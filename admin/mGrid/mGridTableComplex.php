<?php  
$GridTitle = "Комплексы";
$from      = array(
	"table"   => "complex"
	, "as"    => "com"
	, "lang"  => "1"
	, "limit" => 100
	, "style" => "width:100%"
	, "nonlang_field" => array(
		"id" => array(
	        "title"        => "id"
	        , "tablestyle" => "width: 40px;"
	        , "colType"    => "lbl"
		)
		, "galleryID" => array(
			"title"        => "Галлерея"
			, "style"      => "width: 250px; "
			, "tablestyle" => "width: 60px;"
			, "colType"    => "select"
			, "table"      => 'gallery_category'
			, "outfield"   => "title"
			, "field"      => "id"
			, 'where'      => 'f.parent=5'
			, "islanged"   => false
            , "rules"      => true
            , 'nonlang'    => true
		)                                                                                                                                                                                   
	)
	, "multylang_field" => array(
		"title" => array(
	        "title"        => "Название"
	        , "colType"    => "text"
		)
		, "description" => array(
			"title"     => "Описание Объекта"
			, "colType" => "textarea"
		)
		, "lending" => array(
            "title"     => "Кредитование"
            , "colType" => "textarea"
		)                                                                                          
	)
	, "row_seq"=> array( "id", "galleryID", "title", "description", "lending")
);