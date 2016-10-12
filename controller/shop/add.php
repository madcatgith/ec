<?php

$langID = Lang::getID();
$data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

if (!is_array($data) || empty($data['amount']) || empty($data['id']) || !ProductCatalogue::isExist($langID, $data['id'])) {
    failureRequest();
} else {    
    Shop::getInstance()->addItem($data);
    successRequest(array('small' => Shop::getInstance()->getCart('small')));
}

