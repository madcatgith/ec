<?php

namespace Form;

class ViewController {

    public function actionIndex() {
        $tpl = new \Template;
        
        $tpl->assign('records', ViewModel::model()->addWhere('formID='. \AUrl::get('formID'))->findAll());
        $tpl->assign('formID', \AUrl::get('formID'));
        
        return $tpl->fetch(VIEW . 'index.tpl');
    }

}
