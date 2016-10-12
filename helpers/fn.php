<?php

if (!function_exists('http_build_url')) {
    define('HTTP_URL_REPLACE', 1);          // Replace every part of the first URL when there's one of the second URL
    define('HTTP_URL_JOIN_PATH', 2);        // Join relative paths
    define('HTTP_URL_JOIN_QUERY', 4);       // Join query strings
    define('HTTP_URL_STRIP_USER', 8);       // Strip any user authentication information
    define('HTTP_URL_STRIP_PASS', 16);      // Strip any password authentication information
    define('HTTP_URL_STRIP_AUTH', 32);      // Strip any authentication information
    define('HTTP_URL_STRIP_PORT', 64);      // Strip explicit port numbers
    define('HTTP_URL_STRIP_PATH', 128);     // Strip complete path
    define('HTTP_URL_STRIP_QUERY', 256);    // Strip query string
    define('HTTP_URL_STRIP_FRAGMENT', 512); // Strip any fragments (#identifier)
    define('HTTP_URL_STRIP_ALL', 1024);     // Strip anything but scheme and host
    // Build an URL
    // The parts of the second URL will be merged into the first according to the flags argument. 
    // 
    // @param  mixed      (Part(s) of) an URL in form of a string or associative array like parse_url() returns
    // @param  mixed      Same as the first argument
    // @param  int        A bitmask of binary or'ed HTTP_URL constants (Optional)HTTP_URL_REPLACE is the default
    // @param  array      If set, it will be filled with the parts of the composed url like parse_url() would return 

    function http_build_url($url, $parts = array(), $flags = HTTP_URL_REPLACE, &$new_url = false)
    {
        $keys = array('user', 'pass', 'port', 'path', 'query', 'fragment');

        // HTTP_URL_STRIP_ALL becomes all the HTTP_URL_STRIP_Xs
        if ($flags & HTTP_URL_STRIP_ALL) {
            $flags |= HTTP_URL_STRIP_USER;
            $flags |= HTTP_URL_STRIP_PASS;
            $flags |= HTTP_URL_STRIP_PORT;
            $flags |= HTTP_URL_STRIP_PATH;
            $flags |= HTTP_URL_STRIP_QUERY;
            $flags |= HTTP_URL_STRIP_FRAGMENT;
        }
        // HTTP_URL_STRIP_AUTH becomes HTTP_URL_STRIP_USER and HTTP_URL_STRIP_PASS
        else if ($flags & HTTP_URL_STRIP_AUTH) {
            $flags |= HTTP_URL_STRIP_USER;
            $flags |= HTTP_URL_STRIP_PASS;
        }

        // Parse the original URL
        $parse_url = parse_url($url);

        // Scheme and Host are always replaced
        if (isset($parts['scheme']))
            $parse_url['scheme'] = $parts['scheme'];
        if (isset($parts['host']))
            $parse_url['host']   = $parts['host'];

        // (If applicable) Replace the original URL with it's new parts
        if ($flags & HTTP_URL_REPLACE) {
            foreach ($keys as $key) {
                if (isset($parts[$key]))
                    $parse_url[$key] = $parts[$key];
            }
        }
        else {
            // Join the original URL path with the new path
            if (isset($parts['path']) && ($flags & HTTP_URL_JOIN_PATH)) {
                if (isset($parse_url['path']))
                    $parse_url['path'] = rtrim(str_replace(basename($parse_url['path']), '', $parse_url['path']), '/') . '/' . ltrim($parts['path'], '/');
                else
                    $parse_url['path'] = $parts['path'];
            }

            // Join the original query string with the new query string
            if (isset($parts['query']) && ($flags & HTTP_URL_JOIN_QUERY)) {
                if (isset($parse_url['query']))
                    $parse_url['query'] .= '&' . $parts['query'];
                else
                    $parse_url['query'] = $parts['query'];
            }
        }

        // Strips all the applicable sections of the URL
        // Note: Scheme and Host are never stripped
        foreach ($keys as $key) {
            if ($flags & (int) constant('HTTP_URL_STRIP_' . strtoupper($key)))
                unset($parse_url[$key]);
        }


        $new_url = $parse_url;

        return
                ((isset($parse_url['scheme'])) ? $parse_url['scheme'] . '://' : '')
                . ((isset($parse_url['user'])) ? $parse_url['user'] . ((isset($parse_url['pass'])) ? ':' . $parse_url['pass'] : '') . '@' : '')
                . ((isset($parse_url['host'])) ? $parse_url['host'] : '')
                . ((isset($parse_url['port'])) ? ':' . $parse_url['port'] : '')
                . ((isset($parse_url['path'])) ? $parse_url['path'] : '')
                . ((isset($parse_url['query'])) ? '?' . $parse_url['query'] : '')
                . ((isset($parse_url['fragment'])) ? '#' . $parse_url['fragment'] : '')
        ;
    }

}

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
} 

