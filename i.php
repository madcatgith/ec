<?php

$def_qu = 80;

if (empty($i)){
    $i = 0;
}

$remember_forever_i = $i;

$temp_small_img_url = $_REQUEST['temp_small_img_url'];

// Закругление изображения
if (isset($_REQUEST["radius"])) {

    $pathInfo   = pathinfo($temp_small_img_url);
    $extention  = strtolower($pathInfo["extension"]);
    $radius     = intval($_REQUEST["radius"]);
    $parName    = (isset($_REQUEST["height"])) ? "height" : "width";
    $parValue   = (isset($_REQUEST[$parName])) ? $_REQUEST[$parName] : 70;

    $pngFileName = $pathInfo['filename'] . ($_REQUEST["resize"] ? "_" . "resize" : "") . "_" . $parName . "_" . $parValue . "_" . $radius . ".png";

    if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/files_small/" . $pngFileName)) {
        header('content-type: image/png');
        echo file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/files_small/" . $pngFileName);
        exit;
    }

    if ($extention == "jpg") {
        $extention = "jpeg";
    }

    $imagecreatefrom = "imagecreatefrom" . $extention;

    if (function_exists($imagecreatefrom) == false) {
        header('content-type: image/gif');
        echo file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/blank.gif");
        exit;
    }

    $img    = imagecreatefromstring(file_get_contents($_SERVER["DOCUMENT_ROOT"] . $temp_small_img_url));
    $width  = imagesx($img);
    $height = imagesy($img);

    if ($parName == "height") {
        $nHeight = $parValue;
        $nWidth  = floor(($parValue/$height) * $width);
    } else {
        $nWidth  = $parValue;
        $nHeight = floor(($parValue/$width) * $height);
    }

    if (isset($_REQUEST["resize"]) && $_REQUEST["resize"] == true && isset($_REQUEST["dHeight"]) && $_REQUEST["dHeight"] > $nHeight) {
        $nHeight = intval($_REQUEST["dHeight"]);
        $nWidth  = floor(($nHeight/$height) * $width);
    }

    $nX = 0;
    $nY = 0;

    $imgout = imagecreatetruecolor($nWidth, $nHeight);

    imagecopyresampled($imgout, $img, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);

    if (isset($_REQUEST["dHeight"]) && intval($_REQUEST["dHeight"]) > 0) {
        $nX      = ceil((imagesx($imgout) - $nWidth) / 2);
        $nY      = ceil((imagesy($imgout) - $_REQUEST["dHeight"]) / 2);
        $nHeight = $_REQUEST["dHeight"];
    } else if (isset($_REQUEST["dWidth"]) && intval($_REQUEST["dWidth"]) > 0) {
            $nX      = ceil((imagesx($imgout) - $_REQUEST["dWidth"]) / 2);
            $nY      = ceil((imagesy($imgout) - $nHeight) / 2);
            $nWidth  = $_REQUEST["dWidth"];
        }

        $img = imagecreatetruecolor($nWidth, $nHeight);
    imagecopy($img, $imgout, 0, 0, $nX, $nY, $nWidth, $nHeight);
    imagedestroy($imgout);

    imagealphablending($img, false);
    imagesavealpha($img, true);

    $rate      = 3;
    $rs_radius = $radius * $rate;
    $rs_size   = $rs_radius * 2;

    $corner = imagecreatetruecolor($rs_size, $rs_size);
    imagealphablending($corner, false);

    $trans = imagecolorallocatealpha($corner, 255, 255, 255, 127);
    imagefill($corner, 0, 0, $trans);

    $positions = array(
    array(0, 0, 0, 0)
    , array($rs_radius, 0, $nWidth - $radius, 0)
    , array($rs_radius, $rs_radius, $nWidth - $radius, $nHeight - $radius)
    , array(0, $rs_radius, 0, $nHeight - $radius)
    );

    foreach ($positions as $pos) {
        imagecopyresampled($corner, $img, $pos[0], $pos[1], $pos[2], $pos[3], $rs_radius, $rs_radius, $radius, $radius);
    }

    $lx = $ly = 0;
    $i = -$rs_radius;
    $y2 = -$i;
    $r_2 = $rs_radius * $rs_radius;

    for (; $i <= $y2; $i++) {

        $y = $i;
        $x = sqrt($r_2 - $y * $y);

        $y += $rs_radius;
        $x += $rs_radius;

        imageline($corner, $x, $y, $rs_size, $y, $trans);
        imageline($corner, 0, $y, $rs_size - $x, $y, $trans);

        $lx = $x;
        $ly = $y;
    }

    foreach ($positions as $i => $pos) {
        imagecopyresampled($img, $corner, $pos[2], $pos[3], $pos[0], $pos[1], $radius, $radius, $rs_radius, $rs_radius);
    }

    header('Content-Type: image/png');
    imagepng($img, $_SERVER["DOCUMENT_ROOT"] . "/files_small/" . $pngFileName);
    imagedestroy($img);

    echo file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/files_small/" . $pngFileName);

    exit;
}


