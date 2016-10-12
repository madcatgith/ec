<?php 

$GridTitle = "Справочники";
$from = array(
		"table" => "menu_admin",
		"as" => "m",
		"lang" => "1",
		"limit" => 100,
		"is_langed" => true,
		"style" => "width:100%",
		
		"nonlang_field" => array(
		        "id" => array (
												"title" => "ID"
												, "tablestyle" => "width: 40px;"
												, "colType" => "lbl"
						),	
            
            "parent_id" => array (
                        "title" => "Родительский пункт"
                        , "tablestyle" => "width: 100px;"
                        , "colType" => "select"
                        , "rules" => true
                        , "multy" => false
                        
                        , "table" => "menu_admin"
                        , "field" => "id"
                        , "outfield_lang" => "title"
                        , "is_langed" => true
            ),

						"active" => array (
                        "title" => "Выводить"
                        , "colType" => "check"
                        , "tablestyle" => "width: 70px; text-align:center;"
            ),
            
            "is_mgrid_table" => array (
                        "title" => "Является таблицей mGrid"
                        , "colType" => "check"
                        , "tablestyle" => "width: 70px; text-align:center;"
            ),
            
            "url" => array (
												"title" => "URL"
												, "colType" => "text"
												, "style" => "width: 100px;"
												, "tablestyle" => "width: 100px;padding-left:10px;"
						),
						
						//"module_id" => array (
//												"title" => "Название модуля"
//												, "colType" => "text"
//												, "style" => "width: 100px;"
//												, "tablestyle" => "width: 100px;padding-left:10px;"
//						),
		),
		"multylang_field" => array (
						"title" => array (
												"title" => "Название пункта меню"
												, "colType" => "text"
												, "tablestyle" => "width:100%;padding-left:10px;"
						)
		),
		"row_seq"	=> array(
							"id"
							, "active"							
							, "title"
              , "is_mgrid_table"
							//, "module_id"
							, "url"  
		)
);