<?php 

$GridTitle        = "Залы";

$from         = array("table" => "hall_map", "as" => "ha", "lang" => "1", "style" => "width:100%",

						//неязыковые поля приведенных таблиц
						"nonlang_field"		=> array (
								"id"   		=> array(
															"title"   => "id",
															"tablestyle" => "width: 40px;",
															"colType" => "lbl"
											),
								"city" 	=> array(
															"title" => "Город",
															"tablestyle" => "width: 100px; white-space:normal;",
															"colType" => "select",
															"islanged" => "true",
															
															"table" => "event_city",
															"outfield" => "title",	
															"field" => "id",

															"colChild" => "hall_id",
															"rules"   => true,
											),
											
								"hall_id" 	=> array(
															"title" => "Место проведения мероприятия",
															"tablestyle" => "width: 200px; white-space:normal;",
															"colType" => "select",
															"islanged" => "true",
															"table" => "hall",	
															"outfield" => "alias",
															"field" => "id",
																
															"colParent" => "city",
															"connField" => "city",
															"rules"   => true,
											),
								"alias"  	=> array(
													"title"   => "Алиас",
													"tablestyle" => "width: 200px; white-space:normal;",
													"colType" => "text"
											),
						),
						"multylang_field" => array(
							"title"  	=> array(
													"title"   => "Название",
													"colType" => "text"
											),				
						)
						,"row_seq"			=> array("id", "city", "hall_id", "alias", "title")
				);