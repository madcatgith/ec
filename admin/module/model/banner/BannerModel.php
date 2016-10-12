<?php

namespace Banner;

class BannerModel extends \AModel {
    
    public function getTable() {
        return '?_banner';
    }
    
    public function deleteByID($ID = 0) {
        if(parent::deleteByID($ID)) {
            $element = array_keys(ElementModel::model()->addWhere('bannerID='. intval($ID))->findAll());
            ElementModel::model()->deleteByIDs($element);     
            return true;
        }
        return false;
    }
    
    public $_attributes = array('active', 'hasFilter', 'title', 'text');
    
}