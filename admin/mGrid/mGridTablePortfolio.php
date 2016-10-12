<?php

$GridTitle = 'Слайдер (главная)';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'id',
    'table' => 'portfolio',
    'as' => 'p',
    'lang' => 1,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'p.sort',
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
        /*'showClients' => array(
            'title' => 'Показывать в блоке клиентов', 
            'tablestyle' => 'width: 170px;',
            'colType' => 'check'
        ),
        'showSidebar' => array(
            'title' => 'Показывать на главной',
            'tablestyle' => 'width: 170px;',
            'colType' => 'check'
        ),
        'date' => array(
            'title' => 'Дата',
            'tablestyle' => 'width: 70px;',
            'colType' => 'date'
        ),*/
        'logo' => array(
            'title' => 'Логотип',
            'colType' => 'image',
            'tablestyle' => 'width:150px;',            
        )/*,	
        'preview' => array(
            'title' => 'Превью',
            'colType' => 'image',
            'tablestyle' => 'width:150px;',            
        ),	
        'image' => array(
            'title' => 'Картинка #1',
            'colType' => 'image',
            'tablestyle' => 'width:150px;',            
        ),
        'image_2' => array(
            'title' => 'Картинка #2',
            'colType' => 'image',
            'tablestyle' => 'width:150px;',            
        ),
        'image_3' => array(
            'title' => 'Картинка #3',
            'colType' => 'image',
            'tablestyle' => 'width:150px;',            
        ),
		'image_main' => array(
            'title' => 'Картинка на главной(большая)',
            'colType' => 'image',
            'tablestyle' => 'width:150px;',            
        ),
		'image_main_preview' => array(
            'title' => 'Картинка на главной(превью)',
            'colType' => 'image',
            'tablestyle' => 'width:150px;',            
        ),
        'cnc' => array(
            'title' => 'ЧПУ',
            'style' => 'width: 100px;',
            'tablestyle' => 'width: 100px;padding-left:10px;',
            'colType' => 'cnc',
            'fields' => array(
                'title' => 'text'
            )
        ),
		'category_id' => array(
            'title'         => 'Категория',
            'style'         => 'width: 100%',
            'tablestyle'    => 'width: 210px;padding-left:10px;',
            'colType'       => 'select',
            'table'         => 'category',
            'field'         => 'id',
            'outfield_lang' => 'title',
            'islanged'      => false,
            'rules'         => true
        ),
		'url' => array(
            'title' => 'Ссылка',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
        )*/
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
	/*	'text' => array(
            'title' => 'Текст',
            'colType' => 'textarea'
        ),
		'features' => array(
            'title' => 'Функции',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
        ),
		'client' => array(
            'title' => 'Клиент',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
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
        ),*/
    ),
    'row_seq' => array('id', 'active', 'title')
);
