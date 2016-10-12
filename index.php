<?php

include getenv('DOCUMENT_ROOT') . '/config.php';

error_reporting(E_ALL);
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', 1);

$buffer = Buffer::getInstance();
$buffer->obStart();

Url::parseUrl(getenv('REQUEST_URI'));
Config::setArray(Registry::get('db')->query("select * from `?_config` where id=0 and lang_id=" . Lang::getID())->fetch());
Config::prepareValue('above_menu', 'text');
Config::set('phone_one', unserialize(Config::get('phone_one')));

Controller::run('authorization/admin');

Shop::init();

try {
    $index = new Page();
    $index->display('index');
} catch (AException $e) {
    $e->logError();
}

$html = $buffer->obGetContents();
$buffer->obEndClean();

echo DelayFunctions::getInstance()->execute($html);