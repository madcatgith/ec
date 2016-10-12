<?php

$GridTitle = 'Словарь уникальных значений Dictionary::GetUniqueWord(id)';
$from      = array(
    'table'           => 'dic_unique',
    'as'              => 'dic',
    'lang'            => 3,
    'limit'           => 500,
    'islanged'        => true,
    'style'           => 'width:100%',
    'multylang_field' => array(
        'id'      => array(
            'title'      => 'id',
            'tablestyle' => 'width: 40px;',
            'colType'    => 'lbl'
        ),
        'code'    => array(
            'title'   => 'Код',
            'colType' => 'text'
        ),
        'title'   => array(
            'title'   => 'Название',
            'colType' => 'text'
        ),
        /*'date_lm' => array(
            'title'   => 'Дата окончания',
            'colType' => 'datetime'
        ),
        'cat'     => array(
            'title'   => 'Категория',
            'colType' => 'text'
        )*/
    ),
    'row_seq'         => array('id', 'code', 'title')
);
