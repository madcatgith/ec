<?php

$GridTitle = "Заказы";
$from      = array(
    "table"           => "simpleForm"
    , "as"            => "sf"
    , "nonlang"       => true
    , "lang"          => 1
    , "style"         => "width:100%;"
    , "nonlang_field" => array (
	    "id" => array(
		    "title"      => "id",
		    "style"      => "width: 100%;",
		    "tablestyle" => "width: 40px;",
		    "colType"    => "lbl"
        )
	, "text"         => array(
								"title"   => "Текст",
								"colType" => "textarea",
								"search"  => true
							)
	    , "active" => array(                            
			"title"   => "Доставленно",
			"tablestyle"   => "width: 110px;",
			"colType" => "check"
        ),
    ),
    "row_seq" => array("id", "text", "active")
);