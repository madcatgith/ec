<?php
if(session_id() == "") session_start();

$captcha = new SimpleCaptcha();

$captcha->CreateImage();

class SimpleCaptcha {

    public $width  = 130;
    public $height = 40;
    public $wordsFile = 'words/en.txt';
    //public $wordsFile = 'words/ru.txt';
    public $minWordLength = 6;
    public $maxWordLength = 8;
    public $backgroundColor = array(255, 255, 255);
    
    public $colors = array(
        // array(27, 78, 181), // blue
        // array(22, 163, 35), // green
        // array(214, 36, 7),  // red
        array(167, 167, 167)  // red
    );

    /** Shadow color in RGB-array or false */
    public $shadowColor = true; //array(0, 0, 0);

    public $fonts = array(
       // 'Antykwa'  => array('spacing' => -3, 'minSize' => 18, 'maxSize' => 18, 'font' => 'AntykwaBold.ttf'),
       // 'Candice'  => array('spacing' =>-1.5,'minSize' => 18, 'maxSize' => 18, 'font' => 'Candice.ttf'),        
       // 'StayPuft' => array('spacing' =>-1.5,'minSize' => 18, 'maxSize' => 18, 'font' => 'StayPuft.ttf'),
       // 'Times'    => array('spacing' => -2, 'minSize' => 18, 'maxSize' => 18, 'font' => 'TimesNewRomanBold.ttf'),
       // 'VeraSans' => array('spacing' => -1, 'minSize' => 18, 'maxSize' => 18, 'font' => 'VeraSansBold.ttf'),
       // 'VeraSans' => array('spacing' => -1, 'minSize' => 18, 'maxSize' => 18, 'font' => 'VeraSansBold.ttf'),
        'VeraSans' => array('spacing' => 1, 'minSize' => 18, 'maxSize' => 18, 'font' => 'ariali.ttf')
    );

    /** Wave configuracion in X and Y axes */
    public $Yperiod    = 12;
    public $Yamplitude = 4;
    public $Xperiod    = 10;
    public $Xamplitude = 6;
    public $maxRotation = 2;
    public $scale = 4;
    public $blur = true;
    public $debug = false;
    public $imageFormat = 'png';
    public $im;

    public function __construct($config = array()) {
    }

    public function CreateImage() {
        $ini = microtime(true);

        /** Initialization */
        $this->ImageAllocate();
        
        /** Text insertion */
        $text = $this->GetCaptchaText();
        $fontcfg  = $this->fonts[array_rand($this->fonts)];
        
        $this->WriteText($text, $fontcfg);

        isset($_GET['name']) ? $_SESSION[$_GET['name']] = $text : $_SESSION['captcha'] = $text;
        
        $_SESSION['error'] = 0;

        /** Transformations */
        $this->WaveImage();
        if ($this->blur) {
            imagefilter($this->im, IMG_FILTER_GAUSSIAN_BLUR);
        }
        $this->ReduceImage();

        if ($this->debug) {
            imagestring($this->im, 1, 1, $this->height-8,
                "$text {$fontcfg['font']} ".round((microtime(true)-$ini)*1000)."ms",
                $this->GdFgColor
            );
        }


        /** Output */
        $this->WriteImage();
        $this->Cleanup();
    }

    protected function ImageAllocate() {
        // Cleanup
        if (!empty($this->im)) {
            imagedestroy($this->im);
        }

        $this->im = imagecreatetruecolor($this->width*$this->scale, $this->height*$this->scale);

        // Background color
        $this->GdBgColor = imagecolorallocate($this->im,
            $this->backgroundColor[0],
            $this->backgroundColor[1],
            $this->backgroundColor[2]
        );
        imagefilledrectangle($this->im, 0, 0, $this->width*$this->scale, $this->height*$this->scale, $this->GdBgColor);

        // Foreground color
        $color           = $this->colors[mt_rand(0, sizeof($this->colors)-1)];
        $this->GdFgColor = imagecolorallocate($this->im, $color[0], $color[1], $color[2]);

        // Shadow color
        if (!empty($this->shadowColor)) {
            $this->GdShadowColor = imagecolorallocate($this->im,
                $this->shadowColor[0],
                $this->shadowColor[1],
                $this->shadowColor[2]
            );
        }
    }

    protected function GetCaptchaText() {
        $text = $this->GetDictionaryCaptchaText();
        
        if (!$text) {
            $text = $this->GetRandomCaptchaText();
        }
        return $text;
    }

