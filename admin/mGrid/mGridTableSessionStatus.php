<?php 

$GridTitle        = "Статус мероприятия";
$from         = array("table" => "session_status", "as" => "st", "lang" => "1", "islanged" => true, "style" => "width:100%",
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
                        ),
                        "row_seq"            => array("id", "title")
                );
