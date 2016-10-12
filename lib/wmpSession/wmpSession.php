<?php

class Session extends Singleton
{
    protected static $_instance = null;
    protected $expiration       = 604800;
    protected $cypher           = MCRYPT_BLOWFISH;
    protected $mode             = MCRYPT_MODE_CBC;
    protected $key              = 'ljm9n93n894h';
    protected $warning          = 300;
    protected $now              = 0;
    protected $_storage         = array();

    protected function __construct()
    {
        parent::__construct();
        
        $this->now = time();
        $this->_getcookie();
    }

    public function set($key, $value = '')
    {
        if (is_array($key) === true) {
            $this->_storage = array_merge($this->_storage, $key);
        } else {
            $this->_storage[$key] = $value;
        }
    }

    public function get($key)
    {
        return (isset($this->_storage[$key])) ? $this->_storage[$key] : null;
    }

    public function getStorage()
    {
        return $this->_storage;
    }

    public function setcookie($key = '', $data = array(), $path = '/')
    {
        if (headers_sent() === false) {
            setcookie($key, serialize($data), $this->expiration + $this->now, $path);
        }
    }

    public function unsetcookie($key = '', $path = '/')
    {
        setcookie($key, '', $this->now - 31500000, $path);
    }

    protected function _getcookie()
    {

        if (count($_SESSION) > 0) {
            $this->set($_SESSION);
        }

        $cookie = $_COOKIE;

        if (isset($cookie["PHPSESSID"]) === true) {
            unset($cookie["PHPSESSID"]);
        }

        if (count($cookie) > 0) {
            foreach (array_filter(array_diff_key($cookie, array('admin_adminuser' => '', 'admin_adminpass' => ''))) as $key => $value) {
                if(strlen($value)) {
                    $this->set($key, @unserialize($value));
                }
            }
        }
    }
}