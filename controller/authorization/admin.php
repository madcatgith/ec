<?php

include BASEPATH . 'admin/lib/wmpAdmin/wmpAdmin.php';
$wmpAdmin = new Admin($_COOKIE);
$wmpAdmin->LogIn();
Registry::set('isLoginAdmin', (int) $wmpAdmin->isLogedIn());
