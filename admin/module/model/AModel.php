<?php

abstract class AModel {

    protected $_attributes = array();
    protected $_attributesLang = array();
    protected $_escapeFields = array();
    protected $_escapeFieldsLang = array();
    protected $_where = ' where 1 = 1 ';
    protected $_order = '';
    
    public static function model() {
        return new static();
    }

    public function getTable() {
        return null;
    }

    public function getTableLang() {
        return null;
    }

    public function beforeInsert(&$data) {
        return $data;
    }

    public function afterSelectAll(&$data) {
        if(!empty($this->_attributesLang)) {
            foreach($data as &$d) {
                foreach($this->_attributesLang as $field) {
                    $fields = array();
                    foreach (Lang::getLanguages() as $langID => $lang) {
                        if(!empty($d[$langID]) && !empty($d[$langID][$field])) {
                            if(is_array($d[$langID][$field])) {
                                $fields[] = $lang['title_short'] . ': ' . implode(', ', $d[$langID][$field]);
                            } else {
                                $fields[] = $lang['title_short'] . ': ' . $d[$langID][$field];
                            }
                        }
                    }
                    $d[0]['langed_' . $field] = (!empty($fields) ? implode('<br />', $fields) : '');
                }
            }
        }
    }

    public function afterSelectOne(&$data) {
        return $data;
    }
    
    public function addWhere($where) {
        $this->_where .= ' and '. $where;
        return $this;
    }
    
    public function addOrder($order) {
        $this->_order = ' order by '. $order;
        return $this;
    }

    public function insert($_data) {
        
        $db         = Registry::get('db');
        $data       = array();
        $data[0]    = array_fill_keys($this->_attributes, '');

        $this->beforeInsert($_data);

        foreach ($this->_attributes as $attr) {
            if (isset($_data[0][$attr])) {
                $data[0][$attr] = in_array($attr, $this->_escapeFields) ? $_data[0][$attr] : mysql_real_escape_string($_data[0][$attr]);
            }
        }

        if (count($data[0]) && $db->exec('insert into ' . $this->getTable() . ' (' . implode(', ', array_keys($data[0])) . ') values ("' . implode('", "', $data[0]) . '")')) {

            $insertID = $db->lastInsertId();

            if ($this->getTableLang() !== null && intval($insertID) > 0) {
                foreach (Lang::getLanguages() as $langID => $lang) {
                    $data[$langID] = array_fill_keys($this->_attributesLang, '');
                    foreach ($this->_attributesLang as $attr) {
                        if (isset($_data[$langID][$attr])) {
                            $data[$langID][$attr] = in_array($attr, $this->_escapeFieldsLang) ? $_data[$langID][$attr] : mysql_real_escape_string($_data[$langID][$attr]);
                        }
                    }
                    
                    $data[$langID]['id'] = $insertID;
                    $data[$langID]['langID'] = $langID;
                    
                    if (count($data[$langID]) > 2) {
                        $db->exec('insert into ' . $this->getTableLang() . ' (' . implode(', ', array_keys($data[$langID])) . ') values ("' . implode('", "', $data[$langID]) . '")');
                    }
                }
            }
            
            return $insertID;
        } else {
            return false;
        }
    }

    public function update($_data) {
        
        $db             = Registry::get('db');
        $data           = array();
        $data[0]        = array_fill_keys($this->_attributes, '');
        $data[0]['id']  = $_data[0]['id'];

        $this->beforeInsert($_data);

        foreach ($this->_attributes as $attr) {
            if (isset($_data[0][$attr])) {
                $data[0][$attr] = in_array($attr, $this->_escapeFields) ? $_data[0][$attr] : mysql_real_escape_string($_data[0][$attr]);
            }
        }

        if ($db->exec('replace into ' . $this->getTable() . ' (' . implode(', ', array_keys($data[0])) . ') values ("' . implode('", "', $data[0]) . '")')) {
            if ($this->getTableLang() !== null) {
                foreach (Lang::getLanguages() as $langID => $lang) {
                    $data[$langID] = array_fill_keys($this->_attributesLang, '');
                    foreach ($this->_attributesLang as $attr) {
                        if (isset($_data[$langID][$attr])) {
                            $data[$langID][$attr] = in_array($attr, $this->_escapeFieldsLang) ? $_data[$langID][$attr] : mysql_real_escape_string($_data[$langID][$attr]);
                        }
                    }
                    
                    $data[$langID]['id'] = $_data[0]['id'];
                    $data[$langID]['langID'] = $langID;

                    $db->exec('replace into ' . $this->getTableLang() . ' (' . implode(', ', array_keys($data[$langID])) . ') values ("' . implode('", "', $data[$langID]) . '")');
                }
            }
            
            return $_data[0]['id'];
        } else {
            return false;
        }
    }

    public function findAll() {

        $db         = Registry::get('db');
        $data       = array();   

        $query = $db->query('select * from ' . $this->getTable() . $this->_where . $this->_order);
        foreach ($query->fetchAll() as $get) {
            $data[$get['id']][0] = $get;
        }
        
        if ($this->getTableLang() !== null && count($data)) {
            $query = $db->query('select * from ' . $this->getTableLang() . ' where id in (' . implode(',', array_keys($data)) . ')');
            foreach ($query->fetchAll() as $get) {
                $data[$get['id']][$get['langID']] = $get;
            }
        }

        $this->afterSelectAll($data);

        return $data;
    }

    public function findOne($id) {
        
        $db     = Registry::get('db');
        $data   = array();

        $query = $db->query('select * from ' . $this->getTable() . ' where id = ' . intval($id));
        foreach ($query->fetchAll() as $get) {
            $data[0] = $get;
        }

        if ($this->getTableLang() !== null && count($data)) {
            $query = $db->query('select * from ' . $this->getTableLang() . ' where id = ' . intval($id));
            foreach ($query->fetchAll() as $get) {
                $data[$get['langID']] = $get;
            }
        }

        $this->afterSelectOne($data);

        return $data;
    }
    
    public function deleteByID($ID = 0) {
        if(Registry::get('db')->exec('delete from '. $this->getTable() .' where id = '. intval($ID)) == 1) {
            if ($this->getTableLang() !== null) {
                Registry::get('db')->exec('delete from '. $this->getTableLang() .' where id = '. intval($ID));
            }
            return true;
        } else {
            return false;
        }
    }

    public function deleteByIDs($IDs = array()) {        
        if (Registry::get('db')->exec('delete from ' . $this->getTable() . ' where id in (' . implode(',', $IDs) .')') == count($IDs)) {
            if ($this->getTableLang() !== null) {
                Registry::get('db')->exec('delete from ' . $this->getTableLang() . ' where id in (' . implode(',', $IDs) .')');
            }
            return true;
        } else {
            return false;
        }
    }
    
    public function updateSort($data) {
        $db = Registry::get('db');
        foreach ($data as $d) {
            $db->exec('update ' . $this->getTable() . ' set sort = ' . intval($d['place']) . ' where id = ' . intval($d['id']));
        }
    }
    
    public function updateField($id, $field, $value) {
        if (Registry::get('db')->exec('update ' . $this->getTable() . ' set ' . $field . ' = "' . $value . '" where id = ' . intval($id))) {
            return true;
        }
        return false;
    }
    
    public function updateFields($id, $values) {
        $fields = array();
        foreach($values as $field => $value) {
            $fields[] = $field .' = "'. $value .'"';
        }
        
        if(Registry::get('db')->exec('update ' . $this->getTable() . ' set ' . implode(', ', $fields) .' where id = ' . intval($id))) {
            return true;
        }
        return false;
    }

}
