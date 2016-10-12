<?php

class ProductHelpers{
  static $productFilter = array(
    "null" 	  => ""
    , "new"   => "РќРѕРІРёРЅРєР°"
    , "share" => "100% РҐРёС‚"
    , "hit"   => "РЎРєРёРґРєР°"	
  );

  function getFiltersList($name = "filter", $value = "")
  {
    $list = "<select name=\"{$name}\">" ;
    
    foreach (self::$productFilter as $key => $value) {
	  $list .= "<option value=\"{$key}\"" . ($value == $key ? " selected=\"selected\"" : "" ) . ">{$value}</option>";
    }
    
    return $list . "</select>";
  } 
}