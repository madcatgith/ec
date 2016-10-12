<?php

$GridTitle = 'Отзывы';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'stolica_reviews',
    'as' => 'sr',
    'lang' => 1,
    'limit' => 100,
    'nonlang' => true,
    'style' => 'width:100%',
    'orderby' => 'sr.sort',
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
        'showBottom' => array(
            'title' => 'Показывать в футере',
            'tablestyle' => 'width: 70px;',
            'colType' => 'check'
        ),
        'image' => array(
            'title' => 'Картинка',
            'colType' => 'image',
            'tablestyle' => 'width:150px;'
        ),	
        'title' => array(
            'title' => 'Название',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
        ),
        'announce' => array(
            'title' => 'Текст отзыва',
            'tablestyle' => 'width:200px;',
            'colType' => 'textarea'
        )
    ),
    'row_seq' => array('id', 'active', 'showMain', 'showBottom', 'title', 'announce', 'image')
);

