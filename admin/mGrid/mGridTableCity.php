<?php

$GridTitle = 'Города';
$from      = array(
    'table'           => 'city',
    'as'              => 'city',
    'lang'            => '1',
    'limit'           => 100,
    'islanged'        => false,
    'style'           => 'width:100%',
    'nonlang_field'   => array(
        'id'     => array(
            'title'      => 'ID',
            'tablestyle' => 'width: 40px;',
            'colType'    => 'lbl'
        ),
        'cnc'    => array(
            'title'      => 'Код',
            'style'      => 'width: 100px;',
            'tablestyle' => 'width: 100px;padding-left:10px;',
            'colType'    => 'cnc',
            'fields'     => array(
                'title' => 'text'
            )
        ),
        'active' => array(
            'title'      => 'Активность',
            'colType'    => 'check',
            'tablestyle' => 'width: 70px; text-align:center;'
        )
    ),
    'multylang_field' => array(
        'title'          => array(
            'title'      => 'Город',
            'colType'    => 'text',
            'tablestyle' => 'width:100%;padding-left:10px;'
        ),
        'SEOKeywords'    => array(
            'title'      => 'SEO Keywords',
            'colType'    => 'text',
            'tablestyle' => 'width:100%;padding-left:10px;'
        ),
        'SEOTitle'       => array(
            'title'      => 'SEO Title',
            'colType'    => 'text',
            'tablestyle' => 'width:100%;padding-left:10px;'
        ),
        'SEODescription' => array(
            'title'      => 'SEO Dedcription',
            'colType'    => 'text',
            'tablestyle' => 'width:100%;padding-left:10px;'
        )
    ),
    'row_seq'         => array('id', 'cnc', 'title', 'SEOTitle', 'SEOKeywords', 'SEODescription', 'active', 'place')
);
