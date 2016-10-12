<?php

$GridTitle        = "Теги для событий";
$from         = array("table" => "event_tags", "as" => "ec", "lang" => "1", "limit" => 100, "islanged" => true, "style" => "width:100%",

                    "multylang_field" => array(
                        "id"      => array(
                                                "title"   => "id",
                                                "tablestyle" => "width: 40px;",
                                                "colType" => "lbl"
                                            ),
                        "title"      => array(
                                                "title"   => "Название",
                                                "colType" => "text"
                                            ),                        
                        "cnc"      => array(
                                                "title"   => "ЧПУ",
                                                "cnc"       => "title",
                                                "colType" => "cnc"
                                            ),                        
                        "active"      => array(
                                                "title"   => "активный",
                                                "tablestyle" => "width: 80px;",
                                                "colType" => "check"
                                            ),                
                        "description"      => array(
                                                "title"   => "Описание",
                                                "colType" => "textarea"
                                            ),
                    ),
                    "row_seq"            => array("id", "title", "active", "cnc", "description")                                                                        
);