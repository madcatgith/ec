<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Lang {

    /**
     * Массив языков по умолчанию
     * 
     * @var mixed
     */
    protected static $_defaults = array(
        'users' => 1,
        'admin' => 1
    );

    public static function getDefaultLang($key = '') {
        return (isset(self::$_defaults[$key]) ? self::$_defaults[$key] : self::$_defaults['users']);
    }

    /**
     * текущая локализация
     *
     * @var integer
     */
    protected static $_langID = 1;

    public static function getID() {
        return self::$_langID;
    }

    /**
     * массив языков
     *
     * @var array
     */
    static $langArray = array();

    /**
     * массив алиасов
     *
     * @var array
     */
    static $aliasArray = array();

    public static function hasLang($alias = '') {
        if (isset(self::$aliasArray[$alias]) === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * инициализация класса
     *
     * @return void
     */
    public static function init() {

        $query = Registry::get('db')->query('select l.*, l.http_accept_language cnc from ?_lang l where l.active=1 order by l.place');

        foreach($query->fetchAll() as $get) {

            # для проверки
            $get['active'] = false;

            self::$langArray[$get['id']] = $get;
            self::$aliasArray[$get['cnc']] = $get['id'];

            if ($get['users_default']) {
                self::$_defaults['users'] = $get['id'];
            }

            if ($get['admin_default']) {
                self::$_defaults['admin'] = $get['id'];
            }
        }
    }

    /**
     * устанавливаем локализацию
     *
     * @param string $alias
     * @return integer
     */
    public static function setLocalization($str = '') {
        if (strlen(trim($str)) && isset(self::$aliasArray[$str])) {
            self::$_langID = self::$aliasArray[$str];
        } else {
            self::$_langID = self::getDefaultLang('users');
        }

        self::$langArray[self::$_langID]['active'] = true;
    }

    /**
     * получаем список языков
     *
     * @return array
     */
    public static function getLanguages() {
        return self::$langArray;
    }

    public static function get($langID, $key = '') {
        if (isset(self::$langArray[$langID][$key])) {
            return self::$langArray[$langID][$key];
        } else {
            return '';
        }
    }

    /**
     * получаем алиас по ид
     *
     * @param integer $ID
     * @param string $str сирокка которую можно склеить с алиасом при его наличии
     */
    public static function getAliasByID($ID) {
        return (isset(self::$langArray[$ID]) === true && strlen(trim(self::$langArray[$ID]['cnc'])) ? self::$langArray[$ID]['cnc'] : '');
    }

    /**
     * вывод списка языков по шаблону
     *
     * @param integer $menuID ид меню
     * @param integer $tplID ид темплейта
     * @return string
     */
    public static function getLangByTemplate($tplID = 1) {

        $tpl = new Template();
        $langArray = self::getLanguages();
        $menuID = Url::get('menuID');
        $contentID = Url::get('contentID');
        $productID = Url::get('productID');
        $tagID = Url::get('tagID');

        if (count($langArray) == 0) {
            return '';
        }

        if ($tagID > 0) {
            foreach ($langArray as $key => $lang) {
                $langArray[$key]['href'] = Url::setUrl(array('lang' => $key, 'menu' => $menuID, 'tag' => Url::get('tagCnc') . '-' . $tagID));
            }
        } else if ($contentID > 0) {
            foreach ($langArray as $key => $lang) {
                $langArray[$key]['href'] = Url::setUrl(array('lang' => $key, 'menu' => $menuID, 'content' => Url::get('contentCNC')));
            }
        } else if ($productID > 0) {
            $ps  = array();
            $res = Registry::get('db')->query('select lang_id,cnc from ?_product where id=' . $productID,true);
             foreach ($res->fetchAll() as $r) {
                $ps[$r['lang_id']] = $r['cnc'];
            }
            foreach ($langArray as $key => $lang)
                $langArray[$key]['href'] = Url::setUrl(array('lang' => $key, 'menu' => $menuID, 'product' => $ps[$key]));
           
        } else if ($menuID > 0) {
            foreach ($langArray as $key => $lang) {
                $langArray[$key]['href'] = Url::setUrl(array('lang' => $key, 'menu' => $menuID));
            }
        } else {
            foreach ($langArray as $key => $lang) {
                $langArray[$key]['href'] = Url::setUrl(array('lang' => $key));
            }
        }


        $tpl->assign('langArray', $langArray);

        return $tpl->fetch(BASEPATH . 'lib/wmpLang/template_' . $tplID . '.tpl');
    }

}
