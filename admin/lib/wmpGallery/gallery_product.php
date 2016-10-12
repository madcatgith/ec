<?
$include = @include("../../admin_top_prod.php");
if(!$include or $adm_wellcome != "Y") exit;

echo '<script type="text/javascript" src="/js/jquery-1.2.6.min.js"></script>';

$parent_cat = 0;
if (isset($_POST['item_id']))
	$parent_cat = $_POST['item_id'];

// Заголовок
//echo admin_func_top('управление галереей');
echo admin_func_sys_message($sys_message);

// Кнопки
//поиск
/*
echo '<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#a5cd38">
		 <tr>
		 	<td width="300" class="td_left" style="vertical-align: top; border: 1px solid #a5cd38;">
		 		<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td style="padding: 14px 4px 4px 4px; border-bottom: 2px solid #213866;"><form action="/admin/gallery.php?oper=search" method="post">';
echo Dictionary::GetAdminWord(64) ." ". admin_func_right_input("text", "search", $search, "100", "") ." ". admin_func_right_input("submit", "", Dictionary::GetAdminWord(341), "", "1");
echo '</form></td></tr><tr><td style="padding: 0;">';
echo admin_func_items_tree("item_id", "gallery.php", "&nbsp;&nbsp;", Dictionary::GetAdminWord(231), "1"
							, "width:300px; float:left; font-size:9px;", array($parent_cat));
echo '      	</td></tr></table>
			</td>
			<td style="vertical-align: top; background: #fff;">';

echo admin_func_right_table_start(0);
*/

/*
echo admin_func_right_table_row_start(2);
echo admin_func_right_table_data( "<IMG src=\"p.gif\" width=1 height=1 border=0>", "", 7);
echo admin_func_right_table_end();

echo admin_func_right_table_start("");
//echo admin_func_right_table_row_start("");
*/

echo "<table id=\"gallery_list\"  border=0 width=\"600\" bgcolor=\"#627080\" cellspacing=1 cellpadding=2>";
echo admin_func_right_table_data( Dictionary::GetAdminWord(178), "560", "");
echo admin_func_right_table_data( Dictionary::GetAdminWord(350), "40", "");

$select_gallery = DB::Query("SELECT * FROM ?_gallery_category WHERE parent ='".$parent_cat."' order by type");
	while($get = DB::GetArray($select_gallery)){				
		if ($get['type'] == 2){//если галлерея
			echo '<tr bgcolor="#ffffff" id="gallery_'.$get['id'].'">';
			echo '<td style="padding: 2px 0px 2px 10px;"><span class="gal" id="'.$get[id].'" style="color: #646567; font: 11px Tahoma;">'.$get[title].'</span></td>';
			echo '<td><input class="gallery_'.$get['id'].'" type="button" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma;" value="удалить"></td>';
			echo '</tr>';
		} else {//если категория
			echo '<tr bgcolor="#eeeeee" id="gallery_'.$get[id].'">';
			echo '<td style="padding: 2px 0px 2px 10px;"><span class="gal_kat" id="'.$get[id].'" style="color: #757678; font: 11px Tahoma;">'.$get[title].'</a></td>';
			//echo '<td style="padding: 2px 0px 2px 10px;"><a href="/admin/gallery.php?item_id='.$get[id].'" style="color: #757678; font: 11px Tahoma;">'.$get[title].'</a></td>';
			echo '<td><input class="gallery_'.$get[id].'" type="button" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma;" value="удалить"></td>';
			echo '</tr>';
		}
	}
echo admin_func_right_table_end();
echo '<div style="padding: 10px; position: relative; overflow: hidden; color: red;" id="error"></div>';

