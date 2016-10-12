<?
// Modified by Shkodenko V. Taras 08/12/2006

if(empty($imgurl)) {
  exit;
}

include '../config.php';

$temp_small_img_url = $imgurl;

if(empty($temp_small_img_url)) {
  header("Content-type: image/gif");
  include '../p.gif';
  exit;
}

$extention = strtolower(@array_pop(@explode(".", $temp_small_img_url)));
$name = $_SERVER['DOCUMENT_ROOT'] . str_replace(_BASE_URL, "", $temp_small_img_url);

header("Content-type: image/jpeg");

if($extention == "jpeg" or $extention == "jpg") $im_in = imagecreatefromjpeg($name);
elseif($extention == "gif") $im_in = imagecreatefromgif($name);
elseif($extention == "png") $im_in = imagecreatefrompng($name);
elseif($extention == "bmp") $im_in = imagecreatefromwbmp($name);

$da = getimagesize($name);

//$w = $pre_product_banner_width;
//$h = (int) (($w/$da[0])*$da[1]);
$w = 30;
$h = 30;

$im_out = imagecreate($w,$h);
//$im_out = imagecreatetruecolor($w,$h);

imagecopyresampled($im_out,$im_in,0,0,0,0,$w,$h,$da[0],$da[1]);
imagejpeg($im_out);
imagedestroy($im_in);
imagedestroy($im_out);

?>