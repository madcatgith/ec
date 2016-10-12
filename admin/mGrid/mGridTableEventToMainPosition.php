<?php 

$GridTitle        = "Позиции блоков в интерактивной афише";
$from         = array("table" => "event_to_main_position", "as" => "etmp", "style" => "width:100%",
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
						),
						"row_seq"			=> array("id", "title")
				);