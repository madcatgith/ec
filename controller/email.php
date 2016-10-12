<?

$tpl = new Template;

$tpl->assign('langID', Lang::getID());
$tpl->assign('body', $context['body']);

return $tpl->fetch(dirname(__FILE__) . '/templates/email.tpl');