if(!empty($_REQUEST['product_id'])){ $product_id = intval($_REQUEST['product_id']);    }
if(!empty($_REQUEST['url_id'])){ $url_id = intval($_REQUEST['url_id']);    }
if(!empty($_REQUEST['content_id'])){ $content_id = intval($_REQUEST['content_id']);    }
if(!empty($_REQUEST['map_id'])){ $map_id = intval($_REQUEST['map_id']);    }
if(!empty($_REQUEST['width'])){ $width = intval($_REQUEST['width']);    }
if(!empty($_REQUEST['height'])){ $height = intval($_REQUEST['height']);    }
if(!empty($_REQUEST['watermark'])){ $watermark = intval($_REQUEST['watermark']);    }
if(!empty($_REQUEST['color'])){ $color = $_REQUEST['color'];    }
if(!empty($_REQUEST['lang_id'])){ $lang_id = intval($_REQUEST['lang_id']);    }

if(!empty($product_id) && !empty($url_id)) {
    include "config.php";
    include "includes.php";
} elseif (!empty($product_id))     {
    include "config.php";
    include "includes.php";
} elseif(!empty($content_id)) {
    include "config.php";
    include "includes.php";
} elseif(!empty($map_id)) {
    include "config.php";
    include "includes.php";
}


//если не задано адрес, то нечего и показывать
if(empty($temp_small_img_url) || !is_file($_SERVER['DOCUMENT_ROOT'] . $temp_small_img_url)) {
    header("Content-type: image/gif");
    include "p.gif";
    exit;
}

if(stristr($temp_small_img_url,"http://")) {
    $temp_small_img_url = @explode("/", $temp_small_img_url);
    @array_shift($temp_small_img_url);
    @array_shift($temp_small_img_url);
    @array_shift($temp_small_img_url);
    $temp_small_img_url = @implode("/", $temp_small_img_url);
}

//ищем существующий файл в папке и если такой есть возвращаем его пользователю без дальнейшей обработки
if(!empty($width))     $wname = 'X'.$width.'___';
if(!empty($url_id))  $urlid = 'U'.$url_id.'___';
if(!empty($height))  $hname = 'Y'.$height.'___';
if(!empty($watermark))  $hname = 'W'.$watermark.'___';
if(!empty($color))  $hname = 'C'.$color.'___';

$temp_small_img_url_cache = @explode("/", $temp_small_img_url);
$temp_small_img_url_cache = @array_reverse($temp_small_img_url_cache);

$oldpath = $temp_small_img_url_cache[1].'___';
$temp_small_img_url_cache = $temp_small_img_url_cache[0];
$temp_small_img_url_cache = $wname.$hname.$urlid.$oldpath.$temp_small_img_url_cache;

$extention = strtolower(@array_pop(@explode(".", $temp_small_img_url)));
if($extention == "jpeg" or $extention == "jpg") $extention = "jpeg";

