<?
//
if(defined('_CRON_INCLUDE') && (_CRON_INCLUDE == 1)) {

    $sys_message = array();

    // журнал для отправки по почте для отладки
    $mail_log = '';

    // сегодня
    $date_lm_db = date("Y-m-d");

    // Получить коды категорий каталога продуктов
    $res1 		   = DB::Query("select id, category_id from `?_product_category` order by category_id asc");
    $relationCat   = array();
    $relationQuery = array();

    while ($get = DB::GetArray($res1)) {
        $prod_cat_id[$get["id"]] = $get["id"];
        $relationCat[$get["id"]] = $get;
    }

    DB::Query('TRUNCATE `?_product_category_to_category`');

    foreach ($relationCat as $key => $value) {
	
		$mKey = $key;

		do {
			$mKey = $relationCat[$mKey]['category_id'];
			DB::Query('replace into `?_product_category_to_category` (`parentID`, `childID`) values ("' . $mKey . '", "' . $key . '")');
		} while($mKey > 0);

		DB::Query('replace into `?_product_category_to_category` (`parentID`, `childID`) values ("' . $key . '", "' . $key . '")');
		
	}

    // Количество найденых категорий
    $count_prod_cat_id = count($prod_cat_id);

    if (!$count_prod_cat_id) {
        $sys_message[] = 'Каталог продуктов не подключен ни к одному из контентов';
    } else {
        $sys_message[] = 'Обработка ' . $count_prod_cat_id . ' категорий каталога продуктов';
    }

    // Для каждой категории
    foreach($prod_cat_id as $value) {

        $sql     = 'select menu_id from ?_content WHERE `text` REGEXP "getCatalogue.+[\w:() ]+,[ ]*' . $value . '[ ]*,[0-9 ]+"';
        $menu_id = array_shift(DB::GetArray(DB::Query($sql)));

        if ($menu_id) {
        	
        	$catArray = array($value);
        	$catQuery = DB::Query('select childID from ?_product_category_to_category where parentID=' . $value);
        	
        	while ($g = DB::GetArray($catQuery))
				$catArray[] = $g['childID'];
        	
            if (DB::Query("UPDATE `?_product` SET menu_id={$menu_id} WHERE category_id in (" . implode(', ', $catArray) . ")")) {
                $mail_log     .= ' Menu id: ' . $menu_id . '. Product category page: ' . $value . ' Products updated: ' . mysql_affected_rows() . " <br />\n";
                $sys_message[] = ' Menu id: ' . $menu_id . '. Product category page: ' . $value . '. Products updated: ' . mysql_affected_rows() . "\n";
            }
        }
    }

    
    $providersQuery = 'select id from ?_providers';
    $providersQuery = DB::Query($providersQuery);
    
    echo '<b>Обновление клиник</b>';
    
    while ($row = DB::GetArray($providersQuery)) {

    	$sql     = 'select menu_id from ?_content WHERE `text` REGEXP "getProvider.+[\w:() ]+,[ ]*' . $row['id'] . '[ ]*"';
    	$menu_id = intval(array_shift(DB::GetArray(DB::Query($sql))));

    	DB::Query('update ?_providers set menu_id="' . intval($menu_id) . '" where id=' . $row['id']);

	}
    

    // Заголовок
    echo "\n" . '<!-- [t_search_cron.php -->' . "\n" . admin_func_top('обновление категорий в каталоге продукции для работы правильной поиска');

    // Вывод сообщений про обработанность различных действий
    echo admin_func_sys_message($sys_message)."\n" . '<!-- t_search_cron.php] -->' . "\n";

} else
    echo 'Error: You can`t call this script directly from ' . _BASE_URL . getenv('REQUEST_URI');