echo '<div style="padding: 10px; position: relative; overflow: hidden;" id="add_gallery">';
	echo '<div style="width: 335px; float: left;"><input type="text" id="name" style="width: 330px; color: #757678; padding: 2px 0 1px 10px; border: 1px solid #D5D6D2; font: 11px Tahoma;"></div>';
	echo '<div style="width: 100px; float: left;"><input type="button" id="galcreat" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma;" value="Создать галерею"></div>';
	echo '<div style="margin-left:20px;width: 100px; float: left;"><input type="button" id="catcreat" style="color: #757678; border: 1px solid #D5D6D2; font: 12px Tahoma;" value="Создать категорию"></div>';	
	echo '<div id="load" style="width: 16px; float: left; padding: 2px 0px 0px 0px; margin-left: 19px; visibility: hidden;"><img src="/images/spinner.gif" alt=""/></div>';
echo '</div>';

echo '<div style="padding: 10px; position: relative; overflow: hidden; color: red;" id="error_upload"></div>';
?>
<script type="text/javascript">
	$(".gal").click(function(){	
		var gal_var = $(this).attr('id'); 
		$('input[name=galleryID]').attr({value:gal_var});
			$('#photogalary1').load('/admin/gallery_action_prod.php', {'item_id':gal_var});
		  });
	$(".gal_kat").click(function(){	
		var gal_var = $(this).attr('id');
	
			$('#photogalary1').load('/admin/gallery_product.php', {'item_id':gal_var});
		  });		  
	$("input[value='удалить']").click(function(){	
		var del_var = $(this).attr('class');	
		$.get("/admin/adm_ajax.php", {'action' : 'delete_category', 'gallery_id' : del_var},
		  function(data){
		    if(data){
		    	$('#'+del_var).fadeOut('slow');
		    }else{
		    	$("#error").html('Не удалось удалить галерею ' + del_var);
		    }
		  });
	});		
	$('#add_gallery #galcreat').click(function(){
	
		$("#load").css("visibility","visible");
		string = "parent=<? echo $parent_cat; ?>&action=creat_gallery&name="+$("#name").val()+"&prod=1";
		$.ajax({
				type: "GET",
				url: "/admin/adm_ajax.php",    		
 				data: string,
 				cache: false,
				success: function(data){
			    	$("#gallery_list").append(data);
		    		$("#name").val('');				    			    		
					$("input[value='удалить']").click(function(){	
						var del_var = $(this).attr('class');	
						$.get("/admin/adm_ajax.php", {'action' : 'delete_category', 'gallery_id' : del_var},
						  function(data){
						    if(data){
						    	$('#'+del_var).fadeOut('slow');
						    }else{
						    	$("#error").html('Не удалось удалить галерею ' + del_var);
						    }
						  });
					});	
				},
				error: function(data){
					if(!data)$("#error_upload").html('Не удалось создать галерею ' + $("#name").val());
				}
			});		
		$("#load").css("visibility","hidden");
	});	
	$('#add_gallery #catcreat').click(function(){
		$("#load").css("visibility","visible");
		string = "parent=<? echo $parent_cat; ?>&action=creat_category&name="+$("#name").val();
		$.ajax({
				type: "GET",
				url: "/admin/adm_ajax.php",    		
 				data: string,
 				cache: false,
				success: function(data){
			    	$("#gallery_list").append(data);
		    		$("#name").val('');				    			    		
					$("input[value='удалить']").click(function(){	
						var del_var = $(this).attr('class');	
						$.get("/admin/adm_ajax.php", {'action' : 'delete_category', 'gallery_id' : del_var},
						  function(data){
						    if(data){
						    	$('#'+del_var).fadeOut('slow');
						    }else{
						    	$("#error").html('Не удалось удалить галерею ' + del_var);
						    }
						  });
					});	
				},
				error: function(data){
					if(!data)$("#error_upload").html('Не удалось создать категорию ' + $("#name").val());
				}
			});		
		$("#load").css("visibility","hidden");
	});		

