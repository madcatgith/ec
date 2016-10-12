<?php

class IBlockElement {
    
    protected $_storage = array();
    
    public function get($key) {
        return $this->has($key) ? $this->_storage[$key] : null;
    }
    
    public function has($key) {
        return (bool) isset($this->_storage[$key]) && !empty($this->_storage[$key]) ? 1 : 0;
    }
    
    public function set($key, $value, $type = 1) {
        switch($type) {
            case 1: 
                $this->_storage[$key] = $value;
                break;
            case 2:
                $this->_storage[$key][] = $value;
        }
    }
    
    public function __construct($data, IBlock $iblock) {
        
        $this->_storage = array_merge($data, $this->_storage);
        
        $this->_storage['iblock']   = $iblock->getIBlockID();   

        if(isset($data['cnc'])) {
            $this->_storage['cnc']      = $data['cnc'];
            $this->_storage['href']     = Url::setUrl(array(
                'lang' => $data['lang_id'], 
                'menu' => $iblock->getMenuID(),
                'iblock' => $data['cnc']
            ));           
        }
    }
}
