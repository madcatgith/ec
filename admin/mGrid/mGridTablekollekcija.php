<?php 

$GridTitle = "Коллекции";
$from      = array(
	"table" => "kollekcija", 
	"as" => "zas", 
	"style" => "width: 100%;",
	"nonlang_field"        => array (
			"id"              => array(
			//отображаемое название поля
			"title"   => "id",
			// css-стиль отображения в редакторе
			"style"   => "width: 100%;",
			// css-стиль отображения в таблице
			"tablestyle"   => "width: 40px;",
			// тип поля лейбл, который выводится, но не редактируется
			"colType" => "lbl"
		),
		"title"         => array(
			"title"   => "Коллекция",
			"colType" => "text",
			"search"  => true
		),
		"active"      => array(
			"title"   => "активный",
			"colType" => "check"
		),
	),
	"row_seq"            => array("id", "title", "active")
);