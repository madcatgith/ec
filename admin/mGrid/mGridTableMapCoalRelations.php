<?php

$GridTitle = 'Связи';
$from      = array(
	'table'    => 'map_coal_relation',
	'as'       => 'mcr',
	'limit'    => 100,
	'style'    => 'width:100%',
	'lang'     => 1,
	"nonlang"  => true,  
	'nonlang_field' => array(
		'id' => array (
			'title'        => 'ID'
			, 'tablestyle' => 'width: 40px;'
			, 'colType'    => 'lbl'
		)
		, "relFrom" => array(
			"title"        => "Откуда"
			, "colType"    => "select"
			, "table"      => 'map_coal_direction_lang'
			, "outfield"   => "title"
			, "field"      => "id"
			, "rules"      => true
			, "islanged"   => true
		) 
		, "relTo" => array(
			"title"        => "Куда"
			, "colType"    => "select"
			, "table"      => 'map_coal_direction_lang'
			, "outfield"   => "title"
			, "field"      => "id"
			, "rules"      => true
			, "islanged"   => true			
		)
        , "active" => array(
			"title"      => "Активность",
			"colType"    => "check",
			"tablestyle" => "width: 80px; text-align: center;"
		)
	)
	, 'row_seq' => array(
		'id'
		, 'relFrom'
		, 'relTo'
		, 'active'
	)
);