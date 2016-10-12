<?php

$GridTitle = 'События';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'event',
    'as' => 'e',
    'lang' => 1,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'e.archive, e.sort',
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
        'archive' => array(
            'title' => 'Архив',
            'tablestyle' => 'width: 70px;',
            'colType' => 'check'
        ),
        'image' => array(
            'title' => 'Картинка',
            'colType' => 'image'
        ),
        'date' => array(
            'title' => 'Дата проведения',
            'colType' => 'date'
        ),
        'cnc' => array(
            'title' => 'ЧПУ',
            'colType' => 'cnc',
            'fields' => array(
                'title' => 'text'
            )
        )
    ),
    'multylang_field' => array(
        'title' => array(
            'title' => 'Название события',
            'colType' => 'text'
        ),
        'city' => array(
            'title' => 'Город проведения',
            'colType' => 'text'
        ),
        'place' => array(
            'title' => 'Место проведения',
            'colType' => 'text'            
        ),
        'text' => array(
            'title' => 'Текст',
            'colType' => 'textarea'
        )
    ),
    'row_seq' => array('id', 'active', 'title', 'city', 'place', 'archive')
);