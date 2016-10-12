<?php

$GridTitle = 'Пакетные решения';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'stolica_package_solutions',
    'as' => 'sps',
    'lang' => 1,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'sps.sort',
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
        'icon' => array(
            'title' => 'Иконка',
            'colType' => 'image',
            'tablestyle' => 'width:150px;',
        ),	
        'icon_white' => array(
            'title' => 'Иконка белая для главной [90x84]',
            'colType' => 'image',
            'tablestyle' => 'width:150px;',
        ),	       
        'category_id' => array(
            'title' => 'Категория',
            'colType' => 'select',
            'islanged' => false,
            'table' => 'stolica_package_solutions',
            'outfield_lang' => 'title',
            'field' => 'id',
            'where' => 'f.category_id="0"',
            'rules' => true,
            'tablestyle' => 'width:200px;',
        ),
        'stolica_production' => array(
            'title'         => 'Продукция',
            'style'         => 'width: 100%',
            'tablestyle'    => 'width: 210px;padding-left:10px;',
            'colType'       => 'select',
            'multy'         => true,
            'table'         => 'stolica_production',
            'link_table'    => 'stolica_package_solutions_to_production',
            'linkfield'     => 'stolica_production',
            'field'         => 'stolica_package_solutions',
            'outfield_lang' => 'title',
            'rulesCol'      => 'title',
            'islanged'      => false,
            'rules'         => true
        ),
        'cnc' => array(
            'title' => 'ЧПУ',
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
    'row_seq' => array('id', 'active', 'icon', 'icon_white', 'title', 'category_id', 'stolica_production', 'announce')
);