</script>
<?
/*echo "<script type=\"text/javascript\">".
."alert('333333');"
"$(document).ready(function(){".	
	"$(\"input[value='удалить']\").click(function(){".	
		"var del_var = $(this).attr('class');".	
		"$.get(\"/admin/adm_ajax.php\", {'action' : 'delete_category', 'gallery_id' : del_var},".
		  "function(data){".
		    "if(data){".
		    	"$('#'+del_var).fadeOut('slow');".
		    "}else{".
		    	"$(\"#error\").html('Не удалось удалить галерею ' + del_var);".
		 "   }".
		  "});".
	"});".		
	"$('#add_gallery #galcreat').click(function(){".
		"$(\"#load\").css(\"visibility\",\"visible\");".
		"string = \"parent=<? echo $parent_cat; ?>&action=creat_gallery&name=\"+$(\"#name\").val();".
		"$.ajax({".
				"type: \"GET\",".
				"url: \"/admin/adm_ajax.php\",".    		
 				"data: string,".
 				"cache: false,".
				"success: function(data){".
			    	"$(\"#gallery_list\").append(data);".
		    		"$(\"#name\").val('');".				    			    		
					"$(\"input[value='удалить']\").click(function(){".	
						"var del_var = $(this).attr('class');".	
						"$.get(\"/admin/adm_ajax.php\", {'action' : 'delete_category', 'gallery_id' : del_var},".
						 " function(data){".
						  "  if(data){".
						    	"$('#'+del_var).fadeOut('slow');".
						    "}else{".
						    	"$(\"#error\").html('Не удалось удалить галерею ' + del_var);".
						    "}".
						  "});".
					"});".	
				"},".
				"error: function(data){".
				"	if(!data)$(\"#error_upload\").html('Не удалось создать галерею ' + $(\"#name\").val());".
				"}".
		"	});".		
		"$(\"#load\").css(\"visibility\",\"hidden\");".
"	});".	
	"$('#add_gallery #catcreat').click(function(){".
		"$(\"#load\").css(\"visibility\",\"visible\");".
		"string = \"parent=".$parent_cat."&action=creat_category&name=\"+$(\"#name\").val();".
	"	$.ajax({".
				"type: \"GET\",".
				"url: \"/admin/adm_ajax.php\",".    		
 				"data: string,".
 				"cache: false,".
			"	success: function(data){".
			    	"$(\"#gallery_list\").append(data);".
		    		"$(\"#name\").val('');".				    			    		
					"$(\"input[value='удалить']\").click(function(){".	
						"var del_var = $(this).attr('class');".	
						"$.get(\"/admin/adm_ajax.php\", {'action' : 'delete_category', 'gallery_id' : del_var},".
						  "function(data){".
						    "if(data){".
						    	"$('#'+del_var).fadeOut('slow');".
						    "}else{".
						    	"$(\"#error\").html('Не удалось удалить галерею ' + del_var);".
						    "}".
						  "});".
					"});".	
				"},".
				"error: function(data){".
					"if(!data)$(\"#error_upload\").html('Не удалось создать категорию ' + $(\"#name\").val());".
				"}".
			"});".		
		"$(\"#load\").css(\"visibility\",\"hidden\");".
	"});".		
"});".	
"</script>";*/
/*
echo "<br>";
echo "<table border=0 cellspacing=5 cellpadding=0 width=600>";
echo "<tr bgcolor=ffffff>";
echo "<td colspan=2>";
echo "<b>". Dictionary::GetAdminWord(356) ."</b>";
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"g/n.gif\" border=0 alt=\"". Dictionary::GetAdminWord(901) ."\" width=11 height=12>";
echo "<td>";
echo  Dictionary::GetAdminWord(318) ;
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"g/e.gif\" border=0 alt=\"". Dictionary::GetAdminWord(371) ."\" width=10 height=12>";
echo "<td>";
echo  Dictionary::GetAdminWord(626) ;
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"g/d.gif\" border=0 alt=\"". Dictionary::GetAdminWord(354) ."\" width=12 height=12>";
echo "<td>";
echo Dictionary::GetAdminWord(905);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=g/f2.gif width=12 height=9 border=0>";
echo "<td>";
echo Dictionary::GetAdminWord(877);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=g/f.gif width=15 height=9 border=0>";
echo "<td>";
echo Dictionary::GetAdminWord(878);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"g/1.gif\" border=0 alt=\"". Dictionary::GetAdminWord(827) ."\" width=11 height=12>";
echo "<td>";
echo Dictionary::GetAdminWord(906);
echo "<tr bgcolor=ffffff>";
echo "<td width=20>";
echo "<img src=\"g/2.gif\" border=0 alt=\"". Dictionary::GetAdminWord(828) ."\" width=11 height=12>";
echo "<td>";
echo Dictionary::GetAdminWord(907);
echo "</table>";
*/
$select_config = DB::Query("select education from `?_config` ");
if(@array_shift(DB::GetArray($select_config)) == "0")
{
/*
echo "<br><br>";
echo "<table border=0 cellspacing=5 cellpadding=0 width=600>";
echo "<tr bgcolor=ffffff>";
echo "<td colspan=2>";
echo "<b>". Dictionary::GetAdminWord(246) ."</b>";
echo "<tr bgcolor=ffffff>";
echo "<td>";

echo "<table cellspacing=0 cellpadding=0 border=0>";
echo "<tr bgcolor=627080 height=22>";
echo "<td class=\"w\"><nobr>&nbsp; ";
echo "<a href=products.php class=\"w\">". Dictionary::GetAdminWord(231) ."</a> <font style=\"font-size:15px\"><b>&raquo;</b>&nbsp;";
echo "</nobr></table>";

echo "<td>";
echo Dictionary::GetAdminWord(908);
echo "<tr bgcolor=ffffff>";
echo "<td>";
echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
echo "<tr bgcolor=E8E8E8 height=22>";
echo "<th class=\"top\"><nobr>&nbsp;". Dictionary::GetAdminWord(178) ."&nbsp;</nobr></th>";
echo "</table>";
echo "<td>";
echo Dictionary::GetAdminWord(909);
echo "<tr bgcolor=ffffff>";
echo "<td>";
echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
echo "<tr bgcolor=E8E8E8 height=22>";
echo "<th class=\"top\"><nobr>&nbsp;". Dictionary::GetAdminWord(478) ."&nbsp;</nobr></th>";
echo "</table>";
echo "<td>";
echo Dictionary::GetAdminWord(910);
echo "<tr bgcolor=ffffff>";
echo "<td>";
echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
echo "<tr bgcolor=E8E8E8 height=22>";
echo "<th class=\"top\"><nobr>&nbsp;". Dictionary::GetAdminWord(350) ."&nbsp;</nobr></th>";
echo "</table>";
echo "<td>";
echo Dictionary::GetAdminWord(911);
echo "<tr bgcolor=ffffff>";
echo "<td>";
echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080>";
echo "<tr bgcolor=E8E8E8 height=22>";
echo "<th class=\"top\"><nobr>&nbsp;". Dictionary::GetAdminWord(479) ."&nbsp;</nobr></th>";
echo "</table>";
echo "<td>";
echo Dictionary::GetAdminWord(912);
echo "<tr bgcolor=ffffff>";
echo "<td valign=top>";
echo "<table cellspacing=1 cellpadding=0 border=0 bgcolor=627080 height=22>";
echo "<tr bgcolor=E8E8E8>";
echo "<th class=\"top\"><nobr>&nbsp;<img src=g/v.gif width=11 height=10 border=0>&nbsp;</nobr></th>";
echo "</table>";
echo "<td>";
echo "". Dictionary::GetAdminWord(913) ."<br> &nbsp;&nbsp;". Dictionary::GetAdminWord(650) ."";
echo "<tr bgcolor=ffffff>";
echo "<td valign=top>";
echo "<input type=submit class=button value=\"". Dictionary::GetAdminWord(495) ."\">";
echo "<td>";
echo Dictionary::GetAdminWord(914);
echo "<tr bgcolor=ffffff>";
echo "<td valign=top>";
echo "<input type=submit class=button value=\"". Dictionary::GetAdminWord(441) ."\">";
echo "<td>";
echo Dictionary::GetAdminWord(915);
echo "</table>"; */
}

//include("admin_footer.php");

?>
