<?php 

$GridTitle        = "Редактор интерактивной афиши";
$from         = array("table" => "event_to_main", "as" => "etm", "lang" => "1", "style" => "width:100%",
						"nonlang_field" => array(
							"id"      => array(
														"title"   => "id",
														"style"   => "width: 40px;",
														"tablestyle"   => "width: 40px;",
														"colType" => "lbl"
											),
							"event_id" 	=> array(
															"title" => "Событие",
															"style" => "width: 100px; ",
															"tablestyle" => "width: 70px; white-space:normal;",
															"colType" => "select",															

															"table" => "event",	
															"outfield_lang" => "title",
															
															"field" => "id",

															"rules"   => true,
											),	
																						
							"date_from"      		=> array(
															"title"   => "Начало",	
															"style"   => "width: 100px;",
															"tablestyle"   => "width: 120px;",														
															"colType" => "date"
															,"description" => "Дата с которой начнется выведение баннера на сайте"
											),											

							"city_id" 	=> array(
															"title" => "Город",
															"style" => "width: 100px; ",
															"tablestyle" => "width: 50px; white-space:normal;",
															"colType" => "select",
															"islanged" => true,

															"table" => "event_city",	
															"outfield" => "title",
															"field" => "id",

															"rules"   => true,
											),
											
							"date_to"      		=> array(
															"title"   => "Окончание",		
															"style"   => "width: 100px;",
															"tablestyle"   => "width: 120px;",													
															"colType" => "date"
															,"description" => "Дата с которой закончится выведение баннера на сайте"
											),												
								
												
							"event_order"      => array(
													"title"   => "Порядок показа",
													"colType" => "text"
											),												

							"position" 	=> array(
													"title" => "Место на афише",
													"style" => "width: 100px; ",
													"tablestyle" => "width: 70px; white-space:normal;",
													"colType" => "select",
													"nonlang" => true,
													"table" => "event_to_main_position",	
													"outfield" => array("id", "title"),
															
													"orderby"	=> "id",
														
													"field" => "id",

													"rules"   => true
													,"description" => "
														в списке выбирается позиция на которой будет размещаться баннер.
														<br /> '12. 425*280' - соответствует позиции 12 в карте баннеров. Разрешение изображения должно быть высотой 280 пикс и шириной 425 пикс.
														<br /> При выборе в выпадающем списке определенной позиции в карте должен подсветиться соответствующий блок.
														<br /> Выбор нужно блока можно осущесвить и с помощью контрола карты.														
													"
											),												
											
							"map"      => array(
													"title"   => "Карта",
													"style" => "float:right; margin-top:10px; width: 255px; border:1px solid SlateGray; padding:1px;",
													"editbody"	  => GetMapHtml(),
													"colType" => "html"
													,"notsave" => true
													,"description" => "
														Карта афиши с наложенными данными по заполненности блоков.
														<br /> Реальное заполнение блоков осуществяется после выбора города, даты 
													"
											),
						),
						  	//position event_id  city_id date_from date_to event_order						  	
						"multylang_field" => array(
							"title"      => array(
													"title"   => "Выводимое название",
													"colType" => "text"
													,"description" => "Всегда выводимый краткий тект. 'Lady Gaga 4ever'"
												),
							"strdate"      => array(
													"title"   => "Выводимая дата",
								 					"colType" => "text"
								 					,"description" => "всегда выводимая дата. Формат Чт 5 Января"
												),												
							"url"      => array(
													"title"   => "Ссылка",
													"colType" => "text"
													,"description" => "Адрес для перехода при двойном нажатии или при нажатии на диалог"
												),
							"image"      => array(
													"title"   => "Изображение"
													,"imagesizing"	=> array('height', 48, 'width', 48)
													,"tablestyle"   => "width: 50px; "													
													,"colType" => "image"
												),
							"announcement"      => array(
													"title"   => "Описание",
													"tablestyle"   => "width: 200px;",
													"colType" => "textarea"
													,"description" => "Текст, что показывается в диалоге"
												),												
						),
						"row_seq"			=> array("id", "position", "city_id", "date_from", "date_to", "announcement", "strdate", "title", "image", "event_order")
				);
				
