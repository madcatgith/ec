<?php

namespace Media;

class MediaModel extends \AModel {
    
    public function getTable() {
        return '?_media';
    }
    
    public function getTableLang() {
        return '?_media_lang';
    }
    
    public function deleteByID($ID = 0) {
        $children = \Registry::get('db')->query('select id from '. $this->getTable() .' where parentID = '. intval($ID))->fetchAll();       
        $elementsIDs = array_keys(ElementModel::model()->addWhere('mediaID = '. intval($ID))->findAll());
        
        if(parent::deleteByID($ID) && !empty($elementsIDs)) {
            ElementModel::model()->deleteByIDs($elementsIDs);
            foreach($children as $child) {
                MediaModel::model()->deleteByID($child['id']);
            }
        }
        return true;
    }
    
    public $_attributes = array('sort', 'parentID');
    public $_attributesLang = array('title', 'text');
}
