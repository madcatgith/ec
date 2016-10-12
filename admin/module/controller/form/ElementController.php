<?php

namespace Form;

class ElementController {

    protected $_types = array(
        'text' => 'Текстовое поле',
        'textarea' => 'Текст',
        'radio' => 'Радио-бокс',
        'checkbox' => 'Чек-бокс',
        'select' => 'Выпадающий список',
        'file' => 'Файл'
    );
    
    protected $_validation = array(
        'telephone' => 'Телефон',
        'email' => 'E-mail',
        'hidden' => 'Скрытое',
        'number' => 'Числовое'
    );

    public function actionIndex() {
        $tpl = new \Template;
        $tpl->assign('formID', \AUrl::get('formID'));
        $tpl->assign('types', $this->_types);
        $tpl->assign('data', ElementModel::model()->addWhere('formID='. \AUrl::get('formID'))->addOrder('sort')->findAll());
        return $tpl->fetch(VIEW . 'index.tpl');
    }

    public function actionInsert() {
        $tpl = new \Template;

        if (filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY)) {
            if (ElementModel::model()->insert(filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY))) {
                $tpl->assign('success', 'Запись успешно добавлена.');
            } else {
                $tpl->assign('error', 'Ошибка при добавлении записи.');
            }
        }

        $tpl->assign('type', \AUrl::get('type'));
        $tpl->assign('types', $this->_types);
        $tpl->assign('validation', $this->_validation);
        $tpl->assign('formID', \AUrl::get('formID'));

        return $tpl->fetch(VIEW . 'insert.tpl');
    }

    public function actionUpdate() {
        $tpl = new \Template;

        if (filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY)) {
            if (ElementModel::model()->update(filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY))) {
                $tpl->assign('success', 'Запись успешно изминена.');
            } else {
                $tpl->assign('error', 'Ошибка при изминении записи.');
            }
        }

        $tpl->assign('formID', \AUrl::get('formID'));
        $tpl->assign('types', $this->_types);
        $tpl->assign('validation', $this->_validation);
        $tpl->assign('data', ElementModel::model()->findOne(\AUrl::get('elementID')));

        return $tpl->fetch(VIEW . 'update.tpl');
    }

}