function GetMapHtml () {
	return '
	<style>
.rh{
	position: relative;
	overflow: hidden;
	font-size:8px;
}
.corner{
	position: absolute;
	width: 6px;
	height: 6px;
	z-index: 2;
}
.row_holder{
	background: #fff;	
	width: 256px;
}
.content_menu{
	width: 20px;
	height: 30px;
	background: #2f003e url("/img/content_menu_top.png") 0 0 no-repeat;
	-moz-border-radius: 5px; -webkit-border-radius: 5px;
}
.content_menu span{
	display: block;
	padding: 34px 0 18px 0;
	text-align: center;
}
.content_menu .bottom{
	background: #2f003e url("/img/content_menu_bottom.png") 0 0 no-repeat;
	width: 37.25px;
	height: 21px;
	position: absolute;
	bottom: 0;
	left: 0;
	z-index: 1;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;	
}
.content_text{
	width: 34px;
	height: 10px;
	font: 22px/1.2 Tahoma;
	color: #ce00af;
	border: 0 none !important;
}
.content_text span{
	padding: 0 10px 0 10px;
	font-size: 5px;
}
.content_text strong{
	display: block;
	text-align: right;
}
.img{
	position: absolute;
	border: 1px solid #000;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;	
	font: 9px Tahoma;
	text-align: center;
	cursor: pointer;
}
.layout{ float: left; width: 128px; height: 74px; padding: 0; }
.two_layout{ float: left; width: 256px; height: 73px; padding: 0; }

.img_001{ width: 53px; height: 35px; line-height: 35px;}
.img_002{ width: 10px;  height: 35px; line-height: 35px;}
.img_003{ width: 32px; height: 19px; line-height: 19px;}
.img_004{ width: 32px; height: 19px; line-height: 19px}
.img_005, .img_009, .img_010, .img_011{ width: 10px;  height: 10px; line-height: 10px; }
.img_006{ width: 19px; height: 10px; line-height: 10px; }
.img_007{ width: 31px; height: 32px; line-height: 32px; }
.img_008{ width: 41px; height: 37px; line-height: 37px; }
.img_012{ height:30px; width:20px; line-height: 30px; }
.img_013{ height:35px; line-height:35px; width:20px; }

.xy0 .img_001{left: 0; top: 0;}
.xy0 .img_002{left: 56px; top: 0;}
.xy0 .content_menu, .xy0 .img_012{left: 69px; top: 0;}
.xy0 .img_003{left: 92px; top: 0;}
.xy0 .content_text{left:92px; top:22px; background-color:BlueViolet;}
.xy0 .img_004{left: 0; top: 38px;}
.xy0 .img_005{left: 0; top: 60px;}
.xy0 .img_006{left: 13px; top: 60px;}
.xy0 .img_007{left: 35px; top: 38px;}
.xy0 .img_008{left: 69px; top: 33px;}
.xy0 .img_009{left: 113px; top: 33px;}
.xy0 .img_010{left: 113px; top: 46px;}
.xy0 .img_011{left: 113px; top: 60px;}

.xy2 .img_001 {left: 58px; top: 0;}
.xy2 .img_003{left: 23px; top: 0;}
.xy2 .img_004 {left: 59px; top: 38px;}
.xy2 .img_005 {left: 57px; top: 60px;}
.xy2 .img_006 {left: 71px; top: 60px;}

.xy2 .content_text {left: 23px;top: 22px; background-color:BlueViolet;}
.xy2 .img_007 {left: 93px; top: 38px;}
.xy2 .img_008{left: 0px; top: 33px;}
.xy2 .img_009 {left: 44px; top: 33px;}
.xy2 .img_010 {left: 44px; top: 47px;}
.xy2 .img_011 {left: 44px; top: 60px;}
.xy2 .img_013 {left: 114px; top: 0;}

.xy2 .second_row{left:219px; top:22px; background-color:BlueViolet;}

#event_36 {left:196px; top:0;}
#event_37 {left:137px; top:0;}
#event_38 {left:219px; top:0;}
#event_39 {left:127px; top:38px;}
#event_40 {left:127px; top:60px;}
#event_41 {left:140px; top:60px;}
#event_42 {left:162px; top:38px;}
#event_43 {left:196px; top:33px;}
#event_44 {left:241px; top:33px;}
#event_45 {left:241px; top:46px;}
#event_46 {left:241px; top:59px;}

#hint_form .alert{
	font: bold 10px Tahoma;
	color: #e32322;
	padding: 10px 0 0 0;
}
#load_event{
	margin: 0;	padding: 0; list-style-type: none;
}
#load_event img{
	padding: 4px 4px 2px 4px;
	vertical-align: middle;
}
#load_event li{
	margin: 0;
	cursor: pointer;
}

