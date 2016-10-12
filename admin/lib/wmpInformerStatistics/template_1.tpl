<script>
  $("body").css("background-image", "url('/img/20x20.png')");
</script>

<div style="padding: 20px 10px 0px 10px;">
	<link rel="stylesheet" type="text/css" href="/css/style.css">

	<form action='{$script}' method="post" class="fieldlist_lang">
			 <div style="width: 100%;"><label for="Date_0" title="" > С:</label>

				<input type="text" style="font-size:11px; margin-bottom: 2px; " name="Date[0]" id="Date_0" class="text ui-widget-content ui-corner-all" value="{$date[0]}" />
				<script type="text/javascript">$("#Date_0").datepicker({showOn: "button", dateFormat: "yy-mm-dd", buttonImage: "/admin/images/calendar.gif", buttonImageOnly: true});</script>
			
				&nbsp;&nbsp;&nbsp;
			
				<label for="Date_1" title="" > по:</label>
				<input type="text" style="font-size:11px; margin-bottom: 2px; " name="Date[1]" id="Date_1" class="text ui-widget-content ui-corner-all" value="{$date[1]}" />
				<script type="text/javascript">$("#Date_1").datepicker({showOn: "button", dateFormat: "yy-mm-dd", buttonImage: "/admin/images/calendar.gif", buttonImageOnly: true});</script>

				<input type="submit" value="Найти" />
			</div>
	</form>

  <style>
      .tb_table_1, .tb_table_2 { border:0; border-left:1px solid #E7E6E7; border-top:1px solid #E7E6E7; width:100%}
      .tb_table_1 td, .tb_table_2 td {border-right:1px solid #E7E6E7; border-bottom:1px solid #E7E6E7; padding:6px 0 8px 14px; line-height:14px; }
      .tb_table_2 {}
      .tb_table_2 td { padding-top:5px; height:42px}

      .tb_td1 { width:130px; background:#F0F2E5}
      .tb_p3 { padding:4px 5px 8px 11px; border:1px solid #E7E6E7; line-height:14px; margin:0;}
      .tb_table_2 br { line-height:14px}
      .tb_td2 {background:#F0F2E5; padding:16px 0 16px 16px; vertical-align:middle}
      .tb_td3 { text-align:center; padding-left:5px!important; padding-right:5px!important}
  </style>
	<table class="tb_table_1">
		<tr>
			<th class="tb_td2" style="width: 50px;">№ п\п</th>
			<th class="tb_td2" style="width: 90px;">Дата</th>
			<th class="tb_td2" style="width: 50%;">Адрес источника</th>
			<th class="tb_td2" style="width: 150px;">Количество просмотров</th>
			<th class="tb_td2" style="width: 150px;">Количество переходов на сайт</th>
		</tr>
		{$i = 1}
      	{$informerName = ''}
		{foreach $entries as $entry}
      		{if $informerName neq $entry['title']}
          		{$informerName = $entry['title']}
        		<tr>
          			<td colspan="5" style="font-size: 14px; font-weight: bold;">{$entry['title']}</td>
        		</tr>
			{/if}
			<tr>
				<td>{$i}</td>
				<td>{$entry['Date']}</td>
				<td>{$entry['SiteUrl']}</td>
				<td>{$entry['Counter']}</td>
				<td>{$entry['Transfers']}</td>
			</tr>
			{$i++}
		{/foreach}
	</table>           
</div>