<?php

$GridTitle = 'Резюме';
$from      = array(
    'table'           	=> 'form_job',
    'as'              	=> 'fj',
    'nonlang'		=> true,
    'limit'           	=> 100,
    'style'           	=> 'width:100%',
    'nonlang_field'   	=> array(
        'id'     => array(
            'title'      => 'ID',
            'tablestyle' => 'width: 40px;',
            'colType'    => 'lbl'
        ),
        'active' => array(
           'title' => 'Просмотрен',
           'colType' => 'check',
           'tablestyle' => 'width: 70px;',
        ),
        'date'     => array(
            'title'      => 'Дата',
            'tablestyle' => 'width: 70px;',
            'colType'    => 'lbl'
	),
        'file' => array(
            'title'      => 'Файл',
            'tablestyle' => 'width: 70px;',
            'colType'    => 'image'            
        ),
    ),
    'row_seq' => array('id', 'date', 'active')
);