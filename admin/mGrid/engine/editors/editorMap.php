<?php


$no_html = 1;
$include = include($_SERVER['DOCUMENT_ROOT'] . "/admin/admin_top.php");

if ( ! $include or $adm_wellcome != "Y") 
	exit;

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

$gMap = new GoogleMaps();
$data = array();

$gMap->setZoom(6);
$gMap->setAddress('Украина');

$data['marker'] = new GoogleMapsMarker;

if (isset($_REQUEST['coords']) && strlen($_REQUEST['coords'])) {
	
	$coords = explode(',', $_REQUEST['coords']);

	$data['marker']->setLat($coords[0]);
	$data['marker']->setLng($coords[1]);	

} else {
    $address = urldecode($_REQUEST['address']);
    $data['marker']->setAddress($address);
}

echo $gMap->show($data, 'admin');