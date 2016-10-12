<?php

$GridTitle = "Свойства для товаров";
$from = array(
    "table" => "dictionary",
    "as" => "dic",
    "lang" => "1",
    "limit" => 100,
    "islanged" => false,
    "style" => "width:100%",
    "nonlang_field" => array(
        "id" => array(
            "title" => "ID"
            , "tablestyle" => "width: 40px;"
            , "colType" => "lbl"
        ), /*
      'parentID' => array(
      "title"           => "Категория"
      , "style"         => "width: 100%;"
      , "colType"       => "select"
      , "table"         => "dictionary"
      , 'field'         => "id"
      , "outfield_lang" => "title"
      , "islanged"      => false
      , "rules"         => true
      , 'where'         => ' f.parentID=0 '
      ) */
    ),
    "multylang_field" => array(
        "title" => array(
            "title" => "Название",
            "colType" => "textarea",
            "tablestyle" => "padding-left:10px;"
        )
    ),
    "row_seq" => array('id', /* 'parentID', */ 'title')
);
