<?
$ostatok = $count % $from['limit'];
$pages_count = ($count - $ostatok ) / $from['limit'];

/*
if ($ostatok > 0) {
	$pages_count++;
}
*/
$pages_count += 1;

if (isset($_GET['page'])) {
	$page_number = $_GET['page'];
}
else 
	$page_number = 0;

$select_page_contol = "<select name=page class='ui-pg-selbox' role='listbox' onchange='changePage(this)'>";
for ($i = 0; $i < $pages_count; $i++)
{
	$select_page_contol .= "<option value='".$i."' ".($page_number == $i? "selected" : "").">".$i."</option>";
}
$select_page_contol .="</select>";
	
echo '<script type="text/javascript">
function goToPage(e, maxPages, input)
{
	if(e.keyCode == 13 && parseInt(input.value) < 62 )
	{
		var ch = parseInt(input.value);
		// убираем лишние записи о старых переходах
		var pars = document.location.search.replace(/&page=[0-9]*/g, "");
		document.location.search = pars + "&page=" + ch; 
	}
	else if((e.keyCode < 48 || e.keyCode > 57) && e.keyCode != 8)
  		e.preventDefault();
}

function changePage(sel)
{
	var ch = parseInt(sel.options[sel.selectedIndex].value);
	// убираем лишние записи о старых переходах
	var pars = document.location.search.replace(/&page=[0-9]*/g, "");	
	document.location.search = pars + "&page=" + ch;
}
</script>';

$pager_control = "<td id='pager_center' align='center' style='white-space: nowrap; width: 227px;'>
<table class='ui-pg-table' cellspacing='0' cellpadding='0' border='0' style='table-layout: auto;'>
	<tr>".navigate_buton($page_number, "first", $pages_count).navigate_buton($page_number, "previous", $pages_count)."
		<td>
			Стр.
			<input class='ui-pg-input' type='text' role='textbox' maxlength='7' size='2' onkeydown='goToPage(event, ".$pages_count.", this)' value='".$page_number."'/>
			из
			<span id='sp_1'>".($pages_count-1)."</span>
		</td>".navigate_buton($page_number, "next", $pages_count).navigate_buton($page_number, "end", $pages_count)."
		<td>".$select_page_contol."
		</td>
	</tr>
</table>  </td>";
			
function navigate_buton($page, $type, $total_page)
{
	$pars = preg_replace('/&page=[0-9]*/i', '', $_SERVER['QUERY_STRING']);
	switch ($type)
	{
		case "next":
			if ($page >= $total_page-1) {
				return "<td id='next' class='ui-pg-button ui-corner-all ui-state-disabled' style='cursor: default;'>
					<span class='ui-icon ui-icon-seek-next'/>
					</td>";
			}
			else {
				return "<td id='next' class='ui-pg-button ui-corner-all ui-state-enabled' style='cursor: default;'>
					<a href='".$_SERVER['PHP_SELF']."?".$pars."&page=".($page+1)."'><span class='ui-icon ui-icon-seek-next'/></a>
					</td>";
			}
			break;
		case "previous":
			if ($page <= 0) {
				return "<td id='prev' class='ui-pg-button ui-corner-all ui-state-disabled'>
					<span class='ui-icon ui-icon-seek-prev'/>
					</td>";
			}
			else {
				return "<td id='prev' class='ui-pg-button ui-corner-all ui-state-enabled'>
					<a href='".$_SERVER['PHP_SELF']."?".$pars."&page=".($page-1)."'><span class='ui-icon ui-icon-seek-prev'/></a>
					</td>";
			}
			break;
		case "first":
			if ($page == 0) {
				return "<td id='first' class='ui-pg-button ui-corner-all ui-state-disabled'>
					<span class='ui-icon ui-icon-seek-first'/>
					</td>";
			}
			else {
				return "<td id='first' class='ui-pg-button ui-corner-all ui-state-enabled'>
					<a href='".$_SERVER['PHP_SELF']."?".$pars."&page=0'><span class='ui-icon ui-icon-seek-first'/></a>
					</td>";
			}
			break;
		case "end":
			if ($page == $total_page-1) {
				return "<td id='last' class='ui-pg-button ui-corner-all ui-state-disabled' style='cursor: default;'>
					<span class='ui-icon ui-icon-seek-end'/>
					</td>";
			}
			else {
				return "<td id='last' class='ui-pg-button ui-corner-all ui-state-enabled' style='cursor: default;'>
					<a href='".$_SERVER['PHP_SELF']."?".$pars."&page=".($total_page-1)."'><span class='ui-icon ui-icon-seek-end'/></a>
					</td>";
			}
			break;
	}
}
?>