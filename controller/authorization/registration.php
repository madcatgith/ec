<?php

$auth = Registry::get('auth');

if($auth->isLogin()) {
    header('Location: '. Url::setUrl(array(
            'lang' => Lang::getID()
    )));
}

$langID = Lang::getID();
$ID = 0;

if(is_array(filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY)) && filter_input(INPUT_POST, 'action') == 'registration')
{
    $fields = $auth->getRegFields();

    $dao = new DefaultArrayObject(filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY));

    foreach($fields as $k => &$v) {
        if($dao->get($k)) {
            $v = mysql_real_escape_string($dao->get($k));
        }
    }

    if(filter_var($fields['email'], FILTER_VALIDATE_EMAIL) === false) {
        $auth->setError('Не верный E-mail.');
    } 
    if($auth->isExist(array('field' => 'email', 'value' => filter_var($fields['email'], FILTER_VALIDATE_EMAIL)))) {
        $auth->setError('Пользователь с данным почтовым адресом уже зарегистрирован.');
    }
    if($auth->isExist(array('field' => 'login', 'value' => $fields['login']))) {
        $auth->setError('Указаный логин занят.');
    }

    $fields['password'] = md5($fields['password']);

    $auth->setRegFields(array_merge($fields, array('lang_id' => $langID, 'reg' => date('Y-m-d H:i:s'))));	

    if(!$auth->hasErrors() && ($ID = $auth->registration($fields)) > 0) {
        $auth->clearRegFields();
    }
}

$tpl = new Template;

$tpl->assign('form', $auth->getRegistrationForm());
$tpl->assign('hasErrors', $auth->hasErrors());
$tpl->assign('errors', $auth->getErrors());
$tpl->assign('success', $ID);

$tpl->display(BASEPATH . 'controller/templates/authorization/page_registration.tpl');