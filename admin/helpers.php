<?php

class Helpers
{
    protected static $_aliases = array();

    protected function _buildRecAliases(& $menus, $langID, $parentID)
    {
        foreach ($menus[$langID][$parentID] as $menuID => $menu) {

            if (isset(self::$_aliases[$langID][$parentID])) {
                self::$_aliases[$langID][$menuID] = preg_replace('/[-]+/usi', '-', preg_replace('/(^[-]*)?([-]*$)?/usi', '', self::$_aliases[$langID][$parentID] . '-' . $menu['cnc']));
            } else {
                self::$_aliases[$langID][$menuID] = $menu['cnc'];
            }

            if (isset($menus[$langID][$menuID])) {
                self::_buildRecAliases($menus, $langID, $menuID);
            }
        }
    }

    protected function _clearRecAliases(& $menus, $langID, $parentID)
    {
        foreach ($menus[$langID][$parentID] as $menuID => $menu) {

            if (mb_strlen($menu['cnc']) == 0) {
                self::$_aliases[$langID][$menuID] = '';
            }

            if (isset($menus[$langID][$menuID])) {
                self::_clearRecAliases($menus, $langID, $menuID);
            }
        }
    }

    public function buildMenuAlias()
    {

        $menus  = array();
        $mQuery = DB::Query('select id, menu_id, cnc, lang_id from ?_menu where 1');

        while ($row = DB::GetArray($mQuery)) {
            $menus[$row['lang_id']][$row['menu_id']][$row['id']] = $row;
        }

        foreach (Lang::getLanguages() as $lang) {
            self::_buildRecAliases($menus, $lang['id'], 0);
            self::_clearRecAliases($menus, $lang['id'], 0);
        }

        foreach (self::$_aliases as $langID => $menus) {
            foreach ($menus as $menuID => $alias) {
                DB::Query('update ?_menu set alias="' . mysql_real_escape_string($alias) . '" where id=' . $menuID . ' and lang_id=' . $langID);
            }
        }
    }

    private static function _prepareString($value)
    {
        return (get_magic_quotes_gpc() == true ? mysql_real_escape_string(stripcslashes($value)) : mysql_real_escape_string($value));
    }

    public static function prepareValue($type, $value)
    {
        switch ($type) {
            case "bool":
                return (bool) $value;
                break;
            case "float":
                return (float) $value;
                break;
            case "integer":
                return (int) $value;
                break;
            default:
                return self::_prepareString((string) $value);
                break;
        }
    }

    public static function getTreeSelect($data, $name, $value, $context = array())
    {

        $select = '<select name="' . $name . '">';

        foreach ($data as $k => $v) {
            $select .= '<option' . ($value == $k ? ' selected="selected"' : '') . ' value="' . htmlspecialchars($k) . '">' . $v . '</option>';
        }

        return $select . '</select>';
    }

