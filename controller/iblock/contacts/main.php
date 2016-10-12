<?php

$iblock = new IBlock('contacts', Lang::getID());
$iblock->setTpl('main')->setOnPage(3)->setPrepare(function($data) {
    foreach ($data as $item) {
        $item->set('tels', $item->get('phone'));
    }
    return $data;
})->displayList();

?>