#hint_form{
	height: 186px;
	overflow-x: hidden;
	overflow-y: auto;
}

#right_holder .ui-draggable .ui-dialog-titlebar-small {
	width: 170px;
}
#right_holder .ui-draggable .ui-dialog-titlebar-small span{
	font: bold 10px Tahoma;
}
#right_holder .ui-dialog{
	width: 190px;
	padding: 0 4px;
}
#right_holder .ui-dialog-small .ui-dialog-titlebar{
	cursor: default;
}
#right_holder .ui-dialog .ui-dialog-buttonpane-small{
	padding: 0;
}
#right_holder .ui-dialog .alert{
	padding-left: 20px;
}
#right_holder .ui-dialog .ui-dialog-content {
	padding: 0;
}
#right_holder{
	width: 200px;
	height: 280px;
	float: right;
}
.load_event{
	background: #eee url("/admin/images/ajax-loader-2.gif") 50% 50% no-repeat !important;
}
#load_event{
	font-size: 11px;
}
#container_holder .ui-state-focus{
	border: 1px solid #ffd27a !important;
}	
.busy {
	background:#e6daed;
}
.curr {
	background:#fbe38f;
}
	</style>

<div class="rh row_holder">
				 <div class="rh layout xy0">
					<div id="event_1" pos="1" class="event img rh img_001">1</div>
					<div id="event_2" pos="2" class="event img rh img_002">2</div>
					<div class="img rh content_menu"> </div>
					<div id="event_3" pos="3" class="event img rh img_003">3</div>
					<div class="img rh content_text"> </div>
					<div id="event_4" pos="4" class="event img rh img_004">4</div>
					<div id="event_5" pos="5" class="event img rh img_005">5</div>
					<div id="event_6" pos="6" class="event img rh img_006">6</div>
					<div id="event_7" pos="7" class="event img rh img_007">7</div>
					<div id="event_8" pos="8" class="event img rh img_008">8</div>
					<div id="event_9" pos="9" class="event img rh img_009">9</div>
					<div id="event_10" pos="10" class="event img rh img_010">10</div>
					<div id="event_11" pos="11" class="event img rh img_011">11</div>
				</div>
				<div class="rh layout xy0">
					<div id="event_12" pos="12" class="event img rh img_001">12</div>
					<div id="event_13" pos="13" class="event img rh img_002">13</div>
					<div id="event_14" pos="14" class="event img rh img_012">14</div>
					<div id="event_15" pos="15" class="event img rh img_003">15</div>
					<div class="img rh content_text"> </div>
					<div id="event_16" pos="16" class="event img rh img_004">16</div>
					<div id="event_17" pos="17" class="event img rh img_005">17</div>
					<div id="event_18" pos="18" class="event img rh img_006">18</div>
					<div id="event_19" pos="19" class="event img rh img_007">19</div>
					<div id="event_20" pos="20" class="event img rh img_008">20</div>
					<div id="event_21" pos="21" class="event img rh img_009">21</div>
					<div id="event_22" pos="22" class="event img rh img_010">22</div>
					<div id="event_23" pos="23" class="event img rh img_011">23</div>
				</div>
			  </div>
		    <div class="rh row_holder">
				<div class="rh two_layout xy2">
					<div id="event_24" pos="24" class="event img rh img_001">24</div>					
					<div id="event_25" pos="25" class="event img rh img_012">25</div>
					<div id="event_26" pos="26" class="event img rh img_003">26</div>
					<div class="img rh content_text"> </div>
					<div id="event_27" pos="27" class="event img rh img_004">27</div>
					<div id="event_28" pos="28" class="event img rh img_005">28</div>
					<div id="event_29" pos="29" class="event img rh img_006">29</div>
					<div id="event_30" pos="30" class="event img rh img_007">30</div>
					<div id="event_31" pos="31" class="event img rh img_008">31</div>
					<div id="event_32" pos="32" class="event img rh img_009">32</div>
					<div id="event_33" pos="33" class="event img rh img_010">33</div>
					<div id="event_34" pos="34" class="event img rh img_011">34</div>									
					<div id="event_35" pos="35" class="event img rh img_013">35</div>
					<div id="event_36" pos="36" class="event img rh img_012">36</div>
					<div id="event_37" pos="37" class="event img rh img_001">37</div>
					<div id="event_38" pos="38" class="event img rh img_003">38</div>
					<div class="img rh content_text second_row"> </div>
					<div id="event_39" pos="39" class="event img rh img_004">39</div>
					<div id="event_40" pos="40" class="event img rh img_005">40</div>
					<div id="event_41" pos="41" class="event img rh img_006">41</div>
					<div id="event_42" pos="42" class="event img rh img_007">42</div>
					<div id="event_43" pos="43" class="event img rh img_008">43</div>
					<div id="event_44" pos="44" class="event img rh img_009">44</div>
					<div id="event_45" pos="45" class="event img rh img_010">45</div>
					<div id="event_46" pos="46" class="event img rh img_011">46</div>
				</div>
			  </div>	
			  
