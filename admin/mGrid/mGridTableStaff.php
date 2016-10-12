<?php

$GridTitle = 'Сотрудники';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'staff',
    'as' => 'st',
    'lang' => 2,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'st.category, st.sort',
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
        'category' => array(
            'title' => 'Категория',
            'colType' => 'select',
            'islanged' => true,
            'table' => 'dic_unique',
            'outfield' => 'title',
            'field' => 'id',
            'where' => 'f.code="staffCategory"',
            'rules' => true
        ),
        'region' => array(
            'title'         => 'Регион',
            'style'         => 'width: 100%',
            'tablestyle'    => 'width: 210px;padding-left:10px;',
            'colType'       => 'select',
            'multy'         => true,
            'table'         => 'region',
            'link_table'    => 'staff_to_region',
            'linkfield'     => 'region',
            'field'         => 'staff',
            'outfield_lang' => 'title',
            'rulesCol'      => 'title',
            'islanged'      => false,
            'rules'         => true
        ),
        'image' => array(
            'title' => 'Фото',
            'colType' => 'image'
        ),
        'phone' => array(
            'title' => 'Телефоны (через запятую)',
            'tablestyle' => 'width:150px;',
            'colType' => 'text'
        ),
        'mail' => array(
            'title' => 'Ел. почта (через запятую)',
            'tablestyle' => 'width:220px;',
            'colType' => 'text'
        )
    ),
    'multylang_field' => array(
        'name' => array(
            'title' => 'ФИО',
            'tablestyle' => 'width:280px;',
            'colType' => 'text'
        ),
        'about' => array(
            'title' => 'О себе',
            'colType' => 'textarea'
        )
    ),
    'row_seq' => array('id', 'name', 'phone', 'mail', 'category', 'region', 'about', 'active')
);
