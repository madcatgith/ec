<?php

$fields = array();
parse_str(filter_input(INPUT_POST, 'form', FILTER_SANITIZE_STRING), $fields);

if(empty($fields['formID']) || empty($fields['data'])) {
    failureRequest(array('message' => 'data is empty.'));
    die();
}

try {
    $forms = new Forms(intval($fields['formID']), Lang::getID());
    successRequest($forms->ajax($fields['data']));
} catch (Exception $e) {
    failureRequest();
}

