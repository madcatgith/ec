<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Search
{

    private static $_regSearchClass = array();

    public static function regClass($class, $searchMethod = "serach")
    {
        self::$_regSearchClass[$class] = $searchMethod;
    }

    public static function getSearchForm($menuID, $tplID = 0)
    {

        $tpl = new Template();
        $def = Dictionary::GetUniqueWord(154);

        $tpl->assign('action', Menu::get(Lang::getID(), $menuID, 'href'));
        $tpl->assign('searchText', (isset($_REQUEST["query"]) === true ? $_REQUEST['query'] : $def));
        $tpl->assign('defaultText', $def);

        return $tpl->fetch(BASEPATH . "lib/wmpSearch/search_form_{$tplID}.tpl");
    }

    private static function emptySerach()
    {
        $tpl = new Template();

        $tpl->assign("noResultText", "Nothing was found.");

        return $tpl->fetch(BASEPATH . "lib/wmpSearch/search_no_results.tpl");
    }

    public static function doSearch($tplID = 1)
    {
        $maxWord = 7;
        $result  = '';
        $key     = '';

        if (isset($_REQUEST['query'])) {
            if (is_array($_REQUEST['query'])) {
                $search = current($_REQUEST['query']);
                $key    = key($_REQUEST['query']);
            } else {
                $search = clearVal($_REQUEST['query']);
            }
        } else {
            $search = '';
        }

        if (strlen($search)) {

            $search = htmlspecialchars(strip_tags($search));
            $search = str_replace(array('&quot;', '\&quot;', '&nbsp;', '&amp;', ',', ':', ';', '!', '(', ')', '?', ' and ', ' or ', ' - ', ' и ', ' і ', ' та ', '/'), ' ', $search);

            if ($search !== '') {

                $tpmArray  = array_unique(explode(' ', $search));
                $wordArray = array();

                foreach ($tpmArray as $value) {
                    $wordArray[] = $value;
                }

                array_splice($wordArray, $maxWord);

                $search    = '' . implode(' ', $wordArray) . '';
                $relevance = implode(' ', $wordArray);
            }
        }

        if (count(self::$_regSearchClass) > 0 && $search && $relevance) {
            foreach (self::$_regSearchClass as $class => $method) {
                if (class_exists($class)) {
                    $result      = $result . $class::$method($search, $relevance, $key);
                }
            }
        }

        if (mb_strlen($result) == 0) {
            return self::emptySerach();
        }

        $tpl = new Template();

        $tpl->assign('result', $result);

        return $tpl->fetch(BASEPATH . "lib/wmpSearch/search.tpl");
    }

}
