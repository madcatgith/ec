<?php

$tpl = new Template;

$langID = Lang::getID();

$tpl->assign('langID', $langID);
$tpl->assign('auth', Registry::get('auth'));
$tpl->display(BASEPATH . 'controller/templates/authorization/index.tpl');