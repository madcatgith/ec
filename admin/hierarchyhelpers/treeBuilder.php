<?php

/* /////////////////////////////////////////////////
  Класс для построения дерева
  как фронт-движок используется некоторый модуль
  jstree. С сайта http://www.jstree.com/
 *//////////////////////////////////////////////////

class wmpTree
{

    public $leafLink             = '';
    public $BranchesSelect       = '';
    public $LeavesSelect         = '';
    public $ShowLeaves           = '';
    public $IdsName              = 'selected_ids';
    public $Branches             = array();
    public $Leaves               = array();
    public $IsCheckBoxedLeaves   = false;
    public $IsCheckBoxedBranches = false;
    public $IsMultyState         = false;

    function retrieveAllLeaves()
    {
        
    }

    function retrieveLeaves($branchId, $branch)
    {

        $items_array = array();

        if (!$branchId) {
            return $items_array;
        }
        
        $where = '';

        $sql         = str_replace(array('{prefix}', '{where}', '{parent_id}'), array(DB::$table_prefix, $where, $branchId), $this->LeavesSelect);
        $items_query = DB::Query($sql);

        while ($get = DB::GetArray($items_query)) {
            $id                           = $get['id'];
            $items_array[$id]             = array();
            $items_array[$id]['parent']   = $get['parent'];
            $items_array[$id]['title']    = $get['title'];
            $items_array[$id]['type']     = $get['type'];
            $items_array[$id]["branches"] = array();
            $items_array[$id]['lvl']      = $branch['lvl'] + 1;
            $items_array[$id]["leaves"]   = array(); //self::retrieveLeaves($get['id'], & $items_array[$id]);
        }

        return $items_array;
    }

    //вытаскивание всего дерева объектов и раскладывание его по частям.
    function retrieveAllBranches($type = '', $where = '')
    {
        $items_array = array();

        if (!empty($type)) {
            $where .= ' AND type = ' . $type;
        }

        $sql         = str_replace(array('{prefix}', '{where}'), array(DB::$table_prefix, $where), $this->BranchesSelect);
        $items_query = DB::Query($sql);

        while ($get = DB::GetArray($items_query)) {

            $id                          = $get['id'];
            $items_array [$id]           = array();
            $items_array [$id]['parent'] = $get['parent'];
            $items_array [$id]['title']  = $get['title'];
            $items_array [$id]['type']   = $get['type'];

            $items_array [$id]["branches"] = array();
            $items_array [$id]["leaves"]   = array();

            //получение уровня вложенности меню на котором мы сейчас находимся
            $items_array [$id]['lvl'] = ($items_array[$get['parent']]['lvl']) ? $items_array[$get['parent']]['lvl'] + 1 : 1;
        }

        foreach ($items_array as $key => &$value) {
            if (is_int($key)) {
                $items_array [$value["parent"]] ["branches"] [$key] = &$value;
                if ($this->ShowLeaves) {
                    $items_array [$key] ['leaves'] = $this->retrieveLeaves($key, $value);
                }
            }
        }

        return $items_array;
    }

    // Вывод дерева объектов
    function GetLeavesHtml($branch, $wrap = false)
    {

        $res = '';

        if (count($branch['leaves'])) {

            foreach ($branch['leaves'] as $key => $item) {
                if ($this->IsCheckBoxedLeaves) {
                    $res .=
                            "<li id=\"phtml_" . $key . "\" item_id=\"" . $key . "\" class=\"ch\" >
                                    <a href='" . $url . $key . "' type='" . $item['type'] . "' rel='" . $key . "' ><ins> </ins><i>" . $item['title'] . "</i></a>"
                            . $this->GetLeavesHtml($item, true)
                            . "</li>";
                } else {
                    $res .=
                            "<li id=\"phtml_" . $key . "\" item_id=\"" . $key . "\" >
                                    <a href='" . $url . $key . "' type='" . $item['type'] . "' rel='" . $key . "' ><i>" . $item['title'] . "</i></a>"
                            . $this->GetLeavesHtml($item, true)
                            . "</li>";
                }
            }

            if ($wrap == true) {
                $res = '<ul>' . $res . '</ul>';
            }
        }

        return $res;
    }

