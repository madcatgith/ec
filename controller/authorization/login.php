<?

$auth = Registry::get('auth');

if($auth->isLogin()) {
	header('Location: '. Url::setUrl(array(
		'lang' => Lang::getID()
	)));
}

$tpl = new Template;

$tpl->assign('form', $auth->getLoginForm());
$tpl->display(BASEPATH . 'controller/templates/authorization/page_login.tpl');