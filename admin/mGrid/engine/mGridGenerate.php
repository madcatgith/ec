<?php

//error_reporting(E_ALL);

include BASEPATH . "/admin/ajax_strip.php";
include BASEPATH . "/admin/prepear.php";
include BASEPATH . "/admin/mGrid/engine/mGridTemplate.php";
include BASEPATH . "/admin/mGrid/engine/mGridHelper.php";
include BASEPATH . "/admin/mGrid/engine/mGridLangLayout.php";

$orderBy = $from["orderby"];

//если не задана извне сортировка, то пітаемся использовать ту, что в разметке
if (isset($_GET['sidx']) && clear($_GET['sidx'])) {
    $sidx    = $_GET['sidx'];
    $sord    = (isset($_GET['sord']) && in_array($_GET['sord'], array("asc", "desc"))) ? $_GET['sord'] : "asc";
    $orderBy = "{$sidx} {$sord}";
}

$SqlRes = GetGridSelectSQL($from, $lang, $id, $orderBy);

if (isset($from['limit'])) {
    if (isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > 0) {
        $SqlRes['SQL'] .= ' limit ' . $from['limit'] * ($_REQUEST['page']) . ', ' . $from['limit'];
    } else {
        $SqlRes['SQL'] .= ' limit ' . $from['limit'];
    }
}

//рисуем хедер
foreach ($from["row_seq"] as $key) {
    //если поле находится в безязыковых колонках
    if (array_key_exists($key, $from['nonlang_field']))
        $value = $from['nonlang_field'][$key];
    elseif (array_key_exists($key, $from['multylang_field']))
        $value = $from['multylang_field'][$key];
    else
        continue;

    if ($key == $sidx) {
        $th = ($sord == "asc") ? $tmpThAsc : $tmpThDesc;
    } else {
        $th = $tmpTh;
    }
    $tmpThSelector .= "<th class='ui-state-default ui-th-column sel' " . (isset($value['tablestyle']) ? 'style="' . $value['tablestyle'] . '"' : '') . " >&nbsp;</th>";
    $tmpThHolder .= str_replace(array("{title}", "{key}", "{style}")
            , array($value['title'], $key, (isset($value['tablestyle']) ? 'style="' . $value['tablestyle'] . '"' : ''))
            , str_replace(array("{field}", "{url}"), array($key, $_SERVER['PHP_SELF'] . '?db=' . $bases . '&lang=' . $lang), $th));
}

// echo $SqlRes['SQL'];
$result = DB::Query($SqlRes['SQL'], true) or die("Couldn't execute query." . mysql_error());
$count  = array_shift(DB::GetArray(DB::Query('SELECT FOUND_ROWS()')));

include BASEPATH . "/admin/mGrid/engine/mGridEditForm.php";
//For pager
include BASEPATH . "/admin/mGrid/engine/mGridPagerLayout.php";

if ($pages_count < 1) {
    $pager_control = "";
}