    public static function getSQLTreeSelect($table, $name, $value, $context = array())
    {

        $context = array_merge(array(
            'name'  => 'title'
            , 'where' => ' 1 '
            , 'order' => ' id desc '
                ), $context);

        $query = DB::Query("select {$table}.id, {$table}.{$context['name']}
        	from `?_{$table}` {$table}        	
        	where {$context['where']}
        	order by {$context['order']}
        ");

        $htmlSelect = '<option value="0">&nbsp;</option>';

        while ($getSeq = DB::GetArray($query))
            $htmlSelect .= '<option value="' . $getSeq['id'] . '"' . ($getSeq['id'] == $value ? ' selected="selected"' : '') . '>' . $getSeq['title'] . '</option>';

        return '<select name="' . $name . '">' . $htmlSelect . '</select>';
    }

    public static function getSQLLangTreeSelect($table, $name, $value, $context = array())
    {

        $context = array_merge(array(
            'name'         => $table . '_lang.title',
            'defaultValue' => '&nbsp;',
            'where'        => '1',
            'order'        => $table . '_lang.title',
            'size'         => 10,
            'multiple'     => false
                ), $context);

        $query = DB::Query("select {$table}.id, {$context['name']}
        	from 
        		`?_{$table}` {$table},
        		`?_{$table}_lang` {$table}_lang
        	where 
                        {$table}.id={$table}_lang.id and {$table}_lang.lang_id=" . Lang::getID() . " and {$context['where']}
        	order by {$context['order']}
        ");

        $htmlSelect = '<option' . (in_array(0, $value) ? ' selected="selected"' : '') . ' value="0">' . $context['defaultValue'] . '</option>';
        $value      = (array) $value;

        while ($getSeq = DB::GetArray($query)) {
            $htmlSelect .= '<option style="padding-left: 13px;" value="' . $getSeq['id'] . '"' . (in_array($getSeq['id'], $value) ? ' selected="selected"' : '') . '>' . $getSeq['title'] . '</option>';
        }

        return '<select' . ($context['multiple'] == true ? ' multiple="multiple" size="' . $context['size'] . '"' : '') . ' id="' . $table . '" name="' . $name . '" style="min-width: 350px;">' . $htmlSelect . '</select>';
    }

    public static function getData($table, $context = array())
    {

        $context = array_merge(array(
            'name'         => $table . '_lang.title',
            'where'        => ' ' . $table . '.id=' . $table . '_lang.id and ' . $table . '_lang.lang_id=' . Lang::getID() . ' ',
            'order'        => $table . '_lang.title',
            'defaultValue' => '',
            'key'          => false
                ), $context);

        $query = DB::Query("select {$table}.id, {$context['name']}
        	from 
        		`?_{$table}` {$table},
        		`?_{$table}_lang` {$table}_lang
        	where {$context['where']}
        	order by {$context['order']}
        ");

        if ($context['key']) {
            $res = array(
                0 => array(
                    'value' => 0,
                    'title' => $context['defaultValue']
                )
            );

            while ($getSeq = DB::GetArray($query)) {
                $res[$getSeq['id']] = array(
                    'value' => $getSeq['id'],
                    'title' => $getSeq['title']
                );
            }
        } else {
            $res = array(
                array(
                    'value' => 0,
                    'title' => $context['defaultValue']
                )
            );

            while ($getSeq = DB::GetArray($query)) {
                $res[] = array(
                    'value' => $getSeq['id'],
                    'title' => $getSeq['title']
                );
            }
        }

        return $res;
    }

    public static function walkRecursive($data, $key, $step, $value)
    {

        $htmlSelect .= "";

        if (isset($data[$key]) == true) {
            foreach ($data[$key] as $k => $v) {
                $htmlSelect .= '<option style="padding-left:' . (15 * $step) . 'px;" value="' . $k . '"' . ($k == $value ? ' selected="selected"' : '') . '>' . $v . '</option>';
                $htmlSelect .= self::walkRecursive($data, $k, $step + 1, $value);
            }
        }

        return $htmlSelect;
    }
    
    public static function optionsRecursive($data, $maxLevel = 0, $optionID = 0, $parentID = 0, $level = 0) {
        $htmlOptions .= "";
        
        if(!empty($data[$parentID])) {
            foreach ($data[$parentID] as $key => $value) {
                $htmlOptions .= '<option value="'. $key .'"'. ($optionID == $key ? ' selected="selected"' : '') . ($maxLevel && $maxLevel <= $level ? ' disabled="disabled"' : '') .'>'. str_repeat(' . ', $level) . $value .'</option>';
                $htmlOptions .= self::optionsRecursive($data, $maxLevel, $optionID, $key, $level + 1);
            }
        }
        
        return $htmlOptions;
    }
    
    public static function optionsFromArray($data, $valueField, $titleField, $value) {
        $htmlOptions = '<option value="0"></option>';
        
        foreach($data as $item) {
            $htmlOptions .= '<option value="'. $item[$valueField] . ($value == $item[$valueField] ? '" selected="selected"' : '"') . '>'. $item[$titleField] .'</option>';
        }
        
        return $htmlOptions;
    }
    
    public static function getMenuSelect($name, $value = 0) {
        $data = array();
        $titles = array();
        
        $menu = Registry::get('db')->query('select * from ?_menu where active = 1 order by place, id')->fetchAll();
        
        foreach($menu as $one) {
            $titles[$one['id']][$one['lang_id']] = $one['title'];
            $data[$one['menu_id']][$one['id']] = 0;
        }
        
        foreach($data as $parentID => $children) {
            foreach($children as $childID => $childData) {
                if(!empty($titles[$childID])) {
                    foreach($titles[$childID] as $title) {
                        if(strlen($title)) {
                            $data[$parentID][$childID] = $title;
                            continue;
                        }
                    }
                } else {
                    $data[$parentID][$childID] = 'Title #' . $childID;
                }
            }
        }
        
        return '<select name="' . $name . '"><option value="0">Верхний уровень</option>' . self::optionsRecursive($data, Registry::get('maxMenuLevel') ?: 4, $value, 0, 1) . '</select>';
    }

	public static function findIBlock($content_id, $lang_id, $text)
	{
		DB::Query('delete from ?_content_to_iblock where content_id = '. $content_id . ' and lang_id = ' . $lang_id);
		
		if(!mb_strlen($text)) {
			return false;
		}
		
		$iblockFind = $iblocks = array();
		
        preg_match_all('/\[iblock:(.*?)\]/', $text, $iblocks);
		
        if(count($iblocks[1])) {
            foreach($iblocks[1] as $iblockController) {
                $iblocksParts = explode('/', $iblockController);
                if($iblocksParts[1] === 'main' && IBlocks::tableHasAlias($iblocksParts[0])) {
                    $iblockFind[] = $iblocksParts[0];
                }
            }
			
			if(!empty($iblockFind)) {
				foreach($iblockFind as $iblock) {
					DB::Query('insert into ?_content_to_iblock (content_id, lang_id, iblock) values ('. $content_id .', '. $lang_id .', "'. $iblock .'")');
				}
			}
        }
	}
	
    public static function getGallerySelect($name, $value = 0) {
        $data = array();
        $titles = array();
        
        $gallery = Registry::get('db')->query('select * from ?_gallery_category order by id, parent')->fetchAll();
        
        foreach($gallery as $one) {
            $data[$one['parent']][$one['id']] = $one['title'] ?: 'Gallery #' . $one['id'];
        }
        
        return '<select name="' . $name . '"><option value="0">Верхний уровень</option>' . self::optionsRecursive($data, Registry::get('maxMenuLevel') ?: 4, $value, 0, 1) . '</select>';
    }
}

function clearNodeAttribute($node, $attribute = '')
{

    $attributes = (array) $attribute;

    for ($index = ($node->attributes->length - 1); $index >= 0; $index--) {

        $nodeAttribute = $node->attributes->item($index);

        if (in_array(strval($nodeAttribute->nodeName), $attributes) !== false) {
            $nodeAttribute->parentNode->removeAttributeNode($nodeAttribute);
        }
    }
}

function clearNodeChild($node, $child = '')
{

    $childrens = (array) $child;

    for ($index = ($node->childNodes->length - 1); $index >= 0; $index--) {

        $nodeChild = $node->childNodes->item($index);

        if (in_array(strval($nodeChild->nodeName), $childrens) !== false) {
            $nodeChild->parentNode->removeChild($nodeChild);
        }
    }
}

function clearNodeAttributes($node, $filter = '')
{

    $filters = (array) $filter;

    for ($index = ($node->attributes->length - 1); $index >= 0; $index--) {

        $nodeAttribute = $node->attributes->item($index);

        if (in_array(strval($nodeAttribute->nodeName), $filters) === false) {
            $nodeAttribute->parentNode->removeAttributeNode($nodeAttribute);
        }
    }
}

function getStrFileSize($file)
{

    if (mb_strlen($file) == 0 || file_exists($fileName = getenv('DOCUMENT_ROOT') . $file) === false) {
        return '';
    }

    $fileSize = filesize($fileName);
    $bytes    = floatval($fileSize);
    $arBytes  = array(
        0 => array(
            'UNIT'  => 'TB',
            'VALUE' => pow(1024, 4)
        ),
        1 => array(
            'UNIT'  => 'GB',
            'VALUE' => pow(1024, 3)
        ),
        2 => array(
            'UNIT'  => 'MB',
            'VALUE' => pow(1024, 2)
        ),
        3 => array(
            'UNIT'  => 'KB',
            'VALUE' => 1024
        ),
        4 => array(
            'UNIT'  => 'B',
            'VALUE' => 1
        ),
    );

    foreach ($arBytes as $arItem) {
        if ($bytes >= $arItem['VALUE']) {
            $result = $bytes / $arItem['VALUE'];
            $result = strval(round($result, 2)) . '' . $arItem['UNIT'];
            break;
        }
    }

    return $result;
}
