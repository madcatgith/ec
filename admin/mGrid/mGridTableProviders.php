<?php

$GridTitle = 'Поставщики';
$from      = array(
    'table'   => 'providers'
    , 'as'    => 'prs'
    , 'lang'  => '1'
    , 'limit' => 100
    , 'style' => 'width:100%'
    , 'nonlang_field' => array (
		'id' => array(
			'title'        => 'id'
			, 'tablestyle' => 'width: 40px;'
			, 'colType'    => 'lbl'
		)
		, 'logotype' =>  array(
			'title'         => 'Логотип'
			, 'colType'     => 'image'
			, 'imagesizing' => array('height', 60, 'width', 60)
			, 'tablestyle'  => 'width: 60px; '
		)	        
		, 'img' =>  array(
			'title'         => 'Фото'
			, 'colType'     => 'image'
			, 'imagesizing' => array('height', 60, 'width', 70)
			, 'tablestyle'  => 'width: 70px; '
		)
		, 'galleryID' => array(	
			'title'         => 'Галерея',
			'style'         => 'width: 100px; ',
			'tablestyle'    => 'width: 70px; white-space:normal;',
			'colType'       => 'select',
			'table'         => 'gallery_category',    
			'outfield'      => 'title',
			'field'         => 'id'
			, 'nonlang'     => true
			, 'where'       => 'f.parent=1'
		)		   
        , 'menu' => array(
            'title'        => 'Пункты меню'
            , 'style'      => 'width: 200px;'
            , 'tablestyle' => 'width: 100px; white-space:normal;'
            , 'link_table' => 'providers_to_menu'
            , 'table'      => 'menu'
            , 'linkfield'  => 'menu'
            , 'field'      => 'providers'
            , 'outfield'   => 'title'
            , 'multy'      => true
            , 'rules'      => true
            , 'islanged'   => true
            , 'colType'    => 'select'
            , 'where'      => 'f.menu_id=6'
        )
		, 'mapcoord' => array(
            'title' => 'Карта google'
            , 'style' => 'width: 250px; '
            , 'fields' => array('Украина' => 'value', 'address' => 'text')
            , 'colType' => 'map'
        )        
    )
    , 'multylang_field' => array(
        'title' => array(
            'title'     => 'Название'
            , 'colType' => 'text'
        )
		, 'announcement' => array(
            'title'     => 'Краткое описание'
            , 'colType' => 'textarea'
        )
        , 'text' => array(
            'title'     => 'Описание'
            , 'colType' => 'textarea'
        )
		, 'phones' => array(
            'title'     => 'Телефоны'
            , 'colType' => 'textarea'
        )                
		, 'address' => array(
            'title'     => 'Адрес'
            , 'colType' => 'textarea'
        )        
    )
    , 'row_seq' => array('id', 'title', 'logotype', 'url', 'announcement', 'text')
);
