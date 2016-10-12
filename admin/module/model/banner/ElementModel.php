<?php

namespace Banner;

class ElementModel extends \AModel {
    
    public function getTable() {
        return '?_banner_element';
    }
    
    public function getTableLang() {
        return '?_banner_element_lang';
    }
    
    public function beforeInsert(&$data) {
        foreach (\Lang::getLanguages() as $langID => $lang) {
            $data[$langID]['text'] = htmlentities($data[$langID]['text'], ENT_QUOTES || ENT_HTML401, "UTF-8");
        }
    }
    
    public function afterSelectOne(&$data) {
        foreach (\Lang::getLanguages() as $langID => $lang) {
            $data[$langID]['text'] = html_entity_decode($data[$langID]['text'], ENT_QUOTES || ENT_HTML401, "UTF-8");
        }
    }
    
    public function findOne($id) {
        $data   = array();
        $filter = array();
        
        if(count($data = parent::findOne($id))) {
            $query = \Registry::get('db')->query('select * from ?_banner_filter where elementID = '. intval($id));
            foreach ($query->fetchAll() as $row) {
                $filter[$row['type'] .'Type'][$row['menuID']][] = $row['valueID'];
            }
            $data[0]['filter'] = $filter;
        }
        
        return $data;
    }
    
    public function findAll() {
        $data = array();
        
        if(count($data = parent::findAll())) 
        {
            $filter = array();
            $result = array();
            
            $menu = new \Menu\MenuController;
            $menuData = $menu->actionSelect(false);
            
            $content = new \Content\ContentController;
            $contentData = $content->actionSelect(false);
            
            $iblockData = \IBlock\IBlockModel::model()->findInMenu(false);
            
            $query = \Registry::get('db')->query('select * from ?_banner_filter');
            foreach($query->fetchAll() as $row) {
                $filter[$row['elementID']][$row['type'] . 'Type'][$row['menuID']][] = $row['valueID'];
            }
            
            foreach($filter as $fKey => $fValue) {
                foreach($fValue as $type => $menuIDs) {
                    switch($type) {
                        case 'menuType':
                            $dataMenuIDs = array_keys($menuIDs);
                            foreach($dataMenuIDs as $menuID) {
                                $result[$fKey][$type][] = $menuData[$menuID]['title'];
                            }
                            $result[$fKey][$type] = implode(', ', $result[$fKey][$type]);
                            break;
                        case 'iblockType':
                            foreach($menuIDs as $menuID => $iblockIDs) {
                                $resultIBlock = array();
                                
                                foreach($iblockIDs as $iblockID) {
                                    $resultIBlock[] = $iblockData[$menuID][$iblockID]['title'];
                                }
                                
                                $result[$fKey][$type][] = array(
                                    'title' => $menuData[$menuID]['title'],
                                    'values' => implode(', ', $resultIBlock)
                                );
                            }
                            break;
                        case 'contentType':
                            foreach($menuIDs as $menuID => $contentIDs) {
                                $resultContent = array();
                                
                                foreach($contentIDs as $contentID) {
                                    $resultContent[] = $contentData[$menuID][$contentID]['title'];
                                }
                                
                                $result[$fKey][$type][] = array(
                                    'title' => $menuData[$menuID]['title'],
                                    'values' => implode(', ', $resultContent)
                                );
                            }
                            break;
                    }
                }
            }
            
            foreach($result as $elID => $elValues) {
                if(isset($data[$elID])) {
                    $data[$elID][0]['filter'] = $elValues;
                }
            }
        }
        
        return $data;
    }

    public function insert($_data) {
        if(intval($id = parent::insert($_data))) {
            $model = BannerModel::model()->findOne($_data[0]['bannerID']);
            if($model[0]['hasFilter'] && isset($_data[0]['filter'])) {
                $this->saveFilters($id, $_data[0]['filter']);
            }
            return $id;
        } else {
            return false;
        }
    }
    
    public function update($_data) {
        if(intval($id = parent::update($_data))) {
            $model = BannerModel::model()->findOne($_data[0]['bannerID']);
            if($model[0]['hasFilter'] && isset($_data[0]['filter'])) {
                $this->saveFilters($id, $_data[0]['filter']);
            }
            return $id;
        } else {
            return false;
        }
    }
    
    public function saveFilters($elementID, $data) {
        $menu       = new \Menu\MenuController;
        $content    = new \Content\ContentController;
        
        $menuData       = $menu->actionSelect(false);
        $contentData    = $content->actionSelect(false);
        $iblockData     = \IBlock\IBlockModel::model()->findInMenu(false);
        
        $sql = array();
        $checkData = array();
        
        foreach($data as $fKey => $fValue) {
            if(!$fValue['type'] || !isset($fValue['menu']) || !count($fValue['menu'])) {
                continue;
            }
            
            if($fValue['type'] === 'menuType') {
                foreach($fValue['menu'] as $mValue) {
                    if(isset($menuData[$mValue]) && !isset($checkData['menu'][$mValue])) {
                        $sql[] = '('. $elementID .', "menu", '. $mValue .', 0)';
                        $checkData['menu'][$mValue] = true;
                    }
                }
            } else {
                $type = str_replace('Type', '', $fValue['type']);
                
                switch($fValue['type']) {
                    case 'contentType':
                        $dataType = $contentData;
                        break;
                    case 'iblockType':
                        $dataType = $iblockData;
                        break;
                }
                
                $sqlArray = array();

                foreach($fValue[$type] as $tValue) {
                    foreach($dataType as $cKey => $cData) {
                        if(isset($cData[$tValue])) {
                            $sqlArray[$cKey][] = $tValue;
                        }
                    }
                }
                
                foreach ($sqlArray as $menuID => $valueIDs) {
                    foreach ($valueIDs as $valueID) {
                        if(strpos($valueID, '/all') !== false) {
                            $sqlArray[$menuID] = array();
                            $sqlArray[$menuID][] = $valueID;
                            
                            continue;
                        }
                    }
                }
                
                foreach ($sqlArray as $menuID => $valueIDs) {
                    foreach ($valueIDs as $valueID) {
                        if(!isset($checkData[$type][$menuID][$valueID])) {
                            $sql[] = '(' . $elementID . ', "' . $type . '", ' . $menuID . ', "' . $valueID . '")';
                            $checkData[$type][$menuID][$valueID] = true;                            
                        }
                    }
                }
            }
        }

        $this->deleteFiltersByID($elementID);
        
        if(count($sql)) {        
            \Registry::get('db')->exec('insert into ?_banner_filter (elementID, type, menuID, valueID) values '. implode(', ', $sql));
        }
    }
    
    public function deleteFiltersByID($elementID) {
        \Registry::get('db')->exec('delete from ?_banner_filter where elementID = '. intval($elementID));
    }
    
    public function deleteFiltersByIDs($IDs = array()) {
        \Registry::get('db')->exec('delete from ?_banner_filter where elementID in ('. implode(', ', $IDs) .')');
    }
    
    public function deleteByID($ID = 0) {
        if(parent::deleteByID($ID)) {
            $this->deleteFiltersByID($ID);
            return true;
        }
        return false;
    }
    
    public function deleteByIDs($IDs = array()) {
        if(parent::deleteByIDs($IDs)) {
            $this->deleteFiltersByIDs($IDs);
            return true;
        }
        return false;
    }

    public $_attributes = array('bannerID', 'image', 'sort');
    public $_attributesLang = array('active', 'title', 'text', 'href');
    public $_escapeFieldsLang = array('text');
} 
