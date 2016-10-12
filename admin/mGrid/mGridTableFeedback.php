<?php

$GridTitle = 'Адреса представительств';
$from      = array(
	'table'    => 'feedback',
	'as'       => 'feedback',
	'lang'     => '1',
	'limit'    => 100,
	'style'    => 'width:100%',
	'nonlang_field' => array(
		'id' => array (
			'title'        => 'ID'
			, 'tablestyle' => 'width: 40px;'
			, 'colType'    => 'lbl'
		)
		, 'active' => array(
			'title'       => 'Активность'
			, 'colType'    => 'check'
			, 'tablestyle' => 'width: 70px; text-align:center;'
		) 
		, 'place' => array (
			'title'        => 'Позиция'
			, 'colType'    => 'text'
			, 'tablestyle' => 'width: 100px;padding-left:10px;'
		)
	)
	, 'multylang_field' => array (
		'address' => array (
			'title'        => 'Адрес'
			, 'colType'    => 'text'
			, 'tablestyle' => 'padding-left:10px;'
		)
		, 'email' => array (
			'title'        => 'Email'
			, 'colType'    => 'text'
			, 'tablestyle' => 'padding-left:10px;'
		)	
	)
	, 'row_seq' => array(
		'id'
		, 'address'
		, 'email'
		, 'place'
		, 'active'
	)
);