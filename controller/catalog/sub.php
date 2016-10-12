<?php

$langID = Lang::getID();
$menuID = Url::get('menuID');

$catalogue = new ProductCatalogue($langID);

$productId = Url::get('productID');

$catalogue->setTpl('sub');

if(!empty($productId)) {
	$catalogue ->displayProduct($productId);
} else {
	$catalogue   
	->addFilter('p.category_id = '.$menuID)
    ->displayProducts();
}
