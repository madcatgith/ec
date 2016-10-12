<?php

$GridTitle = 'Контакты';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'contacts',
    'as' => 'cont',
    'lang' => 1,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'cont.sort',
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
        'email' => array(
            'title' => 'email(разделитель \',\')',
            'colType' => 'text',
            'tablestyle' => 'width:150px;',
        ),
		'phone' => array(
            'title' => 'Телефон(разделитель \',\')',
            'colType' => 'text',
            'tablestyle' => 'width:150px;',
        ),
		'skype' => array(
            'title' => 'Факс(разделитель \',\')',
            'colType' => 'text',
            'tablestyle' => 'width:150px;',
        ),
        'mob' => array(
            'title' => 'Мобильный(разделитель \',\')',
            'colType' => 'text',
            'tablestyle' => 'width:150px;',
        )
    ),
    'multylang_field' => array(
        'title' => array(
            'title' => 'Название(разделитель \',\')',
            'colType' => 'text'
        ),
		'announce' => array(
            'title' => 'Аннонс',
            'colType' => 'text'
        ),
        'address' => array(
            'title' => 'Адрес',
            'colType' => 'text'
        ),
        'country' => array(
            'title' => 'Страна',
            'colType' => 'text'
        )
    ),
    'row_seq' => array('id', 'active', 'email', 'title', 'announce', 'country')
);