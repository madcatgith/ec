<?php

$GridTitle = 'Тип карты';
$from      = array(
	'table'    => 'map_type',
	'as'       => 'maptype',
	'lang'     => '1',
	'limit'    => 100,
	'style'    => 'width:100%',
	'nonlang_field' => array(
		'id' => array (
			'title'        => 'ID'
			, 'tablestyle' => 'width: 40px;'
			, 'colType'    => 'lbl'
		)		
	)
	, 'multylang_field' => array (
		'title' => array (
			'title'        => 'Тип'
			, 'colType'    => 'text'
		)	
	)
	, 'row_seq' => array(
		'id'
		, 'title'
	)
);