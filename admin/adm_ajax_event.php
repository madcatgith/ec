<?

include "../ajax-post.php";
$_GET = Config::$ajaxPost->clean($_GET);
$_POST = Config::$ajaxPost->clean($_POST);

include("../config.php");

switch($oper){
	
	case "get":
				
		(stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))?header("Content-type: application/xhtml+xml;charset=utf-8"):header("Content-type: text/xml;charset=utf-8"); 
		
		$limit = $rows;		
		$start = (($page - 1) * $limit);
		$wh    = "";		
		$searchOn = $_search;
		
		if($searchOn){
			$fld = $searchField;						
			if( $fld == 'e.title' || $fld == 'ec.title' ) {				
				$fldata = $searchString;
				$foper  = $searchOper;
				$sopt   = array("eq" => " AND {field} = '{value}' ", "ne" => " AND {field} <> '{value}' ", "lt" => " AND {field} < '{value}' ", "le" => " AND {field} <= '{value}' ", "gt" => " AND {field} > '{value}' ", "ge" => " AND {field} >= '{value}' ", "bw" => " AND {field} LIKE '{value}%' ", "ew" => " AND {field} LIKE '%{value}' ", "cn" => " AND {field} LIKE '%{value}%' ");
				$wh     = str_replace(array("{field}", "{value}"), array($fld, $fldata), $sopt[$foper]);
			}
		}

		$query_status = DB::Query("SELECT SQL_CALC_FOUND_ROWS e.id as id, e.title as title, ec.title as city_title
		FROM ?_event as e 
		LEFT JOIN ?_event_city as ec ON ec.lang_id='{$lang_id}' AND ec.id=e.city_id
		WHERE e.lang_id='{$lang_id}' {$wh}
		ORDER BY {$sidx} {$sord} LIMIT {$start}, {$limit}");
		
		$count 	= @array_shift(DB::GetRow(DB::Query("SELECT FOUND_ROWS()")));
		$total_pages = ceil($count/$limit); 
		
		echo "<?xml version='1.0' encoding='utf-8'?>"; 
		echo "<rows>"; 
		echo "<page>".$page."</page>"; 
		echo "<total>".$total_pages."</total>"; 
		echo "<records>".$count."</records>"; 	
			
		while($row = DB::GetArray($query_status)){
			echo "<row id='". $row['id']."'>";             
				echo "<cell>". $row['id']."</cell>"; 				
            	echo "<cell><![CDATA[". $row['title']."]]></cell>";
            	echo "<cell><![CDATA[". $row['city_title']."]]></cell>";
			echo "</row>";
		}		
		
		echo "</rows>";  	
				
	break;	
	
	case "del":
				
		DB::Query("DELETE FROM ?_event_to_date WHERE event_id IN ({$list_id})");
		DB::Query("DELETE FROM ?_event_to_tags WHERE event_id IN ({$list_id})");
		DB::Query("DELETE FROM ?_event_to_category WHERE event_id IN ({$list_id})");
		DB::Query("DELETE FROM ?_event_to_main WHERE event_id IN ({$list_id})");
		DB::Query("DELETE FROM ?_event WHERE id IN ({$list_id})");		

	break;
}