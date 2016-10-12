<?php

$context = array_merge(array('id' => 2, 'tplID' => 2), $context);

try {
    $forms = new Forms(intval($context['id']), Lang::getID());
    $forms->setTpl($context['tplID']);

    if($return) {
        return $forms->showForm();
    } else {
        $forms->displayForm();
    }
} catch(Exception $ex) {
    if($return) {
        return $ex->getMessage();
    } else {
        echo $ex->getMessage();
    }    
}
