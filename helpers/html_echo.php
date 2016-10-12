<?php

function partOfString($string, $limit = 85, $end = ' ...')
{

    if (strlen($string) <= $limit)
        return $string;

    $string = substr(strip_tags($string), 0, $limit);
    $string = explode(' ', $string);

    array_pop($string);

    return implode(' ', $string) . $end;
}

function string_upper($text)
{
    $text = str_replace(array('й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ъ', 'ф', 'ы', 'в', 'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'э', 'я', 'ч', 'с', 'м', 'и', 'т', 'ь', 'б', 'ю', 'ї', 'і', 'є', 'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm'), array('Й', 'Ц', 'У', 'К', 'Е', 'Н', 'Г', 'Ш', 'Щ', 'З', 'Х', 'Ъ', 'Ф', 'Ы', 'В', 'А', 'П', 'Р', 'О', 'Л', 'Д', 'Ж', 'Э', 'Я', 'Ч', 'С', 'М', 'И', 'Т', 'Ь', 'Б', 'Ю', 'Ї', 'І', 'Є', 'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M'), $text);
    return($text);
}

//выведение некоего хтмл-кода с пересыпкой евалов для спецвставок
function EchoWithEvalingHtml($str_content)
{

    static $mask = '/{{([а-яїієА-ЯЇЄA-Za-z0-9_\(\)\'\"\,\-\>\[\]\"\"\$\s\=]+)}}/Usi';
    $str_content = str_replace('&#39;', "'", $str_content);

    if (preg_match_all($mask, $str_content, $array_for1)) {

        $i = 0;

        foreach ($array_for1[1] as $k => $v) {
            $v    = str_replace("$", "\$", $v);
            unset($result);
            $comm = "\$result=" . $v . ";\n";
            @eval($comm);

            $v = str_replace("$", "\$", $v);
            $v = str_replace("'", "'", $v);
            $v = str_replace("(", "\(", $v);
            $v = str_replace(")", "\)", $v);

            $patterns[$i] = "/{{" . $v . "}}/";

            $patterns[$i]     = str_replace('$', '\\$', $patterns[$i]);
            $replacements[$i] = $result;
            $i++;
        }
        // \\Обробка вставок в контенті
        $str_content = preg_replace($patterns, $replacements, $str_content, 1);
    }

    if (stristr("$str_content", "{php") != "" and stristr("$str_content", "php}") != "") {
        $temp_explode = @explode("{php", $str_content);
        echo $temp_explode[0];
        for ($xyz = 1; $xyz < count($temp_explode); $xyz++) {
            $temp2_explode = @explode("php}", $temp_explode[$xyz]);
            $php_str       = @strip_tags($temp2_explode[0]);
            eval($php_str);
            echo $temp2_explode[1];
        }
    } else
        echo $str_content;
}

function ConvertWithEvalingHtml($str_content)
{
    ob_start();
    EchoWithEvalingHtml($str_content);
    $text = ob_get_contents();
    ob_end_clean();
	
    return $text;
}

//выведение некоего хтмл-кода без спецвставок
function ConvertAltToHtml($text)
{

    $text = str_replace("&quot;", "\"", $text);
    $text = str_replace("&temp_gt;", "&gt;", $text);
    $text = str_replace("&temp_lt;", "&lt;", $text);
    $text = str_replace("&gt;", ">", $text);
    $text = str_replace("&lt;", "<", $text);
    $text = str_replace("&#39;", "'", $text);
     
    return $text;
}

//выведение некоего хтмл-кода без спецвставок
function EchoWithNoEvalingHtml($text)
{
    echo ConvertAltToHtml($text);
}

//для сохранения в базу, преобразование
function ConvertHtmlToAlt($text)
{
    $text = str_replace("\"", "&quot;", $text);
    $text = str_replace("&gt;", "&temp_gt;", $text);
    $text = str_replace("&lt;", "&temp_lt;", $text);
    $text = str_replace(">", "&gt;", $text);
    $text = str_replace("<", "&lt;", $text);
    $text = str_replace("'", "&#39;", $text);

    return $text;
}

function quoteExplode($string)
{
    return array_merge(array_filter(preg_split('/["\'»«]+/usi', $string)), array('', ''));
}

function caseExplode($string)
{
    return preg_split('/(?=[A-ZА-Я])/u', $string, -1, PREG_SPLIT_NO_EMPTY);
}

function wrapWord($string, $wPosition, $wrapper = '')
{

    $strings = explode(' ', $string);

    if (isset($strings[$wPosition])) {
        $strings[$wPosition] = '<' . $wrapper . '>' . $strings[$wPosition] . '</' . $wrapper . '>';
    }

    return implode(' ', $strings);
}

function parseProperty($string = '')
{

    $options     = explode(',', $string);
    $optionsHtml = '';

    foreach ($options as $option) {

        $option = trim($option);

        if (strpos($option, '-') !== false) {

            $rangeOptions = explode('-', $option);

            foreach (range(trim($rangeOptions[0]), trim($rangeOptions[1])) as $val) {
                $optionsHtml .= '<option value="' . htmlspecialchars($val, ENT_QUOTES) . '">' . $val . '</option>';
            }
        } else {
            $optionsHtml .= '<option value="' . htmlspecialchars($option, ENT_QUOTES) . '">' . $option . '</option>';
        }
    }

    return $optionsHtml;
}

/*
 * Gallery
 * Youtube
 * Form
 * IBlock
 * Banner
 * Map
 * Menu
 * Controller
 * Catalog
 */
function makeAction($text)
{
    $matches = array();
    preg_match_all('/\[gallery:(.*?)\]/', $text, $matches);
    if (is_array($matches) && count($matches[1])) {
        foreach ($matches[1] as $k => $m) {
            if (count($gallery = explode(',', $m)) < 2) {
                $gallery[1] = 1;
            }
            $galleryText = Gallery::getContentGallery(intval($gallery[0]), $gallery[1]);
            $text = str_replace($matches[0][$k], $galleryText, $text);
        }

        unset($matches);
    }

    $matches = array();
    preg_match_all('/\[youtube:(.*?)\]/', $text, $matches);
    if (is_array($matches) && count($matches[1])) {
        foreach ($matches[1] as $k => $m) {
            $query = array();
            $context = array('width' => 0, 'height' => 0);

            $video = explode(',', $m);
            $url = parse_url($video[0]);
            parse_str($url['query'], $query);

            if (!isset($query['v']) || empty($query['v'])) {
                continue;
            }

            if (isset($video[1]) && isset($video[2])) {
                $context = array('width' => intval($video[1]), 'height' => intval($video[2]));
            }

            $videoText = Gallery::getVideoOne('//www.youtube.com/embed/' . $query['v'] . '?wmode=transparent', $context);
            $text = str_replace($matches[0][$k], $videoText, $text);
        }

        unset($matches);
    }

    $matches = array();
    preg_match_all('/\[form:(.*?)\]/', $text, $matches);
    if (is_array($matches) && count($matches[1])) {
        foreach ($matches[1] as $k => $m) {
            if (count($form = explode(',', $m)) < 2) {
                $form[1] = 1;
            }

            $formObj = new Forms(intval($form[0]), intval(Lang::getID()));
            $formText = $formObj->setTpl($form[1])->showForm();
            $text = str_replace($matches[0][$k], $formText, $text);
        }

        unset($matches);
    }

    $matches = array();
    preg_match_all('/\[iblock:(.*?)\]/', $text, $matches);
    if (is_array($matches) && count($matches[1])) {
        foreach($matches[1] as $k => $m) {
            $iblockText = Controller::run('iblock/' . $m, array(), true);
            $text = str_replace($matches[0][$k], $iblockText, $text);
        }

        unset($matches);
    }
    
    $matches = array();
    preg_match_all('/\[catalog:(.*?)\]/', $text, $matches);
    if (is_array($matches) && count($matches[1])) {
        foreach($matches[1] as $k => $m) {
            $catalogText = Controller::run('catalog/' . $m, array(), true);
            $text = str_replace($matches[0][$k], $catalogText, $text);
        }
        
        unset($matches);
    }
    
    $matches = array();
    preg_match_all('/\[banner:(.*?)\]/', $text, $matches);    
    if (is_array($matches) && count($matches[1])) {
        if (strlen($matches[1][0])) {
            $parts = explode(',', $matches[1][0]);
            $bannerText = Banner::getByLocation($parts[0], $parts[1] ? trim($parts[1]) : 1);
            $text = str_replace($matches[0][0], $bannerText, $text);
        }
        
        unset($matches);
    }
    
    $matches = array();
    preg_match_all('/\[map:(.*?)\]/', $text, $matches);    
    if (is_array($matches) && count($matches[1])) {
        foreach($matches[1] as $k => $m) {
            $coords = array_filter(explode(',', $m));
            if(count($coords) > 2) {
                $marker = new GoogleMapsMarker;
                $marker->setLat($coords[0])->setLng($coords[1]);
                
                $map = new GoogleMaps;
                $map
                    ->setLat($coords[0])
                    ->setLng($coords[1])
                    ->addMarker($marker);
                
                if(isset($coords[2])) {
                    $map->setWidth(intval($coords[2]));
                    
                    if(isset($coords[3])) {
                        $map->setHeight(intval($coords[3]));
                    }
                }
                
                if(isset($coords[4])) {
                    $map->setZoom(intval($coords[4]));
                }
                
                $text = str_replace($matches[0][$k], $map->show(), $text);
                
                unset($marker); unset($map);
            }
        }
        
        unset($matches);
    }    
    
    $matches = array();
    preg_match_all('/\[menu:(.*?)\]/', $text, $matches);
    if (is_array($matches) && count($matches[1])) {
        if (strlen($matches[1][0])) {
            $parts = explode(',', $matches[1][0]);
            $menu = Menu::getTreeByTemplate(Lang::getID(), $parts[0], $parts[1]);
            $text = str_replace($matches[0][0], $menu, $text);
        }

        unset($matches);
    }
    
    $matches = array();
    preg_match_all('/\[controller:(.*?)\]/', $text, $matches);
    if (is_array($matches) && count($matches[1])) {
        foreach($matches[1] as $k => $m) {
            $controllerText = Controller::run($m, array(), true);
            $text = str_replace($matches[0][$k], $controllerText, $text);
        }
    }

    return $text;    
}
