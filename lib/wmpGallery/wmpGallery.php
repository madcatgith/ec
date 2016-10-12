<?php

class Gallery
{

    public static function displayGallery($galleryID, $tplID = 1)
    {
        echo self::getGallery($galleryID, array(), $tplID);
    }
	
    public static function getContentGallery($galleryID, $tplID = 1, $context = array())
    {
        return self::getGallery($galleryID, array(), $tplID, $context);
    }	

    public static function groupByType($images)
    {
        $types = array(0 => array(), 1 => array());
        
        foreach ($images as $image) {
            $types[$image['type']][] = $image;
        }
        
        return $types;
    }

    public static function getGallery($galleryID, $images = array(), $tplID = 1, $context = array())
    {
        $sqlLimit = '';

        if (isset($context['limit'])) {
            $sqlLimit = ' limit 0, ' . $context['limit'];
        }

        $tpl = new Template;
        $langID = Lang::getID();
        $galleryImages = Registry::get('db')->query("select 
        		gl.*, 
                g.* 
        	from ?_gallery g
        		left join ?_gallery_lang gl on gl.id=g.id and gl.lang_id='{$langID}' 
        	where 
        		g.category_id='{$galleryID}' 
                    and 
                g.active=1
        	order by place
			". $sqlLimit ."
        ")->fetchAll();

        foreach($galleryImages as $row) {
            $images[] = array(
                'src'   => $row['href'],
                'title' => $row['title'],
                'type'  => $row['type'],
                'data'  => $row['data'] ? unserialize($row['data']) : ''
            );
		}
		
		if(!count($images)) {
			return;
		}

        $tpl->assign('id', $galleryID);
        $tpl->assign('images', $images);

        return $tpl->fetch(dirname(__FILE__) . '/getGallery_' . $tplID . '.tpl');
    }
    
    public static function getVideoOne($href, array $context, $tplID = 1)
    {
        $def = new DefaultArrayObject($context);

        $tpl = new Template;
        $tpl->assign('width', $def->get('width', 420));
        $tpl->assign('height', $def->get('height', 315));
        $tpl->assign('href', $href);
        
        return $tpl->fetch(__DIR__ .'/'. __FUNCTION__ .'_'. $tplID .'.tpl');
    }

}
