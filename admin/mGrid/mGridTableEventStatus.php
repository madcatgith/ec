<?php 

$GridTitle        = "Статус события";
$from         = array("table" => "event_status", "as" => "st", "lang" => "1", "islanged" => true, "style" => "width:100%",
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
							"imgurl_full"  	=> array(
													"title"   => "иконка",
													"colType" => "image"
												),							
							"place"      => array(
													"title"   => "Порядок показа",
													"colType" => "text"
												),							
						),
						"row_seq"			=> array("id", "title", "cnc", "active", "imgurl_full", "place")
				);
