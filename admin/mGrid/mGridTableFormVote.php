<?php

$GridTitle = 'Оценка работы';
$from      = array(
    'table'           	=> 'form_vote',
    'as'              	=> 'fa',
	'nonlang'			=> true,
    'limit'           	=> 100,
    'style'           	=> 'width:100%',
    'nonlang_field'   	=> array(
        'id'     => array(
            'title'      => 'ID',
            'tablestyle' => 'width: 40px;',
            'colType'    => 'lbl'
        ),
        'date'     => array(
            'title'      => 'Дата',
            'tablestyle' => 'width: 70px;',
            'colType'    => 'lbl'
        ),
		'fio' => array(
			'title' 	=> 'ФИО',
			'colType' 	=> 'text', 
			'tablestyle' => 'width: 270px;'
		),
		'phone' => array(
			'title' 	=> 'Телефон',
			'colType' 	=> 'text', 
			'tablestyle' => 'width: 170px;'
		),
		'email' => array(
			'title' 	=> 'Почтовый адрес',
			'colType' 	=> 'text', 
			'tablestyle' => 'width: 170px;'
		),
		'text' => array(
			'title' 	=> 'Отзыв',
			'colType' 	=> 'textarea',
		)
	),
	'row_seq'	=> array('id', 'date', 'fio', 'phone', 'email')
);