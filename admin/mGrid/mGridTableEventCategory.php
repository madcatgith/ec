<?php 

$GridTitle        = "Категории мероприятий";
$from         = array("table" => "event_category", "as" => "ec", "lang" => "1", "limit" => 100, "islanged" => true, "style" => "width:100%",

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
						"cnc"  	=> array(
												"title"   => "ЧПУ",
												"cnc" 	  => "title",
												"colType" => "cnc"
											),
						"color"  	=> array(
												"title"   => "Цвет"
												,"colType" => "color"
												,"description" => "хранится некоторый цвет. Для чего нужен, не определено"
											),						
						"active"  	=> array(
												"title"   => "активный",
												"colType" => "check"
											),	
						"default_category"  	=> array(
												"title"   => "по умолчанию",
												"colType" => "check"
											),												
						"description"  	=> array(
												"title"   => "описание",
												"colType" => "textarea"
											),
						"place"      => array(
												"title"   => "Порядок показа",
												"colType" => "text"
											),
											/*
						"category_id"  => array(
												"title" => "родительский элемент",
												"style"   => "white-space:normal;",
												"colType" => "select",
												"rulesField" => "event_category",
												"rulesCol" => "title",
												"rules"   => true,
										)		*/										
					),
					"row_seq"			=> array("id", "title", "color", "cnc", "active", "default_category", "description", "place")																		
);