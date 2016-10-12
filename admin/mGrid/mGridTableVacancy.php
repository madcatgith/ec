<?php

$GridTitle = 'Вакансии';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'vacancy',
    'as' => 'v',
    'lang' => 2,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'v.sort',
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
            'title' => 'Название вакансии',
            'tablestyle' => 'width:320px;',
            'colType' => 'text'
        ),
        'about' => array(
            'title' => 'Требованию',
            'colType' => 'textarea'
        )
    ),
    'row_seq' => array('id', 'title', 'about', 'active')
);
