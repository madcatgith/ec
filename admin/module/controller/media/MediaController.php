<?php

namespace Media;

class MediaController {
    
    public function actionIndex() {
        
        if(filter_input(INPUT_GET, 'clearCache')) {
            \File::deleteDir(RESIZE_PATH);
            //echo '<meta http-equiv="Refresh" content="0;url=/admin/module/media" />';
        }
        
        if (filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY) && filter_input(INPUT_POST, 'mediaInsert', FILTER_VALIDATE_INT)) {
            MediaModel::model()->insert(filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY));
        }
        
        if (filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY) && filter_input(INPUT_POST, 'mediaUpdate', FILTER_VALIDATE_INT)) {
            MediaModel::model()->update(filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY));
        }
        
        $media = MediaModel::model()->findAll();
        $mediaRoot = array_filter($media, function($item) {
            return $item[0]['parentID'] == 0;
        });
        
        foreach($media as $item) {
            $structure[$item[0]['parentID']][$item[0]['id']] = $item[0]['id'];
        }
        unset($structure[0]);

        $tpl = new \Template;        
        
        $tpl->assign('media', $media);
        $tpl->assign('structure', $structure);
        $tpl->assign('mediaRoot', $mediaRoot);
        
        $tpl->assign('self', $this);
        $tpl->assign('element', new ElementController);
        $tpl->assign('elements', ElementModel::model());
        
        return $tpl->fetch(VIEW . 'index.tpl');
    }
    
    public function actionInsert() {        
        $tpl = new \Template;
        $tpl->assign('options', MediaController::getOptions(1));
        return $tpl->fetch(VIEW . 'insert.tpl');
    }
    
    public function actionUpdate() {        
        $tpl = new \Template;
        $tpl->assign('options', MediaController::getOptions(1));
        return $tpl->fetch(VIEW . 'update.tpl');
    }
    
    public static function getOptions($maxLevel = 0) {
        $options = array();
        
        $media = MediaModel::model()->addOrder('parentID')->findAll();
        foreach($media as $item) {
            $options[$item[0]['parentID']][$item[0]['id']] = $item[1]['title'];
        }        
        
        return  \Helpers::optionsRecursive($options, $maxLevel);
    }
}
