<?php

$GridTitle = 'Шаги';
$from      = array(
    'table'   => 'steps'
    , 'as'    => 's'
    , 'lang'  => '1'
    , 'limit' => 100
    , 'style' => 'width:100%'
    , 'nonlang_field' => array (
		'id' => array(
			'title'        => 'id'
			, 'tablestyle' => 'width: 40px;'
			, 'colType'    => 'lbl'
		)        
		, 'img' =>  array(
			'title'         => 'Фото'
			, 'colType'     => 'image'
			, 'imagesizing' => array('height', 60, 'width', 70)
			, 'tablestyle'  => 'width: 70px; '
		)
		, 'place' =>  array(
			'title'         => 'Позиция'
			, 'colType'     => 'text'
			, 'tablestyle'  => 'width: 70px; text-align: center;'
		)	   		
		, 'active' =>  array(
			'title'         => 'Активность'
			, 'colType'     => 'check'
			, 'tablestyle'  => 'width: 70px; text-align: center;'
		)	   
    )
    , 'multylang_field' => array(
        'title' => array(
            'title'     => 'Название'
            , 'colType' => 'text'
        )
        , 'text' => array(
            'title'     => 'Описание'
            , 'colType' => 'textarea'
        )      
    )
    , 'row_seq' => array('id', 'title', 'text', 'img', 'active', 'place')
);
