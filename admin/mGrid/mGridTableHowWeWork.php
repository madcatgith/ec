<?php

$GridTitle = 'Блок "Как мы работаем"';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'how_we_work',
    'as' => 'hw',
    'lang' => 1,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'hw.sort',
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
        'value_goal' => array(
            'title' => 'Максимальное значение [Число]',
            'colType' => 'text',
            'tablestyle' => 'width:150px;',
        ),
        'value' => array(
            'title' => 'Целевое значене [Число]',
            'colType' => 'text',
            'tablestyle' => 'width:150px;',
        ),
    ),
    'multylang_field' => array(
        'top' => array(
            'title' => 'Верхняя надпись',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
        ),
        'bottom' => array(
            'title' => 'Нижняя надпись',
            'colType' => 'text'
        ),
    ),
    'row_seq' => array('id', 'active', 'value_goal', 'value', 'top', 'bottom')
);
