<?php

$categoryID = 64;
$langID = Lang::getID();
$images = array();
$categories = array();

$query = DB::Query('select * from ?_gallery_category where parent = '. $categoryID .' order by title');
while($get = DB::GetRow($query)) {
    $categories[$get['id']] = $get['title'];
}

if(count($categories)) {
    
    $query  = DB::Query("select 
        gl.*, g.*
        from ?_gallery g
            left join ?_gallery_lang gl on gl.id=g.id and gl.lang_id='{$langID}' 
        where 
        g.category_id in (". implode(', ', array_filter(array_keys($categories))) .") and 
        g.active = 1
        order by g.place
    ");
            
    while ($row = DB::GetArray($query)) {
        $images[$row['category_id']][] = array(
            'src'   => $row['href'],
            'title' => $row['title'],
            'type'  => $row['type'],
            'data'  => $row['data'] ? unserialize($row['data']) : ''
        );
    }
    
    $tpl = new Template();
    $tpl->assign('categories', $categories);
    $tpl->assign('images', $images);
    
    $tpl->display(BASEPATH .'lib/wmpGallery/getGalleries.tpl');
    
}

