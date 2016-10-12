<?php

class Url extends Registry {

    /**
     * гененрируем гкд
     *
     * @param array $array
     * @return string
     */
    public static function setUrl(array $array = array()) {

        $url = '';

        foreach (array_filter($array) as $key => $value) {
            switch ($key) {
                case 'lang':
                    $url .= '/' . Lang::getAliasByID($value);
                    break;
                case 'menu':
                    $url .= '/' . Menu::getAliasByID(Lang::getID(), $value);
                    break;
                case 'tag':
                case 'page':
                    $url .= '/' . $key . '/' . $value;
                    break;
                case 'content':
                case 'product':
                case 'iblock':
                    $url .= '/' . $value;
                    break;
            }
        }

        return $url;
    }

    public static function parseUrl($url = '', $step = 0, $setDefaults = false) {

        $hasPage = true;
        $query = array_filter(explode('?', $url));
        $parts = array_filter(explode('/', $query[0]));
        $matches = array();
        
        if (count($parts) > 0) {

            $lang = array_shift($parts);

            if (Lang::hasLang($lang)) {
                Lang::setLocalization($lang);
            } else {
                Lang::setLocalization();
                $hasPage = false;
            }
        } else { 
			Lang::setLocalization();
		}
        $langID = Lang::getID();
        self::set('langID', $langID);
        
        Dictionary::init($langID);
        
        if ($hasPage === false) {
            self::pageNotFound();
        }

        $menuID = 0;
        Menu::load($langID);
        
        if (count($parts) > 0) {

            $partsType = array_shift($parts);
            $menuID = Menu::getAliasID($langID, $partsType);

            if ($menuID === 0) {
                self::pageNotFound();
            }
        }
        
        self::set('menuID', $menuID);
        Menu::buildActiveArray($langID, $menuID);
        
        Banner::setType('menu');
        Registry::set('TITLE', Menu::get($langID, $menuID, 'title'));
        
        if(count($parts) > 0) {
            
            $isFound  = false;
            $elementCnc = htmlspecialchars(array_shift($parts));
                
            $content = Registry::get('db')->query("select id contentID, title contentTitle, cnc contentCNC from ?_content where menu_id='{$menuID}' and lang_id='{$langID}' and cnc='{$elementCnc}' limit 1")->fetch();
            
            $product = array();
            if(in_array($menuID, Menu::getChildrenIDs($langID, 3))) {
                $product = Registry::get('db')->query("select id productID, title productTitle, cnc productCNC from ?_product where category_id = {$menuID} and lang_id = {$langID} and cnc = '{$elementCnc}' limit 1")->fetch();
            }
            
            if (!empty($content)) 
            {
                self::setArray($content);
                
                Banner::setType('content');
                Registry::set('TITLE', $content['contentTitle']);
                
                $isFound = true;
            } 
            elseif(!empty($product)) 
            {
                self::setArray($product);
                
                Banner::setType('product');
                Registry::set('TITLE', $product['productTitle']);
                
                $isFound = true;                
            } 
            else 
            {
                $contentText = Registry::get('db')->query('select text from ?_content where active = 1 and lang_id = '. intval($langID) .' and menu_id = '. intval($menuID) .' limit 1')->fetch(PDO::FETCH_COLUMN);
                
                $iblockFind = array();
                $iblocks    = array();
                
                preg_match_all('/\[iblock:(.*?)\]/', $contentText, $iblocks);
                if(count($iblocks[1])) {
                    foreach($iblocks[1] as $iblockController) {
                        $iblocksParts = explode('/', $iblockController);
                        if($iblocksParts[1] === 'main' && IBlocks::tableHasAlias($iblocksParts[0])) {
                            $iblockFind[] = $iblocksParts[0];
                        }
                    }
                }

                foreach($iblockFind as $iblock) {
                    $iblockObj = new IBlock($iblock, $langID);
                    $data = $iblockObj->setMenuID($menuID)->setOnPage(1)->addWhere('t.cnc = "'. $elementCnc .'"')->getList();
                    unset($iblockObj);
                    if(count($data)) {
                        self::setArray(array(
                            $iblock . 'ID' => $data[0]->get('id'),
                            $iblock . 'CNC' => $data[0]->get('cnc'),
                            $iblock . 'Title' => $data[0]->get('title'),
                            'iblocks' => array('string' => $iblock),
                            'iblockID' => $data[0]->get('id'),
                            'iblockType' => $iblock
                        ));
                        
                        Menu::addCrumb(array(
                            'title' => $data[0]->get('title'),
                            'href'  => $data[0]->get('href')
                        ));
                        
                        SEO::addSeo(array('iblock' => array(
                            'title' => $data[0]->get('title'),
                            'SEOTitle' => $data[0]->get('SEOTitle') ?: $data[0]->get('title'),
                            'SEOKeywords' => $data[0]->get('SEOKeywords'),
                            'SEODescription' => $data[0]->get('SEODescription')
                        )));
                        
                        Banner::setType('iblock');
                        Registry::set('TITLE', $data[0]->get('title'));
                        
                        if($data[0]->has('category_id')) {
                            self::set($iblock . 'CID', intval($data[0]->get('category_id')));
                        }
                            
                        $isFound = true;
                        
                        break;
                    }
                }
                if (!$isFound && $elementCnc !== 'page') {
                    self::pageNotFound();
                }
            }
        }/* elseif(intval(Menu::get($langID, $menuID, 'contentCount')) === 1) {

            $contentText = Registry::get('db')->query('select text from ?_content where active = 1 and lang_id = '. intval($langID) .' and menu_id = '. intval($menuID) .' limit 1')->fetch(PDO::FETCH_COLUMN);

            $iblocks = array();
            preg_match_all('/\[iblock:(.*?)\]/', $contentText, $iblocks);
            if(count($iblocks[1])) {
                
                $iblocksPrepare = array();
                
                foreach($iblocks[1] as $iblockController) {
                    $iblocksParts = explode('/', $iblockController);
                    $iblocksPrepare['array'][] = $iblocksParts[0];
                }
                
                $iblocksPrepare['string'] = implode(' ', $iblocksPrepare['array']);
                
                Registry::setArray(array('iblocks' => $iblocksPrepare));
                
                unset($iblocksPrepare);
                unset($iblocksParts);
            }
        }*/
        
        Banner::init($langID);

        preg_match_all('|/page/([\d]+)|usi', $url, $matches);
        if (isset($matches[1][0]) == true) {
            self::set('page', $matches[1][0]);
        }
    }

