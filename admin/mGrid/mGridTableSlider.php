<?php

$GridTitle = 'Слайдер';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'slider',
    'as' => 'sl',
    'lang' => 1,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'sl.sort',
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
        ),
    ),
    'multylang_field' => array(
        'title' => array(
            'title' => 'Третяя строчка',
            'colType' => 'text'
        ),
        'line1' => array(
            'title' => 'Первая строчка',
            'colType' => 'textarea'
        ),
        'line2' => array(
            'title' => 'Вторая строчка',
            'colType' => 'textarea'
        )
    ),
    'row_seq' => array('id', 'active', 'image', 'title', 'line1', 'line2')
);