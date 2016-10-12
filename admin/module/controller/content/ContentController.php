<?php

namespace Content;

class ContentController {
    
    public function actionSelect($isJson = true) {
        $model  = ContentModel::model()->addWhere('active=1 and lang_id=1');
        $data   = array();
                
        foreach($model->findAll() as $c) {
            $data[$c[0]['menu_id']][$c[0]['id']] = array('id' => $c[0]['id'], 'title' => $c[0]['title']);
        }
        
        return $isJson ? json_encode($data) : $data;        
    }
}
