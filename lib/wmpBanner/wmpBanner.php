<?php

class Banner {

    private static $_banners = array();
    private static $_elements = array();
    private static $_filters = array();
    private static $_type = null;

    public static function setType($type) {
        self::$_type = $type;
    }

    public function init($langID) {
        $db = \Registry::get('db');

        $query = $db->query('select * from ?_banner where active = 1');
        foreach ($query->fetchAll() as $banner) {
            self::$_banners[$banner['id']] = $banner;
        }

        if (!count(self::$_banners)) {
            return;
        }

        $query = $db->query('select t.*, tl.* from ?_banner_element t left join ?_banner_element_lang tl on t.id = tl.id where tl.active = 1 and tl.langID = ' . intval($langID) . ' and t.bannerID in (' . implode(', ', array_keys(self::$_banners)) . ') order by t.sort');
        foreach ($query->fetchAll() as $element) {
            self::$_banners[$element['bannerID']]['child'][] = $element['id'];
            $element['text'] = html_entity_decode($element['text'], ENT_QUOTES || ENT_HTML401, "UTF-8");
            self::$_elements[] = $element;
        }

        if (!count(self::$_elements)) {
            return;
        }

        $query = $db->prepare('select * from ?_banner_filter where type=:typeID and elementID=:ID');
        foreach (self::$_elements as $element) {
            if (self::$_banners[$element['bannerID']]['hasFilter']) {
                $query->bindValue(':typeID', self::$_type, \PDO::PARAM_STR);
                $query->bindValue(':ID', $element['id'], \PDO::PARAM_INT);
                $query->execute();

                if (self::$_type === 'menu') {
                    foreach ($query->fetchAll() as $filter) {
                        self::$_filters[$filter['menuID']][] = $filter['elementID'];
                    }
                } else {
                    foreach ($query->fetchAll() as $filter) {
                        self::$_filters[$filter['menuID']][$filter['valueID']][] = $filter['elementID'];
                    }
                }
            }
        }
    }

    public static function show($bannerID, $tplID) {
        if (!isset(self::$_banners[$bannerID]) || empty(self::$_banners[$bannerID]['child'])) {
            return false;
        }
        
        $elements = array_filter(self::$_elements, function($element) use ($bannerID) {
            return $element['bannerID'] == $bannerID;
        });

        if (self::$_banners[$bannerID]['hasFilter']) {
            $menuID = \Url::get('menuID');
            if (!empty(self::$_filters[$menuID])) {
                $filterElementIDs = array();
                
                if (self::$_type === 'menu') {
                    $filterElementIDs = array_values(self::$_filters[$menuID]);
                } else {
                    $typeID = \Url::get(self::$_type . 'ID');
                    $typeID = (self::$_type === 'iblock' ? \Url::get('iblockType') . '/' . $typeID : $typeID);
                    
                    $filterElementIDs = self::$_filters[$menuID][$typeID];
                    
                    foreach(self::$_filters[$menuID] as $valueID => $valueIDs) {
                        if(strpos($valueID, '/all') !== false) {
                            $filterElementIDs = !empty($filterElementIDs) ? array_merge($valueIDs, $filterElementIDs) : $valueIDs;
                        }
                    }
                }

                $elements = array_filter($elements, function($element) use ($filterElementIDs) {
                    return (bool) in_array($element['id'], $filterElementIDs);
                });
            } else {
                unset($elements);
            }
        }

        if (empty($elements)) {
            return false;
        }
        
        $tpl = new \Template;
        $tpl->assign('bannerID', $bannerID);
        $tpl->assign('elements', array_values($elements));
        return $tpl->fetch(dirname(__FILE__) . '/' . __FUNCTION__ . '_' . $tplID . '.tpl');
    }

}