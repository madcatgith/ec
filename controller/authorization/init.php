<?

/*
if (isset($_GET['network'])) {

    $json = file_get_contents('http://ulogin.ru/token.php?token=' . filter_input(INPUT_POST, 'token') . '&host=' . getenv('HTTP_HOST'));
    $user = json_decode($json, true);
    $ID   = 0;
 
    if (is_array($user)) {
        if ($auth->loginByNetwork($user['network'], $user['uid'])) {
            $ID = $auth->get('id');
        } else {
            if ($auth->hasErrors() === false) {
                $ID = $auth->registration(array(
                    'network'    => $user['network'],
                    'externalID' => (int) $user['uid'],
                    'approved'   => 1,
                    'active'     => 1,
                    'surname'    => $user['last_name'],
                    'name'       => $user['first_name'],
                    'email'      => $user['email'],
                    'lang_id'    => Lang::getID(),
                    'ip'         => getenv('REMOTE_ADDR')
                ));
            }
        }

        if (($_SESSION['userInfo']['ID'] = $ID)) {
            header('Location: http://' . getenv('HTTP_HOST') . Url::setUrl(array(
                        'lang'    => Lang::getID(),
                        'city'    => Url::get('cityID'),
                        'menu'    => Url::get('menuID'),
                        'page'    => Url::get('page'),
                        'content' => Url::get('contentCNC'),
                        'event'   => Url::get('eventCNC')
            )));
        }
    }
} else 
*/

$session = Session::getInstance();
$auth    = new Authorization('client');

if (filter_input(INPUT_GET, 'logOut')) {

    $_SESSION['userInfo'] = array();

    $session->unsetcookie('userInfo');

    header('Location: http://' . getenv('HTTP_HOST') . Url::setUrl(array(
                'lang'    => Lang::getID(),
                'menu'    => Url::get('menuID'),
                'content' => Url::get('contentCNC'),
    )));

    exit;
	
} else {

    if (filter_input(INPUT_POST, 'action') == 'authorization') {

        $data = array_merge(array(
            'login'    => '',
            'password' => '',
            'remember' => 0
        ), (array) filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY));

        if ($auth->login($data['login'], $data['password'])) {

            Registry::set('firstLogin', true);

            if ($data['remember'] == 1) {
                $session->setcookie('userInfo', array(
                    'login'    => $data['login'],
                    'password' => $data['password']
                ));
            }

            $_SESSION['userInfo']['ID'] = $auth->get('id');

            header('Location: http://' . getenv('HTTP_HOST') . Url::setUrl(array(
                        'lang'    => Lang::getID(),
                        'menu'    => Url::get('menuID'),
                        'content' => Url::get('contentCNC')
            )));
        }
		
    } else if (isset($_SESSION['userInfo']['ID'])) {
        $auth->loginById($_SESSION['userInfo']['ID']);
    } else if (is_array($session->get('userInfo'))) {

        $userInfo = $session->get('userInfo');

        if (isset($userInfo['login']) && isset($userInfo['password'])) {
            if ($auth->login($userInfo['login'], $userInfo['password'])) {

                Registry::set('firstLogin', true);

                $_SESSION['userInfo']['ID'] = $auth->get('id');
            }
        }
    }
}

Registry::set('auth', $auth);
Registry::set('isLogin', $auth->isLogin());
