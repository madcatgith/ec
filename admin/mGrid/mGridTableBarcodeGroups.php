<?php 

$GridTitle        = "Группы кодируемых объектов";

$from         = array("table" => "barcode_group", "as" => "ds", "style" => "width:100%",
                        "nonlang_field" => array(
                            "id"      => array(
                                                        "title"   => "id",
                                                        "style"   => "width: 40px;",
                                                        "colType" => "lbl"
                                                    ),
                            "title"      => array(
                                                    "title"   => "Название",
                                                    "colType" => "text"
                                                )
                            ,"date"      => array(
                                                    "title"   => "Время старта генерации",
                                                    "colType" => "datetime"
                                                )
                            ,"comment"      => array(
                                                    "title"   => "Описание",
                                                    "colType" => "text"
                                                )                                                
                        )
                        ,"row_seq"            => array("id", "title", 'comment')
                ); 