// если задано использование водных знаков
if(isset($watermark)){
    // все водные знаки переводим в png
    $extention_to = "png";
    header("Content-type: image/". $extention_to);

    $ttt = @explode(".", $temp_small_img_url_cache);
    $name = $ttt[0].".png";

    $name = $_SERVER['DOCUMENT_ROOT'].'/files_small/'.$name;
    if (is_file($name)) {
        header("Expires: Mon, 26 Jul 2020 05:00:00 GMT\n");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($name)) . " GMT");
        header("Cache-Control: max-age=360000, must-revalidate");

        echo file_get_contents($name);
        die();
    }
} else {
    //по дефолту переводим в тот же формат, что и был
    $extention_to = $extention;

    $name = $_SERVER['DOCUMENT_ROOT'].'/files_small/'.$temp_small_img_url_cache;
    if (is_file($name)) {
        //нашли файл - вывернуть нужно и выти отсюда
        header("Expires: Mon, 26 Jul 2020 05:00:00 GMT\n");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($name)) . " GMT");
        header("Content-type: image/". $extention_to);

        header("Cache-Control: max-age=360000, must-revalidate");

        echo file_get_contents($name);
        die();
    }
}

//таки не нашли превьюху, значит будем делать преобразования
if(!stristr($temp_small_img_url,"http://") && substr($temp_small_img_url, 0, 1) == "/")
    $temp_small_img_url = substr($temp_small_img_url, 1, (strlen($temp_small_img_url) - 1));

$temp_small_img_url = $_SERVER['DOCUMENT_ROOT'] . "/" . $temp_small_img_url;
$name = $temp_small_img_url;

//мегаизмывания над размерностями входного изображения и выходного $width & $height
$da = getimagesize($name);
if(!empty($width))  $w = $width;
elseif(!empty($height)) $h = $height;
else {
    $w = $da[0];
    $h = $da[1];
}

if (!empty($height)&&!empty($width)) {
    $w = $width;
    $h = $height;
    $my_h = (int) (($w/$da[0])*$da[1]);
    if ($my_h>$h) {
        $w = (int) (($h/$da[1])*$da[0]);
        unset($h);
    }

    $my_w = (int) (($h/$da[1])*$da[0]);
    if ($my_w>$w) {
        $h = (int) (($w/$da[0])*$da[1]);
        unset($w);
    }
}

if(empty($h)) {
    $h = (int) (($w/$da[0])*$da[1]);
}

if(empty($w)) {
    $w = (int) (($h/$da[1])*$da[0]);
}

if    ($extention == "jpeg") $im_in = imagecreatefromjpeg($name);
elseif($extention == "gif" ) $im_in = imagecreatefromgif($name);
elseif($extention == "png" ) {
    $im_in = imagecreatefrompng($name);
    imagealphablending($im_in, true);
    imagesavealpha($im_in, true);
}
elseif($extention == "bmp" ) $im_in = imagecreatefromwbmp($name);

if (isset($_REQUEST["width"]) && isset($_REQUEST["dWidth"]) && intval($_REQUEST["dWidth"]) > 0 && isset($_REQUEST["dHeight"]) && intval($_REQUEST["dHeight"]) > 0) {
    if ($_REQUEST["dHeight"] > $h) {
        $h = $_REQUEST["dHeight"];
        $w = (int) ($h * $da[0] / $da[1]);
    }
}

if (isset($_REQUEST["height"]) && isset($_REQUEST["dWidth"]) && intval($_REQUEST["dWidth"]) > 0 && isset($_REQUEST["dHeight"]) && intval($_REQUEST["dHeight"]) > 0) {
    if ($_REQUEST["dWidth"] > $h) {
        $w = $_REQUEST["dWidth"];
        $h = (int) ($w * $da[1] / $da[0]);
    }
}

//делаем превьюху размера, который вычислили выше
if($extention == "jpeg") $im_out = imagecreatetruecolor($w,$h);
elseif($extention == "png" ) {
    $im_out = imagecreatetruecolor($w,$h);
    imagealphablending($im_out, false);
    imagesavealpha($im_out, true);
}
else $im_out = imagecreate($w, $h);


imagecopyresampled($im_out, $im_in, 0, 0, 0, 0, $w, $h, $da[0], $da[1]);

