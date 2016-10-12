<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Menu {

    private static $_alias = null;
    private static $_menuArray = null;
    private static $_storage = array();
    private static $_activeArray = array();
    protected static $_crumbs = array();

    /**
     * инициализация массива для языковой версии
     *
     * @param mixed $langID
     */
    public static function load($langID = 1) {
        self::buildMenuArray($langID);
        self::buildAlias($langID);
    }
    
    public static function getStorage() {
        return self::$_storage;
    }

    /**
     * получить свойство меню
     *
     * @param integer $langID ид языковой версии
     * @param integer $ID     ид пункта меню
     * @param title $key      свойство
     * @return mixed
     */
    static public function get($langID = 1, $ID = 0, $key = '') {
        return isset(self::$_storage[$langID][$ID][$key]) ? self::$_storage[$langID][$ID][$key] : '';
    }

    public static function set($langID = 1, $ID = 0, $key = '', $value = '') {
        if (isset(self::$_storage[$langID][$ID])) {
            self::$_storage[$langID][$ID][$key] = $value;
        }
    }

    /**
     * строим масив активных менюшек
     *
     * @param integer $langID ид языковой версии
     * @param integer $ID      ид пункта меню
     * @return void
     */
    public static function buildActiveArray($langID = 1, $ID = 0) {

        $_active = array();
        $_storage = self::$_storage;

        while (isset($_storage[$langID][$ID]['menu_id']) === true) {
            $_active[$ID] = true;
            $ID = $_storage[$langID][$ID]['menu_id'];
        }

        self::$_activeArray = $_active;
    }

    /**
     * проверка активности пункта меню
     *
     * @param integer $ID ид пункта меню
     * @return boolean
     */
    public static function isActive($ID) {
        return isset(self::$_activeArray[$ID]) == true ? true : false;
    }

    /**
     * получаем массив родителей
     *
     * @param integer $langID ид языковой версии
     * @param integer $ID     ид пункта меню
     * @return array
     */
    public static function getParents($langID = 1, $ID = 0) {

        $parents = array($ID);

        while ($ID > 0) {
            $ID = self::$_storage[$langID][$ID]['menu_id'];
            $parents[] = $ID;
        }

        return array_reverse($parents);
    }

    /**
     * получение ид родителя
     *
     * @param integer $langID ид языковой версии
     * @param integer $ID     ид пункта меню
     * @param integer $lvl    уровень родителя
     * @return integer
     */
    public static function getParentMenu($langID = 1, $ID = 0, $lvl = 1) {
        $parents = self::getParents($langID, $ID);

        return (isset($parents[$lvl]) === true) ? $parents[$lvl] : $parents[0];
    }

    public static function getParentAttribute($langID, $ID, $attribute = '') {

        $parents = self::getParents($langID, $ID);
        $res = 0;

        while (!$res && $ID > 0) {
            $ID = array_pop($parents);
            $res = self::get($langID, $ID, $attribute);
        }

        return $res;
    }

    public static function getChildrens($langID = 1, $ID = 0) {
        return isset(self::$_menuArray[$langID][$ID]) ? self::$_menuArray[$langID][$ID] : array();
    }

    /**
     * получаем массив менюшек
     *
     * @param integer $langID ид языковой версии
     * @return array
     */
    public static function buildMenuArray($langID = 1) {

        if (isset(self::$_storage[$langID]) === true) {
            return self::$_storage[$langID];
        }

        $query = Registry::get('db')->query('select m.*, (select count(*) from ?_content c where c.menu_id = m.id and c.active = 1 and c.lang_id = ' . $langID . ') contentCount from ?_menu m where m.lang_id = ' . $langID . ' and m.active = 1 order by m.place');

        foreach ($query->fetchAll() as $get) {
            $get['announce'] = ConvertAltToHtml($get['announce']);
            self::$_storage[$langID][$get['id']] = $get;
            self::$_menuArray[$langID][$get['menu_id']][$get['id']] = $get['id'];
        }
    }

    /**
     * формируем масив алиасов, потому что ... (Nested set)
     *
     * @param inetger $langID ид языковой версии
     * @return void
     */
    public static function buildAlias($langID = 1) {
        foreach (self::$_storage[$langID] as $ID => $menu) {
            self::$_alias[$langID][$ID] = $menu['alias'];
            self::$_storage[$langID][$ID]['href'] = Url::setUrl(array(
                        'lang' => $langID,
                        'menu' => $ID
            ));
        }
    }

    /**
     * получаем массив аллиасов менюшекк
     *
     * @param integer $langID ид языковой версии
     * @return array
     */
    public static function getAliasArray($langID = 1) {
        return self::$_alias[$langID];
    }

    public static function getAliasID($langID = 1, $string = '') {
        return intval(array_search($string, self::$_alias[$langID]));
    }

    /**
     * Получаем ид менюшки по алиасу
     *
     * @param integer $langID ид языковой версии
     * @param string $alias алиас
     * @return iteger
     */
    public static function getIDByAlias($langID = 1, $alias = '') {

        $aliasArray = array_flip(self::getAliasArray($langID));

        return isset($aliasArray[$alias]) ? $aliasArray[$alias] : 0;
    }

    /**
     * получаем alias по ид
     *
     * @param intteger $langID
     * @param integer $ID
     * @return string
     */
    public static function getAliasByID($langID = 1, $ID = 0) {
        return isset(self::$_alias[$langID][$ID]) && self::$_alias[$langID][$ID] != '' ? self::$_alias[$langID][$ID] : '';
    }

    public static function addCrumb($crumb = array()) {
        self::$_crumbs[] = $crumb;
    }

    /**
     * формируем хлебные крошки для текущего пункта меню
     *
     * @param integer $template
     * @return string
     */
    public static function getCrumbs($tplID = 1) {
        $tpl = new Template;
        $langID = Lang::getID();
        $menuID = Url::get('menuID');
        $contentID = Url::get('contentID');
        $productID = Url::get('productID');
        $tagID = Url::get('tagID');
        $crumbs = self::$_crumbs;

        if ($contentID > 0) {
            $crumbs[] = array(
                'title' => Url::get('contentTitle'),
                'href' => Url::setUrl(array(
                    'lang' => $langID,
                    'menu' => $menuID,
                    'content' => Url::get('contentCNC')
                ))
            );
        }

        if ($productID > 0) {
            $crumbs[] = array(
                'title' => Url::get('productTitle'),
                'href' => Url::setUrl(array(
                    'lang' => $langID,
                    'menu' => $menuID,
                    'product' => Url::get('productCNC')
                ))
            );
        }

        if ($tagID > 0) {
            $crumbs[] = array(
                'title' => Url::get('tagTitle'),
                'href' => Url::setUrl(array(
                    'lang' => $langID,
                    'menu' => $menuID,
                    'content' => Url::get('contentCNC'),
                    'tag' => Url::get('tagCNC')
                ))
            );
        }

        if ($menuID > 0) {
            while (strlen(Menu::get($langID, $menuID, 'cnc'))) {

                $crumbs[] = array(
                    'title' => Menu::get($langID, $menuID, 'title'),
                    'href' => Menu::get($langID, $menuID, 'href')
                );

                $menuID = Menu::get($langID, $menuID, 'menu_id');
            }
        }

        $crumbs[] = array(
            'title' => Dictionary::GetUniqueWord(1),
            'href' => '/'
        );

        $tpl->assign('crumbs', array_reverse($crumbs));

        return $tpl->fetch('lib/wmpMenu/crumbs_' . $tplID . '.tpl');
    }

    public static function getIDs($langID, $menuID) {
        $array = array();

        if (isset(self::$_menuArray[$langID][$menuID])) {
            foreach (self::$_menuArray[$langID][$menuID] as $val) {
                $array[] = $val;
                $array = array_merge($array, self::buildTree($langID, $val));
            }
        }

        return $array;
    }

    public static function getChilds($langID, $menuID) {

        $array = array();

        if (isset(self::$_menuArray[$langID][$menuID])) {
            foreach (self::$_menuArray[$langID][$menuID] as $val) {
                $array[$val] = self::$_storage[$langID][$val];
            }
        }

        return $array;
    }

    public static function getChildrenIDs($langID, $menuID) {

        $ids = array($menuID);

        foreach (Menu::getChildrens($langID, $menuID) as $ID) {
            $ids = array_merge($ids, self::getChildrenIDs($langID, $ID));
        }

        return $ids;
    }

    public static function buildTree($langID, $menuID) {

        $array = array();

        if (isset(self::$_menuArray[$langID][$menuID])) {
            foreach (self::$_menuArray[$langID][$menuID] as $val) {
                $tArray = self::$_storage[$langID][$val];
                $tArray['child'] = self::buildTree($langID, $val);
                $array[] = $tArray;
            }
        }

        return $array;
    }

    /**
     * получаем дерево меню по шаблоку
     *
     * @param integer $langID ид языка
     * @param integer $menuID ид родителького меню
     * @param integer $tplID ид шаблона
     * @return string
     */
    public static function getTreeByTemplate($langID, $menuID, $tplID = 1, $menuIDs = array()) {

        $tpl = new Template();
        $array = self::buildTree($langID, $menuID);
		
        if (!count($array)) {
            return '';
        }
        
        if(!empty($menuIDs)) {
            foreach($menuIDs as $mID) {
                if (isset(self::$_storage[$langID][$mID])) {
                    $array[] = self::$_storage[$langID][$mID];
                }
            }
        }

        $tpl->assign('langID', $langID);
        $tpl->assign('menuID', $menuID);
        $tpl->assign('menuArray', $array);

        return $tpl->fetch('lib/wmpMenu/template_menu_' . $tplID . '.tpl');
    }

    public static function showInfo($langID, $menuID, $tplID = 1) {

        $tpl = new Template();

        $tpl->assign('langID', $langID);
        $tpl->assign('menuID', $menuID);
        $tpl->assign('menu', self::$_storage[$langID][$menuID]);

        return $tpl->fetch(__DIR__ . '/' . __FUNCTION__ . '_' . $tplID . '.tpl');
    }

    public static function getMenu($langID, array $menuIDs) {
        $menu = array();

        foreach ($menuIDs as $menuID) {
            $menu[$menuID] = (array) self::$_storage[$langID][$menuID];
        }

        return $menu;
    }

}
