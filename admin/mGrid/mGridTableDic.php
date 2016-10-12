<?php 

$GridTitle        = "Словарь системных слов Dictionary::GetWord(id)";
$from  = array(
		"table" => "dic", 
		"as" => "dc", 
		"lang" => "1", 
		"limit" => 200, 
		"islanged" => true, 
		"style" => "width:100%",  					
		"multylang_field" => array(
												"id"      => array( 
																			"title"   => "id",
																			"tablestyle" => "width: 40px;",
																			"colType" => "lbl"
																			),						
												"code"		=> array(												
																			"title"   => "Код",
																			"colType" => "text"											
																			),						
												"title"		=> array(												
																			"title"   => "Название",
																			"colType" => "text"											
																			),						                 
												"date_lm" => array(
																			"title"   => "дата окончания",
																			"colType" => "datetime"											
																			),
												"cat"     => array(
																			"title"   => "категория",
																			"colType" => "text"
																						),                                            
		),
		"row_seq"			=> array("id", "code", "title", "date_lm", "cat")																		
);
