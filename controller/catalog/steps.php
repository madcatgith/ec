<?php

$langID = Lang::getID();

$context = array_merge(array(
    'parentID' => array(),
    'categoryIDs' => array(),
    'class' => '',
    'tpl' => 'steps',
    'showInSteps' => false,
    'showInMain' => false
), $context);

if(!empty($context['parentID'])) {
    $context['categoryIDs'] = array_merge(Menu::getChildrenIDs($langID, $context['parentID']), $context['categoryIDs']);
}

if(empty($context['categoryIDs'])) {
    return;
}

$catalogue = new ProductCatalogue($langID);

if($context['showInSteps']) {
    $catalogue->addFilter('p.showInSteps = 1');
} 
if($context['showInMain']) {
    $catalogue->addFilter('p.showInMain = 1');
}

$catalogue
    ->addFilter('p.category_id in ('. implode(', ', $context['categoryIDs']) .')')
    ->setTpl($context['tpl'])
    ->setHtmlClass($context['class'])
    ->displayProducts();


