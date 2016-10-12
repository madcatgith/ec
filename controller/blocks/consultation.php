<?php

$tpl = new Template;

$langID = Lang::getID();

$tpl->assign('langID', $langID);
$tpl->display(BASEPATH . 'controller/templates/blocks/consultation.tpl');