<?php
	class InformerStatistics {
		static function GetStatistics() {
			$where = '';
			if (isset($_POST['Date'])) {
				$date = $_POST['Date'];
				if (!empty($date[0])) {
					$where .= " and `date` >= '" . $date[0] . "' ";
				}
				
				if (!empty($date[1])) {
					$where .= " and `date` <= '" . $date[1] . "' ";
				}
			}
			
			$query = "
        select 
          * 
        from 
          `?_informer_statistics` statistics
          LEFT JOIN `?_informers` inf on statistics.InformerId = inf.id
        where 
          1=1 
          " . $where . " 
        order by 
          statistics.`date` desc
          , statistics.`InformerId` asc";
			
			$arr = DB::SuperQuery($query, true, true);
			
			$tpl = new Template();
			$tpl_fn = BASEPATH . "admin/lib/wmpInformerStatistics/template_1.tpl";
			$tpl->set('entries', $arr);
			$tpl->set('script', $_SERVER['SCRIPT_NAME']);

			if (isset($date))
				$tpl->set('date', $date);
      else
        $tpl->set('date',array('',''));

			return $tpl->fetch($tpl_fn);
		}
	}
?>