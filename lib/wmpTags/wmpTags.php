<?php

class Tags {

    protected static $_storage = array();
    protected static $_tags = array();

    public static function load($langID) {

        $tagsQuery = DB::Query('select t.*, tl.title, tl.text from ?_tag t, ?_tag_lang tl where t.isActive=1 and tl.id=t.id and tl.lang_id=' . intval($langID) . ' order by t.place');
        $_storage = array();

        while ($row = DB::GetArray($tagsQuery)) {
            $row['text'] = ConvertWithEvalingHtml(ConvertAltToHtml($row['text']));

            $_storage[$row['id']] = $row;
        }

        $_tags = array_fill_keys(array_keys($_storage), array());

        foreach ($_storage as $tag) {
            $_tags[$tag['parentID']][$tag['id']] = $tag['id'];
        }

        self::$_tags = $_tags;
        self::$_storage = $_storage;
    }

    public static function buildTree($parentID) {

        $array = array();

        foreach (self::$_tags[$parentID] as $val) {
            $tArray = self::$_storage[$val];
            $tArray['child'] = self::buildTree($val);
            $array[] = $tArray;
        }

        return $array;
    }

    static public function get($ID = 0, $key = '') {
        return isset(self::$_storage[$ID][$key]) ? self::$_storage[$ID][$key] : '';
    }

    protected static function _getChildrens($tagID) {
        if (isset(self::$_tags[$tagID])) {
            return self::$_tags[$tagID];
        } else {
            return array();
        }
    }

    public static function getChildrens($tagID, $parent = true) {

        if ($parent === true) {
            $ids = array($tagID);
        } else {
            $ids = array();
        }

        foreach (self::_getChildrens($tagID) as $ID) {
            $ids = array_merge($ids, self::getChildrens($ID));
        }

        return $ids;
    }

    public static function getList($parentID, $menuID, $tplID = 1) {
        $tags = array();
        $langID = Lang::getID();

        foreach (self::buildTree($parentID) as $t) {
            $tags[$t['category']][] = array_merge($t, array('href' => Url::setUrl(array('lang' => $langID, 'menu' => $menuID, 'tag' => $t['cnc'] . '-' . $t['id']))));
        }

        $tpl = new Template;
        $tpl->assign('category', Dictionary::getWordByCode('tagCategory'));
        $tpl->assign('tags', $tags);
        $tpl->assign('title', Menu::get($langID, $menuID, 'title'));

        return $tpl->fetch(__DIR__ . '/' . __FUNCTION__ . '_' . $tplID . '.tpl');
    }
    
    public static function getListAll($parentID, $menuID, $tplID = 1)
    {
        $tags = array();
        $langID = Lang::getID();

        foreach (self::buildTree($parentID) as $t) {
            $tags[] = array_merge($t, array('href' => Url::setUrl(array('lang' => $langID, 'menu' => $menuID, 'tag' => $t['cnc'] . '-' . $t['id']))));
        }

        $tpl = new Template;
        $tpl->assign('tags', $tags);
        $tpl->assign('title', Menu::get($langID, $menuID, 'title'));

        return $tpl->fetch(__DIR__ . '/' . __FUNCTION__ . '_' . $tplID . '.tpl');        
    }

    public static function routeList() {
        switch (intval(Url::get('menuID'))) {
            case 6:
            case 37:
                return self::getListAll(30, 37);
                break;
            case 5:
            case 38:
            case 10:
            case 11:
                return self::getList(1, 38);
                break;
            default:
                return false;
                break;
        }
    }

    public static function routeTitle() {
        switch (intval(Url::get('menuID'))) {
            case 6:
            case 37:
                return Dictionary::GetUniqueWord(619) . ' ' . Url::get('tagTitle');
                break;
            case 5:
            case 38:
                return ''; //Dictionary::GetUniqueWord(643);
                break;
            default:
                return false;
                break;
        }
    }

}
