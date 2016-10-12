<?php

# подключаем конфиг
include getenv('DOCUMENT_ROOT') . '/config.php';
include BASEPATH . 'admin/admin_modules.php';

$wmpAdmin = new Admin($_COOKIE);
$wmpAdmin->LogIn();

if (!$wmpAdmin->isLogedIn()) {
    header('Location: /admin/');
}

# пытаемся сжать контент
$buffer = Buffer::getInstance();
$buffer->obStart();

header("Content-Type: text/html; charset=utf-8");

Url::parseUrl('/');
Config::setArray((array) DB::GetRow(DB::Query("SELECT * FROM `?_config` WHERE id=0 and lang_id=" . Lang::getID())));
Config::prepareValue('above_menu', 'text');

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

switch (filter_input(INPUT_GET, 'fn')) {
    case 'generate/uri':

        include dirname(__FILE__) . '/lib/GoogleTranslate.php';

        //$gt     = new GoogleTranslate('ua');
        //$string = $gt->prepare('en')->execute(filter_input(INPUT_POST, 'string'));
		$string = transliterate(filter_input(INPUT_POST, 'string'));
        if (mb_strlen($string)) {
            successRequest(array(
                'string' => $string
            ));
        } else {
            failureRequest(array(
                'string' => $string
            ));
        }
        break;
    case 'map/generate':
        
        $code = '';
        $http = 'http://'. getenv('HTTP_HOST');
        $escapeMenuIDs = array();
        $iblockHasAlias = IBlocks::getTablesHasAlias();
        
        foreach(Lang::getLanguages() as $lang) 
        {
            $menu = array();
            $content = array();
            $menuIDs = array();
            
            Menu::load($lang['id']);
            
            $menu = Menu::buildTree($lang['id'], 0);      

            foreach ($menu as $m) 
            {
                if(in_array($m['id'], $escapeMenuIDs)) {
                    continue;
                }
                
                foreach ($m['child'] as $m1) 
                {
                    $code .= "<url>\n";
                    $code .= "\t<loc>". $http . $m1['href'] ."</loc>\n";
                    $code .= "\t<priority>0.7</priority>\n";
                    $code .= "</url>\n";	
                    
                    foreach ($m1['child'] as $m2) 
                    {
                        $code .= "<url>\n";
                        $code .= "\t<loc>". $http . $m2['href'] ."</loc>\n";
                        $code .= "\t<priority>0.7</priority>\n";
                        $code .= "</url>\n";					
                    }
                }
            }            
            
            $contentQuery = Registry::get('db')->query('select id, menu_id, cnc, text from `?_content` where lang_id = '. $lang['id'] .' and active = 1');
            foreach ($contentQuery->fetchAll() as $get) 
            {
                $menuIDs[$get['menu_id']][] = $get['id'];
                
                $content[$get['id']] = array(
                    'menu_id' => $get['menu_id'],
                    'text' => $get['text'],
                    'cnc' => $get['cnc'],
                    'href' => $http . Url::setUrl(array('lang' => $lang['id'], 'menu' => $get['menu_id'], 'content' => $get['cnc']))
                );
            }
            
            foreach ($content as $c) {
                if(strlen($c['cnc']) && intval(Menu::get($lang['id'], $c['menu_id'], 'contentCount')) > 1) {
                    $code .= "<url>\n";
                    $code .= "\t<loc>". $c['href'] ."</loc>\n";
                    $code .= "\t<priority>0.7</priority>\n";
                    $code .= "</url>\n";
                }
            }  
            
            foreach ($menuIDs as $menuID => $arMenu) 
            {
                if(count($arMenu) === 1) 
                {
					$iblockFind = Registry::get('db')->query("select cb.iblock from ?_content_to_iblock cb where cb.lang_id = {$lang['id']} and cb.content_id in (select * from (select GROUP_CONCAT(c.id SEPARATOR ',') from ?_content c where c.lang_id = {$lang['id']} and c.menu_id = {$menuID}) t)")->fetchAll();
	
					if(!empty($iblockFind))
					{
		                foreach($iblockFind as $iblockRow) 
		                {
		                	$iblock = $iblockRow['iblock'];
		                    $iblockObj = new IBlock($iblock, $lang['id']);
	
	                        $data = $iblockObj->setMenuID($menuID)->getList();
	
	                        foreach($data as $item) {
	                            $code .= "<url>\n";
	                            $code .= "\t<loc>". $http . $item->get('href') ."</loc>\n";
	                            $code .= "\t<priority>0.7</priority>\n";
	                            $code .= "</url>\n";                                
	                        }
	
	                        unset($iblockObj);
	                    }
                    }
                }
            }
            
            /*$product = new ProductCatalogue($lang['id']);
            foreach($product->getProducts() as $p)
            {
                $code .= "<url>\n";
                $code .= "\t<loc>". $http . $p->getUrl() ."</loc>\n";
                $code .= "\t<priority>0.7</priority>\n";
                $code .= "</url>\n";                 
            }*/
        }
        
        $file = BASEPATH .'sitemap.xml';

        $fope = fopen($file, "w");
		
		if($fope === false) {
			die(failureRequest());
		}
		
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:image=\"http://www.google.com/schemas/sitemap-image/1.1\">\n";

        fwrite($fope, $xml);	
        fwrite($fope, $code);
        fwrite($fope, "</urlset>");
        fclose($fope);

        successRequest();     
        
        break;
    default:
        failureRequest();
        break;
}

$buffer->obEndFlush();
