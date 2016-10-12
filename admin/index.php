<?php

include getenv('DOCUMENT_ROOT') . '/config.php';
include BASEPATH . 'admin/admin_modules.php';

$default_lang = Lang::getID();

require_once("admin_functions.php");

$wmpAdmin = new Admin($_COOKIE);
if (filter_input(INPUT_GET, 'exit', FILTER_VALIDATE_INT) && filter_input(INPUT_GET, 'exit', FILTER_VALIDATE_INT) === 1)
{
    $wmpAdmin->LogOut();
}
elseif (!$wmpAdmin->isLogedIn())
{
    $wmpAdmin->LogIn();

    if (!$wmpAdmin->isLogedIn()) 
    {
	echo Admin::GetAuthorizationForm();
    }
    else 
    {
        if(filter_input(INPUT_GET, 'backUrl')) {
            $url = unserialize(base64url_decode(filter_input(INPUT_GET, 'backUrl')));
            if(is_array($url)) {
                header('Location: '. http_build_url('', $url));
            } else {
                header('Location: /admin/admin_top.php');
            }
        } else {
            header('Location: /admin/admin_top.php');
        }
    }
}
