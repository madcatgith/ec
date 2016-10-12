<?php

define("_PATH", getenv('DOCUMENT_ROOT'));

include _PATH . "/config.php";
include _PATH . "/admin/mGrid/engine/mGridHelper.php";
include _PATH . "/admin/prepear.php";
include _PATH . "/admin/mGrid/mGridTable{$_POST['table']}.php";

header("Content-type: text/html; charset=utf-8");

function mJSON($str = '')
{

    return str_replace(array(
        "\n"
        , "\r"
        , "\t"
        , "\""
        , "\b"
        , "\f"
            ), array(
        "\\n"
        , "\\r"
        , "\\t"
        , "\\\""
        , "\\b"
        , "\\f"
            ), $str);
}

switch ($_POST['oper']) {

    case "select":
        $matches = array();
        $action  = preg_match('/([a-zA-Z_]*)_([0-9])/s', $_POST['name'], $matches);
        preg_match('/([a-zA-Z_]*)_([0-9])/s', $_POST['parent'], $parent);
        $value   = (int) $_POST['value'];

        if ($action) {
            //xxx не доделано для многоязычных полей
            if (array_key_exists($parent[1], $from["nonlang_field"])) {
                $par_field = $from["nonlang_field"][$parent[1]];
                $ch_field  = $from["nonlang_field"][$par_field['colChild']];

                // реальная лажа, должен передаваться сюда язык, который сейчас выбран в выведении грида и здесь использоваться
                //$langer = ($ch_field['nonlang'])? "AND f.lang_id='{$from['lang']}'" : "";
                $lang = $from['lang'];

                if ($ch_field['nonlang']) {
                    $SQL = "SELECT f.id, f.{$ch_field['outfield']} as outres
					FROM ?_{$ch_field['table']} as f
					WHERE f.{$ch_field["connField"]}='{$value}'
					ORDER BY f.{$ch_field['outfield']}";
                } elseif ($ch_field['islanged']) {
                    $SQL = "SELECT f.id, f.{$ch_field['outfield']} as outres
					FROM ?_{$ch_field['table']} as f
					WHERE f.{$ch_field["connField"]}='{$value}' AND f.lang_id='{$lang}'
					ORDER BY f.{$ch_field['outfield']}";
                } else {
                    //языковая версия
                    $out_field = (isset($ch_field['outfield'])) ?
                            "f.{$ch_field['outfield']}" :
                            "f_lang.{$ch_field['outfield_lang']}";

                    if (isset($ch_field["connField"])) {
                        $SQL = "SELECT f.id, {$out_field} as outres
						FROM ?_{$ch_field['table']} as f
						LEFT JOIN ?_{$ch_field['table']}_lang as f_lang
						ON f_lang.id = f.id AND f_lang.lang_id='{$lang}'
						WHERE f.{$ch_field["connField"]}='{$value}'
						ORDER BY {$out_field}";
                    } else {
                        $SQL = "SELECT f.id, {$out_field} as outres
						FROM
						?_{$ch_field['table']}_lang as f_lang
						LEFT JOIN ?_{$ch_field['table']}_lang as f
						ON f_lang.id = f.id
						WHERE f_lang.{$ch_field["connField_lang"]}='{$value}' AND f_lang.lang_id='{$lang}'
						ORDER BY {$out_field}";
                    }
                }
            }

            $dataResult = array();
            $dataQuery  = DB::Query($SQL);

            while ($get = DB::GetRow($dataQuery)) {
                $get['outres'] = mJSON($get['outres']);
                $dataResult[]  = '"' . $get['id'] . '":"' . reprepear($get['outres'], "input") . '"';
            }

            echo $dataResult = '{"options":{' . implode(',', $dataResult) . '}}';

            /*
              $SQL = "SELECT
              f.id,
              f.title
              FROM
              ?_dic_{$matches['1']} as f

              WHERE
              f.lang_id='{$matches['2']}'
              AND
              f.{$parent['1']}='{$value}'
              ORDER BY f.title";
              $dataResult = array();
              $dataQuery  = DB::Query($SQL);

              while($get  = DB::GetRow($dataQuery)){
              $get['title'] = str_replace(array("\"", "&quot;"),  array("\\\"","\\\""), $get['title']);
              $dataResult[] = '"'.$get['id'].'":"'.reprepear($get['title'], "input").'"';
              }

              echo $dataResult = '{"options":{' . implode(',', $dataResult) . '}}';
             */
        }
        break;
    case "fillData":

        $lang_array = Lang::getLanguages();
        
        $id         = (int) $_POST['id'];
        $getRes     = array();
        $dataQuery  = array();
        $dataResult = array();

        //функция пробегается по полям которые передаются и складывает значения в нужные по жисону ячейки
        function GetLangJsonReply($lang_id, $fields, $def_get)
        {
            $dataTemp = array();
            foreach ($fields as $key => $value) {
                if (isset($value['multy']) && $value['multy']) {

                    $def_get[$key]         = mJSON($def_get[$key]);
                    $def_get[$key . "_id"] = mJSON($def_get[$key . "_id"]);

                    $arr_names = explode(', ', $def_get[$key]);
                    $arr_ids   = explode(',', $def_get[$key . '_id']);
                    $tmp_str   = "";

                    for ($i = 0; $i < count($arr_ids); $i++)
                        $tmp_str .= ($tmp_str == "" ? "" : ",") . '"' . (trim($arr_ids[$i])) . '":"' . (trim($arr_names[$i])) . '"';

                    $dataTemp[] = '"' . $key . '":{"multy":"1","type":"' . $value['colType'] . '","values":{' . $tmp_str . '}}';
                } elseif ($value['colType'] == 'select') {

                    $def_get[$key] = mJSON($def_get[$key]);

                    if (!isset($def_get[$key . "_id"]))
                        $def_get[$key . "_id"] = mJSON($def_get[$key]);
                    else
                        $def_get[$key . "_id"] = mJSON($def_get[$key . "_id"]);

                    $dataTemp[] = '"' . $key . '":{"type":"' . $value['colType'] . '","title":"' . $def_get[$key] . '","values":"' . $def_get[$key . '_id'] . '"}';
                } else {
                    $def_get[$key] = mJSON($def_get[$key]);
                    $dataTemp[]    = '"' . $key . '":"' . reprepear($def_get[$key], $value) . '"';
                }
            }
            return '"' . $lang_id . '":{' . implode(',', $dataTemp) . '}';
        }

        // вытаскиваем данные из базы сразу для всех языков
        foreach ($lang_array as $key => $value) {
            if (is_int($key)) {
                $SqlRes       = GetGridSelectSQL($from, $key, $id);
                $getRes[$key] = DB::GetRow(DB::Query($SqlRes['SQL']));
            }
        }

        $def_get    = array_keys($lang_array);
        $def_get    = $getRes[$def_get[0]];

        $dataResult[] = GetLangJsonReply(0, $from["nonlang_field"], $def_get);

        foreach ($lang_array as $key => $value) {
            if (is_int($key)) {
                $dataResult[] = GetLangJsonReply($key, $from["multylang_field"], $getRes[$key]);
            }
        }
        echo '{' . implode(',', $dataResult) . '}';
        break;
    default:
        break;
}