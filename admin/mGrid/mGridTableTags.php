<?php

function tagTreeSelect(& $array, $key, $step) {

    $str = '<optgroup>';


    foreach ($array[$key] as $item) {

        $str .= '<option value="' . $item['id'] . '">' . reprepear($item['outres'], 'input') . '</option>';

        if (isset($array[$item['id']])) {
            $str .= tagTreeSelect($array, $item['id'], $step + 1);
        }
    }

    return $str . '</optgroup>';
}

$GridTitle = 'Теги';
$from = array(
    'jsSort' => 1,
    'jsSortField' => 'place',
    'table' => 'tag',
    'as' => 't',
    'lang' => '2',
    'limit' => 100,
    'islanged' => false,
    'orderby' => 't.parentID, t.category, t.place',
    'style' => 'width:100%',
    'nonlang_field' => array(
        'id' => array(
            'title' => 'ID',
            'tablestyle' => 'width: 40px;',
            'colType' => 'lbl'
        ),
        'parentID' => array(
            'title' => 'Категория',
            'style' => 'width: 100%',
            'tablestyle' => 'width: 210px;padding-left:10px;',
            'colType' => 'select',
            'table' => 'tag',
            'field' => 'id',
            'outfield_lang' => 'title',
            'render' => function($query) {
        $items = array();

        while ($row = DB::GetArray()) {
            $items[$row['parentID']][] = $row;
        }

        return tagTreeSelect(& $items, 0, 0);
    },
            'islanged' => false,
            'rules' => true
        ),
        'category' => array(
            'title' => 'Направление',
            'colType' => 'select',
            'islanged' => true,
            'table' => 'dic_unique',
            'outfield' => 'title',
            'field' => 'id',
            'where' => 'f.code="tagCategory"',
            'rules' => true,
            'tablestyle' => 'width: 210px;padding-left:10px;',
        ),
        /* 'class'    => array(
          'title'      => 'Класс [html]',
          'colType'    => 'text',
          'style'      => 'width: 100px',
          'tablestyle' => 'width: 60px;'
          ),
          'place'    => array(
          'title'      => 'Позиция',
          'colType'    => 'text',
          'style'      => 'width: 100px',
          'tablestyle' => 'width: 60px;'
          ), */
        'isActive' => array(
            'title' => 'Активность',
            'colType' => 'check',
            'tablestyle' => 'width: 70px; text-align:center;'
        ),
        'cnc' => array(
            'title' => 'Код',
            'style' => 'width: 100px;',
            'tablestyle' => 'width: 100px;padding-left:10px;',
            'colType' => 'cnc',
            'fields' => array(
                'title' => 'text'
            )
        )
    ),
    'multylang_field' => array(
        'title' => array(
            'title' => 'Надпись',
            'colType' => 'text',
            'tablestyle' => 'width:100%;padding-left:10px;'
        ),
        'SEOKeywords' => array(
            'title' => 'SEO Keywords',
            'colType' => 'text',
            'tablestyle' => 'width:100%;padding-left:10px;'
        ),
        'SEOTitle' => array(
            'title' => 'SEO Title',
            'colType' => 'text',
            'tablestyle' => 'width:100%;padding-left:10px;'
        ),
        'SEODescription' => array(
            'title' => 'SEO Description',
            'colType' => 'text',
            'tablestyle' => 'width:100%;padding-left:10px;'
        ),
        'text' => array(
            'title' => 'Текст',
            'colType' => 'textarea'
        )
    ),
    'row_seq' => array('id', 'parentID', 'category', 'title', 'cnc', 'isActive')
);
