<?php


function createSprite($ID)
{

	$spriteQuery = DB::Query('select * from ?_map_mark_type where id=' . intval($ID) . ' order by id limit 1');
	
	while ($row = DB::GetArray($spriteQuery)) {
		
		$images = new Images;
		
		$images->addImage(new Image($_SERVER['DOCUMENT_ROOT'] . $row['img']));
		$images->addImage(new Image($_SERVER['DOCUMENT_ROOT'] . $row['img_hover']));
		$images->addImage(new Image($_SERVER['DOCUMENT_ROOT'] . $row['img_active']));

		$images->combine('vertical')->save($_SERVER['DOCUMENT_ROOT'] . '/files_small/map_sprite_' . $row['id'] . '.png');

	}
}

$GridTitle = 'Обозначения';
$from      = array(
	'table'    => 'map_mark_type',
	'as'       => 'mapmarktype',
	'lang'     => '1',
	'limit'    => 100,
	'style'    => 'width:100%',
	'callback' => array(
		'onInsert'   => array('createSprite')
		, 'onUpdate' => array('createSprite')
		, 'onDelete' => array('createSprite')
	),	
	'nonlang_field' => array(
		'id' => array (
			'title'        => 'ID'
			, 'tablestyle' => 'width: 40px;'
			, 'colType'    => 'lbl'
		)
		, "typeID" => array(
			"title"        => "Тип карты"
			, "tablestyle" => "width: 220px;"
			, "colType"    => "select"
			, "table"      => 'map_type_lang'
			, "outfield"   => "title"
			, "field"      => "id"
			, "islanged"   => true
            , "rules"      => true
		)  
		, 'img' => array (
			'title'        => 'Изображение'
			, 'tablestyle' => 'width: 100px;'
			, 'colType'    => 'image'
			, 'imagesizing' => array('height', 30, 'width', 200)
		)
		, 'img_hover' => array (
			'title'        => 'Изображение [наведение]'
			, 'tablestyle' => 'width: 160px;'
			, 'colType'    => 'image'
			, 'imagesizing' => array('height', 30, 'width', 200)
		)
		, 'img_active' => array (
			'title'        => 'Изображение [активность]'
			, 'tablestyle' => 'width: 164px;'
			, 'colType'    => 'image'
			, 'imagesizing' => array('height', 30, 'width', 200)
		)	
		, 'mark' => array (
			'title'        => 'Иконка'
			, 'tablestyle' => 'width: 104px;'
			, 'colType'    => 'image'
			, 'imagesizing' => array('height', 30, 'width', 200)
		)
		, 'mark_hover' => array (
			'title'        => 'Иконка [hover]'
			, 'tablestyle' => 'width: 104px;'
			, 'colType'    => 'image'
			, 'imagesizing' => array('height', 30, 'width', 200)
		)	
	)
	, 'multylang_field' => array (
		'title' => array (
			'title'        => 'Обозначение'
			, 'colType'    => 'text'
		)	
	)
	, 'row_seq' => array(
		'id'
		, 'title'
		, 'typeID'
		, 'img'
		, 'img_hover'
		, 'img_active'
		, 'mark'
		, 'mark_hover'
	)
);
