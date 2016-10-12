<?php

namespace Media;

class ElementModel extends \AModel {
    
    public function getTable() {
        return '?_media_element';
    }
    
    public function getTableLang() {
        return '?_media_element_lang';
    }
    
    public function afterSelectAll(&$data) {
        foreach($data as &$item) {
            $item[0]['src'] = MEDIA . $item[0]['subdir'] . '/' . $item[0]['source'];
        }
        parent::afterSelectAll($data);
    }
    
    public function deleteByIDs($IDs = array()) {
        $models = ElementModel::model()->addWhere('id in ('. implode(', ', $IDs) .')')->findAll();
        foreach($models as $model) {
            \File::deleteDir(MEDIA_PATH . $model[0]['subdir']);
            \File::deleteCache($model[0]['id']);
        }        
        parent::deleteByIDs($IDs);
        return true;
    }
    
    public $_attributes = array('sort', 'mediaID', 'source', 'subdir', 'type', 'width', 'isImage', 'height');
    public $_attributesLang = array('title', 'text');
}
