<?php

$GridTitle = 'Управления залами';
$from      = array(
    'table'           => 'hall',
    'as'              => 'hall',
    'lang'            => '1',
    'style'           => 'width:100%',
    'nonlang_field'   => array(
        'id'       => array(
            'title'      => 'ID',
            'tablestyle' => 'width: 40px;',
            'colType'    => 'lbl'
        ),
        'regionID' => array(
            'title'         => 'Регион',
            'style'         => 'width: 100%;',
            'colType'       => 'select',
            'table'         => 'tag',
            'outfield_lang' => 'title',
            'field'         => 'id',
            'where'         => ' f.parentID=1 ',
            'colChild'      => 'cityID',
            'useColChild'   => true,
            'rules'         => true
        ),
        'cityID'   => array(
            'title'         => 'Город',
            'style'         => 'width: 100%;',
            'colType'       => 'select',
            'table'         => 'tag',
            'outfield_lang' => 'title',
            'field'         => 'id',
            'colParent'     => 'regionID',
            'connField'     => 'parentID',
            'rules'         => true
        )
    ),
    'multylang_field' => array(
        'title'   => array(
            'title'   => 'Название',
            'colType' => 'text'
        ),
        'address' => array(
            'title'   => 'Адрес',
            'colType' => 'text'
        ),
        'src'     => array(
            'title'       => 'Схема зала',
            'colType'     => 'image',
            'description' => 'Схема залов SVG'
        )
    ),
    'row_seq'         => array('id', 'regionID', 'cityID', 'title', 'address')
);
