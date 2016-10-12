<?php

class Content {
    
    /*
     * Replaced by Menu::get($langID, $menuID, 'contentCount')
     * @return int
     */
    public static function hasPages($langID, $menuID) {

        $pages = Registry::get('db')->query('select count(1) "check" from ?_content where active=1 and lang_id=' . intval($langID) . ' and menu_id=' . intval($menuID) . ' limit 2')->fetch(PDO::FETCH_COLUMN);

        return intval($pages);
    }

    /**
     * Вывод контента
     * 
     * @param mixed $langID
     * @param mixed $menuID
     * @param mixed $contentID
     * @param mixed $onPage
     * @return string
     */
    public static function getBody($langID = 1, $menuID = 0, $contentID = 0, $onPage = 0) {
        if (Menu::get($langID, $menuID, 'only_for_users') && Registry::get('isLogin') == false) {
            return ConvertWithEvalingHtml(self::upContentOne(78));
        }

        if ($contentID) {
            return makeAction(self::upContentOne($contentID));
        } else {
            if (intval(Menu::get($langID, $menuID, 'contentCount'))) {				
                if (intval(Menu::get($langID, $menuID, 'onPage')) > 0) {
                    $onPage = intval(Menu::get($langID, $menuID, 'onPage'));
                }
                return makeAction(self::upContentList($langID, $menuID, '', (int) Url::get('page'), $onPage, true, ''));
            } else {
                return makeAction(self::upContentList($langID, $menuID, '', 0, $onPage, true, ''));
            }
        }
    }

    /**
     * Вывод контента по ид
     * 
     * @param mixed $querySelect
     * @param mixed $tplID
     * @param mixed $readMore
     */
    public static function getOneContent($sqlSelect, $tplID = '') {

        $data = $sqlSelect->fetch();

        if (isset($data['id']) === false) {
            return '';
        }

        $tpl = new Template();

        if ($data['another_page'] != "") {

            $temp = str_replace("www.", "", getenv('HTTP_HOST'));

            if (substr($data['another_page'], 0, 1) == "/") {
                $data['another_page'] = "http://" . $temp . $data['another_page'];
            } else if (substr($data['another_page'], 0, 1) == "i") {
                $data['another_page'] = "http://" . $temp . "/" . $data['another_page'];
            }

            if (!headers_sent()) {
                header("Location: {$data['another_page']}");
            } else {
                echo '<meta http-equiv="Refresh" content="0;url=' . $data['another_page'] . '" />';
            }

            exit;
        }

        $tpl->assign('menuID', $data['menu_id']);

        $temp_time = explode(":", $data['time']);
        $temp_time = $temp_time[0] . ":" . $temp_time[1];
        $tplID = (!strlen($tplID)) ? $data["view"] : $tplID;

        $text = ConvertAltToHtml($data['text']);

        $textArray = explode("{page_break}", $text);

        if (count($textArray) > 1) {

            $page = intval(Url::get("page"));
            $page = ( $page > 0 ? $page : 1 );
            $page = ( $page > count($textArray) ? 1 : $page);

            $tpl->assign('pagination', Url::pagination('page', $page, 1, 3, 'page', 1));
            $tpl->assign('hasPagination', true);
            $tpl->assign('text', $textArray[$page - 1]);
        } else {
            $tpl->assign('pagination', '');
            $tpl->assign('hasPagination', false);
            $tpl->assign('text', $textArray[0]);
        }
        
        $tpl->assign('time', $temp_time);
        $tpl->assign('date', $data['date']);
        $tpl->assign('sDate', $data['sDate']);
        
        $date = explode('.', $data['date']);
        $tpl->assign('cDate', Calendar::getMonth($data['lang_id'], $date[1]) . ' ' . $date[0] . ', '. $date[2]);

        $tpl->assign('slogan', ConvertAltToHtml($data['slogan']));
        $tpl->assign('announcement', ConvertAltToHtml($data['announcement']));
        $tpl->assign('url', $data['url']);
        $tpl->assign('title', $data["title"]);
        $tpl->assign('langID', $data['lang_id']);
        $tpl->assign('menuID', $data['menu_id']);
        $tpl->assign('ID', $data['id']);
        $tpl->assign('place', $data['place']);
        $tpl->assign('alt', $data['alt']);
        $tpl->assign('tplID', $tplID);
        $tpl->assign('href', Url::setUrl(array(
                    "lang" => $data['lang_id'],
                    "menu" => $data['menu_id'],
                    "content" => $data['cnc']
        )));

        if ($data['imgurl'] !== "") {
            $tpl->assign('hasImage', true);
            $tpl->assign('imgurl', $data['imgurl']);
        } else {
            $tpl->assign('hasImage', false);
        }

        return $tpl->fetch(BASEPATH . 'lib/wmpContent/template_content_' . $tplID . '.tpl');
    }

