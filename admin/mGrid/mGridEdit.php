<?php
header("Content-type: text/html; charset=utf-8");

define("_PATH", $_SERVER['DOCUMENT_ROOT']);

include _PATH."/config.php";
include _PATH."/admin/prepear.php";
include _PATH."/admin/mGrid/mGridHelper.php";

include _PATH."/admin/mGrid/mGridTable{$_POST['table']}.php";

$success = array();
//error_reporting(E_ALL);
switch($_POST['oper']){   

    case "genFileBill":
        $mask = $_POST['mask'];       
       
        // Р Р…РЎС“Р В¶Р Р…Р С• Р С—РЎР‚Р С•Р С‘Р В·Р Р†Р ВµРЎРѓРЎвЂљР С‘ Р С—Р ВµРЎР‚Р ВµР С–Р ВµР Р…Р ВµРЎР‚Р С‘РЎР‚Р С•Р Р†Р В°Р Р…Р С‘Р Вµ Р Р†РЎРѓР ВµРЎвЂ¦ РЎвЂћР В°Р в„–Р В»Р С•Р Р† Р С‘Р С?Р С—Р С•РЎР‚РЎвЂљР В°
        include BASEPATH."lib/wmpDataConnector.php";
        $connData = new DataConnector();  
                
        $val = $connData -> genTransfferXMLFile($mask);
        echo json_encode($val);
        break;
 
    case "updateGenFilesBill":
        $mask = $_POST['mask'];       
        $val = array();        
        
        $dir = opendir(BASEPATH."admin/import/res");
        while (($file = readdir($dir)) !== false) {
            if (strpos($file, $mask) === 0 ){
                $dt = date ("F d Y H:i:s.", filemtime(BASEPATH."admin/import/res/".$file));
                
                $val[] = array('name' => "<div><a target='_blank' href=\"/admin/import/res/{$file}\">{$file} </a></div>", 'date' => $dt);
            }
        }                
                
        echo json_encode($val);
        break;    
    
	case "updatePlayBill":
		$city = intval($_POST['city']);
				
		$datefrom = ($_POST['datefrom']) ? date($_POST['datefrom']) : "";
//		$dateto = ($_POST['dateto']) ? date($_POST['dateto']) : "";
		
		$event_query = "SELECT 
							etm.position as position
						FROM 
							?_event_to_main as etm
						WHERE 1=1 ";
		
		if ($city) 
			$event_query .= " AND etm.city_id='$city'";

		if ($datefrom) 
			$event_query .= " AND				
					    	  '$datefrom' BETWEEN etm.date_from AND etm.date_to ";
  			
		$event_query .= " GROUP BY etm.position ";
		$event_query = DB::Query($event_query);		

		$val = array();		
		
		while($get = DB::GetArray($event_query)){	
			$val[] = $get['position'];
		}		
		
		echo json_encode($val);
		break;
	case "edit":
		$id     = (int)$_POST['id'];
				
		if ($from["islanged"]){			
			//РЎРѓРЎвЂљР В°Р Р…Р Т‘Р В°РЎР‚Р Р…РЎвЂљРЎвЂ№Р в„– id-lang_id РЎРѓРЎвЂ¦Р ВµР С?Р В° Р С—РЎР‚Р С•РЎРѓРЎвЂљР В°РЎРЏ.
			foreach($_POST['data'] as $lang_id => $fields){
				if ($lang_id == 0)
					continue;				
				$action = array();
				foreach($from['multylang_field'] as $key => $value){
					if ($value['notsave'] || $value['colType'] == 'lbl')
						continue;					
					if ($value['multy']){
						//Р В·Р Т‘Р ВµРЎРѓРЎРЉ Р С‘Р Т‘Р ВµРЎвЂљ Р В·Р В°РЎвЂЎР С‘РЎРѓРЎвЂљР С”Р В° Р С‘ РЎРѓР С”Р В»Р В°Р Т‘РЎвЂ№Р Р†Р В°Р Р…Р С‘Р Вµ Р Р…Р С•Р Р†РЎвЂ№РЎвЂ¦ Р В·Р Р…Р В°РЎвЂЎР ВµР Р…Р С‘Р в„– 
						DB::Query("DELETE FROM ?_{$value['link_table']} WHERE {$from['table']}='{$id}' AND lang_id='{$lang_id}'");
						
						$items = explode(",", $fields[$key]);
						$sqlitems = array();
						foreach ($items as $arg) {
							if ($arg > 0)
								$sqlitems[] = "({$id}, {$arg}, {$lang_id})";
						}
						$str = implode(",",$sqlitems);
						
						DB::Query(
						"INSERT INTO ?_{$value['link_table']}  ({$from['table']}, {$key}, lang_id) 
						 VALUES {$str}" );	
					} elseif (!isset($value['colChild'])){
						$tValue     = $fields[$key];//prepear($fields[$key], $value['colType']);
						$tmValue    = $tValue;
						$action[]   = ($tmValue) ? "$key='{$tmValue}'" : "$key='{$tValue}'";
					}
				}
				//Р ВµРЎРѓР В»Р С‘ Р Р…РЎС“Р В¶Р Р…Р С• РЎС“РЎвЂљР С•РЎвЂЎР Р…Р С‘РЎвЂљРЎРЉ Р С”Р В°Р С”Р С•Р в„– РЎРѓР В»Р С•Р Р†Р В°РЎР‚РЎРЉ
				if (isset($_POST['data']['OwnerCode']))
					$success[$lang_id] = (DB::Query("UPDATE ?_{$from['table']} SET ".implode(",", $action)." 
													  WHERE id='{$id}' AND owner_code='{$_POST['data']['OwnerCode']}' AND lang_id='{$lang_id}'")) 
												? 1: 0;
				else		
					$success[$lang_id] = (DB::Query("UPDATE ?_{$from['table']} SET ".implode(",", $action)." 
													  WHERE id='{$id}' AND lang_id='{$lang_id}'"))
											 	? 1: 0;	
			}
		} else {	
			foreach($_POST['data'] as $lang_id => $fields){
				$action = array();
				// Р Р…РЎС“Р В¶Р Р…Р С• Р Т‘Р В»РЎРЏ Р С?Р Р…Р С•Р В¶Р ВµРЎРѓРЎвЂљР Р†Р ВµР Р…Р Р…РЎвЂ№РЎвЂ¦ Р Р†РЎвЂ№Р В±Р С•РЎР‚ Р С•Р С—РЎР‚Р ВµР Т‘Р ВµР В»Р С‘РЎвЂљРЎРЉ РЎРѓР С—Р ВµРЎвЂ  Р С•Р В±РЎР‚Р В°Р В±Р С•РЎвЂљРЎвЂЎР С‘Р С”
				if ($lang_id != 0){
					foreach($from['multylang_field'] as $key => $value){
						if ($value['notsave'] || $value['colType'] == 'lbl')
							continue;					
						if ($value['multy']){
							//Р В·Р Т‘Р ВµРЎРѓРЎРЉ Р С‘Р Т‘Р ВµРЎвЂљ Р В·Р В°РЎвЂЎР С‘РЎРѓРЎвЂљР С”Р В° Р С‘ РЎРѓР С”Р В»Р В°Р Т‘РЎвЂ№Р Р†Р В°Р Р…Р С‘Р Вµ Р Р…Р С•Р Р†РЎвЂ№РЎвЂ¦ Р В·Р Р…Р В°РЎвЂЎР ВµР Р…Р С‘Р в„– 
							DB::Query("DELETE FROM ?_{$value['link_table']} WHERE {$from['table']}='{$id}' AND lang_id='{$lang_id}'");
							
							$items = explode(",", $fields[$key]);
							$sqlitems = array();
							foreach ($items as $arg) {
								if ($arg > 0)
									$sqlitems[] = "({$id}, {$arg}, {$lang_id})";
							}
							$str = implode(",",$sqlitems);
							
							DB::Query(
							"INSERT INTO ?_{$value['link_table']}  ({$from['table']}, {$key}, lang_id) 
							 VALUES {$str}" );	
						} elseif (!isset($value['colChild'])){												
							$tValue     = $fields[$key];//prepear($fields[$key], $value['colType']);
							$tmValue    = $tValue;
							$action[]   = ($tmValue) ? "$key='{$tmValue}'" : "$key='{$tValue}'";
						}
					}
				//Р ВµРЎРѓР В»Р С‘ Р Р…РЎС“Р В¶Р Р…Р С• РЎС“РЎвЂљР С•РЎвЂЎР Р…Р С‘РЎвЂљРЎРЉ Р С”Р В°Р С”Р С•Р в„– РЎРѓР В»Р С•Р Р†Р В°РЎР‚РЎРЉ
				if (isset($_POST['data']['OwnerCode']))
					$success[$lang_id] = (DB::Query("UPDATE ?_{$from['table']}_lang SET ".implode(",", $action)." 
													  WHERE id='{$id}' AND owner_code='{$_POST['data']['OwnerCode']}' AND lang_id='{$lang_id}'")) 
												? 1: 0;
				else		
					$success[$lang_id] = (DB::Query("UPDATE ?_{$from['table']}_lang SET ".implode(",", $action)." 
													  WHERE id='{$id}' AND lang_id='{$lang_id}'"))
											 	? 1: 0;	
				} else {
 					foreach($from['nonlang_field'] as $key => $value){
						if ($value['notsave'] || $value['colType'] == 'lbl')
							continue;					
						if ($value['multy']){	
							
							//Р В·Р Т‘Р ВµРЎРѓРЎРЉ Р С‘Р Т‘Р ВµРЎвЂљ Р В·Р В°РЎвЂЎР С‘РЎРѓРЎвЂљР С”Р В° Р С‘ РЎРѓР С”Р В»Р В°Р Т‘РЎвЂ№Р Р†Р В°Р Р…Р С‘Р Вµ Р Р…Р С•Р Р†РЎвЂ№РЎвЂ¦ Р В·Р Р…Р В°РЎвЂЎР ВµР Р…Р С‘Р в„– 
							DB::Query("DELETE FROM ?_{$value['link_table']} 
                                            WHERE {$from['table']}='{$id}'");
						
							$items = explode(",", $fields[$key]);
							$sqlitems = array();
							foreach ($items as $arg) {
								if ($arg > 0)
									$sqlitems[] = "({$arg}, {$id})";
							}
							$str = implode(",",$sqlitems);
							
							DB::Query(
							"INSERT INTO ?_{$value['link_table']}  
                                ({$key}, {$from['table']}) 
							 VALUES {$str}"	 );									 
						} elseif (!isset($value['colChild'])){
							$tValue     = $fields[$key];//prepear($fields[$key], $value['colType']);
							$tmValue    = $tValue;
							$action[]   = ($tmValue) ? "$key='{$tmValue}'" : "$key='{$tValue}'";
						}
					}

				//Р ВµРЎРѓР В»Р С‘ Р Р…РЎС“Р В¶Р Р…Р С• РЎС“РЎвЂљР С•РЎвЂЎР Р…Р С‘РЎвЂљРЎРЉ Р С”Р В°Р С”Р С•Р в„– РЎРѓР В»Р С•Р Р†Р В°РЎР‚РЎРЉ
				if (isset($_POST['data']['OwnerCode']))
					$success[$lang_id] = (DB::Query("UPDATE ?_{$from['table']} SET ".implode(",", $action)." 
													  WHERE id='{$id}' AND owner_code='{$_POST['data']['OwnerCode']}' ")) 
												? 1: 0;
				else		
					$success[$lang_id] = (DB::Query("UPDATE ?_{$from['table']} SET ".implode(",", $action)." 
													  WHERE id='{$id}'"))
											 	? 1: 0;
				}
			}		
		}
		$lang_id = ($from['lang'] && !$from['nonlang'])? 
                        $from['lang'] : 0;
                        
		echo json_encode(array("flag" => "{$success[$lang_id]}", "id" => "{$id}"));
		break;			
		
		/*
		$lang_id = ($from['lang'])? $from['lang'] : 0;
		echo json_encode(array("flag" => "{$success[$lang_id]}"));		
		foreach($_POST['data'] as $lang_id => $fields){
			//error_reporting(E_ALL);			
			$whlang = ($lang_id == 0) ? "" : "AND lang_id='{$lang_id}'";
			$check  = array_shift(DB::GetRow(DB::Query("SELECT id FROM ?_{$from['table']} WHERE id='{$id}' {$whlang} LIMIT 1")));
			
			//echo "SELECT id FROM ?_{$from['table']} WHERE id='{$id}' {$whlang} LIMIT 1";
			//die();
			if($check){
				$action = array();
				foreach($normal_field as $key => $value){
					if (!isset($value['colChild']) && $value['colType'] != 'lbl' && !$value['notsave']){
						$tValue     = prepear($fields[$key], $value['colType']);
						$tmValue    = $tValue;
						$action[]   = ($tmValue) ? "$key='{$tmValue}'" : "$key='{$tValue}'";
					}
				}
				$success[$lang_id] = DB::Query("UPDATE ?_{$from['table']} SET ".implode(",", $action)." WHERE id='{$id}' {$whlang}");
				//echo "UPDATE ?_{$from['table']} SET ".implode(",", $action)." WHERE id='{$id}' AND lang_id='{$lang_id}'";
			}else{
				if ($lang_id != 0){
					$field  = array('id', 'lang_id');
					$values = array($id, $lang_id);
				} else {
					$field  = array('id');
					$values = array($id);
				}
				foreach($normal_field as $key => $value){	
					if (!isset($value['colChild']) && $value['colType'] != 'lbl' && !$value['notsave']){
						$tValue     = prepear($fields[$key], $value['colType']);
						$tmValue    = $tValue;
						$field[]    = $key;
						$values[]   = ($tmValue) ? "'{$tmValue}'" : "'{$tValue}'";
					}
				}
				$success[$lang_id] = DB::Query("INSERT INTO ?_{$from['table']}  (".implode(",", $field).") VALUES (".implode(",", $values).")");
			}
		}
		$lang_id = ($from['lang'])? $from['lang'] : 0;
		echo json_encode(array("flag" => "{$success[$lang_id]}"));	
		break;
		*/
	case "add":
		
		//error_reporting(E_ALL);
		$id = array_shift(DB::GetRow(DB::Query("SELECT max(id) FROM ?_{$from['table']}"))) + 1;
		if ($from["islanged"]){
			//РЎРѓРЎвЂљР В°Р Р…Р Т‘Р В°РЎР‚Р Р…РЎвЂљРЎвЂ№Р в„– id-lang_id РЎРѓРЎвЂ¦Р ВµР С?Р В° Р С—РЎР‚Р С•РЎРѓРЎвЂљР В°РЎРЏ.
			foreach($_POST['data'] as $lang_id => $fields){
				if ($lang_id != 0){
					$field  = array('id', 'lang_id');
					$values = array($id, $lang_id);
				} else {
					$field  = array('id');
					$values = array($id);
				}
				
				foreach($from['multylang_field'] as $key => $value){
					if ($value['notsave'] || $value['colType'] == 'lbl')
						continue;					
					if ($value['multy']){
						DB::Query(
						"INSERT INTO ?_{$value['link_table']}  
							({$key}, {$from['table']}) 
						 VALUES ({$fields[$key]}, {$id})"
						 );						
					} elseif (!isset($value['colChild'])){
						$tValue     = $fields[$key];// prepear($fields[$key], $value['colType']);
						$tmValue    = $tValue;
						$field[]    = $key;
						$values[]   = ($tmValue) ? "'{$tmValue}'" : "'{$tValue}'";
					}
				}

				//Р ВµРЎРѓР В»Р С‘ Р Р…РЎС“Р В¶Р Р…Р С• РЎС“РЎвЂљР С•РЎвЂЎР Р…Р С‘РЎвЂљРЎРЉ Р С”Р В°Р С”Р С•Р в„– РЎРѓР В»Р С•Р Р†Р В°РЎР‚РЎРЉ
				if (isset($_POST['data']['OwnerCode']))
					$success[$lang_id] = DB::Query(
						"INSERT INTO ?_{$from['table']}  
							(".implode(",", $field)." , owner_code) 
						 VALUES (".implode(",", $values).",".$_POST['data']['OwnerCode'].")"
						 );
				else			
					$success[$lang_id] = DB::Query(
						"INSERT INTO ?_{$from['table']}  
							(".implode(",", $field).") 
						 VALUES (".implode(",", $values).")"
						 );	 
			}
		} else {			
			//print_r ($_POST['data']);
			//error_reporting (E_ALL);
			
			foreach($_POST['data'] as $lang_id => $fields){
				// Р Р…РЎС“Р В¶Р Р…Р С• Р Т‘Р В»РЎРЏ Р С?Р Р…Р С•Р В¶Р ВµРЎРѓРЎвЂљР Р†Р ВµР Р…Р Р…РЎвЂ№РЎвЂ¦ Р Р†РЎвЂ№Р В±Р С•РЎР‚ Р С•Р С—РЎР‚Р ВµР Т‘Р ВµР В»Р С‘РЎвЂљРЎРЉ РЎРѓР С—Р ВµРЎвЂ  Р С•Р В±РЎР‚Р В°Р В±Р С•РЎвЂљРЎвЂЎР С‘Р С”
				if ($lang_id != 0){
					$field  = array('id', 'lang_id');
					$values = array($id, $lang_id);
					
					foreach($from['multylang_field'] as $key => $value){
						if ($value['notsave'] || $value['colType'] == 'lbl')
							continue;					
						if ($value['multy']){
							DB::Query(
							"INSERT INTO ?_{$value['link_table']}  
								({$key}, {$from['table']}, lang_id) 
							 VALUES ({$fields[$key]}, {$id}, {$lang_id})"
							 );
						} elseif (!isset($value['colChild'])){												
							$tValue     = $fields[$key];//prepear($fields[$key], $value['colType']);
							$tmValue    = $tValue;
							$field[]    = $key;
							$values[]   = ($tmValue) ? "'{$tmValue}'" : "'{$tValue}'";
						}
					}
	
					//Р ВµРЎРѓР В»Р С‘ Р Р…РЎС“Р В¶Р Р…Р С• РЎС“РЎвЂљР С•РЎвЂЎР Р…Р С‘РЎвЂљРЎРЉ Р С”Р В°Р С”Р С•Р в„– РЎРѓР В»Р С•Р Р†Р В°РЎР‚РЎРЉ
					if (isset($_POST['data']['OwnerCode']))
						$success[$lang_id] = (DB::Query(
							"INSERT INTO ?_{$from['table']}_lang  
								(".implode(",", $field)." , owner_code) 
							 VALUES (".implode(",", $values).",".$_POST['data']['OwnerCode'].")"
							 )) ? 1: 0;
					else			
						$success[$lang_id] = 
							(DB::Query(
							"INSERT INTO ?_{$from['table']}_lang  
								(".implode(",", $field).") 
							 VALUES (".implode(",", $values).")"
							 ))? 1 : 0;
				} else {
					$field  = array('id');
					$values = array($id);					
												
					foreach($from['nonlang_field'] as $key => $value){
						if ($value['notsave'] || $value['colType'] == 'lbl')
							continue;					
						if ($value['multy']){	
							
							$items = explode(",", $fields[$key]);
							$sqlitems = array();
							foreach ($items as $arg) {
								if ($arg > 0)
									$sqlitems[] = "({$arg}, {$id})";
							}
							$str = implode(",",$sqlitems);
							
							DB::Query(
							"INSERT INTO ?_{$value['link_table']}  ({$key}, {$from['table']}) 
							 VALUES {$str}"	 );									 
						} elseif (!isset($value['colChild'])){
							$tValue     = $fields[$key];//prepear($fields[$key], $value['colType']);
							$tmValue    = $tValue;
							$field[]    = $key;
							$values[]   = ($tmValue) ? "'{$tmValue}'" : "'{$tValue}'";
						}
					}
	
					//Р ВµРЎРѓР В»Р С‘ Р Р…РЎС“Р В¶Р Р…Р С• РЎС“РЎвЂљР С•РЎвЂЎР Р…Р С‘РЎвЂљРЎРЉ Р С”Р В°Р С”Р С•Р в„– РЎРѓР В»Р С•Р Р†Р В°РЎР‚РЎРЉ
					if (isset($_POST['data']['OwnerCode']))
						$success[$lang_id] = (DB::Query(
							"INSERT INTO ?_{$from['table']}  
								(".implode(",", $field)." , owner_code) 
							 VALUES (".implode(",", $values).",".$_POST['data']['OwnerCode'].")"
							 ) )? 1 : 0;
					else			
						$success[$lang_id] = (DB::Query(
							"INSERT INTO ?_{$from['table']}  
								(".implode(",", $field).") 
							 VALUES (".implode(",", $values).")"
							 ) ) ? 1 : 0;	

				}						
			}		
		}
		//$lang_id = ($from['lang'])? $from['lang'] : 0;
		echo json_encode(array("flag" => "{$success[$lang_id]}", "id" => "{$id}"));
		break;
		
	case "del":
		foreach($from['multylang_field'] as $key => $value){
			if ($value['multy']){					
				DB::Query("DELETE FROM ?_{$value['link_table']} WHERE {$from['table']}='{$_POST['id']}'");
			}
		}
		foreach($from['nonlang_field'] as $key => $value){
			if ($value['multy']){					
				DB::Query("DELETE FROM ?_{$value['link_table']} WHERE {$from['table']}='{$_POST['id']}'");
			}
		}
		
		if (!$from['islanged']){
			DB::Query("DELETE FROM ?_{$from['table']}_lang WHERE id='{$_POST['id']}'");
		}
			
		$success[$from['lang']] = DB::Query("DELETE FROM ?_{$from['table']} WHERE id='{$_POST['id']}'");
		echo json_encode(array("flag" => "{$success[$from['lang']]}"));
		break;
	default:
		break;
}
