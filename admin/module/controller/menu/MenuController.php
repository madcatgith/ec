<?php

namespace Menu;

class MenuController {
    
    public function actionSelect($isJson = true) {
        $menu   = MenuModel::model()->addWhere('active=1 and cnc!=""')->findAll();
        $data   = array(0 => array('id' => 0, 'title' => 'Главная'));
        $titles = array();
        
        foreach($menu as $m) {
            $titles[$m[0]['id']][$m[0]['lang_id']] = $m[0]['title'];
        }
        
        foreach($menu as $m) {
            $title = '';
            
            if(!empty($titles[$m[0]['id']])) {
                foreach($titles[$m[0]['id']] as $t) {
                    if(strlen($t)) {
                        $title = $t;
                        continue;
                    }
                }
            }
            
            if(!strlen($title)) {
                $title = 'Menu #'. $m[0]['id'];
            }
            
            $data[$m[0]['id']] = array('id' => $m[0]['id'], 'title' => $title);
        }
        
        return $isJson ? json_encode($data) : $data; 
    }    
}
