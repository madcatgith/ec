<?php

namespace Banner;

class ElementController {
    
    protected $types = array(
        'menuType' => 'Меню',
        'contentType' => 'Контент',
        'productType' => 'Каталог',
        'iblockType' => 'Инфоблок'
    );

    public function actionIndex() {
        $tpl = new \Template;
        $tpl->assign('types', $this->types);
        $tpl->assign('bannerID', \AUrl::get('bannerID'));
        $tpl->assign('banner', BannerModel::model()->findOne(\AUrl::get('bannerID')));
        $tpl->assign('data', ElementModel::model()->addWhere('bannerID='. \AUrl::get('bannerID'))->addOrder('sort')->findAll());
        return $tpl->fetch(VIEW . 'index.tpl');
    }
    
    public function actionInsert() {
        $tpl = new \Template;

        if (filter_input(INPUT_POST, 'data', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY)) {
            if (ElementModel::model()->insert(filter_input(INPUT_POST, 'data', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY))) {
                $tpl->assign('success', 'Запись успешно добавлена.');
            } else {
                $tpl->assign('error', 'Ошибка при добавлении записи.');
            }
        }
        
        $tpl->assign('types', $this->types);
        $tpl->assign('banner', BannerModel::model()->findOne(\AUrl::get('bannerID')));
        $tpl->assign('menu', new \Menu\MenuController);
        $tpl->assign('content', new \Content\ContentController);
        $tpl->assign('iblock', \IBlock\IBlockModel::model());
        
        return $tpl->fetch(VIEW . 'insert.tpl');        
    } 
    
    public function actionUpdate() {
        $tpl = new \Template;

        if (filter_input(INPUT_POST, 'data', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY)) {
            if (ElementModel::model()->update(filter_input(INPUT_POST, 'data', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY))) {
                $tpl->assign('success', 'Запись успешно изминена.');
            } else {
                $tpl->assign('error', 'Ошибка при изминении записи.');
            }
        }
        
        $tpl->assign('types', $this->types);        
        $tpl->assign('data', ElementModel::model()->findOne(\AUrl::get('elementID')));
        $tpl->assign('banner', BannerModel::model()->findOne(\AUrl::get('bannerID')));
        $tpl->assign('menu', new \Menu\MenuController);
        $tpl->assign('content', new \Content\ContentController);
        $tpl->assign('iblock', \IBlock\IBlockModel::model());
        
        return $tpl->fetch(VIEW . 'update.tpl');        
    }
} 
