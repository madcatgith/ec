<?php

$GridTitle = "Заказы";
$from      = array(
    "table"           => "zakaz"
    , "as"            => "zas"
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
	, "date"         => array(
								"title"   => "Дата",
								"colType" => "text",
                                "tablestyle" => "width: 60px;",
								"search"  => true
							),
	"fio"         => array(
								"title"   => "Заказчик",
								"colType" => "text",
								"search"  => true
							),
	"tel"         => array(
								"title"   => "Телефон",
								"colType" => "text",
								"search"  => true
							),
	"emails"         => array(
								"title"   => "Почта",
								"colType" => "text",
								"search"  => true
							),
	"address"         => array(
								"title"   => "Адрес",
								"colType" => "text",
								"search"  => true
							),							
    "country"         => array(
                                "title"   => "Страна",
                                "colType" => "text",
                                "search"  => true
                            ),
    "city"         => array(
                                "title"   => "Город",
                                "colType" => "text",
                                "search"  => true
                            ),
    "comment"         => array(
                                "title"   => "Коментарий",
                                "colType" => "textarea",
                                "tablestyle"   => "width: 160px;",
                                "search"  => true
                            ),
	"koment"         => array(
								"title"   => "Доп. Инфо",
								"colType" => "textarea",
                                "tablestyle"   => "width: 240px;",
								"search"  => true
							),
	"zakaz"         => array(
								"title"   => "Заказ",
								"colType" => "textarea",
                                "tablestyle"   => "width: 200px;",
								"search"  => true
							),
	    "active" => array(
			"title"   => "Доставленно",
			"colType" => "check"
        ),
    ),
    "row_seq" => array("id", "date", "fio", "tel", "emails", /*"country",*/ "city", 'address', "comment", "koment", "active")
);