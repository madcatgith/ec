<?php

header("Content-type: text/html; charset=utf-8");

define("_PATH", $_SERVER['DOCUMENT_ROOT']);

include _PATH . "/config.php";
include _PATH . "/admin/prepear.php";
include _PATH . "/admin/mGrid/engine/mGridHelper.php";
include _PATH . "/admin/mGrid/mGridTable{$_POST['table']}.php";

$success = array();

switch($_POST['oper']){   

	case "genFileBill":
		
		$mask = $_POST['mask'];       
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

		$id = (int) $_POST['id'];

		if (isset($from['callback']['beforeUpdate']))
			if (is_string($from['callback']['beforeUpdate']))
				$from['callback']['beforeUpdate']($id);
			else
				foreach ($from['callback']['beforeUpdate'] as $callback)
					$callback($id);

		if (isset($from["islanged"]) && $from["islanged"]){			
			//стандарнтый id-lang_id схема простая.
			foreach($_POST['data'] as $lang_id => $fields){
				if ($lang_id == 0)
					continue;				
				$action = array();
				foreach($from['multylang_field'] as $key => $value){
					if ($value['notsave'] || $value['colType'] == 'lbl')
						continue;					
					if ($value['multy']){
						//здесь идет зачистка и складывание новых значений 
						DB::Query("DELETE FROM ?_{$value['link_table']} WHERE {$from['table']}='{$id}' AND lang_id='{$lang_id}'");

						$items = explode(",", $fields[$key]);
						$sqlitems = array();
						foreach ($items as $arg) {
							if ($arg > 0)
								$sqlitems[] = "({$id}, {$arg}, {$lang_id})";
						}
						$str = implode(",", $sqlitems);

						DB::Query(
						"INSERT INTO ?_{$value['link_table']}  ({$from['table']}, {$key}, lang_id) 
						VALUES {$str}" );	
					} elseif (!isset($value['colChild']) || isset($value['useColChild'])){
						
						$tValue     = $fields[$key];
						$tmValue    = $tValue;
						$tValue     = $tmValue ? $tmValue : $tValue;

						if (isset($value['callback']['beforePrepeareValue']) && $value['callback']['beforePrepeareValue']($tValue, array(
						'id'     => $id
						, 'data' => $_POST
						)) === false)
							continue;

						// добавляем проверку колбека 
						// пользовательская обработка значения
						if (isset($value['callback']['prepareValue']))
							$action[] = "{$key}='" . mysql_real_escape_string($value['callback']['prepareValue']($tValue)) . "'";
						else
							$action[] = "{$key}='" . mysql_real_escape_string($tValue) . "'";	

					}
				}
				//если нужно уточнить какой словарь
				if (isset($_POST['data']['OwnerCode']))
					$success[$lang_id] = (DB::Query("UPDATE ?_{$from['table']} SET " . implode(",", $action) . " 
					WHERE id='{$id}' AND owner_code='{$_POST['data']['OwnerCode']}' AND lang_id='{$lang_id}'")) 
					? 1: 0;
				else
					$success[$lang_id] = (DB::Query("replace ?_{$from['table']} SET " . implode(",", $action ). " , id='{$id}', lang_id='{$lang_id}'")) ? 1: 0;	

			}
		} else {	
			foreach($_POST['data'] as $lang_id => $fields){
				$action = array();
				// нужно для множественных выбор определить спец обработчик
				if ($lang_id != 0){
					foreach($from['multylang_field'] as $key => $value){
						if ((isset($value['notsave']) && $value['notsave']) || $value['colType'] == 'lbl')
							continue;					
						if (isset($value['multy']) && $value['multy']){
							//здесь идет зачистка и складывание новых значений 
							DB::Query("DELETE FROM ?_{$value['link_table']} WHERE {$from['table']}='{$id}' AND lang_id='{$lang_id}'");

							$items = explode(",", $fields[$key]);
							$sqlitems = array();
							foreach ($items as $arg) {
								if ($arg > 0)
									$sqlitems[] = "({$id}, {$arg}, {$lang_id})";
							}
							$str = implode(",", $sqlitems);

							DB::Query(
							"INSERT INTO ?_{$value['link_table']}  ({$from['table']}, {$key}, lang_id) 
							VALUES {$str}" );	
						} elseif (!isset($value['colChild']) || isset($value['useColChild'])){

							$tValue     = $fields[$key];
							$tmValue    = $tValue;
							$tValue     = $tmValue ? $tmValue : $tValue;

							if (isset($value['callback']['beforePrepeareValue']) && $value['callback']['beforePrepeareValue']($tValue, array(
							'id'     => $id
							, 'data' => $_POST
							)) === false)
								continue;

							// добавляем проверку колбека 
							// пользовательская обработка значения
							if (isset($value['callback']['prepareValue']))
								$action[] = "{$key}='" . mysql_real_escape_string($value['callback']['prepareValue']($tValue)) . "'";
							else
								$action[] = "{$key}='" . mysql_real_escape_string($tValue) . "'";

						}
					}
					//если нужно уточнить какой словарь
					if (isset($_POST['data']['OwnerCode']))
						$success[$lang_id] = (DB::Query("UPDATE ?_{$from['table']}_lang SET " . implode(",", $action) . " 
						WHERE id='{$id}' AND owner_code='{$_POST['data']['OwnerCode']}' AND lang_id='{$lang_id}'")) 
						? 1: 0;
					else		
						$success[$lang_id] = (DB::Query("replace ?_{$from['table']}_lang SET " . implode(",", $action) . ", id='{$id}', lang_id='{$lang_id}'")) ? 1: 0;	
				} else {

					foreach($from['nonlang_field'] as $key => $value){
						if ((isset($value['notsave']) && $value['notsave']) || $value['colType'] == 'lbl')
							continue;					
						if (isset($value['multy']) && $value['multy']){	

							//здесь идет зачистка и складывание новых значений 
							DB::Query("DELETE FROM ?_{$value['link_table']} 
							WHERE {$from['table']}='{$id}'");

							$items = explode(",", $fields[$key]);
							$sqlitems = array();
							foreach ($items as $arg) {
								if ($arg > 0)
									$sqlitems[] = "({$arg}, {$id})";
							}

							$str = implode(",", $sqlitems);							

							DB::Query(
							"INSERT INTO ?_{$value['link_table']}  
							({$key}, {$from['table']}) 
							VALUES {$str}"	 );		

						} elseif (!isset($value['colChild']) || isset($value['useColChild'])){

							$tValue     = $fields[$key];
							$tmValue    = $tValue;
							$tValue     = $tmValue ? $tmValue : $tValue;

							if (isset($value['callback']['beforePrepeareValue']) && $value['callback']['beforePrepeareValue']($tValue, array(
							'id'     => $id
							, 'data' => $_POST
							)) === false)
								continue;

							// добавляем проверку колбека 
							// пользовательская обработка значения
							if (isset($value['callback']['prepareValue']))
								$action[] = "{$key}='" . mysql_real_escape_string($value['callback']['prepareValue']($tValue)) . "'";
							else
								$action[] = "{$key}='" . mysql_real_escape_string($tValue) . "'";

						}
					}

					//если нужно уточнить какой словарь
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

		$lang_id = ($from['lang'] && ( ! isset($from['nonlang']) || ! $from['nonlang'])) ? $from['lang'] : 0;

		if (isset($from['callback']['onUpdate']))
			if (is_string($from['callback']['onUpdate']))
				$from['callback']['onUpdate']($id, $_POST);
			else
				foreach ($from['callback']['onUpdate'] as $callback)
					$callback($id, $_POST);

		echo json_encode(array("flag" => "{$success[$lang_id]}", "id" => "{$id}"));

		break;			

	case "add":

		//error_reporting(E_ALL);
		$id = array_shift(DB::GetRow(DB::Query("SELECT max(id) FROM ?_{$from['table']}"))) + 1;
		if ($from["islanged"]){
			//стандарнтый id-lang_id схема простая.
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
					} elseif (!isset($value['colChild']) || isset($value['useColChild'])) {

      					$tValue     = $fields[$key];
						$tmValue    = $tValue;
						$tValue     = $tmValue ? $tmValue : $tValue;

						if (isset($value['callback']['beforePrepeareValue']) && $value['callback']['beforePrepeareValue']($tValue, array(
							'id'     => $id
							, 'data' => $_POST
						)) === false)
							continue;

						// добавляем проверку колбека 
						// пользовательская обработка значения
   						if (isset($value['callback']['prepareValue'])) {
							$field[]  = $key;
							$values[] = mysql_real_escape_string($value['callback']['prepareValue']($tValue));
						} else {
							$field[]  = $key;
							$values[] = mysql_real_escape_string($tValue);
						}

					}
				}

				//если нужно уточнить какой словарь
				if (isset($_POST['data']['OwnerCode']))
					$success[$lang_id] = DB::Query(
					"INSERT INTO ?_{$from['table']}  
					(`" . implode("`, `", $field) . "` , owner_code) 
					VALUES (".implode(",", $values).",".$_POST['data']['OwnerCode'].")"
					);
				else			
					$success[$lang_id] = DB::Query(
					"INSERT INTO ?_{$from['table']}  
					(`" . implode("`, `", $field) . "`) 
					VALUES ('" . implode("', '", $values) . "')"
					);	 
			}
		} else {			
			//print_r ($_POST['data']);
			//error_reporting (E_ALL);

			foreach($_POST['data'] as $lang_id => $fields){
				// нужно для множественных выбор определить спец обработчик
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
						} elseif (!isset($value['colChild']) || isset($value['useColChild'])){												
      						
      						$tValue     = $fields[$key];
							$tmValue    = $tValue;
							$tValue     = $tmValue ? $tmValue : $tValue;

							if (isset($value['callback']['beforePrepeareValue']) && $value['callback']['beforePrepeareValue']($tValue, array(
								'id'     => $id
								, 'data' => $_POST
							)) === false)
								continue;

							// добавляем проверку колбека 
							// пользовательская обработка значения
     						if (isset($value['callback']['prepareValue'])) {
								$field[]  = $key;
								$values[] = mysql_real_escape_string($value['callback']['prepareValue']($tValue));
							} else {
								$field[]  = $key;
								$values[] = mysql_real_escape_string($tValue);
							}

						}
					}

					//если нужно уточнить какой словарь
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
						(`" . implode("`, `", $field) . "`) 
						VALUES ('" . implode("', '", $values) . "')"
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

						} elseif (!isset($value['colChild']) || isset($value['useColChild'])){

							$tValue     = $fields[$key];
							$tmValue    = $tValue;
							$tValue     = $tmValue ? $tmValue : $tValue;

							if (isset($value['callback']['beforePrepeareValue']) && $value['callback']['beforePrepeareValue']($tValue, array(
								'id'     => $id
								, 'data' => $_POST
							)) === false)
								continue;

							// добавляем проверку колбека 
							// пользовательская обработка значения
							if (isset($value['callback']['prepareValue'])) {
								$field[]  = $key;
								$values[] = mysql_real_escape_string($value['callback']['prepareValue']($tValue));
							} else {
								$field[]  = $key;
								$values[] = mysql_real_escape_string($tValue);
							}

						}
					}
					
					//если нужно уточнить какой словарь
					if (isset($_POST['data']['OwnerCode']))
						$success[$lang_id] = (DB::Query(
						"INSERT INTO ?_{$from['table']}  
						(".implode(",", $field)." , owner_code) 
						VALUES (".implode(",", $values).",".$_POST['data']['OwnerCode'].")"
						) )? 1 : 0;
					else			
						$success[$lang_id] = (DB::Query(
						"INSERT INTO ?_{$from['table']}  
						(`" . implode("`, `", $field)."`) 
						VALUES ('" . implode("', '", $values) . "')"
						) ) ? 1 : 0;

				}						
			}		
		}

		if (intval($id) > 0 && isset($from['callback']['onInsert']))
			if (is_string($from['callback']['onInsert']))
				$from['callback']['onInsert']($id, $_POST);
			else
				foreach ($from['callback']['onInsert'] as $callback)
					$callback($id, $_POST);

		echo json_encode(array("flag" => "{$success[$lang_id]}", "id" => "{$id}"));
		break;

	case "del":

		$ID = intval($_POST['id']);

		if (intval($ID) > 0 && isset($from['callback']['beforeDelete']))
			if (is_string($from['callback']['beforeDelete']))
				$from['callback']['beforeDelete']($ID);
			else
				foreach ($from['callback']['onDelete'] as $callback)
					$callback($ID);
		
		foreach ($from['multylang_field'] as $key => $value)
			if ($value['multy'])
				DB::Query("DELETE FROM ?_{$value['link_table']} WHERE {$from['table']}='{$ID}'");

			foreach ($from['nonlang_field'] as $key => $value)
			if ($value['multy'])
				DB::Query("DELETE FROM ?_{$value['link_table']} WHERE {$from['table']}='{$ID}'");

			if ( ! $from['islanged'])
			DB::Query("DELETE FROM ?_{$from['table']}_lang WHERE id='{$ID}'");

		$success[$from['lang']] = DB::Query("DELETE FROM ?_{$from['table']} WHERE id='{$ID}'");

		if (intval($ID) > 0 && isset($from['callback']['onDelete']))
			if (is_string($from['callback']['onDelete']))
				$from['callback']['onDelete']($ID);
			else
				foreach ($from['callback']['onDelete'] as $callback)
					$callback($ID);

		echo json_encode(array("flag" => "{$success[$from['lang']]}"));

		break;
	default:
		break;
}
