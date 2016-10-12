<?php 

$GridTitle        = "Тип кодируемого объекта";

$from         = array("table" => "barcodetypes", "as" => "ds", "style" => "width:100%",
						"nonlang_field" => array(
							"id"      => array(
														"title"   => "id",
														"style"   => "width: 40px;",
														"colType" => "lbl"
													),
							"title"  	=> array(
													"title"   => "Название",
													"colType" => "text"
												)
						)
						,"row_seq"			=> array("id", "title")
				); 