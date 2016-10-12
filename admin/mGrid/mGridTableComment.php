<?

$title        = "Комментарии";

$from         = array("table" => "comment", "as" => "c", "lang" => "1", "style" => "width:900px",

						"normal_field" => array(
							"id"      => array(
													"title"   => "id",
													"style"   => "width: 40px; text-align: center;",
													"colType" => "lbl"
												),	
							"user_id" => array(
													"title" => "Пользователь",
													"style"   => "width: 170px; text-align: center;",
													"colType" => "text",
													"rulesField" => "user",
													"rulesCol" => "email",
													"rules"   => true,
													"nonlang" => true
											),
							"text"  	=> array(
													"title"   => "Комментарий",
													"style"   => "min-width: 300px;white-space:normal;",
													"colType" => "textarea",
												),
							"add_time"  	=> array(
													"title"   => "Время добавления",
													"style"   => "width: 120px; text-align: center;",
													"colType" => "date"
												),						
							"active"  	=> array(
													"title"   => "Активность",
													"style"   => "width: 70px;",
													"colType" => "check"
												)
																		
						)
				);