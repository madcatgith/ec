<?php

$GridTitle = 'Примеры работ';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'stolica_works',
    'as' => 'sw',
    'lang' => 1,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'sw.sort',
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
        'stolica_production' => array(
            'title'         => 'Продукция',
            'style'         => 'width: 100%',
            'tablestyle'    => 'width: 210px;padding-left:10px;',
            'colType'       => 'select',
            'multy'         => true,
            'table'         => 'stolica_production',
            'link_table'    => 'stolica_works_to_production',
            'linkfield'     => 'stolica_production',
            'field'         => 'stolica_works',
            'outfield_lang' => 'title',
            'rulesCol'      => 'title',
            'islanged'      => false,
            'rules'         => true
        ),
        'stolica_package_solutions' => array(
            'title'         => 'Пакетные решения',
            'style'         => 'width: 100%',
            'tablestyle'    => 'width: 210px;padding-left:10px;',
            'colType'       => 'select',
            'multy'         => true,
            'table'         => 'stolica_package_solutions',
            'link_table'    => 'stolica_works_to_package_solutions',
            'linkfield'     => 'stolica_package_solutions',
            'field'         => 'stolica_works',
            'outfield_lang' => 'title',
            'rulesCol'      => 'title',
            'islanged'      => false,
            'rules'         => true
        ),
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
    ),
    'row_seq' => array('id', 'active', 'title', 'announce', 'stolica_production', 'stolica_package_solutions')
);