if ((!isset($from['limit'])) || (isset($page_number) && $page_number >= 0 && $page_number <= $pages_count) || ($count < $from['limit'])) {
    $tempTable = '';
    //вытащили одну запись из базы данных и пытаемся её нарисовать
    while ($row       = DB::GetRow($result)) {
        $tempTable .= str_replace("{id}", $row['id'], $tmpTrContentStart) . "\n";

        //пробегаемся по полям одной записи и рисуем их по разметке
        foreach ($from["row_seq"] as $key) {
            //если поле находится в безязыковых колонках
            if (array_key_exists($key, $from['nonlang_field']))
                $value = $from['nonlang_field'][$key];
            elseif (array_key_exists($key, $from['multylang_field']))
                $value = $from['multylang_field'][$key];
            else
                continue;

            //всякие мультивыборы
            if (isset($value['multy']) && $value['multy']) {
                $str = reprepear($row[$key], 'input');
                $str = ($str) ? $str : "&nbsp;";
                $tempTable .= (isset($value['hidden'])) ? '' : str_replace(array("{title}", "{style}")
                                , array($str, $value['tablestyle']), $tmpTd['select']);
            } else {
                switch ($value['colType']) {
                    case 'image':
                        $str = reprepear($row[$key], 'input');
                        $str = ($str) ? $str : "&nbsp;";

                        $tempTable .= (isset($value['hidden'])) ? '' : str_replace(array("{title}", "{par1}", "{val1}", "{style}")
                                        , array($str, $value['imagesizing'][0], $value['imagesizing'][1], $value['tablestyle']), $tmpTd['image']);
                        break;
                    case 'menu':
                        $str = reprepear($row[$key], 'input');
                        $str = ($str) ? $str : "&nbsp;";

                        $tempTable .= (isset($value['hidden'])) ?
                                '' : str_replace(array("{title}", "{style}")
                                        , array($str, $value['tablestyle'])
                                        , $tmpTd['menu']
                        );
                        break;
                    case 'gallery':
                        $str = reprepear($row[$key], 'input');
                        $str = ($str) ? $str : "&nbsp;";

                        $tempTable .= (isset($value['hidden'])) ?
                                '' : str_replace(array("{title}", "{style}")
                                        , array($str, $value['tablestyle'])
                                        , $tmpTd['gallery']
                        );
                        break;
                    case 'color':
                        $str   = reprepear($row[$key], 'input');
                        $color = ($str) ? $str : "000";

                        $tempTable .= (isset($value['hidden'])) ? '' : str_replace(array("{title}", "{style}", "{val1}", "{color}")
                                        , array($str, $value['tablestyle'], $str, $color), $tmpTd['color']);
                        break;
                    case 'input':
                    case 'date':
                    case 'datetime':
                    case 'cnc':
                    case 'textarea':
                    case 'text' :
                    case 'lbl' :
                    case 'map' :
                    case 'select' :
                        $str = reprepear($row[$key], 'input');
                        $str = ($str) ? $str : "&nbsp;";

                        $tempTable .= (isset($value['hidden'])) ?
                                '' : str_replace(array("{title}", "{style}")
                                        , array($str, $value['tablestyle'])
                                        , $tmpTd[$value['colType']]
                        );
                        break;
                    case 'check' :
                        $class = ($row[$key] == 1) ? 'checked' : 'unchecked';
                        $tempTable .= (isset($value['hidden'])) ? '' :
                                str_replace(array("{title}", "{style}"), array($class, $value['tablestyle']), $tmpTd['check']);
                        break;

                    case 'html' :
                        // если поле скрытое или не определен шаблон для отобрадения в гриде - то выходим
                        if (isset($value['hidden']) || !isset($value['tablebody']))
                            break;
                        // задали имя метода, но метод не создали
                        if (!function_exists($value['tablebody']))
                            break;

                        $str = $value['tablebody']($row, $row['id'], $key);

                        $tempTable .= str_replace(array("{title}", "{style}")
                                , array($str, $value['tablestyle'])
                                , $tmpTd[$value['colType']]);
                        break;
                    default:
                }
            }
        }

        $tempTable .= $tmpTrContentEnd . "\n";
    }
} else
    $tempTable = "";

$lang_control = GetLangControl();

$tmpEnd = str_replace(array("{pager_control}", "{lang_control}"), array($pager_control, $lang_control), $tmpEnd);
echo str_replace(array("{title}", "{count}", "{pager_control}", "{style}", "{lang_control}"), array($GridTitle, $count, $pager_control, ($from['style'] ? $from['style'] : ""), $lang_control), $tmpStart)
 . $tmpTrStrat . $tmpThSelector . $tmpTrEnd
 . $tmpTrStrat . $tmpThHolder . $tmpTrEnd . $tempTable . $tmpEnd;
 
if (isset($from['js'])) {
    ?>
    <script type="text/javascript">
        $(function()
        {
    <? echo $from['js']; ?>
        });
    </script>
    <?
} 
 
?>
