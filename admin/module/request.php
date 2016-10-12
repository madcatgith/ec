<?php

$no_top = true;
$no_html = true;
$include = include getenv('DOCUMENT_ROOT') . '/admin/admin_top.php';

header("Content-Type: text/html; charset=utf-8");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!$include or $adm_wellcome != "Y") {
    failureRequest();
} else {

    AUrl::parse(filter_input(INPUT_POST, 'uri'));
    
    define('URL', '/admin/module/'. AUrl::get('module'));
    define('VIEW', APP . '/view/' . AUrl::get('module') . '/' . AUrl::get('controller') . '/');

    switch (filter_input(INPUT_GET, 'fn')) {
        case 'form/delete':
            if (\Form\FormModel::model()->deleteByID(filter_input(INPUT_POST, 'id'))) {
                successRequest();
            } else {
                failureRequest();
            }
            break;
        case 'form/element/delete':
            if (\Form\ElementModel::model()->deleteByID(filter_input(INPUT_POST, 'id'))) {
                successRequest();
            } else {
                failureRequest();
            }
            break;
        case 'form/view/delete':
            if (\Form\ViewModel::model()->deleteByID(filter_input(INPUT_POST, 'id'))) {
                successRequest();
            } else {
                failureRequest();
            }            
            break;
        case 'form/element/sort':
            \Form\ElementModel::model()->updateSort(filter_input(INPUT_POST, 'ids', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY));
            successRequest();
            break;
        case 'banner/delete':
            if (\Banner\BannerModel::model()->deleteByID(filter_input(INPUT_POST, 'id'))) {
                successRequest();
            } else {
                failureRequest();
            }
            break;
        case 'banner/element/delete':
            if (\Banner\ElementModel::model()->deleteByID(filter_input(INPUT_POST, 'id'))) {
                successRequest();
            } else {
                failureRequest();
            }
            break;
        case 'banner/element/sort':
            \Banner\ElementModel::model()->updateSort(filter_input(INPUT_POST, 'ids', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY));
            successRequest();
            break;
        case 'media/delete':
            if (\Media\MediaModel::model()->deleteByID(filter_input(INPUT_POST, 'id'))) {
                successRequest();
            } else {
                failureRequest();
            }            
            break;
        case 'media/element/delete':
            if(\Media\ElementModel::model()->deleteByIDs(filter_input(INPUT_POST, 'ids', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY))) {
                successRequest();
            } else {
                failureRequest();
            }
            break;
        case 'media/element/sort':
            \Media\ElementModel::model()->updateSort(filter_input(INPUT_POST, 'ids', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY));
            successRequest();
            break;
        default:
            failureRequest();
            break;
    }
}