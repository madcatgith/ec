<?php

$GridTitle = 'Подписчики';
$from      = array(
    'table'         => 'subscribers',
    'lang'          => '1',
    'nonlang'       => true,
    'as'            => 's',
    'style'         => 'width:100%',
    'nonlang_field' => array(
        'id'       => array(
            'title'      => 'id',
            'tablestyle' => 'width: 50px;',
            'colType'    => 'lbl'
        ),
        'name'    => array(
            'title'   => 'Имя',
            'colType' => 'text'
        ),
        'email'    => array(
            'title'   => 'E-mail',
            'colType' => 'text'
        ),
        'active'   => array(
            'title'      => 'Активный',
            'tablestyle' => 'width: 120px;',
            'colType'    => 'check'
        ),
        'approved' => array(
            'title'      => 'Подтвержден',
            'tablestyle' => 'width: 120px;',
            'colType'    => 'check'
        ),
        'lang_id'  => array(
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
        ),
        'date'     => array(
            'title'      => 'Дата регистрации',
            'tablestyle' => 'width: 150px;',
            'colType'    => 'lbl'
        ),
        'ip'       => array(
            'title'      => 'IP',
            'tablestyle' => 'width: 150px;',
            'colType'    => 'lbl'
        )
    ),
    'row_seq'       => array('id', 'name', 'email', 'active', 'approved', 'ip', 'date', 'lang_id')
);