    public static function pagination($page, $total, $onPage, $numLink = 5, $type = 'pg', $tplID = 1) {

        $uri = preg_replace('/\/' . $type . '\/[\d]+/usi', '', getenv('REQUEST_URI'));
        $url = explode('?', $uri);
        $numPages = ceil($total / $onPage);

        if (isset($url[1])) {
            $rPart = '?' . $url[1];
        } else {
            $rPart = '';
        }

        if ($numPages == 1 or !$numPages) {
            return '';
        }

        if ($page > $total) {
            $page = ($numPages - 1) * $onPage;
        }

        $tpl = new Template();

        $tpl->assign('loopStart', (($page - $numLink) > 0) ? $page - ($numLink - 1) : 1);
        $tpl->assign('loopEnd', (($page + $numLink) < $numPages) ? $page + $numLink - 1 : $numPages);

        $tpl->assign('url', $url[0]);
        $tpl->assign('page', $page);
        $tpl->assign('numLink', $numLink);
        $tpl->assign('numPages', $numPages);
        $tpl->assign('onPage', $onPage);
        $tpl->assign('total', $total);
        $tpl->assign('type', $type);
        $tpl->assign('rPart', $rPart);

        return $tpl->fetch(BASEPATH . 'lib/wmpUrl/pagination_' . $tplID . '.tpl');
    }

    public static function pageNotFound() {
        header('HTTP/1.0 404 Not Found');
        header('Location: http://' . getenv('HTTP_HOST') . self::setUrl(array(
            'lang' => Lang::getID()
        )));
        exit;
    }

}
