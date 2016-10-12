<?php

include getenv('DOCUMENT_ROOT') . '/config.php';

$buffer = Buffer::getInstance();
$buffer->obStart();

header("Content-Type: text/html; charset=utf-8");

if(!filter_input(INPUT_POST, 'sessid', FILTER_SANITIZE_STRING) || !check_wmp_sessid()) {
    failureRequest(array('message' => 'session_id is empty.'));
    die();
}

Url::parseUrl(filter_input(INPUT_POST, 'uri'));
Config::setArray(Registry::get('db')->query("select * from `?_config` where id=0 and lang_id=" . Lang::getID())->fetch());
Config::prepareValue('above_menu', 'text');
Config::set('phone_one', unserialize(Config::get('phone_one')));

Controller::run('authorization/admin');
Shop::init();

switch (filter_input(INPUT_GET, 'fn')) { 
    case 'subscribe/send':
        Controller::run('request/subscribe');
        break;
    case 'callback/send':
        Controller::run('request/callback');
        break;
    case 'shop/add':
        Controller::run('shop/add');
        break;
    case 'shop/delete':
        Controller::run('shop/delete');
        break;
    case 'shop/setColor':
        Controller::run('shop/setColor');
        break;
    case 'shop/edit':
        Controller::run('shop/edit');
        break;
    case 'forms/ajax':
        Controller::run('forms/ajax');
        break;
    case 'socials':
        Controller::run('socials');
        break;
    case 'dictionary/edit':
        if(Registry::get('isLoginAdmin')) {
            $data       = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
            $affected   = Dictionary::edit($data['id'], $data['lang'], $data['title']);  
            if($affected) {
                successRequest();
            } else {
                failureRequest();
            }
        } else {
            failureRequest();
        }
        break;
    default:
        failureRequest();
        break;
}

$buffer->obEndFlush();
