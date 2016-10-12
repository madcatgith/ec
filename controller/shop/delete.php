<?php

if(!Shop::isExistCartID()) {
    failureRequest();
    die();
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!intval($id)) {
    failureRequest();
} else {
    $shop = Shop::getInstance();

    if ($shop->hasItem($id)) {
        $shop->removeItem($id);
        successRequest(array('small' => Shop::getInstance()->getCart('small')));
    } else {
        failureRequest();
    }
}
