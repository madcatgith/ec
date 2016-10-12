<?php

if(!Shop::isExistCartID() || !Shop::getInstance()->hasItems()) {
    return Shop::getCartEmpty(1);
}

$shop = Shop::getInstance();
$city = Dictionary::getByCode('city', Lang::getID());
$fields = array_fill_keys(array('name', 'email', 'phone', 'city', 'comment', 'deliveryType'), '');
$fieldsLang = array(
    'name' => Dictionary::GetUniqueWord(63),
    'email' => Dictionary::GetUniqueWord(64),
    'phone' => Dictionary::GetUniqueWord(65),
    'city' => Dictionary::GetUniqueWord(66),
    'comment' => Dictionary::GetUniqueWord(67),
    'pickupType' => Dictionary::GetUniqueWord(106),
    'deliveryType' => Dictionary::GetUniqueWord(68),
    'mailType' => Dictionary::GetUniqueWord(107)
);
$required = array('name', 'email', 'phone');

$errors = array();
$success = false;

if(filter_input(INPUT_POST, 'cart', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY)) {
    $cart = filter_input(INPUT_POST, 'cart', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
    
    if(!check_wmp_sessid()) {
        $errors[] = Dictionary::GetUniqueWord(22);
    }
    
    foreach ($fields as $field => $value) {
        if(!empty($cart[$field])) {
            $fields[$field] = htmlspecialchars($cart[$field]);
        } elseif (in_array($field, $required)) {
            $errors[$field] = Dictionary::GetUniqueWord(21) . ' "'. $fieldsLang[$field] .'"';
        }
    }
    
    if(empty($fields['email']) || !filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = Dictionary::GetUniqueWord(58);
    }
    
    $fields['city'] = !empty($city[$fields['city']]) ? $city[$fields['city']]['title'] : '';
    
    if(!count($errors) && $shop->makeOrder($fields)) {
        $success = true;
    }
}

$tpl = new Template();

if($success) {
    $tpl->assign('cartTpl', '');
} else {
    $tpl->assign('cartTpl', $shop->getCart(1));
}

$tpl->assign('cities', $city);

$tpl->assign('errors', $errors);
$tpl->assign('success', $success);

$tpl->assign('fields', $fields);
$tpl->assign('fieldsLang', $fieldsLang);

return $tpl->fetch(dirname(__FILE__) . '/index.tpl');

