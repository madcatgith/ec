<?php

$GridTitle = 'Таблица товара';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'sort',
    'table' => 'product_attr',
    'as' => 'pattr',
    'lang' => 1,
    'limit' => 100,
    'islanged' => false,
    'style' => 'width:100%',
    'orderby' => 'pattr.sort',
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
		 'product_id' => array(
            'title'         => 'Товар',
            'style'         => 'width: 100%',
            'tablestyle'    => 'width: 210px;padding-left:10px;',
            'colType'       => 'select',
            'table'         => 'product',
            'field'         => 'id',
            'outfield' 		=> 'title',
            'islanged'      => true,
            'rules'         => true
        ),
    ),
    'multylang_field' => array(
        'weight' => array(
            'title' => 'Типоразмер',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
        ),
		'length' => array(
            'title' => 'Крутящий момент (NM) min',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
        ),
		'quantity' => array(
            'title' => 'Крутящий момент (NM) max',
            'tablestyle' => 'width:200px;',
            'colType' => 'text'
        ),
    ),
    'row_seq' => array('id','product_id','active')
);
