<?php
	class _Date {
		public static	$DaysInMonthes = array (
																		"1"  => "31"
																	, "2"  => "28"
																	, "3"  => "31"
																	, "4"  => "30"
																	, "5"  => "31"
																	, "6"  => "30"
																	, "7"  => "31"
																	, "8"  => "31"
																	, "9"  => "30"
																	, "10" => "31"
																	, "11" => "30"
																	, "12" => "31");
		
		static function GetMonthBounds($month='', $date='') {
			
			if (!empty($date) && is_int($date)) {
				$cd = getdate($date);
			}	elseif (!empty($date) && is_array($date)) {
				$cd = $date;
			} else {
				$cd = getdate();
			}
			
			if (!empty($month)) {
				$cd['mon'] = $month;
			}
			
			$month = $cd['mon'];
			
			if ($cd['mon'] < 10)
				$cd['mon'] = "0" . $cd['mon'];
				
			$d = array();
			$d['min'] = sprintf("%s-%s-%s", $cd['year'], $cd['mon'], "01");
			$d['max'] = sprintf("%s-%s-%s", $cd['year'], $cd['mon'], self::$DaysInMonthes[$month]);

			return $d;
		}
	}
?>
