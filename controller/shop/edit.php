<?php

if(!Shop::isExistCartID()) {
    failureRequest();
    die();
}

$langID = Lang::getID();
$data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
$shop = Shop::getInstance();

if (!is_array($data) || empty($data['amount']) || empty($data['id']) || !$shop->hasItem($data['id'])) {
    failureRequest();
} else {    
    $shop->_resaveItemAmount($data['id'], $data['amount']);
    successRequest();
}