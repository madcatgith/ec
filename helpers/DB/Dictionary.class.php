<?php

class Dictionary {

    private static $_storage = array();
    private static $_dic_words = array();

    public static function init($langID) 
    {
        $query = Registry::get('db')->query("select * from ?_dic_unique where title<>'' and lang_id = " . $langID);
        foreach ($query->fetchAll() as $get) {
            $get['selected'] = false;
            self::$_storage[$get['id']] = $get;
        }
        
        foreach(Registry::get('db')
            ->query('select * from ?_dictionary_lang where lang_id = '. $langID .' and title<>""')
            ->fetchAll()
        as $row) {
            self::$_dic_words[$row['id']] = $row['title'];
        }
    }
    
    public static function getSelected() 
    {
        return array_filter(self::$_storage, function($item) {
            return $item['selected'] === true;
        });
    }
    
    public static function edit($id, $lang_id, $title) 
    {
        return (int) Registry::get('db')->exec("update ?_dic_unique set title = '{$title}' where id = {$id} and lang_id = {$lang_id}");
    }
    
    public static function getByCode($code, $lang_id = 1)
    {
        $data = array();
        
        foreach(Registry::get('db')->query("select * from ?_dic_unique where code = '{$code}' and lang_id = {$lang_id}")->fetchAll()
        as $dic
        ) {
            $data[$dic['id']] = $dic;
        }
        
        return $data;
    }
    
    public static function GetDicWords()
    {
        $result = array();
        $data = Registry::get('db')->query('select * from ?_dictionary_lang where lang_id<>"" and title<>""')->fetchAll();
        
        foreach ($data as $item) {
            $result[$item['lang_id']][$item['id']] = array('id' => $item['id'], 'title' => $item['title']);
        }
        
        return $result;
    }

    static public function GetEntry($entry = '', $dictionary = '', $lang_id = '') {
        $query = "
		select
		d.code
		, d.image
		, d1.title
		, d1.url
		, d.id
		from
		`?_dictionary` d
		left join `?_dictionary_lang` d1 on d1.id = d.id
		where
		d.isactive = 1
		and d.code='" . $entry . "'
		and d1.lang_id='" . (Lang::getID() > 0 ? Lang::getID() : $lang_id) . "'";

        if (!empty($dictionary)) {
            $query .= " and d.parentcode='" . $dictionary . "'";
        }
        // echo $query;

        $res = DB::GetArray(DB::Query($query));
        return $res;
    }

    static public function GetEntryById($id = '', $lang_id = '') {
        $query = "
		select
		d.code
		, d.image
		, d1.title
		from
		`?_dictionary` d
		left join `?_dictionary_lang` d1 on d1.id = d.id
		where
		d.isactive = 1
		and d.id = '" . $id . "'
		and d1.lang_id='" . (Lang::getID() > 0 ? Lang::getID() : $lang_id) . "'";

        //echo $query;

        $res = DB::GetArray(DB::Query($query));
        return $res;
    }

    static public function GetEntryTitle($entry = '', $dictionary = '', $lang_id = '') {
        $res = self::GetEntry($entry, $dictionary, $lang_id);

        if (empty($res))
            return $entry;
        else
            return $res['title'];
    }

    static public function GetEntryTitleById($id = '', $lang_id = '') {
        $res = self::GetEntryById($id, $lang_id);

        if (empty($res))
            return $entry;
        else
            return $res['title'];
    }

    static public function GetDictionaryEntries($dictionary = '', $root = '', $filter = '') {
        if (empty($dictionary) && empty($root) && empty($filter))
            return;

        $query = "
		select
		d.id
		, d.code
		, d1.title
		from
		`?_dictionary` d
		left join `?_dictionary_lang` d1 on d1.id = d.id
		where
		d.isactive = 1 " .
                (empty($dictionary) ? "" : " and d.parentcode='" . $dictionary . "' ") .
                (empty($root) ? "" : " and d.root='" . $root . "' and d.code <> '" . $root . "' " ) .
                (empty($filter) ? "" : " and " . $filter) .
                " and d1.lang_id='" . (Lang::getID() > 0 ? Lang::getID() : 2) . "' ";

        $qr = DB::Query($query);

        $result_arr = array();
        while ($res = DB::GetRow($qr)) {
            $result_arr[] = $res;
        }

        return $result_arr;
    }

    static public function GetWord($dic, $langID = 0) {

        $langID = ($langID ? $langID : Lang::getID());

        if (is_string($dic))
            $res = DB::GetArray(DB::Query("select title from `?_dic` where code='{$dic}' and lang_id={$langID}"));
        else if (is_numeric($dic))
            $res = DB::GetArray(DB::Query("select title from `?_dic` where id='{$dic}' and lang_id={$langID}"));

        return ( is_array($res) ? $res['title'] : $dic );
    }

    static public function GetUniqueWord($dic = '', $langID = 0, $field = 'title') {
        if (isset(self::$_storage[intval($dic)]) && $langID == 0) {
            self::$_storage[intval($dic)]['selected'] = true;
            return self::$_storage[$dic][$field];
        }

        $langID = ($langID ? $langID : Lang::getID());

        if (is_string($dic))
            $res = DB::GetArray(DB::Query("select title from `?_dic_unique` where code='{$dic}' and lang_id={$langID}"));
        elseif (is_numeric($dic))
            $res = DB::GetArray(DB::Query("select title from `?_dic_unique` where id='{$dic}' and lang_id={$langID}"));

        return ( is_array($res) ? $res['title'] : $dic );
    }
    
    public static function getDicWord($id) {
        return !empty(self::$_dic_words[$id]) ? self::$_dic_words[$id] : $id;
    }

    public static function getWordByCode($code, $context = array()) {
        $words = array();
        $order = '';
        $dao = new DefaultArrayObject($context);

        if ($dao->get('order')) {
            $order .= ' order by ' . $dao->get('order');
        }

        $words = Registry::get('db')->query('select id, title from ?_dic_unique where code="' . $code . '" and lang_id = "' . Lang::getID() . '" order by id asc' )->fetchAll();
       /* while ($get = DB::GetRow($query)) {
            $words[$get['id']] = $get['title'];
        }*/

        return $words;
    }

    static public function GetAdminWord($dic, $langID = 0) {

        $langID = ($langID ? $langID : Lang::getID());

        if (is_string($dic))
            $res = array_shift(DB::GetArray(DB::Query("select title from `?_dic_admin` where code='{$dic}' and lang_id='{$langID}'")));
        else if (is_numeric($dic))
            $res = @array_shift(DB::GetArray(DB::Query("select title from `?_dic_admin` where id='{$dic}' and lang_id='{$langID}'")));

        return (!strlen($res) ? $dic : $res );
    }
    
}
