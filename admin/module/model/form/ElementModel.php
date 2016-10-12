<?php

namespace Form;

class ElementModel extends \AModel {

    public $_attributes = array('formID', 'type', 'sort');
    public $_attributesLang = array('isActive', 'isRequired', 'isMulty', 'validation', 'title', 'placeholder', 'value');
    public $_escapeFieldsLang = array('value');    
    
    public function getTable() {
        return '?_form_element';
    }

    public function getTableLang() {
        return '?_form_element_lang';
    }

    public function beforeInsert(&$data) {
        if (in_array($data[0]['type'], array('select', 'radio', 'checkbox'))) {
            foreach (\Lang::getLanguages() as $langID => $lang) {
                $data[$langID]['value'] = addslashes(serialize($data[$langID]['value']));
            }
        }

        return $data;
    }

    public function afterSelectOne(&$data) {
        if (in_array($data[0]['type'], array('select', 'radio', 'checkbox'))) {
            foreach (\Lang::getLanguages() as $langID => $lang) {
                $data[$langID]['value'] = unserialize($data[$langID]['value']);
            }
        }

        return $data;
    }

    public function afterSelectAll(&$data) {
        foreach ($data as &$d) {
            if (in_array($d[0]['type'], array('select', 'radio', 'checkbox'))) {
                foreach (\Lang::getLanguages() as $langID => $lang) {
                    $d[$langID]['value'] = unserialize($d[$langID]['value']);
                }
            }
        }
        parent::afterSelectAll($data);
    }

}
