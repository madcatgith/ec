<?php

$GridTitle = 'Слайдер';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'ATTR',
    'as' => 'at',
    'lang' => 1,
    'limit' => 100,
    "nonlang"       => true,
    'style' => 'width:100%',
    'orderby' => 'at.sort',
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
        'weight' => array(
            'title' => 'Типоразмер',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
        ),
		'length_p' => array(
            'title' => 'Крутящий момент (NM) min',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
        ),
		'quantity' => array(
            'title' => 'Крутящий момент (NM) max',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
        )  
    ),
    'row_seq' => array('id', 'active', 'weight', 'length', 'quantity')
);