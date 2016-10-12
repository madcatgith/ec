<?php

function GetGridSelectSQL($from, $lang, $idtosearch = -1, $orderby = '')
{
    $field_list = array();
    $temp_as    = "{$from['as']}";

    $field_left     = array("");
    $field_left_end = array();
    $typeField      = array();
    //error_reporting(E_ALL);
    foreach ($from["nonlang_field"] as $key => $value) {
        if ($value['colType'] == 'html')
            continue;
        // если установлено правило связывания разных таблиц
        if (isset($value['rules'])) {
            if (isset($value['multy']) && $value['multy']) {
                //если множенственные выборки "1,3,77"
                $temp_as_left = $temp_as . $key;
                $temp_as_link = $temp_as . $key . 'link';
                //таблица линкования
                $link_table   = $value['link_table'];
                $table        = "";

                if (isset($value['outfield'])) {
                    $outfield = $value['outfield'];
                    $table    = $value['table'];
                } elseif (isset($value['outfield_lang'])) {
                    $outfield = $value['outfield_lang'];
                    $table    = $value['table'] . "_lang";
                }


                //таблица линкования может быть привязана к мультиязычной части.
                $langer           = ($value['nonlang']) ?
                        "" : "AND {$temp_as_left}.lang_id={$lang}";
                $field_list[$key] = "(SELECT group_concat({$temp_as_left}.{$outfield} SEPARATOR ', ')
                FROM ?_{$link_table} as {$temp_as_link}
                INNER JOIN ?_{$table} as {$temp_as_left}
                ON {$temp_as_link}.{$value['linkfield']} = {$temp_as_left}.id
                WHERE {$temp_as_link}.{$value['field']} = {$from['as']}.id
                {$langer}
                ) as {$key},
                (SELECT group_concat({$temp_as_link}.{$value['linkfield']} SEPARATOR ', ')
                FROM ?_{$link_table} as {$temp_as_link}
                WHERE {$temp_as_link}.{$value['field']} = {$from['as']}.id
                ) as {$key}_id";
            } else {
                // это есть селект и не мультяшный
                // мы не связаны с детьми...
                if (!isset($value['colChild']))
                    $connParentField = "{$temp_as}.{$key}";
                // мы связаны с детьми и детей уже обработали
                elseif (isset($from['nonlang_field'][$value['colChild']]['conn_to_parent_field']))
                    $connParentField = $from['nonlang_field'][$value['colChild']]['conn_to_parent_field'];
                // мы связаны с детьми но детей еще не обрабатывали
                else
                    $connParentField = "{connfield}"; //

                if ($value['nonlang']) {
                    //безязыковая версия
                    $temp_as_left = $temp_as . $key;
                    if (is_array($value['outfield'])) {
                        $str              = "concat(" . $temp_as_left . "." . implode(",'. ', " . $temp_as_left . ".", $value['outfield']) . ")";
                        $field_list[$key] = "{$str} as " . $key;
                    } else
                        $field_list[$key] = $temp_as_left . ".{$value['outfield']} as " . $key;

                    $field_list[$key . "_lang"] = $temp_as_left . ".id as " . $key . "_id";

                    $field_left_end[$key] = "LEFT JOIN ?_{$value["table"]} as {$temp_as_left}
                    ON {$connParentField}={$temp_as_left}.{$value["field"]}";

                    $from['nonlang_field'][$key]['conn_to_parent_field'] = $temp_as_left . "." . $value['connField'];
                } elseif ($value['islanged']) {
                    //таблица приведенного типа
                    $temp_as_left = $temp_as . $key;
                    //$field_list[$key] = $temp_as_left.".{$value['outfield']} as ".$key;
                    if (is_array($value['outfield'])) {
                        $str              = "concat(" . $temp_as_left . "." . implode(",'. ', " . $temp_as_left . ".", $value['outfield']) . ")";
                        $field_list[$key] = "{$str} as " . $key;
                    } else {
                        $field_list[$key] = $temp_as_left . ".{$value['outfield']} as " . $key;
                    }
                    $field_list[$key . '_lang'] = $temp_as_left . ".id as " . $key . "_id";

                    $field_left_end[$key] = "LEFT JOIN ?_{$value["table"]} as {$temp_as_left}
                    ON {$connParentField}={$temp_as_left}.{$value["field"]} AND {$temp_as_left}.lang_id='{$lang}'";

                    $from['nonlang_field'][$key]['conn_to_parent_field'] = $temp_as_left . "." . $value['connField'];
                } else {
                    //языковая версия
                    $temp_as_left      = $temp_as . $key;
                    $temp_as_left_lang = $temp_as . $key . "_lang";

                    $field_list[$key]         = (isset($value['outfield'])) ?
                            $temp_as_left . ".{$value['outfield']} as " . $key :
                            $temp_as_left_lang . ".{$value['outfield_lang']} as " . $key;
                    $field_list[$key . '_lang'] = $temp_as_left . ".id as " . $key . "_id";

                    if (isset($value["field"])) {
                        /*  LEFT JOIN table as t1 ON t_main.fl = t1.f1
                          LEFT JOIN table_lang as t1_lang  ON t1_lang.lang = 1 AND t1.id = t1_lang.id */
                        $field_left_end[$key] = "LEFT JOIN ?_{$value["table"]} as {$temp_as_left}
                        ON {$connParentField}={$temp_as_left}.{$value["field"]}";
                        $field_left_end[$key] .= " LEFT JOIN ?_{$value["table"]}_lang as {$temp_as_left_lang}
                        ON {$temp_as_left_lang}.id = {$temp_as_left}.id AND {$temp_as_left_lang}.lang_id='{$lang}'";
                    } else {
                        /*  LEFT JOIN table_lang as t1_lang  ON t1_lang.lang = 1 AND t1_lang.f1 = t_main.fl
                          LEFT JOIN table as t1 ON t1_lang.id = t1.id */
                        $field_left_end[$key] = "LEFT JOIN ?_{$value["table"]}_lang as {$temp_as_left_lang}
                        ON {$connParentField}={$temp_as_left_lang}.{$value["field_lang"]} AND {$temp_as_left_lang}.lang_id='{$lang}'";
                        $field_left_end[$key] .= " LEFT JOIN ?_{$value["table"]} as {$temp_as_left}
                        ON {$temp_as_left_lang}.id={$temp_as_left}.id";
                    }
                    $from['nonlang_field'][$key]['conn_to_parent_field'] = (isset($value['connField'])) ?
                            $temp_as_left . "." . $value['connField'] :
                            $temp_as_left_lang . "." . $value['connField_lang'];
                }

                //ищем своего ребенка и срочно пытаемся ему установить поле для связи.
                if (isset($value['colParent'])) {
                    $field_left_end[$value['colParent']] = str_replace("{connfield}", $from['nonlang_field'][$key]['conn_to_parent_field'], $field_left_end[$value['colParent']]);
                }
            }
        } else {
            $field_list[$key] = $temp_as . "." . $key;
        }
    }

    if ((!isset($from['islanged']) || !$from['islanged']) && count($from["multylang_field"]) > 0) {
        $langSql = "LEFT JOIN ?_{$from['table']}_lang as {$temp_as}_lang
        ON {$temp_as}.id={$temp_as}_lang.id and {$temp_as}_lang.lang_id = {$lang}";

        $temp_as = "{$from['as']}_lang";
    }

    foreach ($from["multylang_field"] as $key => $value) {
        if ($value['colType'] == 'html')
            continue;
        // если установлено правило связывания разных таблиц
        if (isset($value['rules'])) {
            if (isset($value['multy']) && $value['multy']) {
                //если множенственные выборки "1,3,77"
                $temp_as_left = $temp_as . $key;
                $temp_as_link = $temp_as . $key . 'link';
                $link_table   = $value['link_table'];

                //таблица линкования может быть привязана к мультиязычной части.
                $langer           = ($value['nonlang']) ?
                        "" : "AND {$temp_as_left}.lang_id={$lang}";
                $field_list[$key] = "(SELECT group_concat({$temp_as_left}.{$value['outfield']} SEPARATOR ', ')
                FROM ?_{$link_table} as {$temp_as_link}
                INNER JOIN ?_{$value['table']} as {$temp_as_left}
                ON {$temp_as_link}.{$value['linkfield']} = {$temp_as_left}.id
                WHERE {$temp_as_link}.{$value['field']} = {$from['as']}.id
                AND {$temp_as_link}.lang_id = {$lang}
                {$langer}
                ) as {$key},
                (SELECT group_concat({$temp_as_link}.{$value['linkfield']} SEPARATOR ', ')
                FROM ?_{$link_table} as {$temp_as_link}
                WHERE {$temp_as_link}.{$value['field']} = {$from['as']}.id
                AND {$temp_as_link}.lang_id = {$lang}
                ) as {$key}_id";
            } else {
                // это есть селект и не мультяшный
                // мы не связаны с детьми...
                if (!isset($value['colChild'])) {
                    $connParentField = "{$temp_as}.{$key}";
                }
                // мы связаны с детьми и детей уже обработали
                elseif (isset($from['multylang_field'][$value['colChild']]['conn_to_parent_field']))
                    $connParentField = $from['multylang_field'][$value['colChild']]['conn_to_parent_field'];
                // мы связаны с детьми но детей еще не обрабатывали
                else
                    $connParentField = "{connfield}"; //

                if ($value['nonlang']) {
                    //безязыковая версия
                    $temp_as_left             = $temp_as . $key;
                    $field_list[$key]         = $temp_as_left . ".{$value['outfield']} as " . $key;
                    $field_list[$key . "_lang"] = $temp_as_left . ".id as " . $key . "_id";

                    $field_left_end[$key] = "LEFT JOIN ?_{$value["table"]} as {$temp_as_left}
                    ON {$connParentField}={$temp_as_left}.{$value["field"]}";

                    $from['multylang_field'][$key]['conn_to_parent_field'] = $temp_as_left . "." . $value['connField'];
                } elseif ($value['islanged']) {
                    //таблица приведенного типа
                    $temp_as_left             = $temp_as . $key;
                    $field_list[$key]         = $temp_as_left . ".{$value['outfield']} as " . $key;
                    $field_list[$key . '_lang'] = $temp_as_left . ".id as " . $key . "_id";

                    $field_left_end[$key] = "LEFT JOIN ?_{$value["table"]} as {$temp_as_left}
                    ON {$connParentField}={$temp_as_left}.{$value["field"]} AND {$temp_as_left}.lang_id='{$lang}'";

                    $from['multylang_field'][$key]['conn_to_parent_field'] = $temp_as_left . "." . $value['connField'];
                } else {
                    //языковая версия
                    $temp_as_left      = $temp_as . $key;
                    $temp_as_left_lang = $temp_as . $key . "_lang";

                    $field_list[$key]         = (isset($value['outfield'])) ?
                            $temp_as_left . ".{$value['outfield']} as " . $key :
                            $temp_as_left_lang . ".{$value['outfield_lang']} as " . $key;
                    $field_list[$key . '_lang'] = $temp_as_left . ".id as " . $key . "_id";

                    if (isset($value["field"])) {
                        /*  LEFT JOIN table as t1 ON t_main.fl = t1.f1
                          LEFT JOIN table_lang as t1_lang  ON t1_lang.lang = 1 AND t1.id = t1_lang.id */
                        $field_left_end[$key] = "LEFT JOIN ?_{$value["table"]} as {$temp_as_left}
                        ON {$connParentField}={$temp_as_left}.{$value["field"]}";
                        $field_left_end[$key] .= " LEFT JOIN ?_{$value["table"]}_lang as {$temp_as_left_lang}
                        ON {$temp_as_left_lang}.id = {$temp_as_left}.id AND {$temp_as_left_lang}.lang_id='{$lang}'";
                    } else {
                        /*  LEFT JOIN table_lang as t1_lang  ON t1_lang.lang = 1 AND t1_lang.f1 = t_main.fl
                          LEFT JOIN table as t1 ON t1_lang.id = t1.id */
                        $field_left_end[$key] = "LEFT JOIN ?_{$value["table"]}_lang as {$temp_as_left_lang}
                        ON {$connParentField}={$temp_as_left_lang}.{$value["field_lang"]} AND {$temp_as_left_lang}.lang_id='{$lang}'";
                        $field_left_end[$key] .= " LEFT JOIN ?_{$value["table"]} as {$temp_as_left}
                        ON {$temp_as_left_lang}.id={$temp_as_left}.id";
                    }
                    $from['multylang_field'][$key]['conn_to_parent_field'] = (isset($value['connField'])) ?
                            $temp_as_left . "." . $value['connField'] :
                            $temp_as_left_lang . "." . $value['connField_lang'];
                }

                //ищем своего ребенка и срочно пытаемся ему установить поле для связи.
                if (isset($value['colParent'])) {
                    $field_left_end[$value['colParent']] = str_replace("{connfield}", $from['multylang_field'][$key]['conn_to_parent_field'], $field_left_end[$value['colParent']]);
                }
            }
        } else {
            $field_list[$key] = $temp_as . "." . $key;
        }
    }

    $langer = (isset($from['islanged']) && $from['islanged']) ? "{$from['as']}.lang_id = {$lang}" : "1=1";
    $SQL    = "SELECT
    SQL_CALC_FOUND_ROWS
    " . implode(",", $field_list) . "
    FROM
    ?_{$from['table']} as {$from['as']} " . $langSql;

    foreach ($field_left as $value) {
        $SQL .= " " . $value;
    }
    $sqlbackstr = "";
    foreach ($field_left_end as $value) {
        $sqlbackstr = $value . " " . $sqlbackstr;
    }
    $SQL .= $sqlbackstr;

    // это было закоменчено
    $SQL .= " WHERE {$langer} ";
    //if ($where!="")  $SQL .= " and ".$where." ";
    // конец камента

    if (isset($from["OwnerCode"]))
        $SQL .= "AND {$from['as']}.owner_code='{$from["OwnerCode"]}'";

    if ($idtosearch)
        $SQL .= "AND {$from['as']}.id='{$idtosearch}'";

    if ($orderby == '') {
        $SQL .= (isset($from['islanged']) && $from['islanged']) ? " ORDER BY {$from['as']}.lang_id" : "";
    } else {
        $SQL .= " ORDER BY {$orderby}";
    }

    //echo $SQL; die();
    return array("SQL" => $SQL);
}

function GetLangIdList()
{
    $langList  = array();
    $langQuery = mysql_query("SELECT * FROM ?_lang WHERE active='1' ORDER BY place");
    while ($get       = mysql_fetch_assoc($langQuery)) {
        $langList[] = $get['id'];
    };

    return implode(',', $langList);
}