    function func_branch_tree($url, $subitems, $lvl = 1, $branch = null)
    {

        $tmp = "";

        foreach ($subitems as $key => $item) {
            //$item = $this->Branches[$key];
            if (is_int($key) && $item['lvl'] == $lvl) {
                if ($this->IsCheckBoxedBranches) {
                    $tmp.="<li id=\"phtml_" . $key . "\" item_id=\"" . $key . "\" class=\"ch\" >
					<a href='" . $url . $key . "' type='" . $item['type'] . "' rel='" . $key . "' ><ins> </ins>" . $item['title'] . "</a>" .
                            $this->func_branch_tree($url, $item["branches"], $lvl + 1, $item) . "</li>";
                } else {
                    $tmp .= "<li id=\"phtml_" . $key . "\" item_id=\"" . $key . "\" ><a href='" . ( (isset($item['type']) && $item['type'] == 2 && $this->leafLink ) ? str_replace('{id}', $key, $this->leafLink) : $url . $key) . "' type='" . $item['type'] . "' rel='" . $key . "' >" . $item['title'] . "</a>" . $this->func_branch_tree($url, $item["branches"], $lvl + 1, $item) . "</li>";
                }
            }
        }

        if ($this->ShowLeaves && !empty($branch)) {
            $tmp .= $this->GetLeavesHtml($branch);
        }

        if ($tmp) {
            return "<ul>" . $tmp . "</ul>";
        } else {
            return '';
        }
    }

