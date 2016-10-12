<?php

/////////////////////////////////////////////////////////
//                                                     //
//  Данный файл содержит функции правой части админки  //
//                                                     //
/////////////////////////////////////////////////////////

/*
  Заголовок
 */
function admin_func_top($title)
{
    $temp_echo = '<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all"><div class="ui-jqgrid"><div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all"><div class="ui-jqgrid"><div class="ui-jqgrid-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix"><span class="ui-jqgrid-title" style="padding-left: 4px;">';
    $temp_echo .= $title;
    $temp_echo .= '</span></div></div></div></div></div>';

    return $temp_echo;
}

/*
  Вывод сообщений про обработанность различных действий
 */

function admin_func_sys_message($sys_message)
{
    if (count($sys_message) > 0) {
        $temp_echo = "<table cellspacing=0 cellpadding=0 border=0 width=\"100%\">";
        $temp_echo .= "<tr><td>";
        $temp_echo .= "<table cellspacing=1 cellpadding=5 border=0 width=\"100%\" bgcolor=627080>";
        $temp_echo .= "<tr><td bgcolor=ffffff height=30 class=\"mes\">";

        $temp = @implode("</li>\n\r<li>", $sys_message);
        $temp = "<li>" . $temp . "</li>";

        $temp_echo .= $temp;

        $temp_echo .= "</td>";
        $temp_echo .= "</table>";
        $temp_echo .= "<tr><td><IMG src=\"p.gif\" width=1 height=1 border=0></td>";
        $temp_echo .= "</table>";
    }

    return $temp_echo;
}

/*
  Вывод начала правой табличной многострочности
 */

function admin_func_right_table_start($type)
{
    if (empty($type))
        $type = " bgcolor=a5cd38 cellspacing=1 cellpadding=2";
    elseif ($type == "1")
        $type = " bgcolor=ffffff cellspacing=1 cellpadding=2";
    elseif ($type == "2")
        $type = " bgcolor=ffffff cellspacing=0 cellpadding=3";
    elseif ($type == "3")
        $type = " bgcolor=ffffff cellspacing=0 cellpadding=0";
    elseif ($type == "4")
        $type = " bgcolor=ffffff cellspacing=5 cellpadding=0";
    elseif ($type == "5")
        $type = " bgcolor=ffffff cellpadding=0 cellspacing=0 align=center";
    elseif ($type == "6")
        $type = " bgcolor=ffffff cellpadding=4 cellspacing=0 align=center";
    elseif ($type == "7")
        $type = " cellpadding=3 cellspacing=0 class=tdall";

    $temp_echo = "<table border=0 width=\"100%\"" . $type . ">";

    return $temp_echo;
}

/*
  Вывод НЕ начала правой табличной многострочности
 */

function admin_func_right_table_end()
{
    $temp_echo = "</table>";

    return $temp_echo;
}

/*
  Вывод начала правой табличной строки
 */

function admin_func_right_table_row_start($type, $context)
{

    $params = explode(".", $type);

    if (count($params) > 1) {
        $type = $params[0];
        $id   = $params[1];
    } else {
        $type = $params[0];
    }

    if (empty($type)) {
        $bgcolor = "class_1";
    } elseif ($type == 1) {
        $bgcolor = "class_2";
    } elseif ($type == 2) {
        $bgcolor = "class_3";
    } elseif ($type == 3) {
        $bgcolor = "class_4";
    } elseif ($type == 4) {
        $bgcolor = "class_5";
    }

    if (isset($context['id'])) {
        $id = $context['id'];
    }
    
    return '<tr class="' . $bgcolor . '" id="' . $id . '">';
}

/*
  Вывод правой табличной ячейки
 */

