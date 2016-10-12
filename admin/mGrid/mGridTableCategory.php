<?php

$GridTitle = 'Категория';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'category',
    'as' => 'cat',
    'lang' => 3,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'cat.sort',
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
        )
    ),
    'multylang_field' => array(
        'title' => array(
            'title' => 'Название',
            'colType' => 'text'
        )
    ),
    'row_seq' => array('id', 'active', 'title')
);