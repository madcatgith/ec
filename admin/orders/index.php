<?php

$include = include ($_SERVER['DOCUMENT_ROOT'] . "/admin/admin_top.php");

if (!$include or $adm_wellcome != "Y") {
    exit;
}

include dirname(__FILE__) . '/lib/Orders.php';
include dirname(__FILE__) . '/helpers/tables.php';

$tpl    = new Template;
$orders = new Orders;
$page   = isset($_REQUEST['page']) ? $_REQUEST['page'] - 1 : 0;

if (isset($_REQUEST['onPage']) && intval($_REQUEST['onPage'])) {
    $orders->setOnPage($_REQUEST['onPage']);
} else {
    $orders->setOnPage(20);
}

$orders->setOffset($page * $orders->getOnPage());

if (isset($_REQUEST['sord'])) {
    $orders->setSord($_REQUEST['sord']);
}

if (isset($_REQUEST['sort'])) {
    $orders->setSort($_REQUEST['sort']);
}

if (isset($_REQUEST['filters'])) {
    foreach (array_filter($_REQUEST['filters']) as $key => $value) {
        if (is_array($value)) {
            if ($value['value']) {
                $orders->addFilter($key, $value['value'], $value['type']);
            }
        } else {
            if ($value) {
                $orders->addFilter($key, $value);
            }
        }
    }
}

$tpl->assign('paymentMethods', array(
    0 => 'Наличными'
));

$tpl->assign('paymentStatus', array(
    0 => 'Не оплачено',
    1 => 'Оплачено'
));

$tpl->assign('deliveryMethods', array(
    1 => 'Самовывоз',
    2 => 'Курьер',
    3 => 'Новая почта'
));

$tpl->assign('deliveryStatus', array(
    0 => 'Новый заказ',
    1 => 'Подтвержден',
    2 => 'Доставляется / Вывозится',
    3 => 'Выполнен'
));

$tpl->assign('page', $page);
$tpl->assign('orders', $orders);
$tpl->display(dirname(__FILE__) . '/view/list.tpl');