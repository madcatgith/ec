<?php
/*
 * Удаление корзин старше 8 дней 
*/

include getenv('DOCUMENT_ROOT') . '/config.php';

set_time_limit(0);
ini_set('memory_limit', '128M');

$db = Registry::get('db');

$carts = $db->query('select id from ?_order_cart where countdown < now() - interval 8 day and now() and orderID = 0')->fetchAll();
$itemDelete = $db->prepare('delete from ?_order_cart_item where cartID = :cartID');
$cartDelete = $db->prepare('delete from ?_order_cart where id = :ID');

foreach ($carts as $cart) 
{
    $itemDelete->bindValue(':cartID', $cart['id'], PDO::PARAM_INT);
    $itemDelete->execute();
    
    $cartDelete->bindValue(':ID', $cart['id'], PDO::PARAM_INT);
    $cartDelete->execute();
}



