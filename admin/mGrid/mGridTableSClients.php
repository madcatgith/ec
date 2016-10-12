<?php

$GridTitle = 'Клиенты';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'stolica_clients',
    'as' => 'sc',
    'lang' => 1,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'sc.sort',
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
            'title' => 'Логотип компании',
            'colType' => 'image',
            'tablestyle' => 'width:150px;'
        ),	
        'cnc' => array(
            'title' => 'ЧПУ',
            'tablestyle' => 'width:200px;',
            'colType' => 'cnc',
            'fields' => array(
                'title' => 'text'
            )
        ),
        'galleryID' => array(
            'title' => 'Галерея',
            'colType' => 'gallery',
            'style' => 'width: 100px;',
            'tablestyle' => 'width: 100px;padding-left:10px;'
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
            'colType' => 'textarea'
        ),
        'SEOKeywords' => array(
            'title' => 'СЕО Ключевые слова',
            'colType' => 'text'
        ),
        'SEODescription' => array(
            'title' => 'СЕО Описание',
            'colType' => 'textarea'
        ),
    ),
    'row_seq' => array('id', 'active', 'title', 'cnc', 'image')
);

