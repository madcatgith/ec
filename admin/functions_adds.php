<?php
function getSendCategories($lang_id = 1,$arra_of_ids = '')
{
$select = DB::Query("select * from `?_subscribe_category` WHERE lang_id='".$lang_id."'order by place,lang_id ");
while($get = DB::GetArray($select))
{
  $id=$get[id];
  if(in_array($id,$arra_of_ids)) {$ch='selected';} else $ch='';
  $text .= "<option value='".$get[id]."' $ch>".$get[title]."</option>"; 
}

$text="<select name='send_categories_".$lang_id."[]' multiple size=10>".$text."</select>";
return $text;
}




function getMyCategories($id,$lang_id)
 {
    $arr=array();
	$select = DB::Query("select category_id from `?_content_category` WHERE lang_id='".$lang_id."' and content_id='".$id."'");
	while($get = DB::GetArray($select))
	 {
		$arr[]=$get[category_id];
	 }
	 return $arr;
 }

?>