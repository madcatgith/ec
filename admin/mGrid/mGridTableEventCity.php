<?php 

$GridTitle        = "Города проведения мероприятий";
$from         = array("table" => "event_city", "as" => "ec", "lang" => "1", "limit" => 100, "islanged" => true, "style" => "width:100%",

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
						"active"  	=> array(
												"title"   => "активный",
												"colType" => "check"
											),				
						"default_city"  	=> array(
												"title"   => "по умолчанию",
												"colType" => "check"
											),
						"place"      => array(
												"title"   => "Порядок показа",
												"colType" => "text"
											),
					),
					"row_seq"			=> array("id", "title", "cnc", "active", "default_city", "place")																		
);