function failureRequest(array $array = array())
{
    echo json_encode(count($array) > 0 ? array_merge(array('action' => false), $array) : array('action' => false));
}

function successRequest(array $array = array())
{
    echo json_encode(count($array) > 0 ? array_merge(array('action' => true), $array) : array('action' => true));
}

function isEmail($address)
{
    return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? false : true;
}

if (get_magic_quotes_gpc()) {

    // очистка POST GET
    function clearVal($val)
    {
        return stripslashes($val);
    }

} else {

    // очистка POST GET
    function clearVal($val)
    {
        return $val;
    }

}

function array_remove_by_key($key, $array)
{
    if (isset($array[$key])) {
        unset($array[$key]);
    }

    return $array;
}

function _d($array = array())
{
    echo '<pre>', print_r($array, 1), '</pre>';
}

function _js($data = array())
{
    echo '<script>console.log(', json_encode($data), ')</script>';
}

function declension($n, $cases)
{
    return $n % 10 == 1 && $n % 100 != 11 ? $cases[0] : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $cases[1] : $cases[2]);
}

function validateDate($date)
{
    $date = explode('/', $date);

    if (count($date) != 3)
        return false;

    return checkdate($date[1], $date[0], $date[2]);
}

function getSortPrice($data)
{
    $query = array();
    $uri   = parse_url('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

    if (isset($uri['query'])) {
        parse_str($uri['query'], $query);
    }

    if (filter_input(INPUT_GET, 'sort')) {
        $sort = filter_input(INPUT_GET, 'sort');
    } else {
        $sort = null;
    }

    if (filter_input(INPUT_GET, 'sord')) {
        $sord = filter_input(INPUT_GET, 'sord');
    } else {
        $sord = 'desc';
    }

    $link   = '';
    $return = '<ul>';

    foreach ($data as $key => $value) {
        if (is_null($sort) && $key == 0) {
            $link = '<span>' . $value[2] . '</span>';
        } else if ($sort == $value[0] && $sord == $value[1]) {
            $link = '<span>' . $value[2] . '</span>';
        } else {
            $query['sort'] = $value[0];
            $query['sord'] = $value[1];
            $return .= '<li><a href="' . http_build_url($uri['path'], array(
                        'query' => http_build_query($query)
                    )) . '">' . $value[2] . '</a></li>';
        }
    }

    return ('<div>' . $link . $return . '</ul></div>');
}

function file_force_download($file)
{
    if (file_exists($file)) {

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));

        readfile($file);
    } else {
        echo 'File does not exist!';
    }
}

function mb_wordwrap($str, $width = 75, $break = "\n", $cut = false)
{
    $lines = explode($break, $str);
    foreach ($lines as &$line) {
        $line   = rtrim($line);
        if (mb_strlen($line) <= $width)
            continue;
        $words  = explode(' ', $line);
        $line   = '';
        $actual = '';
        foreach ($words as $word) {
            if (mb_strlen($actual . $word) <= $width)
                $actual .= $word . ' ';
            else {
                if ($actual != '')
                    $line .= rtrim($actual) . $break;
                $actual = $word;
                if ($cut) {
                    while (mb_strlen($actual) > $width) {
                        $line .= mb_substr($actual, 0, $width) . $break;
                        $actual = mb_substr($actual, $width);
                    }
                }
                $actual .= ' ';
            }
        }
        $line .= trim($actual);
    }
    return implode($break, $lines);
}