    protected function GetRandomCaptchaText($length = null) {
       
        if ( is_null($length) ) {
            $length = rand($this->minWordLength, $this->maxWordLength);
        }
        
        if ($length == 0)
            return "";
        
        $words  = "abcdefghijlmnopqrstvwyz";
        $vocals = "aeiou";

        $text  = "";
        $vocal = rand(0, 1);
        for ($i=0; $i<$length; $i++) {
            if ($vocal) {
                $text .= substr($vocals, mt_rand(0, 4), 1);
            } else {
                $text .= substr($words, mt_rand(0, 22), 1);
            }
            $vocal = !$vocal;
        }
        return $text;
    }

    function GetDictionaryCaptchaText($extended = false) {
        if (empty($this->wordsFile)) {
            return false;
        }

        $fp     = fopen($this->wordsFile, "r");
        $length = strlen(fgets($fp));
        if (!$length) {
            return false;
        }
        $line   = rand(0, (filesize($this->wordsFile)/$length)-1);
        if (fseek($fp, $length*$line) == -1) {
            return false;
        }
        $text = trim(fgets($fp));
        fclose($fp);


        /** Change ramdom volcals */
        if ($extended) {
            $text   = str_split($text, 1);
            $vocals = array('a', 'e', 'i', 'o', 'u');
            foreach ($text as $i => $char) {
                if (mt_rand(0, 1) && in_array($char, $vocals)) {
                    $text[$i] = $vocals[mt_rand(0, 4)];
                }
            }
            $text = implode('', $text);
        }
        
        $length = rand($this->minWordLength, $this->maxWordLength); 
        $cnt = strlen($text);
        if ($cnt < $length) {
            $text = $text.$this->GetRandomCaptchaText($length - $cnt);    
        } else {
            $text = substr($text, 0, $length);    
        }        
                
        return $text;
    }

    protected function WriteText($text, $fontcfg = array()) {
        if (empty($fontcfg)) {
            // Select the font configuration
            $fontcfg  = $this->fonts[array_rand($this->fonts)];
        }
        $fontfile = 'fonts/'.$fontcfg['font'];

        /** Increase font-size for shortest words: 9% for each glyp missing */
        $lettersMissing = $this->maxWordLength-strlen($text);
        $fontSizefactor = 1+($lettersMissing*0.09);

        // Text generation (char by char)
        $x      = 5*$this->scale;//20*$this->scale - 20;
        $y      = round(($this->height*27/40)*$this->scale);
        $length = strlen($text);
        for ($i=0; $i<$length; $i++) {
            $degree   = rand($this->maxRotation*-1, $this->maxRotation);
            $fontsize = rand($fontcfg['minSize'], $fontcfg['maxSize'])*$this->scale*$fontSizefactor;
            $letter   = substr($text, $i, 1);

            if ($this->shadowColor) {
                $coords = imagettftext($this->im, $fontsize, $degree,
                    $x+$this->scale, $y+$this->scale,
                    $this->GdShadowColor, $fontfile, $letter);
            }
            $coords = imagettftext($this->im, $fontsize, $degree,
                $x, $y,
                $this->GdFgColor, $fontfile, $letter);
            $x += ($coords[2]-$x) + ($fontcfg['spacing']*$this->scale);
        }
    }

    protected function WaveImage() {
        // X-axis wave generation
        $xp = $this->scale*$this->Xperiod*rand(1,3);
        $k = rand(0, 100);
        for ($i = 0; $i < ($this->width*$this->scale); $i++) {
            imagecopy($this->im, $this->im,
                $i-1, sin($k+$i/$xp) * ($this->scale*$this->Xamplitude),
                $i, 0, 1, $this->height*$this->scale);
        }

        // Y-axis wave generation
        $k = rand(0, 100);
        $yp = $this->scale*$this->Yperiod*rand(1,2);
        for ($i = 0; $i < ($this->height*$this->scale); $i++) {
            imagecopy($this->im, $this->im,
                sin($k+$i/$yp) * ($this->scale*$this->Yamplitude), $i-1,
                0, $i, $this->width*$this->scale, 1);
        }
    }

    protected function ReduceImage() {
        // Reduzco el tamaсo de la imagen
        $imResampled = imagecreatetruecolor($this->width, $this->height);
        imagecopyresampled($imResampled, $this->im,
            0, 0, 0, 0,
            $this->width, $this->height,
            $this->width*$this->scale, $this->height*$this->scale
        );
        imagedestroy($this->im);
        $this->im = $imResampled;
    }

    protected function WriteImage() {
        if ($this->imageFormat == 'png') {
            header("Content-type: image/png");
            imagepng($this->im);
        } else {
            header("Content-type: image/jpeg");
            imagejpeg($this->im, null, 100);
        }
    }

    protected function Cleanup() {
        imagedestroy($this->im);
    }
}
?>
