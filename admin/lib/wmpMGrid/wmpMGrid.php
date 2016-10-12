<?php
  class mGrid {
    private $db = '';
    public $title = '';
    public $lang = 1;
    public $orderBy;
    public $options;
    private $sortBy;
    private $sortDirection;

    public function __construct($db_name) {
      include_once BASEPATH."/admin/ajax_strip.php";
      include_once BASEPATH."/admin/prepear.php";
      include_once BASEPATH."/admin/mGrid/engine/mGridTemplate.php";

      if ($db_name) {
        $this->db = $db_name;

        // для дебага
        if (isset ($_GET['debug']) && $_GET['debug'] = 1)
          error_reporting(E_ALL);

        //Подключение конфигурационного файла
        $path = "/admin/mGrid/mGridTable" . $this->db . ".php";
        if (file_exists(BASEPATH . $path))
          include BASEPATH . $path;
        else
          die("
            <div style='color: #A77777; padding: 20px;'>
              File {$path} does not exist!<br />\n
              Script work is stopped
            </div>
          ");

        $this->lang = $from['lang'];
        if (isset($_GET['lang'])) {
          $this->lang = intval($_GET['lang']);
        }

        $orderBy = '';
        if (isset($from["orderby"])) {
          $orderBy = $from["orderby"];
          list($this->sortBy, $this->sortDirection) = explode(' ', $orderBy);
        }

        //если не задана извне сортировка, то пітаемся использовать ту, что в разметке
        if (isset($_GET['sidx']) && clear($_GET['sidx']) ) {
            $sidx                = $_GET['sidx'];
            $sord                = (isset($_GET['sord']) && in_array($_GET['sord'], array("asc", "desc"))) ?
                                        $_GET['sord'] : "asc";

            $orderBy = "{$sidx} {$sord}";

            $this->sortBy = $sidx;
            $this->sortDirection = $sord;
        }

        $this->orderBy = $orderBy;

        $this->options = $from;
      }
    }

    public function Show() {
      error_reporting(E_ALL);
//      include BASEPATH."/admin/mGrid/engine/mGridGenerate.php";

      $SqlRes = $this->GetGridSelectSQL ($this->options, $this->lang, -1, $this->orderBy);
      $result = DB::Query( $SqlRes['SQL'], true) or die("Couldn't execute query.".mysql_error());
      $count  = array_shift(DB::GetArray(DB::Query('SELECT FOUND_ROWS()')));

      $tpl = new Template();
      $tpl_fn = BASEPATH . 'admin/lib/wmpMGrid/templates/main.tpl';

      $tpl->set('options', $this->options);
      $tpl->set('sortBy', $this->sortBy);
      $tpl->set('sortDirection', $this->sortDirection);

      $header = $this->GenerateHeader();

      return $tpl->fetch($tpl_fn);
    }

    private function GetGridSelectSQL($from, $lang, $idtosearch = -1, $orderby = ''){
      $field_list  = array();
      $temp_as     = "{$from['as']}";

      $field_left  = array("");
      $field_left_end = array();
      $typeField     = array();
      //error_reporting(E_ALL);
      foreach($from["nonlang_field"] as $key => $value){
        if($value['colType'] == 'html') continue;
        // если установлено правило связывания разных таблиц
        if(isset($value['rules'])){
          if (isset($value['multy']) && $value['multy']){
            //если множенственные выборки "1,3,77"
            $temp_as_left = $temp_as.$key;
            $temp_as_link = $temp_as.$key.'link';
            //таблица линкования
            $link_table = $value['link_table'];
            $table = "";

            if (isset($value['outfield'])) {
              $outfield = $value['outfield'];
              $table = $value['table'];
            } elseif (isset($value['outfield_lang'])) {
              $outfield = $value['outfield_lang'];
              $table = $value['table'] . "_lang";
            }


            //таблица линкования может быть привязана к мультиязычной части.
            $langer = ($value['nonlang']) ?
                "" : "AND {$temp_as_left}.lang_id={$lang}";
            $field_list[$key] =
                "(SELECT group_concat({$temp_as_left}.{$outfield} SEPARATOR ', ')
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
            elseif(isset($from['nonlang_field'][$value['colChild']]['conn_to_parent_field']))
               $connParentField = $from['nonlang_field'][$value['colChild']]['conn_to_parent_field'];
            // мы связаны с детьми но детей еще не обрабатывали
            else
              $connParentField = "{connfield}";//

            if (isset ($value['nonlang']) && $value['nonlang'] == true) {
              //безязыковая версия
              $temp_as_left = $temp_as.$key;
              if (is_array($value['outfield'])){
                $str = "concat(".$temp_as_left.".".implode (",'. ', ".$temp_as_left.".", $value['outfield']).")";
                $field_list[$key] = "{$str} as ".$key;
              } else
                $field_list[$key] = $temp_as_left.".{$value['outfield']} as ".$key;

              $field_list[$key."_lang"] = $temp_as_left.".id as ".$key."_id";

              $field_left_end[$key] = "LEFT JOIN ?_{$value["table"]} as {$temp_as_left}
                          ON {$connParentField}={$temp_as_left}.{$value["field"]}";

              $from['nonlang_field'][$key]['conn_to_parent_field'] = $temp_as_left.".".$value['connField'];
            } elseif ($value['islanged']) {
              //таблица приведенного типа
              $temp_as_left = $temp_as.$key;
              //$field_list[$key] = $temp_as_left.".{$value['outfield']} as ".$key;
              if (is_array($value['outfield'])){
                $str = "concat(".$temp_as_left.".".implode (",'. ', ".$temp_as_left.".", $value['outfield']).")";
                $field_list[$key] = "{$str} as ".$key;
              } else
                $field_list[$key] = $temp_as_left.".{$value['outfield']} as ".$key;

              $field_list[$key.'_lang'] = $temp_as_left.".id as ".$key."_id";

              $field_left_end[$key] = "LEFT JOIN ?_{$value["table"]} as {$temp_as_left}
                          ON {$connParentField}={$temp_as_left}.{$value["field"]} AND {$temp_as_left}.lang_id='{$lang}'";

              $from['nonlang_field'][$key]['conn_to_parent_field'] = $temp_as_left.".".$value['connField'];
            } else {
              //языковая версия
              $temp_as_left = $temp_as.$key;
              $temp_as_left_lang = $temp_as.$key."_lang";

              $field_list[$key] = (isset($value['outfield'])) ?
                        $temp_as_left.".{$value['outfield']} as ".$key:
                        $temp_as_left_lang.".{$value['outfield_lang']} as ".$key;
              $field_list[$key.'_lang'] = $temp_as_left.".id as ".$key."_id";

              if (isset($value["field"])){
                $field_left_end[$key] = "LEFT JOIN ?_{$value["table"]} as {$temp_as_left}
                          ON {$connParentField}={$temp_as_left}.{$value["field"]}";
                $field_left_end[$key] .= " LEFT JOIN ?_{$value["table"]}_lang as {$temp_as_left_lang}
                          ON {$temp_as_left_lang}.id = {$temp_as_left}.id AND {$temp_as_left_lang}.lang_id='{$lang}'";
              } else {
                $field_left_end[$key] = "LEFT JOIN ?_{$value["table"]}_lang as {$temp_as_left_lang}
                          ON {$connParentField}={$temp_as_left_lang}.{$value["field_lang"]} AND {$temp_as_left_lang}.lang_id='{$lang}'";
                $field_left_end[$key] .= " LEFT JOIN ?_{$value["table"]} as {$temp_as_left}
                          ON {$temp_as_left_lang}.id={$temp_as_left}.id";
              }
              $from['nonlang_field'][$key]['conn_to_parent_field'] = (isset($value['connField'])) ?
                            $temp_as_left.".".$value['connField'] :
                            $temp_as_left_lang.".".$value['connField_lang'];
            }

            //ищем своего ребенка и срочно пытаемся ему установить поле для связи.
            if (isset($value['colParent']))  {
              $field_left_end[$value['colParent']] = str_replace("{connfield}", $from['nonlang_field'][$key]['conn_to_parent_field'], $field_left_end[$value['colParent']]);
            }
          }
        } else {
          $field_list[$key] = $temp_as.".".$key;
        }
      }

      if ((!isset($from['islanged']) || !$from['islanged']) && count($from["multylang_field"]) > 0) {
        $langSql = "LEFT JOIN ?_{$from['table']}_lang as {$temp_as}_lang
                          ON {$temp_as}.id={$temp_as}_lang.id and {$temp_as}_lang.lang_id = {$lang}";

        $temp_as     = "{$from['as']}_lang";
      }

      foreach($from["multylang_field"] as $key => $value){
        if($value['colType'] == 'html') continue;
        // если установлено правило связывания разных таблиц
        if(isset($value['rules'])){
          if (isset($value['multy']) && $value['multy']){
            //если множенственные выборки "1,3,77"
            $temp_as_left = $temp_as.$key;
            $temp_as_link = $temp_as.$key.'link';
            $link_table = $value['link_table'];

            //таблица линкования может быть привязана к мультиязычной части.
            $langer = ($value['nonlang']) ?
                "" : "AND {$temp_as_left}.lang_id={$lang}";
            $field_list[$key] =
                "(SELECT group_concat({$temp_as_left}.{$value['outfield']} SEPARATOR ', ')
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
            if (!isset($value['colChild']))  {
              $connParentField = "{$temp_as}.{$key}";
            }
            // мы связаны с детьми и детей уже обработали
            elseif(isset($from['multylang_field'][$value['colChild']]['conn_to_parent_field']))
               $connParentField = $from['multylang_field'][$value['colChild']]['conn_to_parent_field'];
            // мы связаны с детьми но детей еще не обрабатывали
            else
              $connParentField = "{connfield}";//

            if ($value['nonlang']){
              //безязыковая версия
              $temp_as_left = $temp_as.$key;
              $field_list[$key] = $temp_as_left.".{$value['outfield']} as ".$key;
              $field_list[$key."_lang"] = $temp_as_left.".id as ".$key."_id";

              $field_left_end[$key] = "LEFT JOIN ?_{$value["table"]} as {$temp_as_left}
                          ON {$connParentField}={$temp_as_left}.{$value["field"]}";

              $from['multylang_field'][$key]['conn_to_parent_field'] = $temp_as_left.".".$value['connField'];
            } elseif ($value['islanged']) {
              //таблица приведенного типа
              $temp_as_left = $temp_as.$key;
              $field_list[$key] = $temp_as_left.".{$value['outfield']} as ".$key;
              $field_list[$key.'_lang'] = $temp_as_left.".id as ".$key."_id";

              $field_left_end[$key] = "LEFT JOIN ?_{$value["table"]} as {$temp_as_left}
                          ON {$connParentField}={$temp_as_left}.{$value["field"]} AND {$temp_as_left}.lang_id='{$lang}'";

              $from['multylang_field'][$key]['conn_to_parent_field'] = $temp_as_left.".".$value['connField'];
            } else {
              //языковая версия
              $temp_as_left = $temp_as.$key;
              $temp_as_left_lang = $temp_as.$key."_lang";

              $field_list[$key] = (isset($value['outfield'])) ?
                        $temp_as_left.".{$value['outfield']} as ".$key:
                        $temp_as_left_lang.".{$value['outfield_lang']} as ".$key;
              $field_list[$key.'_lang'] = $temp_as_left.".id as ".$key."_id";

              if (isset($value["field"])){
                /*  LEFT JOIN table as t1 ON t_main.fl = t1.f1
                  LEFT JOIN table_lang as t1_lang  ON t1_lang.lang = 1 AND t1.id = t1_lang.id      */
                $field_left_end[$key] = "LEFT JOIN ?_{$value["table"]} as {$temp_as_left}
                          ON {$connParentField}={$temp_as_left}.{$value["field"]}";
                $field_left_end[$key] .= " LEFT JOIN ?_{$value["table"]}_lang as {$temp_as_left_lang}
                          ON {$temp_as_left_lang}.id = {$temp_as_left}.id AND {$temp_as_left_lang}.lang_id='{$lang}'";
              } else {
                /*  LEFT JOIN table_lang as t1_lang  ON t1_lang.lang = 1 AND t1_lang.f1 = t_main.fl
                  LEFT JOIN table as t1 ON t1_lang.id = t1.id    */
                $field_left_end[$key] = "LEFT JOIN ?_{$value["table"]}_lang as {$temp_as_left_lang}
                          ON {$connParentField}={$temp_as_left_lang}.{$value["field_lang"]} AND {$temp_as_left_lang}.lang_id='{$lang}'";
                $field_left_end[$key] .= " LEFT JOIN ?_{$value["table"]} as {$temp_as_left}
                          ON {$temp_as_left_lang}.id={$temp_as_left}.id";
              }
              $from['multylang_field'][$key]['conn_to_parent_field'] = (isset($value['connField'])) ?
                            $temp_as_left.".".$value['connField'] :
                            $temp_as_left_lang.".".$value['connField_lang'];
            }

            //ищем своего ребенка и срочно пытаемся ему установить поле для связи.
            if (isset($value['colParent']))  {
              $field_left_end[$value['colParent']] = str_replace("{connfield}", $from['multylang_field'][$key]['conn_to_parent_field'], $field_left_end[$value['colParent']]);
            }
          }
        } else {
          $field_list[$key] = $temp_as.".".$key;
        }
      }

      $langer = ($from['islanged']) ? "{$from['as']}.lang_id = {$lang}" : "1=1";
      $SQL = "SELECT
              SQL_CALC_FOUND_ROWS
              ".implode(",", $field_list)."
            FROM
              ?_{$from['table']} as {$from['as']} ".$langSql;

      foreach ($field_left as $value){
        $SQL .= " ".$value;
      }
      $sqlbackstr = "";
      foreach ($field_left_end as $value){
        $sqlbackstr = $value." ".$sqlbackstr;
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

      if ($orderby == ''){
        $SQL .=  ($from['islanged']) ? " ORDER BY {$from['as']}.lang_id" : "";
      } else {
        $SQL .=  " ORDER BY {$orderby}";
      }

      if (isset($from['limit'])) {
          if (isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > 0) {
              $SQL .= ' limit '.$from['limit']*($_REQUEST['page']).', ' .$from['limit'];
          }
          else {
              $SQL .= ' limit '. $from['limit'];
          }
      }
      return array("SQL" => $SQL);
    }

    private function GetLangIdList(){
      $langList  = array();
      $langQuery = mysql_query("SELECT * FROM ?_lang WHERE active='1' ORDER BY place");
      while($get  = mysql_fetch_assoc($langQuery)){
        $langList[] = $get['id'];
      };

      return implode(',', $langList);
    }

    private function GetLangControl() {
      global $lang;
      $pars = preg_replace('/&lang=[0-9]*/i', '', $_SERVER['QUERY_STRING']);

      $lang_control = "<div id='langs_change' align='center' style='white-space: nowrap; margin-left:20px;'>";

      $lang_array = Lang::getLanguages();
      foreach ($lang_array as $key => $value){
        if (is_int($key))
          $lang_control .= lang_buton($key, $value['title_short'], $pars, $key == $lang);
      }
      $lang_control .="</div>";

      return $lang_control;
    }

    private function lang_buton($lang, $name, $path, $active) {
      $active = ($active) ? "active" : "";
      return "<div id='lang_{$name}' class='langselect {$active}' style='cursor: default; float:left; margin-left:10px;'>
              <a href='".$_SERVER['PHP_SELF']."?".$path."&lang=".$lang."'>| {$name} |</a>
          </div>";
    }

    private function GenerateHeader() {
      $tpl = new Template();
      $tpl_fn = BASEPATH . 'admin/lib/wmpMGrid/templates/header.tpl';

      $tpl->set('options', $this->options);
      $tpl->set('sortBy', $this->sortBy);
      $tpl->set('sortDirection', $this->sortDirection);

      return $tpl->fetch($tpl_fn);
    }
  }
?>
