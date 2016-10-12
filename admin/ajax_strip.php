<?
function clear($data)
{	
	$search  = array("&", "\"");
	$replace = array("&amp;", "&quot;");	
	return str_replace($search, $replace, $data);	
}

function Strip($value)
{
	if(get_magic_quotes_gpc() != 0)
  	{
    	if(is_array($value))  
			if ( array_is_associative($value) )
			{
				foreach( $value as $k=>$v)
					$tmp_val[$k] = stripslashes($v);
				$value = $tmp_val; 
			}				
			else  
				for($j = 0; $j < sizeof($value); $j++)
        			$value[$j] = stripslashes($value[$j]);
		else
			$value = stripslashes($value);
	}
	return $value;
}

function floattostring($float) {
	$float = explode('.',$float);
	if (isset($float[1])){ 
		if ($float[1] == '00') {unset($float[1]);}
		elseif (substr($float[1],1) == '0') {$float[1] = substr($float[1],0,1);}
	}
	return implode(',',$float);
};

function parseFloat($ptString) {
            if (strlen($ptString) == 0) {
                    return false;
            }
           
            $pString = str_replace(" ", "", $ptString);
           
            if (substr_count($pString, ",") > 1)
                $pString = str_replace(",", "", $pString);
           
            if (substr_count($pString, ".") > 1)
                $pString = str_replace(".", "", $pString);
           
            $pregResult = array();
       
            $commaset = strpos($pString,',');
            if ($commaset === false) {$commaset = -1;}
       
            $pointset = strpos($pString,'.');
            if ($pointset === false) {$pointset = -1;}
       
            $pregResultA = array();
            $pregResultB = array();
       
            if ($pointset < $commaset) {
                preg_match('#(([-]?[0-9]+(\.[0-9])?)+(,[0-9]+)?)#', $pString, $pregResultA);
            }
            preg_match('#(([-]?[0-9]+(,[0-9])?)+(\.[0-9]+)?)#', $pString, $pregResultB);
            if ((isset($pregResultA[0]) && (!isset($pregResultB[0])
                    || strstr($preResultA[0],$pregResultB[0]) == 0
                    || !$pointset))) {
                $numberString = $pregResultA[0];
                $numberString = str_replace('.','',$numberString);
                $numberString = str_replace(',','.',$numberString);
            }
            elseif (isset($pregResultB[0]) && (!isset($pregResultA[0])
                    || strstr($pregResultB[0],$preResultA[0]) == 0
                    || !$commaset)) {
                $numberString = $pregResultB[0];
                $numberString = str_replace(',','',$numberString);
            }
            else {
                return false;
            }
            $result = (float)$numberString;
            return $result;
}

?>