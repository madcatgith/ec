<?

$GridTitle = "Направления";
$from      = array(
	"table"   => "map_coal_direction"
	, "as"    => "mcd"
	, "lang"  => "1"
	, "style" => "width:100%",
	"nonlang_field" => array(
		"id" => array(
			"title"      => "id",
			"style"      => "width: 40px;",
			"tablestyle" => "width: 40px;",
			"colType"    => "lbl"
		),
        "active" => array(
			"title"      => "Активность",
			"colType"    => "check",
			"tablestyle" => "width: 80px; text-align: center;"
		),
		'coords' => array (
			'title'        => 'Координаты'
			, 'colType'    => 'map'
			, 'style'      => 'width: 336px;'
			, 'tablestyle' => 'width: 220px;'
            , "fields"     => array(
            	'title' => 'text'
            )			
		)			
    ),
    "multylang_field" => array(
	    "title" => array(
	        "title"        => "Пункт",
	        "colType"      => "text"
	    )
    ),
	"row_seq" => array("id", "title", "coords", "active")
);