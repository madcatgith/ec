<?php

$GridTitle = 'Резервирование';
$from      = array(
    'table'         => 'reservation',
    'as'            => 'r',
    'lang'          => '1',
    'limit'         => 100,
    'nonlang'       => true,
    'style'         => 'width:100%',
    'orderby'       => ' r.id desc ',
    'nonlang_field' => array(
        'id'         => array(
            'title'      => 'ID',
            'tablestyle' => 'width: 40px;',
            'colType'    => 'lbl'
        ),
        'hotelID'    => array(
            'title'    => 'Отель',
            'style'    => 'width: 100%',
            'colType'  => 'select',
            'islanged' => true,
            'table'    => 'menu',
            'outfield' => 'title',
            'field'    => 'id',
            'rules'    => true,
            'where'    => 'f.lang_id=1',
            'colChild' => 'roomID'
        ),
        'inProgress' => array(
            'title'      => 'Обработан',
            'tablestyle' => 'width: 70px; ',
            'colType'    => 'check'
        ),
        'roomID'     => array(
            'title'     => 'Номер',
            'style'     => 'width: 100%',
            'colType'   => 'select',
            'islanged'  => true,
            'table'     => 'product',
            'outfield'  => 'title',
            'field'     => 'id',
            'rules'     => true,
            'where'     => 'f.lang_id=1',
            'colParent' => 'hotelID',
            'connField' => 'category_id'
        ),
        'name'       => array(
            'title'   => 'Имя',
            'colType' => 'text',
            'colType' => 'text'
        ),
        'phone'      => array(
            'title'   => 'Телефон',
            'colType' => 'text'
        ),
        'from'       => array(
            'title'   => 'Дата с',
            'colType' => 'date'
        ),
        'to'         => array(
            'title'   => 'Дата по',
            'colType' => 'date'
        ),
        'adult'      => array(
            'title'      => 'Кол. взрослых',
            'tablestyle' => 'width: 90px; ',
            'colType'    => 'text'
        ),
        'children'   => array(
            'title'      => 'Кол. детей',
            'tablestyle' => 'width: 75px;',
            'colType'    => 'text'
        ),
        'text'       => array(
            'title'   => 'Тест',
            'colType' => 'textarea'
        ),
        'lang_id'    => array(
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
    'row_seq'       => array('id', 'hotelID', 'roomID', 'name', 'phone', 'from', 'to', 'adult', 'children', 'text', 'inProgress')
);
