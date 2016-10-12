<?php

$GridTitle = 'Области/Города';
$from = array(
    'table' => 'region',
    'as' => 'reg',
    'lang' => 2,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'nonlang_field' => array(
        'id' => array(
            'title' => 'ID',
            'tablestyle' => 'width: 40px;',
            'colType' => 'lbl'
        ),
        'active' => array(
            'title' => 'Активность',
            'tablestyle' => 'width: 70px;',
            'colType' => 'check'
        ),
        'coords' => array(
            'title' => 'Координаты',
            'tablestyle' => 'width:250px;',
            'colType' => 'map',
            "fields"     => array(
            	'title' => 'text'
            )
        ),
    ),
    'multylang_field' => array(
        'title' => array(
            'title' => 'Название',
            'tablestyle' => 'width:320px;',
            'colType' => 'text'
        ),
    ),
    'row_seq' => array('id', 'title', 'coords', 'active')
);