    // Вывод дерева менюидов
    function func_items_tree($name_name, $url, $repeating, $main_page_name, $java, $view, $items_ids, $ischeckboxed = false, $type = 1, $where = '')
    {
        
        global $hrefauthorization;

        $this->Branches = $this->retrieveAllBranches($type, $where); // admin_retrieve_items($type);

        if (!empty($url)) {
            if (strstr($url, "?"))
                $url .= "&$hrefauthorization&" . $name_name . "=";
            else
                $url .= "?$hrefauthorization&" . $name_name . "=";
        }

        //формируем красивое уникальное имя для дерева.
        $treeview_id = 'tree' . uniqid();
        $temp_echo .= "<div id='{$treeview_id}' class='treeview' style='{$view}; display:none;'><ul>";

        //если не был задан $menu_id значит верхний уровень
        $isactive = (!$item_id && (!isset($_REQUEST['search']) || empty($_REQUEST['search']) ) ) ? "class='active'" : "";

        if ($this->IsCheckBoxedBranches) {
            $temp_echo .= "<li id=\"phtml_all\" item_id=\"all\" class='open ch' ><a href=\"{$url}\" ><ins> </ins>Все</a><ul>";
            $temp_echo .= "<li id=\"phtml_0\" item_id=\"0\" class='open ch' ><a href=\"{$url}\" {$isactive} ><ins> </ins> " . $main_page_name . " </a><br />";
        } else {
            $temp_echo .= "<li id=\"phtml_0\" item_id=\"0\" class='open' ><a href=\"{$url}\" {$isactive} > " . $main_page_name . " </a><br />";
        }

        $temp_echo .= $this->func_branch_tree($url, $this->Branches);

        //запускаем скрипт по показыванию дерева и открытия ветвей где находиться текущий элемент
        $temp_echo.= '</li>';

        if ($this->IsCheckBoxedBranches) {
            $temp_echo .= '</ul></li>';
        }
        $temp_echo .= (isset($_REQUEST['search']) && !empty($_REQUEST['search'])) ?
                "<li id=\"phtml_0\"><a href=\"#\" class=\"active\">Поиск:</a><br /></li>" : '';


        if ($this->IsCheckBoxedBranches || $this->IsCheckBoxedLeaves) {
            $them = "ui : { theme_name : \"checkbox\" }, plugins : {  checkbox : { three_state : false }  },";

            $highlight_handler = "";
            foreach ($items_ids['checked'] as $value) {
                $highlight_handler .= "
				$(\"#{$treeview_id} .ch#phtml_" . $value . " a:first\").addClass(\"checked\")
				.parent().parents(\"li.closed\").removeClass(\"closed\").addClass(\"open\");
				var input_name = \"{$this->IdsName}[" . $value . "]\";
				$(\"#{$treeview_id}\").append (\"<input type='hidden' id='\"+ input_name +\"' name='\"+ input_name +\"' value='true' />\");
				";
            }

            if ($this->IsMultyState) {
                foreach ($items_ids['ignored'] as $value) {
                    $highlight_handler .= "
					$(\"#{$treeview_id} .ch#phtml_" . $value . " a:first\").addClass(\"ignored\")
					.parent().parents(\"li.closed\").removeClass(\"closed\").addClass(\"open\");
					var input_name = \"{$this->IdsName}[" . $value . "]\";
					$(\"#{$treeview_id}\").append (\"<input type='hidden' id='\"+ input_name +\"' name='\"+ input_name +\"' value='ignored' />\");
					";
                }

                foreach ($items_ids['undetermined'] as $value) {
                    $highlight_handler .= "
					$(\"#{$treeview_id} .ch#phtml_" . $value . " a:first\").addClass(\"undetermined\")
					.parent().parents(\"li.closed\").removeClass(\"closed\").addClass(\"open\");
					var input_name = \"{$this->IdsName}[" . $value . "]\";
					$(\"#{$treeview_id}\").append (\"<input type='hidden' id='\"+ input_name +\"' name='\"+ input_name +\"' value='undetermined' />\");
					";
                }
            }
            if (count($items_ids) == 0) {
                $highlight_handler .= "
				$(\"#{$treeview_id}\").append (\"<input type='hidden' id='{$this->IdsName}[all]' name='{$this->IdsName}[all]' value='true' />\");
				$(\"#{$treeview_id} .ch#phtml_all a:first\").addClass(\"checked\");
				";
            }
            $click_handler  = '';
            $change_handler = "
			var node = $(node).find(\"a:first\");
			var input_selector = \"#{$this->IdsName}\\\\[\"+ node.parent().attr(\"item_id\") +\"\\\\]\";

			var inp = $(\"#{$treeview_id} \" + input_selector);
			if(inp.length == 0){
			var in_name = \"{$this->IdsName}[\"+ node.parent().attr(\"item_id\") +\"]\";
			$(\"#{$treeview_id}\").append (\"<input type='hidden' id='\" + in_name + \"' name='\"+ in_name +\"' />\");
			inp = $(\"#{$treeview_id} \" + input_selector);
			}

			if (inp.attr('value') == 'false' || inp.attr('value') == ''){
			node.removeClass(\"ignored\");
			node.removeClass(\"unchecked\");
			node.removeClass(\"undetermined\");
			node.addClass(\"checked\");
			inp.attr({ value: 'true' });
			} ";

            if ($this->IsMultyState) {
                $change_handler .=
                        "else if(inp.attr('value') == 'true') {
				node.removeClass(\"checked\");
				node.removeClass(\"unchecked\");
				node.addClass(\"undetermined\");
				inp.attr({ value: 'undetermined' });
				} else if (inp.attr('value') == 'undetermined'){
				node.removeClass(\"unchecked\");
				node.removeClass(\"checked\");
				node.removeClass(\"undetermined\");
				node.addClass(\"ignored\");
				inp.attr({ value: 'ignored' });
				}";
            }
            $change_handler .= "
			else {
			node.removeClass(\"checked\");
			node.removeClass(\"ignored\");
			node.addClass(\"unchecked\");
			inp.attr({ value: 'false' });
			}
			";
        } else {
            $them = "ui : { theme_name : \"apple\" },";

            $highlight_handler = "";
            foreach ($items_ids as $value) {
                $highlight_handler .= "
				$(\"#{$treeview_id} #phtml_" . $value . " a:first\").addClass(\"active\")
				.parents(\"li.closed\").removeClass(\"closed\").addClass(\"open\");
				";
            }

            if (empty($java)) {
                $click_handler = 'DefaultClickOnNode (node);';
            } else {
                $click_handler = $java;
            }
        }
        return $temp_echo . '</div>'
                . "<script type=\"text/javascript\">
		$(function () {
		$(\"#" . $treeview_id . "\").tree({
		"
                . $them .
                "callback : {
		onload : function () {
		$(\"#" . $treeview_id . "\").slideDown();
		" . $highlight_handler . "
		}
		, onselect : function (node) {
		" . $click_handler . "
		}
		, onchange : function (node) {
		" . $change_handler . "
		}
		}
		});

		function DefaultClickOnNode (node){
		var addr = $(node).find(\"a:first\").attr(\"href\");
		document.location.href = addr;
		}
		});
		</script>";
    }

    public static $PreDefSql = array(
        'galleryBranches'         => 'SELECT * FROM {prefix}gallery_category WHERE 1 ORDER BY parent asc, type, title',
        'galleryBranchesWithLeaf' => 'SELECT * FROM {prefix}gallery_category WHERE 1 ORDER BY parent asc, type, title',
        'productBranches'         => 'SELECT *, menu_id as parent, 1 as type from ?_menu where 1=1 {where} order by  parent,  type, title',
        'productLeaves'           => 'SELECT *, category_id as parent, 1 as type FROM ?_product WHERE 1=1 AND category_id = {parent_id} ORDER BY parent, type, title',
        'tagBranches'             => 'SELECT *, t.parentID as parent, 1 as type FROM ?_tag t LEFT JOIN ?_tag_lang tl on t.id = tl.id WHERE 1=1 AND t.parentID = 0 {where} ORDER BY parent, type, tl.title',
        'cityBranches'            => 'SELECT *, t.parentID as parent, 1 as type FROM ?_tag t LEFT JOIN ?_tag_lang tl on t.id = tl.id WHERE 1=1 AND t.parentID = 1 {where} ORDER BY parent, type, tl.title',
        'tagLeaves'               => 'SELECT *, t.parentID as parent, 1 as type FROM ?_tag t LEFT JOIN ?_tag_lang tl on t.id = tl.id WHERE 1=1 AND t.parentID = {parent_id} {where} ORDER BY t.parentID, t.category, t.place desc'
    );

}
