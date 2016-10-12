<?php

namespace Form;

class FormModel extends \AModel {

    public function getTable() {
        return '?_form';
    }

    public function getTableLang() {
        return '?_form_lang';
    }

    public $_attributes = array('isSend', 'hasCaptcha');
    public $_attributesLang = array('title', 'message');

    public function deleteByID($ID) {
        parent::deleteByID($ID);

        $elementIDs = array_keys(ElementModel::model()->addWhere('formID = '. intval($ID))->findAll());
        ElementModel::model()->deleteByIDs($elementIDs);

        $valuesIDs = ViewModel::model()->addWhere('formID = '. intval($ID))->findAll();
        \Registry::get('db')->exec('delete from '. ViewModel::model()->getTable() .' where formID = '. intval($ID));
        \Registry::get('db')->exec('delete from '. ViewModel::model()->getTableLang() .' where id in (' . implode(', ', array_keys($valuesIDs)) . ')');

        return true;
    }

}
