<?

include $_SERVER['DOCUMENT_ROOT'] .'/config.php';

if(!$_REQUEST['action']) {
	die();
}

switch($_REQUEST['action']) {
	case 'sort':
		foreach ($_REQUEST['ids'] as $i) {
            $res[] = (int)DB::Query('update ?_'. $_REQUEST['bases'] .' set '. $_REQUEST['sortField'] .'=' . intval($i['place']) . ' where id=' . intval($i['id']));
		}
		
		$res = array_filter($res, function($value) {
			return (bool) ($value != 1);
		});
		
		if(!count($res)) {
			successRequest();
		} else {
			failureRequest();
		}
	break;
}