<?
function prepear($value, $type = ''){
	switch($type){
		case 'img':
			$value = trim($value);
			$value = ($value == 'http://')?'':$value;
			break;
		case 'input':
			$value = htmlspecialchars(stripslashes(trim($value)), ENT_QUOTES);
			break;
		case 'text':
			$value = str_replace(array('&gt;', '&lt;', '&quot;'),array('&t_gt;', '&t_lt;', '&t_quot;'),$value);
			$value = htmlspecialchars(stripslashes(trim($value)), ENT_QUOTES);
			break;
		default:
			$value = htmlspecialchars(stripslashes(trim($value)), ENT_QUOTES);
			break;
	}
	return $value;
}
function reprepear($value, $type = ''){
	switch($type){
		case 'img':
			$value = ($value)?$value:'http://';
			break;
		case 'input':
			$value = $value;
			break;
		case 'text':
			$value = htmlspecialchars_decode($value, ENT_QUOTES);
			$value = str_replace(array('&t_gt;', '&t_lt;', '&t_quot;'), array('&gt;', '&lt;', '&quot;'),$value);			
			break;
		default:
			$value = $value;
			break;
	}
	return $value;
}