    /**
     * Получить контент по ид контента
     * 
     * @param mixed $contentID ид контента
     * @param mixed $tplID шаблон
     * @param mixed $readMore читайте также да/нет
     * @return mixed
     */
    public static function upContentOne($contentID, $tplID = '') {

        $select = Registry::get('db')->query("select 
                c.*
                , date_format(c.date, '%d.%m.%Y') date
                , date_format(c.date, '%Y-%m-%d') sDate
            from `?_content` c
            where 
            	c.id={$contentID} and c.active=1 and c.lang_id='" . Lang::getID() . "' limit 1
        ");

        return self::getOneContent($select, $tplID);
    }

    public static function upContentOneByMenu($langID, $menuID, $tplID = '') {

        $select = Registry::get('db')->query("select 
                c.*
                , date_format(c.date, '%d.%m.%Y') date
                , date_format(c.date, '%Y-%m-%d') sDate
            from `?_content` c
            where 
            	c.menu_id='" . intval($menuID) . "' and c.active=1 and c.lang_id='" . intval($langID) . "' order by c.place asc limit 1
        ");

        return self::getOneContent($select, $tplID);
    }

    /**
     * Вывод списка контента
     * 
     * @param integer $langID ид языка
     * @param mixed $menu_ids ид пунктов меню
     * @param string $search дополнительное условие для вывобрки контента
     * @param integer $onPage количество контента на страницу
     * @param bool $ifOneShow если один контент отобразить или нет
     * @param string $orderBy сортировать по
     * @param integer $tplID ид шаблона
     * @return string
     */
    public static function upContentList($langID = 1, $menu_ids = "", $search = "", $page = 0, $onPage = 9, $ifOneShow = true, $orderBy = "", $tplID = '', $showEmpty = true) {

        $where_plus = '';

        if ($menu_ids) {
            $where_plus .= " and c.menu_id in ({$menu_ids}) ";
        } else {
            $where_plus .= " and c.menu_id=0 ";
        }

        if ($search !== "") {
            $where_plus .= " " . $search . " ";
        }

        if ($orderBy == "") {
            $orderBy = " c.place ";
        }

        $order_for_db = " order by {$orderBy}";
        $page = ($page > 0 ? $page : 1);


        if ($onPage > 0) {
            $start_position = ($onPage * $page) - $onPage;
            $limit_for_db = " limit {$start_position}, {$onPage}";
        } else {
            $limit_for_db = "";
        }

        if (!strlen($tplID)) {
            $tplID = Menu::get($langID, $menu_ids, "viewListContent");
        }

        $contents = array();
        $selectAll = Registry::get('db')->query("select SQL_CALC_FOUND_ROWS 
                 c.*
                , date_format(c.date, '%d.%m.%Y') date
                , date_format(c.date, '%Y-%m-%d') sDate
            from `?_content` c
            where 
		c.active=1 and c.lang_id='{$langID}' {$where_plus} 
            {$order_for_db} 
            {$limit_for_db}
        ")->fetchAll();

        $total = Registry::get('db')->query('select found_rows()')->fetch(PDO::FETCH_COLUMN);

        if ($ifOneShow == true && $total == 1) {
        	Registry::set('TITLE', $selectAll[0]['title']);
            return self::upContentOneByMenu($langID, $menu_ids);
        } else if ($total == 0) {
            if ($showEmpty) {
                $tpl = new Template();
                $tpl->assign('nothing_title', Dictionary::GetUniqueWord(3));
                return $tpl->fetch(BASEPATH . 'lib/wmpContent/templates_content_nothing.tpl');
            } else {
                return '';
            }
        }

        $tpl = new Template();

        foreach ($selectAll as $get) {

            $content = $get;
            $content['announcement'] = ConvertAltToHtml($get['announcement']);
            $text_array = explode("{page_break}", $get['text']);
            $content['text'] = ConvertAltToHtml($text_array[0]);
            $temp_time = explode(":", $get['time']);
            $content['time'] = $temp_time[0] . ":" . $temp_time[1];

            if ($get['imgurl'] !== "") {
                $content['altimg'] = htmlspecialchars($get['title']);
                $content['hasImage'] = true;
            } else {
                $content['hasImage'] = false;
            }
        
            $date = explode('.', $content['date']);
            $content['cDate'] = Calendar::getMonth($langID, $date[1]) . ' ' . $date[0] . ', '. $date[2];            

            $content['menu_href'] = Menu::get($langID, $get["menu_id"], "href");
            $content['href'] = Url::setUrl(array(
                        "lang" => $langID,
                        "menu" => $get["menu_id"],
                        "content" => $get["cnc"]
            ));

            $contents[] = $content;
        }

        $tpl->assign('contents', $contents);
        $tpl->assign('langID', $langID);
        $tpl->assign("menuID", $menu_ids);

        # pagination data
        $tpl->assign('total', $total);
        $tpl->assign('page', $page);
        $tpl->assign('onPage', $onPage);
        $tpl->assign('type', 'page');

        return $tpl->fetch(BASEPATH . 'lib/wmpContent/template_content_list_' . $tplID . '.tpl');
    }    
    
    public static function makeParts($html) {
        
        $doc = new DOMDocumentExtended();
        $doc->loadHTML($html);
        
        $nodeList = $doc->getElementsByTagName("h3");

        foreach ($nodeList as $h) {
            
            $h->setAttribute('class', 'toggleHiddenText');
            
            $div = $doc->createElement("div");
            $div->setAttribute("class", "hiddenText");

            while ($h->nextSibling && $h->nextSibling->localName !== "h3") {
                $div->appendChild($h->nextSibling);
            }

            $div->insertAfter($h);
        }
        
        return $doc->saveHTML();
    }
}
