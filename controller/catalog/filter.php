<?php

$langID = Lang::getID();
$menuID = Url::get('menuID');


$class = '';

if(in_array(15, $catalogIDs)) {
    $class .= '_coffee';
} else if(in_array(16, $catalogIDs)) {
    $class .= '_accessories';
}

$catalogue = new ProductCatalogue($langID);
$catalogue
    ->addFilter('p.category_id in (12))
    ->setTpl('steps')
    ->setFilterOrder(filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_STRING))
    ->displayProducts();