function admin_func_right_table_data($name, $width, $type, $needNobr = true, $style = "")
{
    if (!empty($width))
        $width = " width=" . $width . "";

    if (empty($type)) {
        $td_start = "<th class=\"top\"" . $width . " style='" . $style . "' >";
        $td_end   = "</th>";
    } elseif ($type == "th") {
        $td_start = "<th " . $width . " style='" . $style . "' >";
        $td_end   = "</th>";
    } elseif ($type == "th1") {
        $td_start = "<th " . $width . " class=\"gr\" id=\"right\" style='" . $style . "' >";
        $td_end   = "</th>";
    } elseif ($type == "1") {
        $td_start = "<td" . $width . " style='" . $style . "' ><b>";
        $td_end   = "</td>";
    } elseif ($type == "1ltb") {
        $td_start = "<td" . $width . " class=tdltb style='" . $style . "' ><b>";
        $td_end   = "</td>";
    } elseif ($type == "1tbr") {
        $td_start = "<td" . $width . " class=tdtbr style='" . $style . "' ><b>";
        $td_end   = "</td>";
    } elseif ($type == "1t") {
        $td_start = "<td" . $width . " class=tdt style='" . $style . "' ><b>";
        $td_end   = "</td>";
    } elseif ($type == "1b") {
        $td_start = "<td" . $width . " class=tdb style='" . $style . "' ><b>";
        $td_end   = "</td>";
    } elseif ($type == "1l") {
        $td_start = "<td" . $width . " class=tdl style='" . $style . "' ><b>";
        $td_end   = "</td>";
    } elseif ($type == "1lt") {
        $td_start = "<td" . $width . " class=tdlt style='" . $style . "' ><b>";
        $td_end   = "</td>";
    } elseif ($type == "1lb") {
        $td_start = "<td" . $width . " class=tdlb style='" . $style . "' ><b>";
        $td_end   = "</td>";
    } elseif ($type == "1r") {
        $td_start = "<td" . $width . " class=tdr style='" . $style . "' ><b>";
        $td_end   = "</td>";
    } elseif ($type == "1tr") {
        $td_start = "<td" . $width . " class=tdtr style='" . $style . "' ><b>";
        $td_end   = "</td>";
    } elseif ($type == "1br") {
        $td_start = "<td" . $width . " class=tdrb style='" . $style . "' ><b>";
        $td_end   = "</td>";
    } elseif ($type == "1ltbr") {
        $td_start = "<td" . $width . " class=tdltbr colspan=2 style='" . $style . "' ><b>";
        $td_end   = "</td>";
    } elseif ($type == "1i") {
        $td_start = "<td " . $width . " style='" . $style . "' ><i>";
        $td_end   = "</td>";
    } elseif ($type == "2") {
        $td_start = "<td" . $width . " style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "2title") {
        $td_start = "<td" . $width . " style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "2tbr") {
        $td_start = "<td" . $width . " class=tdtbr style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "2t") {
        $td_start = "<td" . $width . " class=tdt style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "2b") {
        $td_start = "<td" . $width . " class=tdb style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "2l") {
        $td_start = "<td" . $width . " class=tdl style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "2li") {
        $td_start = "<td" . $width . " class=tdl style='" . $style . "' ><i>";
        $td_end   = "</i></td>";
    } elseif ($type == "2lt") {
        $td_start = "<td" . $width . " class=tdlt style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "2lb") {
        $td_start = "<td" . $width . " class=tdlb style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "2lbi") {
        $td_start = "<td" . $width . " class=tdlb style='" . $style . "' ><i>";
        $td_end   = "</i></td>";
    } elseif ($type == "2r") {
        $td_start = "<td" . $width . " class=tdr style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "2tr") {
        $td_start = "<td" . $width . " class=tdtr style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "2br") {
        $td_start = "<td" . $width . " class=tdbr style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "2i") {
        $td_start = "<td" . $width . "><i>";
        $td_end   = "</td>";
    } elseif ($type == "2lang") {
        $td_start = "<td" . $width . " style=\"font-size:10px;" . $style . "\">";
        $td_end   = "</td>";
    } elseif ($type == "3") {
        $td_start = "<td class=\"w\"" . $width . " style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "3b") {
        $td_start = "<td class=\"w\"" . $width . " style='" . $style . "' ><b>";
        $td_end   = "</td>";
    } elseif ($type == "3lang") {
        $td_start = "<td " . $width . " style='" . $style . "' ><font color=white><b>";
        $td_end   = "</td>";
    } elseif ($type == "3small") {
        $td_start = "<td style=\"font-size: 10px;\">";
        $td_end   = "</td>";
    } elseif ($type == "3l") {
        $td_start = "<td valign=\"top\" " . $width . " class=tdl style='" . $style . "' ><b>";
        $td_end   = "</td>";
    } elseif ($type == "4") {
        $td_start = "<td bgcolor=FEFaF4" . $width . " style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "5") {
        $td_start = "<td align=center" . $width . " style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "5b") {
        $td_start = "<td align=center" . $width . " style='" . $style . "' ><b>";
        $td_end   = "</b></td>";
    } elseif ($type == "5w") {
        $td_start = "<td align=center" . $width . " class=\"w\" style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "6") {
        $td_start = "<td align=right" . $width . " style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "7") {
        $td_start = "<td colspan=2" . $width . " style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "7w") {
        $td_start = "<td colspan=2" . $width . " class=\"w\" height=\"42\" style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "7c") {
        $td_start = "<td colspan=2" . $width . " align=\"center\" style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "7r") {
        $td_start = "<td colspan=2" . $width . " align=\"right\" style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "7lbr") {
        $td_start = "<td colspan=2" . $width . " class=\"tdlbr\" style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "7lr") {
        $td_start = "<td colspan=2" . $width . " class=\"tdlr\" style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "7i") {
        $td_start = "<td colspan=2" . $width . " style='" . $style . "' ><i>";
        $td_end   = "</i></td>";
    } elseif ($type == "8") {
        $td_start = "<td colspan=2" . $width . " style='" . $style . "' ><b>";
        $td_end   = "</b></td>";
    } elseif ($type == "8lr") {
        $td_start = "<td colspan=2" . $width . " class=\"tdlr\" style='" . $style . "' ><b>";
        $td_end   = "</b></td>";
    } elseif ($type == "9") {
        $td_start = "<td valign=top" . $width . " style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "9b") {
        $td_start = "<td valign=top" . $width . " style='" . $style . "' ><b>";
        $td_end   = "</b></td>";
    } elseif ($type == "9i") {
        $td_start = "<td valign=top" . $width . " style='" . $style . "' ><i>";
        $td_end   = "</i></td>";
    } elseif ($type == "10") {
        $td_start = "<td " . $width . " colspan=\"3\" class=\"w\" style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "11") {
        $td_start = "<td " . $width . " colspan=\"10\" style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "11r") {
        $td_start = "<td " . $width . " colspan=\"10\" align=\"right\" style='" . $style . "' >";
        $td_end   = "</td>";
    } elseif ($type == "12") {
        $td_start = "<td " . $width . " colspan=\"3\" style='" . $style . "' >";
        $td_end   = "</td>";
    } else {
        $td_start = "<td " . $width . " " . $type . " style='" . $style . "' >";
        $td_end   = "</td>";
    }

    if ($needNobr) {
        if ($type == "2title")
            $temp_echo = $td_start . "<nobr class=w>" . $name . "</nobr>" . $td_end;
        else
            $temp_echo = $td_start . "<nobr class=gr>" . $name . "</nobr>" . $td_end;
    } else
        $temp_echo = $td_start . "<span class=gr>" . $name . "</span>" . $td_end;

    return $temp_echo;
}

