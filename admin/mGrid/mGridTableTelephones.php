<?php

$GridTitle = "Телефоны";
$from = array(
    "table" => "telephone",
    "as" => "tel",
    "lang" => "1",
    "limit" => 200,
    "islanged" => false,
    "style" => "width:100%",
    "nonlang_field" => array(
        "id" => array(
            "title" => "id",
            "tablestyle" => "width: 40px;",
            "colType" => "lbl"
        ),
        'active' => array(
            'title' => 'Активность',
            'colType' => 'check'
        ),
        'tel' => array(
            'title' => 'Телефон',
            'colType' => 'text'
        ),
        'sort' => array(
            'title' => 'Сортировка',
            'colType' => 'text'
        )
    ),
    "multylang_field" => array(
        'name' => array(
            'title' => 'Название города',
            'colType' => 'text'
        )
    ),
    'row_seq' => array('id', 'name', 'tel', 'sort', 'active')
);
