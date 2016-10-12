<?php

$GridTitle = 'Преформа';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'preform',
    'as' => 'preform',
    'lang' => 1,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'preform.sort',
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
		 
    ),
    'multylang_field' => array(
		'name' => array(
            'title' => 'Название',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
        ),
    ),
    'row_seq' => array('id', 'active')
);
