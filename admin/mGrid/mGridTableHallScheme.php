<?php 

$GridTitle        = "Цпе";
$from         = array("table" => "hall_scheme", "as" => "hs", "style" => "width:100%", "lang" => 1, "nonlang" => true,
						"nonlang_field" => array(
							"id"         => array(
													"title"   => "id",
													"tablestyle" => "width: 40px;",
													"colType" => "lbl"
												),
							"title"      => array(
													"title"   => "размер картинки",
													"colType" => "text"
												),
								"city" 	=> array(
															"title" => "Город",
															"style" => "width: 100px; ",
															"tablestyle" => "width: 100px; white-space:normal;",
															"colType" => "select",
															"islanged" => "true",
															"table" => "event_city",	
															"outfield" => "title",
															"field" => "id",
															"colChild" => "hall",
															"rules"   => true,
								),

								"hall" 	=> array(
															"title" => "Место проведения",
															"style" => "width: 100px; ",
															"tablestyle" => "width: 100px; white-space:normal;",
															"colType" => "select",
															"islanged" => "true",
															"table" => "hall",	
															"outfield" => "alias",
															"field" => "id",
															"colChild" => "map_id",
															"colParent" => "city",
															"connField" => "city",
															
															"rules"   => true,
								),
								"map_id" 	=> array(
															"title" => "Зал",
															"style" => "width: 100px; ",
															"tablestyle" => "width: 100px; white-space:normal;",
															"colType" => "select",
															"table" => "hall_map",
															"outfield" => "alias",
															"field" => "id",
															"colParent" => "hall",
															"connField" => "hall_id",

															"rules"   => true,
								),								
				
						),
						"row_seq"			=> array("id", "city", "hall", "map_id", "title")
				);