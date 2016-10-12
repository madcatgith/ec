<?php

interface IImage
{

    public function load($src);

    public function setImage($image);

    public function getImage();

    public function resize($width, $height);

    public function crop($width, $height);

    public function round($radius);

    public function save($desSrc);

    public function fill(array $rgv);
}

class Image implements IImage
{

    protected static $_key  = 'f0-t4';
    protected static $_pack = array(
        'height='    => '/h/=',
        'width='     => '/w/=',
        'src='       => '/s/=',
        'resizible=' => '/re/=',
        'radius'     => '/ra/=',
        '/files/'    => '/f/='
    );

    public static function mEncrypt($string = '')
    {
        return urlencode(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, self::$_key, strtr($string, self::$_pack), MCRYPT_MODE_CBC, md5(self::$_key))));
    }

    public static function mDecrypt($string)
    {
        return strtr(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, self::$_key, base64_decode(urldecode($string)), MCRYPT_MODE_CBC, md5(self::$_key)), "\0"), array_flip(self::$_pack));
    }

    public static function html2rgb($color)
    {

        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        if (strlen($color) == 6) {
            list($r, $g, $b) = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } else if (strlen($color) == 3) {
            list($r, $g, $b) = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return false;
        }

        return array(hexdec($r), hexdec($g), hexdec($b));
    }

    public function setExtension($ext)
    {

        switch ($ext) {
            case 'jpg':
                $this->_extension = 'jpeg';
                break;
            default:
                $this->_extension = $ext;
                break;
        }
        
        return $this;
    }

    protected $_extension = '';
    protected $_quality   = 100;
    protected $_img       = null;

    public function __construct($src = null)
    {
        if ($src) {
            $this->load($src);
        }
    }

    private function destroy()
    {

        if ($this->isImage()) {
            imagedestroy($this->_img);
        }

        return $this;
    }

    public function __destruct()
    {
        
    }

    public function load($src)
    {

        // ждем 5.4 getimagesize($src)[2];
        list(,, $type) = getimagesize($src);

        $this->setExtension(substr(image_type_to_extension($type), 1));

        if (file_exists($src) && function_exists($imagecreatefrom = 'imagecreatefrom' . $this->_extension)) {
            $this->_img = $imagecreatefrom($src);
        }
    }

    public function isImage()
    {
        if (is_resource($this->_img) && get_resource_type($this->_img) === 'gd') {
            return true;
        } else {
            return false;
        }
    }

    public function setImage($image)
    {

        if (is_resource($image) && get_resource_type($image) === 'gd') {
            $this->_img = $image;
        }
        
        return $this;
    }

    public function getImage()
    {
        return $this->_img;
    }

    public function getHeight()
    {
        return imagesy($this->_img);
    }

    public function getWidth()
    {
        return imagesx($this->_img);
    }

    public function getExtension()
    {
        return $this->_extension;
    }

    public function getQuality()
    {
        return $this->_quality;
    }

    public function setQuality($quality)
    {
        $this->_quality = (int) $quality;
    }

    public function setAlpha()
    {
        switch ($this->_extension) {
            case 'png':
            case 'gif':
                imagealphablending($this->_img, false);
                imagesavealpha($this->_img, true);
                break;
        }

        return $this;
    }

    public function resize($width, $height)
    {

        $img = imagecreatetruecolor($width, $height);
        $res = new Image;

        $res->setImage($img);
        $res->setExtension($this->_extension);
        $res->setAlpha();

        imagecopyresampled($img, $this->_img, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());

        $this->destroy()->setImage($res->getImage());

        return $this;
    }

    public function crop($width, $height)
    {

        $img = imagecreatetruecolor($width, $height);
        $res = new Image;

        $res->setImage($img)->setExtension($this->_extension)->setAlpha()->fillAlpha();
        
        imagecopy($img, $this->_img, ceil(($width - $this->getWidth()) / 2), ceil(($height - $this->getHeight()) / 2), 0, 0, $this->getWidth(), $this->getHeight());

        $this->destroy()->setImage($res->getImage());

        return $this;
    }

    public function round($radius)
    {

        $img = imagecreatetruecolor($this->getWidth(), $this->getHeight());
        $res = new Image;

        $res->setImage($img);
        $res->setExtension('png');
        $res->setAlpha();

        imagecopy($img, $this->_img, 0, 0, 0, 0, $this->getWidth(), $this->getHeight());

        $rs_radius = $radius * 3;
        $rs_size   = $rs_radius * 2;

        $corner = imagecreatetruecolor($rs_size, $rs_size);
        imagealphablending($corner, false);

        $trans = imagecolorallocatealpha($corner, 255, 255, 255, 127);
        imagefill($corner, 0, 0, $trans);

        $positions = array(
            array(0, 0, 0, 0),
            array($rs_radius, 0, $res->getWidth() - $radius, 0),
            array($rs_radius, $rs_radius, $res->getWidth() - $radius, $res->getHeight() - $radius),
            array(0, $rs_radius, 0, $res->getHeight() - $radius)
        );

        foreach ($positions as $pos) {
            imagecopyresampled($corner, $img, $pos[0], $pos[1], $pos[2], $pos[3], $rs_radius, $rs_radius, $radius, $radius);
        }

        $lx  = 0;
        $ly  = 0;
        $i   = -$rs_radius;
        $y2  = -$i;
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

        imagedestroy($corner);
        imagedestroy($trans);

        $this->destroy()->setImage($res->getImage());

        return $this;
    }

    public function save($desSrc)
    {
        switch ($this->_extension) {
            case 'jpeg':
                return imagejpeg($this->_img, $desSrc, $this->_quality);
                break;
            case 'gif':
                return imagegif($this->_img, $desSrc);
                break;
            case 'png':
                return imagepng($this->_img, $desSrc);
                break;
            case 'bmp':
                return imagebmp($this->_img, $desSrc);
                break;
            default:
                return false;
                break;
        }
    }

    public function fillAlpha()
    {
        
        imagefill($this->_img, 0, 0, 0x7fff0000);
        
        return $this;
    }
    
    public function fill(array $rgb)
    {

        $img = imagecreatetruecolor($this->getWidth(), $this->getHeight());
        $res = new Image;

        $res->setImage($img);
        $res->setExtension($this->_extension);

        imagefill($img, 0, 0, imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]));
        imagecopyresampled($img, $this->_img, 0, 0, 0, 0, $res->getWidth(), $res->getHeight(), $this->getWidth(), $this->getHeight());

        return $this;
    }

}

