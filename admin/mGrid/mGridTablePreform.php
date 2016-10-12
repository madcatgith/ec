<?php

$GridTitle = 'Преформа';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'pservices',
    'as' => 'serv',
    'lang' => 3,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'serv.sort',
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
        ),
            'text' => array(
            'title' => 'Текст',
            'colType' => 'textarea'
        ),
        'SEOTitle' => array(
            'title' => 'СЕО Название',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
        ),
        'SEOKeywords' => array(
            'title' => 'СЕО Ключевые слова',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
        ),
        'SEODescription' => array(
            'title' => 'СЕО Описание',
            'colType' => 'textarea'
        )
    ),
    'row_seq' => array('id', 'active', 'title')
);
