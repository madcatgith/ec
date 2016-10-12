<?php

$GridTitle = 'Обратный звонок';
$from      = array(
    'table'         => 'callback',
    'as'            => 'c',
    'lang'          => 1,
    'nonlang'       => true,
    'limit'         => 100,
    'style'         => 'width:100%',
    'orderby'       => ' c.id desc ',
    'nonlang_field' => array(
        'id'        => array(
            'title'      => 'id',
            'style'      => 'width: 40px; text-align: center;',
            'tablestyle' => 'width: 40px; white-space:normal;',
            'colType'    => 'lbl'
        ),
        'name'      => array(
            'title'   => 'Имя',
            'style'   => 'min-width: 300px;white-space:normal;',
            'colType' => 'text',
        ),
        'phone'     => array(
            'title'   => 'Телефон',
            'style'   => 'min-width: 300px;white-space:normal;',
            'colType' => 'text',
        ),
        'email'     => array(
            'title'   => 'E-mail',
            'style'   => 'min-width: 300px;white-space:normal;',
            'colType' => 'text',
        ),        
        'text'      => array(
            'title'   => 'Описание',
            'style'   => 'min-width: 300px;white-space:normal;',
            'colType' => 'textarea',
        ),
        'processed' => array(
            'title'      => 'Обработан',
            'style'      => 'width: 120px; text-align: center;',
            'tablestyle' => 'width: 120px; white-space:normal;',
            'colType'    => 'check'
        ),
        'lang_id'   => array(
            'title'      => 'Язык',
            'style'      => 'width: 200px;',
            'tablestyle' => 'width: 160px; white-space:normal;',
            'colType'    => 'select',
            'nonlang'    => 'true',
            'table'      => 'lang',
            'outfield'   => 'title',
            'field'      => 'id',
            'rules'      => true,
            'where'      => 'f.active=1'
        )
    ),
    'row_seq'       => array('id', 'name', 'phone', 'email', 'text', 'lang_id', 'processed')
);