<script>
		$(".event").live("mouseover",function(){$(this).css("borderColor", "#ffd27a");});
		$(".event").live("mouseout",function(){$(this).css("borderColor", "#000000");});
		
		$(".event").live("click",function(){			
			$(".event").removeClass("curr");
			$(this).addClass("curr");
			var pos_id = $(this).attr("pos");
			$(".fieldlist_lang select#position_0 option").attr("selected","");		
			$(".fieldlist_lang select#position_0").find("option[value=\'"+pos_id+"\']").attr("selected", "selected");	
		});	

		var updatePlaybill = function(){
			dtfr = $("#date_from_0").attr("value");
			dtto = $("#date_to_0").attr("value");
			city = $("#city_id_0").attr("value");
			
			$.post("/admin/mGrid/engine/mGridEdit.php",{table:\''.$bases.'\', oper:\'updatePlayBill\', id:id, datefrom:dtfr, dateto:dtto, city:city},function(data){
				$(".event ").removeClass("busy");
				$.each(data, function(key, value) {
					$(".event[pos="+value+"]").addClass("busy");
				});
			},"json");			
		};
		
		$("#date_from_0").change( function(){			
			updatePlaybill();
		});
		$("#date_to_0").change( function(){			
			updatePlaybill();
		});
		$("#city_id_0").change( function(){
			updatePlaybill();
		});		

		$("#position_0").change( function(){
			var value = $(".fieldlist_lang select#position_0").find("option:selected").attr("value");
			$(".event").removeClass("curr");
			$(".event[pos="+value+"]").addClass("curr");
		});		
		
</script>			  
	';
}