if (isset($_REQUEST["resize"]) && $_REQUEST["resize"] == true && isset($_REQUEST["dHeight"]) && $_REQUEST["dHeight"] > $nHeight) {

    $nHeight = $h;
    $nWidth  = $w;

    $nX      = 0;
    $nY      = 0;

    if (isset($_REQUEST["dHeight"]) && intval($_REQUEST["dHeight"]) > 0 && !isset($_REQUEST["dWidth"])) {
    $nX      = ceil((imagesx($im_out) - $nWidth) / 2);
    $nY      = ceil((imagesy($im_out) - $_REQUEST["dHeight"]) / 2);
    $nHeight = $_REQUEST["dHeight"];
    } else if (isset($_REQUEST["dWidth"]) && intval($_REQUEST["dWidth"]) > 0 && !isset($_REQUEST["dHeight"])) {
    $nX      = ceil((imagesx($im_out) - $_REQUEST["dWidth"]) / 2);
    $nY      = ceil((imagesy($im_out) - $nHeight) / 2);
    $nWidth  = $_REQUEST["dWidth"];
    } else {
    $nWidth  = $_REQUEST["dWidth"];
    $nHeight = $_REQUEST["dHeight"];
    $nX      = ceil((imagesx($im_out) - $nWidth) / 2);
    $nY      = ceil((imagesy($im_out) - $nHeight) / 2);
    }

    $img  = imagecreatetruecolor($nWidth, $nHeight);

    imagecopy($img, $im_out, 0, 0, $nX, $nY, $nWidth, $nHeight);
    imagedestroy($im_out);

    $im_out = $img;

}

if(!empty($watermark)) {
    header("Content-type: image/png");
    // водные знаки для уголка скроллера
    if ($watermark == 2) {
        // берем водный знак и открываем

        $values = array(
        2,  28,  // Point 1 (x, y)
        30,  0, // Point 2 (x, y)
        30,  56,  // Point 3 (x, y)
        );

        $blue = imagecolorallocatealpha($im_out
        , hexdec(substr($color, 0, 2))
        , hexdec(substr($color, 2, 2))
        , hexdec(substr($color, 4, 2))
        , 0);
        imagefilledpolygon($im_out, $values, 3, $blue);

        imagepng($im_out,$_SERVER['DOCUMENT_ROOT']."/files_small/$temp_small_img_url_cache", $def_qu);
        imagepng($im_out, '', $def_qu);

    }elseif ($watermark == 5) {
        $watermark_width = 106;
        $watermark_height = 50;

        $p_w = 53 - $w/2;
        $p_h = 25 - $h/2;

        $watermark = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/images/podlog_na.png");
        imagealphablending($watermark, true);
        imagesavealpha($watermark, true);

        imagecopymerge_alpha ($watermark, $im_in, $p_w, $p_h, 0, 0, $watermark_width, $watermark_height, 0);

        imagepng($watermark,$_SERVER['DOCUMENT_ROOT']."/files_small/$temp_small_img_url_cache", $def_qu);
        imagepng($watermark, '', $def_qu);
    }
} else {
    header("Content-type: image/". $extention_to);

    if    ($extention == "jpeg") {
        imagejpeg($im_out, $_SERVER['DOCUMENT_ROOT']."/files_small/".$temp_small_img_url_cache, $def_qu);
        imagejpeg($im_out, '', $def_qu);
    }
    elseif($extention == "gif" ) {imagegif($im_out,$_SERVER['DOCUMENT_ROOT']."/files_small/$temp_small_img_url_cache");imagegif($im_out);}
    elseif($extention == "png" ) {
        imagepng($im_out,$_SERVER['DOCUMENT_ROOT']."/files_small/{$temp_small_img_url_cache}"); imagepng($im_out, '');
    }
    elseif($extention == "bmp" ) {imagebmp($im_out,$_SERVER['DOCUMENT_ROOT']."/files_small/$temp_small_img_url_cache");imagebmp($im_out);}
}

imagedestroy($im_in);
imagedestroy($im_out);
imagedestroy($img);

//функция наложения красивых водных знаков.
function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
    $opacity=$pct;
    // getting the watermark width
    $w = imagesx($src_im);
    // getting the watermark height
    $h = imagesy($src_im);

    // creating a cut resource
    $cut = imagecreatetruecolor($src_w, $src_h);

    // copying that section of the background to the cut
    imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
    // inverting the opacity
    $opacity = 100 - $opacity;

    // placing the watermark now
    imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
    imagecopymerge($dst_im, $cut, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity);
}