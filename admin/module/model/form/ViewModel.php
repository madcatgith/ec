<?php

namespace Form;

class ViewModel extends \AModel {
    
    public function getTable() {
        return '?_form_values';
    }
    
    public function getTableLang() {
        return '?_form_values_element';
    }
    
    public function findAll() {
        $db     = \Registry::get('db');
        $data   = array();

        $query = $db->query('select * from '. $this->getTable() . $this->_where . $this->_order);
        foreach ($query->fetchAll() as $get) {
            $data[$get['id']] = $get;
        }
        
        if(count($data)) {
            $query = $db->query('select * from '. $this->getTableLang() .' where id in (' . implode(', ', array_keys($data)) . ')');
            foreach ($query->fetchAll() as $get) {
                if(mb_strpos($get['value'], '/files/form/') !== false) {
                    $get['value'] = '<a target="_blank" href="'. $get['value'] .'">'. $get['value'] .'</a>';
                }
                
                $data[$get['id']]['fields'][] = $get;
            }
        }

        return $data;        
    }
    
}