function transliterate($str)
{
    $letters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
        'і' => 'i', 'І' => 'I', 'ї' => 'yi', 'Ї' => 'YI', 'є' => 'ye', 'Є' => 'YE',
        "А" => "A", "Б" => "B", "В" => "V", "Г" => "G",
        "Д" => "D", "Е" => "E", "Ж" => "J", "З" => "Z", "И" => "I",
        "Й" => "Y", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N",
        "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T",
        "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "TS", "Ч" => "CH",
        "Ш" => "SH", "Щ" => "SCH", "Ъ" => "", "Ы" => "YI", "Ь" => "",
        "Э" => "E", "Ю" => "YU", "Я" => "YA", "а" => "a", "б" => "b",
        "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "j",
        "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
        "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
        "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
        "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y",
        "ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya", " " => "_", 
        "/" => "/", "-" => "-"
    );
    
    $str = strtr($str, $letters);
    
    for($i = 0; $i < strlen($str); $i++) {
        if(!in_array($str[$i], $letters)) {
            $str[$i] = '';
        }        
    }
    
    return $str;
}

function formatBytes($bytes, $precision = 2) 
{ 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 

function htmlSizes($src) {
    $file = getenv('DOCUMENT_ROOT') . $src;
    
    if(!file_exists($file)) {
        return null;
    }

    $sizes = getimagesize($file);
    return $sizes[3];
}

function htmlSizesSmall($solt, $isSolt = true) {
    $baseSrc = filesSmallGenerate($solt, $isSolt);
    
    if($baseSrc) {
        return htmlSizes($baseSrc);
    }
    
    return null;
}

function htmlImageTag($value, $alt, $isSmall = true) 
{
	/*
	 * false - указывать только массивом.
	*/
	$src = $isSmall ? filesSmallGenerate($value, false) : $value;
	
	return '<img src="'. $src .'" '. htmlSizes($src) .' alt="'. htmlspecialchars($alt) .'" title="'. htmlspecialchars($alt) .'">';
}

function filesSmallGenerate($solt, $isSolt = true) 
{
    $blank = '/blank.gif';
    $par   = array();
	
	if($isSolt) {
    	$query = Image::mDecrypt($solt);
	   	parse_str($query, $par);
	} else {
		$par = $solt;
		unset($solt['src']);
		$query = http_build_query($solt);
	}

    if (empty($par['src']) || !file_exists(getenv('DOCUMENT_ROOT') . $par['src'])) {
        return $blank;
    } else {

        $image = new Image(getenv('DOCUMENT_ROOT') . $par['src']);
       
        $baseSrc = '/files_small/' . md5_file(getenv('DOCUMENT_ROOT') . $par['src']) . '_' . preg_replace('([^\w_]+)', '_', $query) . '.' . ( isset($par['ext']) ? $par['ext'] : $image->getExtension());
        $desSrc  = getenv('DOCUMENT_ROOT') . $baseSrc;

        if (file_exists($desSrc)) {
            return $baseSrc;
        } else {
            if (!$image->isImage()) {
                return $blank;
            } else {
                $par = array_merge(array(
                    'fn'        => '',
                    'width'     => 0,
                    'height'    => 0,
                    'maxWidth'  => 0,
                    'maxHeight' => 0
                        ), $par);

                if ($par['maxWidth'] || $par['maxHeight']) {
                    if ($par['maxWidth']) {

                        if(!$par['height']) {
                            $height = $image->getHeight();
                            $par['height'] = $height;
                        } else {
                            $height = $par['height'];
                        }
                        
                        $width  = floor($height * $image->getWidth() / $image->getHeight());
                        
                        if ($width > $par['maxWidth']) {
                            $width  = $par['maxWidth'];
                            $height = floor($width * $image->getHeight() / $image->getWidth());
                        }

                        $image = $image->resize($width, $height)->crop($width, $height);
                    }
                } else {
                    if ($par['width'] > 0 && $par['height'] > 0) {

                        $width  = $par['width'];
                        $height = floor($width * $image->getHeight() / $image->getWidth());

                        if ($height < $par['height']) {
                            $height = $par['height'];
                            $width  = floor($height * $image->getWidth() / $image->getHeight());
                        }

                        $image = $image->resize($width, $height)->crop($par['width'], $par['height']);
                    } else if ($par['width'] > 0) {
                        $image = $image->resize($par['width'], floor($par['width'] * $image->getHeight() / $image->getWidth()));
                    } else if ($par['height'] > 0) {
                        $image = $image->resize(floor($par['height'] * $image->getWidth() / $image->getHeight()), $par['height']);
                    }
                }

                if (isset($par['fill'])) {
                    $image = $image->fill(Image::html2rgb($par['fill']));
                }

                if (isset($par['ext'])) {
                    $image->setExtension($par['ext']);
                }
                
                if (isset($par['radius'])) {
                    $image->round($par['radius']);
                }
                
                $image->setAlpha();

                if (isset($par['quality'])) {
                    $image->setQuality($par['quality']);
                }

                if ($image->save($desSrc)) {
                    //$image->destroy();
                    return $baseSrc;
                } else {
                    return $blank;
                }
            }
        }
        
        unset($image);
    }
}

function getForeachByDigit($digit, $count = 5) {
    
    $data = array();
    
    for($i = 0; $i < $count; $i++) {
        $data[] = ($i + 1) * $digit;
    }

    return $data;
}

/*
 * Функции по закрытию уязвимости в формах и request-ах
 */
function wmp_sessid() {
    if (!is_array($_SESSION) || !isset($_SESSION['fixed_session_id'])) {
        wmp_sessid_set();
    }
    return $_SESSION["fixed_session_id"];
}

function wmp_sessid_set($val = false) {
    if ($val === false) {
        $val = wmp_sessid_val();
    }
    $_SESSION["fixed_session_id"] = $val;
}

function wmp_sessid_val() {
    if(!Registry::get('server_uniq_id')) {
        Registry::set('server_uniq_id', 0912199222032012); // md5(uniqid(rand(), true));
    }
    return md5(Registry::get('server_uniq_id') . session_id());
}

function check_wmp_sessid($varname = 'sessid') {
    return (bool) filter_input(INPUT_POST, $varname, FILTER_SANITIZE_STRING) == wmp_sessid();
}

function wmp_sessid_input($varname = 'sessid') {
    return '<input type="hidden" name="'.$varname.'" id="'.$varname.'" value="'.wmp_sessid().'" />';
}

function getFacebookUsersCount($id) {
    
    $curl = new Curl;
    $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
    $curl->get('http://graph.facebook.com/' . $id);
    
    if(!$curl->error) {
        $number = $curl->response->likes;
        
        return '<b>'. $number .'</b> '. declensionOfVerbs($number, explode(',', Dictionary::GetUniqueWord(83)));
    }
    
    return false;
}

function getVkUsersCount($id) {

    $curl = new Curl;
    $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
    $curl->get('http://api.vk.com/method/groups.getById?gid='. $id .'&fields=members_count');
    
    if(!$curl->error) {
        $number = $curl->response->response[0]->members_count;      
        
        return '<b>'. $number .'</b> '. declensionOfVerbs($number, explode(',', Dictionary::GetUniqueWord(83)));
    }
    
    return false;
}

function getCountryInfo() {
	
    $curl = new Curl;
    $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
    $curl->get('http://api.hostip.info/get_json.php');
    
    if(!$curl->error) {
        return $curl->response->country_code;
    }	
	
    return false;
}
