<?php 

$GridTitle        = "Место проведения мероприятий";

$from = array (
    "table" => "informers"
    , "as" => "inf"
    , "lang" => "1"
    , "nonlang" => true
    , "style" => "width:100%"
    
    , "nonlang_field" => array (
        "id" => array (
            "title"   => "id",
            "tablestyle" => "width: 40px;",
            "colType" => "lbl"
        )
        
        , "title" => array (
            "title" => "Название"
            , "tablestyle" => "width: 200px;"
            , "colType" => "text"
        )
        
        , "command" => array (
            "title" => "Комманда на исполнение"
            , "tablestyle" => "width: 100%;"
            , "colType" => "text"
        )
        
        , "params" => array (
            "title" => "Параметры комманды"
            , "tablestyle" => "width: 100%;"
            , "colType" => "text"
        )
    )
    
    , "row_seq" => array(
        "id"
        , "title"
        , "command"
        , "params"
    )
);
        
?>