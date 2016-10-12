<?php

class Admin
{

    static $_COOKIES = '';
    static $userHost = '';
    static $key      = '';
    static $now      = '';
    public $info     = array();
    static $current  = null;
    private static $solt = 'f2269d36fe07b677c0fccda2440dadf7';

    public function __construct($cookies)
    {
        self::$key = "7a";
        $this->_COOKIES            = $cookies;
        $this->userHost            = $_SERVER['REMOTE_ADDR'];
        $this->now                 = time();
        $this->expire_pass_cookies = $this->now + 18000; //+ 31536000;
        $this->expire_cookies      = $this->now - 18000; //- 31536000;
    }

    public static function GetAuthorizationForm()
    {
        $tpl = new Template();
        return $tpl->fetch(BASEPATH . 'admin/lib/wmpAdmin/templates_auth_form.tpl');
    }

    private function GetAdminInfo()
    {         
        $admin = Registry::get('db')
                    ->query("select id, adminuser user, adminpass pass, status from ?_admin where adminuser = '{$this->info['user']}' limit 1")
                    ->fetch();

        $admin['pass'] = md5($admin['pass'] . self::$solt);
                    
        return $admin;
    }

    public function LogIn()
    {
        if (filter_input(INPUT_POST, 'admlogin', FILTER_SANITIZE_STRING) && check_wmp_sessid()) {
            $this->info['user'] = filter_input(INPUT_POST, 'admlogin', FILTER_SANITIZE_STRING);
            $this->info['pass'] = md5(filter_input(INPUT_POST, 'admpass', FILTER_SANITIZE_STRING) . self::$solt);
            $is_post            = true;
        } elseif (!empty($this->_COOKIES['admin_adminuser']) && !empty($this->_COOKIES['admin_adminpass'])) {
            $this->info['user'] = $this->_COOKIES['admin_adminuser'];
            $this->info['pass'] = $this->_COOKIES['admin_adminpass'];
            $is_post            = false;
        }
        if (!empty($this->info['user'])) {
            $tmpAdmin = $this->GetAdminInfo();
            if (isset($this->info['pass']) && $is_post && $tmpAdmin['pass'] == $this->info['pass']) {
                setcookie("admin_adminuser", $tmpAdmin['user'], $this->expire_pass_cookies, '/');
                setcookie("admin_adminpass", $tmpAdmin['pass'], $this->expire_pass_cookies, '/');
                $this->info = $tmpAdmin;
                self::$current = $this;
                header("Location: " . $_SERVER['HTTP_REFERER']);
            } elseif (isset($this->_COOKIES['admin_adminpass']) && !$is_post && $this->_COOKIES['admin_adminpass'] == $this->info['pass']) {
                setcookie("admin_adminuser", $tmpAdmin['user'], $this->expire_pass_cookies, '/');
                setcookie("admin_adminpass", $this->info['pass'], $this->expire_pass_cookies, '/');
                $this->info = $tmpAdmin;
                self::$current = $this;
            }
        } else {
            $this->info = array();
        }
    }

    public function LogOut()
    {
        $this->info = array();
        self::$current = null;
        
        unset($_COOKIE['admin_adminpass']);
        unset($_COOKIE['admin_adminuser']);
        setcookie("admin_adminpass", null, -1, '/');
        setcookie("admin_adminuser", null, -1, '/');
        
        header("Location: /admin/");
    }

    public function isLogedIn()
    {
        if (!empty($this->info['user']) && !empty($this->info['user'])) {
            return true;
        }
        return false;
    }

}

?>
