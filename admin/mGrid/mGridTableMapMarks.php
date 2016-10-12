<?php

$GridTitle = 'Координаты';
$from      = array(
	'table'    => 'map_marks',
	'as'       => 'mapmarks',
	'lang'     => '1',
	'limit'    => 100,
	'style'    => 'width:100%',
	'nonlang_field' => array(
		'id' => array (
			'title'        => 'ID'
			, 'tablestyle' => 'width: 40px;'
			, 'colType'    => 'lbl'
		)
		, "typeID" => array(
			"title"        => "Тип"
			, "tablestyle" => "width: 220px;"
			, "colType"    => "select"
			, "table"      => 'map_mark_type_lang'
			, "outfield"   => "title"
			, "field"      => "id"
			, "islanged"   => true
            , "rules"      => true
		)  		
		, 'coords' => array (
			'title'        => 'Координаты'
			, 'colType'    => 'map'
			, 'style'      => 'width: 336px;'
		)	
	)
	, 'multylang_field' => array (
		'title' => array (
			'title'        => 'Название'
			, 'colType'    => 'text'
		)	
		, 'description' => array (
			'title'        => 'Описание'
			, 'colType'    => 'textarea'
		)			
	)	
	, 'row_seq' => array(
		'id'
		, 'typeID'
		, 'title'
	)
);