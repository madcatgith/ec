<?php 

$GridTitle = 'Регионы сети магазинов';
$from      = array(
	'table' => 'region'
	, 'as' => 'reg'
	, 'lang' => '1'
	, 'limit' => 100
	, 'style' => 'width:100%'
	, 'nonlang_field' => array(
		'id' => array(
            'title' => 'id'
            , 'tablestyle' => 'width: 40px;'
            , 'colType' => 'lbl'
		)
		, 'active' => array(
	        'title' => 'Активное'
	        , 'tablestyle' => 'width: 45px; '
	        , 'colType' => 'check'
	    )
	    , 'code' => array(
			'title' => 'Код связазывания'
			, 'colType' => 'text'
			, 'description' => 'Код региона, который возвращает флеш-карта, максимальная длина 75 символов'
		)
		, 'outurl' => array(
			'title' => 'Внешняя ссылка'
			, 'colType' => 'text'
			, 'description' => 'Для внешних ссылок. Веллмарт и тп, если хотим чтобы переход был на стороннюю страницу'
		)
		, 'menu' => array(
			'title' => 'Города'
			, 'style' => 'width: 200px;'
			, 'tablestyle' => 'width: 200px; white-space:normal;'
			, 'table' => 'menu'
			, 'link_table' => 'region_to_menu'
			, 'field' => 'region'
			, 'linkfield' => 'menu'
			, 'outfield' => 'title'
			, 'multy' => true
			, 'rules' => true
			, 'colType' => 'select'
			, 'where' => 'f.menu_id=72'
		)
	)
	, 'multylang_field' => array(
		'title' => array(
			'title' => 'Название'
			, 'colType' => 'text'
		)
		, 'place' => array(
			'title' => 'Порядок показа'
			, 'colType' => 'text'
		)
	)
	, 'row_seq' => array('id', 'title', 'menu', 'active', 'outurl', 'place', 'code')
);