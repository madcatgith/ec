<?php 

$GridTitle        = "Партнёры";

$from = array (
    "table" => "Partner"
    , "as" => "par"
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
        
        , "url" => array (
            "title" => "Ссылка на сайт"
            , "colType" => "text"
        )
        
        , "img" => array (
            "title" => "Фото"
            ,"imagesizing"    => array('height', 48, 'width', 48)
            ,"tablestyle" => "width: 60px; "
            ,"colType" => "image"
        )
    )
    
    , "row_seq" => array(
        "id"
        , "title"
        , "img"
        , "url"
    )
);
        
?>