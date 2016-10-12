<?php

namespace Media;

class ElementController {
    
    public function actionIndex($mediaID) {
        $tpl = new \Template;
        $tpl->assign('mediaID', $mediaID);
        $tpl->assign('elements', ElementModel::model()->addOrder('sort')->addWhere('mediaID='. $mediaID)->findAll());
        return $tpl->fetch(APP .'/view/media/element/index.tpl');
    }
    
    public function actionInsert() {
        $error = '';
        if (filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY) && filter_input(INPUT_POST, 'elementInsert', FILTER_VALIDATE_INT)) {
            $data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
            if(!empty($_FILES['mediaSource'])) {
                $file = $_FILES['mediaSource'];
                if(intval($file['error'])) {
                    $error = \File::getUploadError($file['error']);
                } elseif(\File::checkExtension(pathinfo($file['name'], PATHINFO_EXTENSION)) === false) {
                    $error = 'Извините, тип этого файла не разрешён для загрузки.';
                } else {
                    if(intval($id = ElementModel::model()->insert($data)) > 0) {
                        $uploadFile = \File::getFilePath($file['name']);
                        if (move_uploaded_file($file['tmp_name'], MEDIA_PATH . $uploadFile['subdir'] . '/' . $uploadFile['name'])) {
                            ElementModel::model()->updateFields($id, array(
                                'type' => $file['type'],
                                'subdir' => $uploadFile['subdir'],
                                'source' => $uploadFile['name']
                            ));   
                            if(strpos($file['type'], 'image/') !== false && is_array($sizes = getimagesize(MEDIA_PATH . $uploadFile['subdir'] . '/' . $uploadFile['name']))) {                               
                                ElementModel::model()->updateFields($id, array(
                                    'width' => $sizes[0],
                                    'height' => $sizes[1],
                                    'isImage' => 1
                                ));
                            } 
                        } else {
                            ElementModel::model()->deleteByID($id);
                            \File::deleteDir(MEDIA_PATH . $uploadFile['subdir'] . '/');
                            $error = 'Файл не загружен. Ошибка перемещения файла.';
                        }   
                    } else {
                        $error = 'Ошибка записи в базу данных.';
                    }
                }
            } else {
                $error = 'Не выбран файл.';
            }
        }
        
        $tpl = new \Template;
        $tpl->assign('error', $error);
        $tpl->assign('options', MediaController::getOptions());
        return $tpl->fetch(APP .'/view/media/element/insert.tpl');
    }
    
    public function actionUpdate() {
        if (filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY) && filter_input(INPUT_POST, 'elementUpdate', FILTER_VALIDATE_INT)) {
            $data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
            
            if(ElementModel::model()->update($data)) {
                \File::writeCache($data);
            }
        }
        
        $tpl = new \Template;
        $tpl->assign('options', MediaController::getOptions());
        return $tpl->fetch(APP .'/view/media/element/update.tpl');
    }
    
}
