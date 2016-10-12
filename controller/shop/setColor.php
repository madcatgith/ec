<?php

if(!Shop::isExistCartID()) {
    failureRequest();
    die();
}

$langID = Lang::getID();
$data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

$shop = Shop::getInstance();

if (!is_array($data) || empty($data['color']) || empty($data['id']) || !$shop->hasItem($data['id'])) {
    failureRequest();
} else {    
    $item = $shop->getItem($data['id']);
    $item['info']->set('color', $data['color']);
    $shop->_resaveItemInfo($data['id'], $item['info']);
    successRequest(array('small' => $shop->getCart('small')));
}
