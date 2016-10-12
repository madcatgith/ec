<?php

$GridTitle = 'Наши проекты';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'stolica_projects',
    'as' => 'sp',
    'lang' => 1,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'sp.sort',
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
        'production_id' => array(
            'title' => 'Продукт',
            'colType' => 'select',
            'islanged' => false,
            'table' => 'stolica_production',
            'outfield_lang' => 'title',
            'field' => 'id',
            'rules' => true,
            'tablestyle' => 'width:200px;',
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
    ),
    'row_seq' => array('id', 'active', 'title', 'production_id', 'announce')
);

