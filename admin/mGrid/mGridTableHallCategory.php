<?php 

$GridTitle        = "Категории мест проведения";
$from         = array("table" => "hall_category", "as" => "hc", "lang" => "1", "islanged" => true, "style" => "width:100%",
						"multylang_field" => array(
							"id"         => array(
													"title"   => "id",
													"tablestyle" => "width: 40px;",
													"colType" => "lbl"
												),
							"title"      => array(
													"title"   => "Название",
													"colType" => "text"
												),
												/*						
							"parent_id"  => array(
													"title" => "родительский элемент",
													"style"   => "white-space:normal;",
													"colType" => "select",
													"rulesField" => "hall_category",
													"rulesCol" => "title",
													"rules"   => true,
											)	*/					
						),
						"row_seq"			=> array("id", "title", "parent_id")
				);