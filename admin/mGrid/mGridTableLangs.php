<?php 

$GridTitle        = "управление языками";
$from         = array("table" => "lang", "as" => "bl", "limit" => 500, "nonlang" => true, "style" => "width:100%",
                        "orderby" => "place",

                    "nonlang_field" => array(
                        "id"      => array(
                                                "title"   => "id",
                                                "tablestyle" => "width: 40px;",
                                                "colType" => "lbl"
                                            ),

                        "active"      => array(
                                                "title"   => "Активный"
                                                , "tablestyle" => "width: 70px;"
                                                , "colType" => "check"
                                            ),                                            
                                           
                        "users_default"      => array(
                                                "title"   => "По-умолчанию для сайта"
                                                , "tablestyle" => "width: 100px;"
                                                , "colType" => "check"
                                            ),

                        "admin_default"      => array(
                                                "title"   => "По-умолчанию для администраторской панели"
                                                , "tablestyle" => "width: 100px;"
                                                , "colType" => "check"
                                            ),
                                            
                        "title"      => array(
                                                "title"   => "Полное название",
                                                "colType" => "text"
                                                , "tablestyle" => "width: 70px;"
                                            ),
                        "title_short"      => array(
                                                "title"   => "Короткое название",
                                                "colType" => "text"
                                                , "tablestyle" => "width: 150px;"
                                            ),
                        "http_accept_language"      => array(
                                                "title"   => "ЧПУ",
                                                "colType" => "text"
                                                , "tablestyle" => "width: 150px;"
                                            ),  
                                                                                      
                        "place"      => array(
                                                "title"   => "Порядок показа",
                                                "colType" => "text"
                                                , "tablestyle" => "width: 150px;"
                                            ),
                        "img" => array(
                            "title" => "Лого",
                            "colType" => "image"
                        )
                    ),
                    "row_seq"            => array("id", "active", "users_default", "admin_default", "title", 'title_short', 'http_accept_language')
);