/*
  Вывод ссылки
 */

function admin_func_right_link($name, $url, $title, $target)
{
    global $hrefauthorization;

    if (substr($url, 0, 7) != "http://") {
        if (strstr($url, "?"))
            $url .= "&$hrefauthorization";
        else
            $url .= "?$hrefauthorization";
    }

    $title = str_replace('"', '&quot;', $title);

    if (!empty($title))
        $title  = " title=\"$title\"";
    if (!empty($target))
        $target = " target=" . $target;

    $temp_echo = "<a href=\"" . $url . "\"" . $title . $target . ">" . $name . "</a>";

    return $temp_echo;
}

/*
  Вывод вывод страничных переходов туда-сюда-обратно
 */

function admin_func_right_pages_view($url)
{
    global $hrefauthorization, $page, $total_results, $on_page;

    if (strstr($url, "?"))
        $url .= "&$hrefauthorization";
    else
        $url .= "?$hrefauthorization";

    $temp_echo = "<br><table border=0 cellpadding=0 cellspacing=0 width=100% align=center>";
    $temp_echo .= "<tr>";
    $temp_echo .= "<td><nobr>";
    $temp_echo .= "<b>Найдено:</b> $total_results&nbsp;&nbsp;&nbsp;&nbsp;";

    if ($page >= 3) {
        $new_page = 1;
        $temp_echo .= "<td><nobr>";
        $temp_echo .= "[";
        $temp_echo .= "<a href=" . $url . "&page=$new_page title=\"" . Dictionary::GetAdminWord(57) . "\"><<<</a>";
        $temp_echo .= "]&nbsp;";
    }

    if ($page >= 2) {
        $new_page = $page - 1;
        $temp_echo .= "<td><nobr>";
        $temp_echo .= "[";
        $temp_echo .= "<a href=" . $url . "&page=$new_page>&lt; " . Dictionary::GetAdminWord(58) . " $on_page</a>";
        $temp_echo .= "]&nbsp;";
    }

    if (ceil($total_results / $on_page) > $page) {
        $new_page = $page + 1;
        $temp_echo .= "<td><nobr>";
        $temp_echo .= "[";
        $temp_echo .= "<a href=" . $url . "&page=$new_page>" . Dictionary::GetAdminWord(59) . " $on_page ></a>";
        $temp_echo .= "]&nbsp;";
    }

    if (ceil($total_results / $on_page) > 2 * $page) {
        $new_page = ceil($total_results / $on_page);
        $temp_echo .= "<td><nobr>";
        $temp_echo .= "[";
        $temp_echo .= "<a href=" . $url . "&page=$new_page title=\"" . Dictionary::GetAdminWord(60) . "\">>>></a>";
        $temp_echo .= "]&nbsp;";
    }

    $temp_echo .= "<td width=100%></td>";
    $temp_echo .= "</table>";

    return $temp_echo;
}

