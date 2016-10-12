<?php

$GridTitle = 'Продукция';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'stolica_production',
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
        'showMain' => array(
            'title' => 'Показывать на главной',
            'tablestyle' => 'width: 170px;',
            'colType' => 'check'
        ),
        'image_slider' => array(
            'title' => 'Картинка для главной [1920x1000]',
            'colType' => 'image',
            'tablestyle' => 'width:150px;',            
        ),
        'icon' => array(
            'title' => 'Иконка красная',
            'colType' => 'image',
            'tablestyle' => 'width:150px;',
        ),
        'icon_white' => array(
            'title' => 'Иконка белая',
            'colType' => 'image',
            'tablestyle' => 'width:150px;',
        ),	
        'category_id' => array(
            'title' => 'Категория',
            'colType' => 'select',
            'islanged' => true,
            'table' => 'dic_unique',
            'outfield' => 'title',
            'field' => 'id',
            'where' => 'f.code="product_category"',
            'rules' => true,
            'tablestyle' => 'width:200px;',
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
        'announce' => array(
            'title' => 'Анонс',
            'colType' => 'textarea'
        ),
        'text' => array(
            'title' => 'Текст',
            'colType' => 'textarea'
        ),	
        'announce_slider' => array(
            'title' => 'Текст для слайдера',
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
    'row_seq' => array('id', 'active', 'showMain', 'title', 'category_id', 'announce', 'galleryID')
);