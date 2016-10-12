<?php 

$GridTitle        = "Мероприятия";

$from         = array ("table" => "session", "as" => "ev", "style" => "width:100%", "limit" => 50, "lang" => 1,

						//неязыковые поля приведенных таблиц
						"nonlang_field"		=> array (
								 "id"      		=> array(
															"title"   => "id",
															"tablestyle"   => "width: 40px;",
															"colType" => "lbl"
												),
                                                
                                "status"     => array(
                                                                    "title" => "Статус",
                                                                    "tablestyle" => "width: 100px; white-space:normal;",
                                                                    "colType" => "select",
                                                                    
                                                                    "islanged" => "true",
                                                                    
                                                                    "table" => "session_status",                                                            
                                                                    "outfield" => "title",                                                            
                                                                    "field" => "id",                                                        
                                                                    
                                                                    "rules"   => true,
                                ),
                                                                                
                                                
								"event_id" 	=> array(
															"title" => "Событие",
															"style" => "width: 100px; ",
															"tablestyle" => "width: 300px; white-space:normal;",
															"colType" => "select",

															"table" => "event",	
															"outfield_lang" => "title",
															"field" => "id",
															
															"where" => "f.active = 1",

															"rules"   => true,
												),
									
								"date_from"      		=> array(
															"title"   => "Начало",	
															"tablestyle"   => "width: 120px;",														
															"colType" => "datetime"
												),											
								"date_to"      		=> array(
															"title"   => "Окончание",		
															"tablestyle"   => "width: 120px;",													
															"colType" => "datetime"
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
								"price_from"      		=> array(
															"title"   => "Цена от",
															"colType" => "text"
														),
								"price_to"      		=> array(
															"title"   => "Цена до",
															"colType" => "text"
														),
															
						),	
						//мультиязыковые поля приведенных таблиц
						"multylang_field"	=> array (												
								"title"      		=> array(
															"title"   => "Название",
															"colType" => "text"
														),
                                "message"              => array(
                                                            "title"   => "Сообщение на билете"
                                                            , "colType" => "textarea"
                                                            , "description" => "Сообщение, что будет печататься на билете, возле цены. <br />
                                                                <b>'Будет вестись видео-съемка', 'При входе жесткий дресс-код'</b>. 
                                                                Максимальная длина - 250 символов."
                                                        ),    
								),
						"row_seq"			=> array("id", 'status', "city", "hall", "map_id", "event_id", "date_from", "date_to", "title")
				);