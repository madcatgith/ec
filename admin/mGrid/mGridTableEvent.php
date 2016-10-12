<?php 

$GridTitle        = "События";

$from         = array ("table" => "event", "as" => "ev", "style" => "width:100%", "limit" => 50, "lang" => 1,

						//неязыковые поля приведенных таблиц
						"nonlang_field"		=> array (
								"id"      		=> array(
															"title"   => "id",
															"tablestyle"   => "width: 40px;",
															"colType" => "lbl"
												),
                                "active"         => array(
                                                            "title"   => "Активное",
                                                            "tablestyle"   => "width: 15px; "
                                                            ,"colType" => "check"
                                                        ),										
                                        		
								"status_id" 	=> array(
																	"title" => "Статус",
																	"tablestyle" => "width: 100px; white-space:normal;",
																	"colType" => "select",
																	
																	"islanged" => "true",
																	
																	"table" => "event_status",															
																	"outfield" => "title",															
																	"field" => "id",														
																	
																	"rules"   => true,
								),												
												
												/*
								"status_id" 	=> array(
														"title" => "Статус",
														"style"   => "width: 100%;",
														"tablestyle"   => "width: 100px; white-space:normal;",
														
														"colType" => "select",
														"rulesField" => "event_status",
														"rulesCol" => "title",
														"rules"   => true,
												),
												*/
								"cnc"  		=> array(
												"title"   => "ЧПУ",
												"cnc" 	  => "title",
												"colType" => "cnc"
											),
											
								"event_category"  	=> array(
												"title"   => "Категория",
												"style"   => "width: 100%;",
												"tablestyle"   => "width: 100px; white-space:normal;",
												
												"link_table" => "event_to_category",
												"field"	  => "title",
												
												"multy"	  => true,
												"rules"   => true,
												"colType" => "select"
											),											

								"recommend"  	=> array(
												"tablestyle"   => "width: 85px; "
												,"title"   => "Рекомендуем"
												,"colType" => "check"
											),											

								"imgurl_full"  	=> array(
												"title"   => "Фото 70х60"
												,"imagesizing"	=> array('height', 60, 'width', 70)
												,"tablestyle"   => "width: 70px; "
												,"colType" => "image"
											),											
											
								"event_tags"  	=> array(
												"title"   => "теги",
												"style"   => "width: 100%;",
												"tablestyle" => "width: 80px; white-space:normal;",
												
												"link_table" => "event_to_event_tags",
												"field"	  => "title",
												
												"multy"	  => true,
												"rules"   => true,
												"colType" => "select"
											),
											
								"imgurl2_full"  	=> array(
												"title"   => "Фото 200х110"
												,"imagesizing"	=> array('height', 50, 'width', 92)
												,"tablestyle"   => "width: 95px; "
												,"colType" => "image"
											),	
								/*			
								"imgurl3_full"  	=> array(
												"title"   => "Фото полное"
												,"imagesizing"	=> array('height', 100, 'width', 100)
												,"tablestyle"   => "width: 100px; "
												,"colType" => "image"
											),
											*/
								),	
						//мультиязыковые поля приведенных таблиц
						"multylang_field"	=> array (												
								"title"      		=> array(
															"title"   => "Название",
															"tablestyle"   => "width: 200px; "
															,"colType" => "text"
														),
                                                        
                                 "ticket_info"      => array(
                                                            "title"   => "Надпись на билете"
                                                            , "colType" => "textarea"
                                                            , "description" => "Описание, что будет печататься на билете под названием. Максимальная длина - 250 символов."
                                                        ),                                                         
                                                        
								 "text"      		=> array(
															"title"   => "Описание",
															"colType" => "textarea"
														), 
								"announcement" 		=> array(
															"title"   => "Аннонс",
															"tablestyle"   => "width: 400px; white-space:normal;",															
															"colType" => "textarea"
														),
								 "notice_author" 		=> array(
															"title"   => "Автор рецензии",
															"colType" => "text"
														),
								"notice" 		=> array(
															"title"   => "Рецензия карабаса",
															"colType" => "textarea"
														),															
								),
						"row_seq"			=> array("id", "active", "title", "recommend", "status_id", "event_category"
													, "event_tags", "announcement", "imgurl_full","imgurl2_full","imgurl3_full")
				);