interface IImages
{

    public function getWidth();

    public function getHeight();

    public function getMaxHeight();

    public function getMinHeight();

    public function getMaxWidth();

    public function getMinWidth();

    public function combine($direction = 'horizontal');

    public function addImage(Image $image);
}

class Images
{

    private $_storage = array();

    public function getWidth()
    {

        $width = 0;

        foreach ($this->_storage as $image) {
            $width += $image->getWidth();
        }

        return $width;
    }

    public function getHeight()
    {

        $height = 0;

        foreach ($this->_storage as $image) {
            $height += $image->getHeight();
        }

        return $height;
    }

    public function getMaxHeight()
    {

        $max = 0;

        foreach ($this->_storage as $image) {
            $max = max($max, $image->getHeight());
        }

        return $max;
    }

    public function getMinHeight()
    {

        $min = 0;

        foreach ($this->_storage as $image) {
            $min = min($min, $image->getHeight());
        }

        return $min;
    }

    public function getMaxWidth()
    {

        $max = 0;

        foreach ($this->_storage as $image) {
            $max = max($max, $image->getWidth());
        }

        return $max;
    }

    public function getMinWidth()
    {
        $min = 0;

        foreach ($this->_storage as $image) {
            $min = min($min, $image->getWidth());
        }

        return $min;
    }

    public function addImage(Image $image)
    {
        $this->_storage[] = $image;
    }

    public function combine($direction = 'horizontal')
    {
        switch ($direction) {
            case 'horizontal':
                return $this->_combineHorizontal();
                break;
            case 'vertical':
                return $this->_combineVertical();
                break;
            default:
                return new Image;
                break;
        }
    }

    private function _combineHorizontal()
    {

        $img  = imagecreatetruecolor($this->getWidth(), $this->getMaxHeight());
        $res  = new Image;
        $desX = 0;

        $res->setImage($img);
        $res->setExtension('png');
        $res->setAlpha();

        foreach ($this->_storage as $image) {
            imagecopy($img, $image->getImage(), $desX, 0, 0, 0, $image->getWidth(), $image->getHeight());
            $desX += $image->getWidth();
        }

        return $res;
    }

    private function _combineVertical()
    {

        $img  = imagecreatetruecolor($this->getMaxWidth(), $this->getHeight());
        $res  = new Image;
        $desY = 0;

        $res->setImage($img);
        $res->setExtension('png');
        $res->setAlpha();

        foreach ($this->_storage as $image) {
            imagecopy($img, $image->getImage(), 0, $desY, 0, 0, $image->getWidth(), $image->getHeight());
            $desY += $image->getHeight();
        }

        return $res;
    }

}