/*
  Вывод input ячеек
 */

function admin_func_right_input($type, $name, $value, $size, $kind, $action = '', $context = array())
{
    if ($type == "submit" and $size == "auto" and !empty($value))
        $size = 6 + strlen($value) * 7;
    if (empty($kind))
        $kind = " style=\"font-size: 10px;\"";
    elseif ($kind == 1)
        $kind = " class=button";
    elseif ($kind == 2)
        $kind = " class=button style=\"background-color:red;\"";
    elseif ($kind == 3)
        $kind = " ";
    elseif ($kind == 4)
        $kind = "src=\"g/n.gif\" alt=\"" . Dictionary::GetAdminWord(355) . "\"";
    elseif ($kind == 5)
        $kind = "class=button";
    elseif ($kind == 6)
        $kind = "onclick=\"return confirm('" . Dictionary::GetAdminWord(588) . " \\n " . Dictionary::GetAdminWord(443) . "');\" class=button";
    elseif ($kind == 7)
        $kind = "style=\"font-weight: bold;\"";
    elseif ($kind == 8)
        $kind = "onclick=\"return confirm('" . Dictionary::GetAdminWord(904) . "');\" class=button";
    elseif ($kind == 9)
        $kind = "onclick=\"return confirm('" . Dictionary::GetAdminWord(644) . "');\" class=button";
    if (!empty($size))
        $size = " style=\"width : " . $size . "px;\"";
    if (!empty($type))
        $type = " type=\"$type\"";
    if (!empty($name))
        $name = " name=\"$name\"";

    $placeholder = '';
    if(!empty($context['placeholder'])) {
        $placeholder = ' placeholder="' . $context['placeholder'] . '" ';
    }

    $temp_echo = "<input" . $type . $name . " value=\"$value\"" . $placeholder . $size . $kind . " {$action} maxlength=250 " . (isset($context['id']) ? ' id="' . $context['id'] . '"' : '');

    if (isset($context['data']))
        foreach ($context['data'] as $key => $val)
            $temp_echo .= ' data-' . $key . '="' . htmlspecialchars($val) . '"';

    $temp_echo .= " />";

    return $temp_echo;
}

function get_input($type, $name, $value, $id = '', $style = '', $element)
{

    $type    = ($type) ? " type=\"{$type}\"" : " type=\"text\"";
    $id      = ($id) ? " id=\"{$id}\"" : "";
    $style   = ($style) ? " style=\"{$style}\"" : "";
    $name    = " name=\"{$name}\"";
    $value   = " value=\"{$value}\" ";
    $element = ($element) ? " {$element}=\"{$element}\"" : "";

    return "<input{$type}{$name}{$id}{$style}{$value}{$element} />";
}

?>