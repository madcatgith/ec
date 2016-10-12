<?php

$GridTitle = 'Руководство';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'director',
    'as' => 'd',
    'lang' => 2,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'd.sort',
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
        'showMain' => array(
            'title' => 'Показывать на главной',
            'tablestyle' => 'width: 70px;',
            'colType' => 'check'
        ),
        'image' => array(
            'title' => 'Картинка',
            'colType' => 'image',
            'tablestyle' => 'width:150px;',
        ),
        'cnc' => array(
            'title' => 'Код',
            'style' => 'width: 100px;',
            'tablestyle' => 'width: 100px;padding-left:10px;',
            'colType' => 'cnc',
            'fields' => array(
                'title' => 'text'
            )
        )
    ),
    'multylang_field' => array(
        'position' => array(
            'title' => 'Должность',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
        ),
        'title' => array(
            'title' => 'Название',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
        ),
        'announce' => array(
            'title' => 'Анонс',
            'colType' => 'textarea'
        ),
        'text' => array(
            'title' => 'Текст',
            'colType' => 'textarea'
        ),	
        'SEOTitle' => array(
            'title' => 'СЕО Название',
            'colType' => 'textarea'
        ),
        'SEOKeywords' => array(
            'title' => 'СЕО Ключевые слова',
            'colType' => 'text'
        ),
        'SEODescription' => array(
            'title' => 'СЕО Описание',
            'colType' => 'textarea'
        )
    ),
    'row_seq' => array('id', 'active', 'showMain', 'title')
);