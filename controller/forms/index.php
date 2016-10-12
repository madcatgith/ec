<?php

$context = array_merge(array('id' => 1, 'tplID' => 1), $context);

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
