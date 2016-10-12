<?php 

$title        = "Статус заказа";

$from         = array("table" => "order_status", "as" => "os", "lang" => "1", "style" => "width:100%",
						"normal_field" => array(
							"id"      => array(
													"title"   => "id",
													"style"   => "width: 40px;",
													"colType" => "lbl"
												),
							"title"  	=> array(
													"title"   => "Название",
													"colType" => "text"
												),
							"color"  	=> array(
													"title"   => "Цвет",
													"colType" => "text"
												),						
							"active"  	=> array(
													"title"   => "Активность",
													"colType" => "check"
												)
																		
						)
				);