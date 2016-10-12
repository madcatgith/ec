<?php 

$GridTitle = "Прайс-лист";
$from      = array (
    "table"           => "price_list"
    , "as"            => "pl"
    , "lang"          => "1"
    , "style"         => "width:100%"
    , "nonlang_field" => array (
        "id" => array (
            "title"      => "id",
            "tablestyle" => "width: 40px;",
            "colType"    => "lbl"
        )
        , "price" => array (
            "title"        => "Цена"
            , "tablestyle" => "width: 200px;"
            , "colType"    => "text"
        )
        , "active" => array (
            "title"     => "Активность"
            , "colType" => "check"
            , "tablestyle" => "width: 120px; text-align: center;",
        )
        , "inBlock" => array (
            "title"     => "Выводить в превью"
            , "colType" => "check"
            , "tablestyle" => "width: 120px; text-align: center;",
        )
        , "place" => array (
            "title"        => "Позиция"
            , "colType"    => "text"
            , "tablestyle" => "width: 80px; text-align: center;",
        )
        , 'menu' => array(
            'title'        => 'Пункты меню'
            , 'style'      => 'width: 200px;'
            , 'tablestyle' => 'white-space:normal;'
            , 'link_table' => 'price_list_to_menu'
            , 'table'      => 'menu'
            , 'linkfield'  => 'menu'
            , 'field'      => 'price_list'
            , 'outfield'   => 'title'
            , 'multy'      => true
            , 'rules'      => true
            , 'islanged'   => true
            , 'colType'    => 'select'
            , 'where'      => 'f.menu_id=6'
        )
    )
    , "multylang_field" => array(
	    "title" => array(
	        "title"     => "Название"
	        , "colType" => "text"
	    )
    )
    , "row_seq" => array(
        "id"
        , "title"
        , "menu"
        , "price"
        , "active"
        , "inBlock"
        , "place"
    )
);
        
?>