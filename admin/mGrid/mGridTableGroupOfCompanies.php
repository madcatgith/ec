<?php 

function createCache()
{
	GroupOfCompanies::save();
}

function createSprite($ID)
{

	$spriteQuery = DB::Query('select 
		gl.logoSmall, gl.logoSmallActive, g.cnc, gl.lang_id 
	from 
		?_groupofcompanies g
		, ?_groupofcompanies_lang gl 
	where g.id=' . intval($ID) . ' and gl.id=g.id
	order by gl.lang_id');
	
	while ($row = DB::GetArray($spriteQuery)) {
		
		$images = new Images;
		
		$images->addImage(new Image($_SERVER['DOCUMENT_ROOT'] . $row['logoSmall']));
		$images->addImage(new Image($_SERVER['DOCUMENT_ROOT'] . $row['logoSmallActive']));

		$images->combine('vertical')->save($_SERVER['DOCUMENT_ROOT'] . '/files_small/' . $row['cnc'] . '-' . $row['lang_id'] . '.png');

	}

}

$GridTitle = 'Группы компаний';
$from      = array(
	'table'    => 'groupofcompanies',
	'as'       => 'groupofc',
	'lang'     => '1',
	'limit'    => 100,
	'style'    => 'width:100%',
	'callback' => array(
		'onInsert'   => array('createSprite', 'createCache')
		, 'onUpdate' => array('createSprite', 'createCache')
		, 'onDelete' => array('createSprite', 'createCache')
	),
	'nonlang_field' => array(
		'id' => array (
			'title'        => 'ID'
			, 'tablestyle' => 'width: 40px;'
			, 'colType'    => 'lbl'
		)
		, 'isActive' => array(
			'title'       => 'Активный'
			, 'colType'    => 'check'
			, 'tablestyle' => 'width: 70px; text-align:center;'
		)
		, 'isMain' => array(
			'title'        => 'Главный'
			, 'colType'    => 'check'
			, 'tablestyle' => 'width: 70px; text-align:center;'
		)
		, 'place' => array (
			'title'        => 'Позиция'
			, 'colType'    => 'text'
			, 'style'      => 'width: 100px;'	
			, 'tablestyle' => 'width: 100px; padding-left:10px;'
		)
		, 'colorScheme' => array (
			'title'        => 'Цветовая схема (общая)'
			, 'data'       => GroupOfCompanies::getColorSchemeData()
			, 'colType'    => 'select'
			, 'tablestyle' => 'width: 100px; padding-left:10px;'
		)
		, 'cnc' => array (
			'title'     => 'ЧПУ'
			, 'fields'  => array(
				'title' => 'text'
			)
			, 'colType' => 'cnc'
		)
		, 'background' => array(
			'title'        => 'Цветовая схема (персональная)'
			, 'data'       => array(
				0   => 'Синяя'
				, 4 => 'Темно синяя'
				, 1 => 'Зеленая'
				, 2 => 'Желтая'
				, 3 => 'Оранжевая'
			)
			, 'colType'    => 'select'
			, 'tablestyle' => 'width: 100px; padding-left:10px;'		
		)
		, 'tpl' => array (
			'title'        => 'Шаблон'
			, 'data'       => array(
				''           => 'С поиском в шапке'
				, '_company' => 'Без поиска в шапке'
			)
			, 'colType'    => 'select'
			, 'tablestyle' => 'width: 100px; padding-left:10px;'
		)
		, "galleryID" => array(
			"title"        => "Галлерея"
			, "tablestyle" => "width: 60px;"
			, "colType"    => "select"
			, "table"      => 'gallery_category'
			, "outfield"   => "title"
			, "field"      => "id"
			, 'where'      => ' f.parent=1 '
			, "islanged"   => false
            , "rules"      => true
            , 'nonlang'    => true
		)  
	)
	, 'multylang_field' => array (
		'title' => array (
			'title'        => 'Название'
			, 'colType'    => 'text'
			, 'tablestyle' => 'width:100%;padding-left:10px;'
		)
		, 'logo' => array (
			'title'         => 'Логотип'
			, 'imagesizing' => array('height', 30, 'width', 200)
			, 'tablestyle'  => 'width: 200px; padding: 4px 0;'
			, 'colType'     => 'image'
		)
		, 'logoSmall' => array (
			'title'         => 'Маленький логотип [117 x 37]'
			, 'imagesizing' => array('height', 48, 'width', 48)
			, 'tablestyle'  => 'width: 50px;'
			, 'colType'     => 'image'
		)
		, 'SEOKeywords' => array (
			'title'        => 'SEO Keywords'
			, 'colType'    => 'text'
			, 'tablestyle' => 'width:100%;padding-left:10px;'
		)				
		, 'logoSmallActive' => array (
			'title'         => 'Маленький логотип активный [117 x 37]'
			, 'imagesizing' => array('height', 48, 'width', 48)
			, 'tablestyle'  => 'width: 50px;'
			, 'colType'     => 'image'
		)				
		, 'SEOTitle' => array (
			'title'        => 'SEO Title'
			, 'colType'    => 'text'
			, 'tablestyle' => 'width:100%;padding-left:10px;'
		)
		, 'phone' => array (
			'title'        => 'Телефон горячей линии'
			, 'colType'    => 'text'
			, 'tablestyle' => 'width:100%;padding-left:10px;'
		)	
		, 'SEODescription' => array (
			'title'        => 'SEO Dedcription'
			, 'colType'    => 'text'
			, 'tablestyle' => 'width:100%;padding-left:10px;'
		)	
		, 'announcement' => array (
			'title'        => 'Текст для Intro'
			, 'colType'    => 'textarea'
			, 'tablestyle' => 'width:100%;padding-left:10px;'
		)
		, 'introPageLogo' => array (
			'title'        => 'Лого для Intro'
			, 'colType'    => 'image'
			, 'tablestyle' => 'width:100%;padding-left:10px;'
		)		
	)
	, 'row_seq' => array(
		'id'
		, 'isActive'
		, 'isMain'
		, 'place'
		, 'colorScheme'
		, 'title'
		, 'logo'
	)
);