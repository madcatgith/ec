<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Authorization
{

    private $_field   = array('*');
    private $_storage = array();
    private $_errors  = array();
	
	private $_regFields = array(
		'name' 			=> '',
		'surname' 		=> '', 
		'patronymic'	=> '',
		'region'		=> '',
		'phone'			=> '',
		'email'			=> '',
		'login'			=> '',
		'password'		=> '',
		'reg'			=> '',
		'lang_id'		=> ''
	);

    public function hasErrors()
    {
        return (bool) count($this->_errors);
    }

    public function getErrors()
    {
        return $this->_errors;
    }
	
	public function setError($data)
	{
		$this->_errors[] = $data;
	}

    public function __construct($table = 'client')
    {
        $this->set('authTable', $table);
        $this->set('loginField', 'login');
    }

    public function addField()
    {

        $args = func_get_args();

        if (count($args) > 0) {
            foreach ($args as $val) {
                $this->_field[] = $val;
            }
        }
    }

    public function update($field, $value)
    {
        DB::Query('update `?_client` set `' . $field . '`="' . mysql_real_escape_string($value) . '" where id=' . $this->get('id'));
    }

    public function set($key, $value = '')
    {
        (is_array($key) === true) ? $this->_storage = array_merge($this->_storage, $key) : $this->_storage[$key] = $value;
    }

    public function get($key)
    {
        return (isset($this->_storage[$key])) ? $this->_storage[$key] : null;
    }

    public function isLogin()
    {
        return ($this->get('id') > 0 ? true : false);
    }

    public function registration($data)
    {

        $data = array_map(function($item) {
            return mysql_real_escape_string($item);
        }, $data);

        DB::Query('insert into ?_' . $this->get('authTable') . ' (`' . implode('`,`', array_keys($data)) . '`) values ("' . implode('","', $data) . '")');

        return DB::insertID();
    }

    public function loginByNetwork($network, $ID)
    {

        if (intval($ID) > 0) {
            if (is_array($data = DB::GetArray(DB::Query('select ' . implode(', ', $this->_field) . ' from ?_' . $this->get('authTable') . ' where network="' . $network . '" and externalID=' . $ID . ' limit 1')))) {
                if (count($errors = array_intersect_assoc(array('active' => 0, 'approved' => 0), $data))) {
                    foreach ($errors as $key => $value) {
                        $this->_errors[] = $key . '-error';
                    }
                } else {
                    $this->set($data);
                }
            }
        } else {
            $this->_errors[] = 'zero-id-error';
        }

        return $this->isLogin();
    }

    public function loginById($ID = 0)
    {

        $ID = (int) $ID;

        if ($ID === 0) {
            return false;
        } else {
            $this->set(DB::GetArray(DB::Query('select ' . implode(', ', $this->_field) . ' from ?_' . $this->get('authTable') . ' where id=' . $ID . ' and active=1 and approved=1 limit 1')));
        }

        return $this->isLogin();
    }

    public function getRow($login)
    {
        if (strlen($login) === 0) {
            return DB::GetArray(DB::Query('select approved, active, password from ?_' . $this->get('authTable') . ' where `' . $this->get('loginField') . '`="' . $login . '" limit 1'));
        }
    }

    public function login($login = '', $password = '')
    {
        if (strlen($login) === 0 || strlen($password) === 0) {
            return false;
        } else {
            $this->set(DB::GetArray(DB::Query('select ' . implode(', ', $this->_field) . ' from ?_' . $this->get('authTable') . ' where `' . $this->get('loginField') . '`="' . $login . '" and password="' . md5($password) . '" and active=1 and approved=1 limit 1')));
        }
        return $this->isLogin();
    }
	
	public function isExist(array $data)
	{
		if(is_array($is = DB::GetRow(DB::Query('select * from ?_' . $this->get('authTable') . ' where '. $data['field'] .' = "'. $data['value'] .'" limit 1')))) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getRegistrationForm()
	{
		$tpl = new Template;
		$tpl->assign('langID', Lang::getID());
		$tpl->assign('local', array(
			'name' 			=> Dictionary::getUniqueWord(626),
			'surname' 		=> Dictionary::getUniqueWord(628), 
			'patronymic'	=> Dictionary::getUniqueWord(627),
			'region'		=> Dictionary::getUniqueWord(629),
			'phone'			=> Dictionary::getUniqueWord(632),
			'email'			=> Dictionary::getUniqueWord(635),
			'login'			=> Dictionary::getUniqueWord(631),
			'password'		=> Dictionary::getUniqueWord(633),
			'repassword'	=> Dictionary::getUniqueWord(634),
			'sendBtn'		=> Dictionary::getUniqueWord(636)
		));
		$tpl->assign('data', $this->getRegFields());
		
		return $tpl->fetch(BASEPATH . 'controller/templates/authorization/form_registration.tpl');
	}
	
	public function getLoginForm()
	{
		$tpl = new Template;
		$tpl->assign('langID', Lang::getID());
		$tpl->assign('local', array(
			'login'			=> Dictionary::getUniqueWord(631),
			'password'		=> Dictionary::getUniqueWord(633),
			'sendBtn' 		=> Dictionary::getUniqueWord(641)
		));		
		
		return $tpl->fetch(BASEPATH . 'controller/templates/authorization/form_login.tpl');
	}
	
	public function setRegFields($data)
	{
		$this->_regFields = array_merge($this->_regFields, $data);
		
		return $this;
	}
	
	public function getRegFields()
	{
		return $this->_regFields;
	}

	public function clearRegFields()
	{
		$this->_regFields = array_map(function() { return ''; }, $this->_regFields);
	}

}
