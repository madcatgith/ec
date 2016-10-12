<?php
exit;
$include = include($_SERVER['DOCUMENT_ROOT'] . '/admin/admin_top.php');

if (!$include || $adm_wellcome != 'Y') {
    exit;
}

$data = $db->query('select * from wmp_product')->fetchAll();
$update = $db->prepare('update wmp_product set strength = :strength, price_for = :price_for, price_for_digit = :price_for_digit, multy = :multy where id = :id and lang_id = :lang_id');

foreach ($data as $item) {
    
    $multy = array();
    foreach (range(1, 7) as $row) {
        $multy[] = array($item['field_name' . $row], $item['field_value' . $row]);
    }
    
    $update->execute(array(
        'id' => $item['id'],
        'lang_id' => $item['lang_id'],
        'strength' => $item['field_value8'],
        'price_for' => $item['field_name8'],
        'price_for_digit' => $item['field_name9'],
        'multy' => (!empty($multy) ? addslashes(serialize($multy)) : '')
    ));

}

echo 'import ok!';
