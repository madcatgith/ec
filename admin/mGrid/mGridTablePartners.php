<?php

$GridTitle = 'Партнеры';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'partners',
    'as' => 'p',
    'lang' => 2,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'p.sort',
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
        'image' => array(
            'title' => 'Картинка',
            'colType' => 'image',
            'tablestyle' => 'width:150px;',
        )
    ),
    'multylang_field' => array(
        'title' => array(
            'title' => 'Название',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
        )
    ),
    'row_seq' => array('id', 'active', 'title')
);