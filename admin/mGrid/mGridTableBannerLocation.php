<?php

$GridTitle = "Баннерные места";
$from      = array("table" => "ban_location", "as" => "bl", "limit" => 500, "nonlang" => true, "style" => "width:100%",

	"nonlang_field" => array(
    		"id"      => array(
								"title"   => "id",
								"tablestyle" => "width: 40px;",
								"colType" => "lbl"
							),
    /*"special"      => array(
                                "title"   => "Особый баннер"
                                , "tablestyle" => "width: 90px;"
                                , "colType" => "check"
                            ),*/
		"title"      => array(
								"title"   => "Название",
								"colType" => "text"
							),
        "ismulty"      => array(
                                "title"   => "Массовый баннер"
                                , "tablestyle" => "width: 70px;"
                                , "colType" => "check"
                            ),
        "description"      => array(
                                "title"   => "Описание",
                                "colType" => "textarea"
                            ),

		"active"  	=> array(
								"title"   => "Активный"
								, "tablestyle" => "width: 70px;"
								, "colType" => "check"
							),
     ),
    "row_seq" => array("id", "active", /*"special",*/ "ismulty", "title", 'description')
);