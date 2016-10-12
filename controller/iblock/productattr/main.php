<?php
$langID = Lang::getID();
$iblock = new IBlock('productattr', Lang::getID());
$iblock->setTpl('main')->addWhere('product_id='.Url::get('productID'))->addOrder('id')->displayList();

/*if(count($data = $iblock->getList()) {
	$iblock->displayList();
}*/