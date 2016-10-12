var fArray = new Array();

var classPlace = function(){

}

classPlace.prototype = {
		id : 0, // айди места 
		pg : 0, // классификация/ценовая группа (price_group)
		status : 4, // статус доступность, квотирование, выделение
		tstatus : 0, // последний статус
        //координата х-->
		//координата у-->
		//угол-->
		//масштаб-->
		//номер картинки элемента из cfg.xml <pics> (начиная от 1)-->
		//<!--    номер (ряд, место) или подпись (текст в кавычках)-->
		color : '0xOOOOOO', //<!--    цвет-->
		visibility : 1, //<!--    видимость-->
	    number : 0, //номер-->
	    reserved : 0
};

var Flash = function(options){

	 var options = $.extend({
    				id : "myFlash", 
    				map_id : 0, 
    				hall_id : 0, 
    				event_id : 0, 
    				session_id : 0, 
    				time : 0, 
    				lang : 1, 
    				cfg : "", 
    				flashID : 0,
    				scheme_id : 0,
    				created : 0
  				},options);
	  				
	this.id = options.id;
	this.map_id = options.map_id;
	this.hall_id = options.hall_id;
	this.event_id = options.event_id;
	this.session_id = options.session_id;
	this.time = options.time;
	this.lang = options.lang;
	this.cfg  = options.cfg;
	this.flashID = options.flashID;
	this.scheme_id = options.scheme_id;
	this.created = options.created;
	
	this.init();
		
	this.status = { reserved : '0xd0d1d3',  sold : '0xee1c25', free : '0x009600' };
}

Flash.prototype = {
	
	//массив мест по ценновым группам
	priceGroupHolder : new Array(),
	
	place : new Array(),
			
	//вставка флешки
	init : function(){ swfobject.embedSWF("/files/7/map.swf?" + this.time, this.id, "691", "480", "9.0.0", "", {"map":{ "id" : this.map_id, "event" : this.event_id}, "flashID" : this.flashID}, {"wmode":"transparent"}, ""); },
	
	//получение флешки как объекта
	getFlash : function(){ this.movie = (navigator.appName.indexOf("Microsoft")!=-1 ? window : document)[this.id] || swfobject.getObjectById(this.id); },
	
	//Заставляет флешку включить множественное выделение
	SetAdmin : function(){ this.movie.SetAdmin(); },
	
	//Загрузка карты
	SetMapFromJS : function(){
		
		this.place = new Array();
		
		tThis = this;
		
		//Получение xml карты мест
		$.post("/admin/hall-xml.php?" + this.time, {lang_id : this.lang, hall_id : this.hall_id, map_id  : this.map_id,	session_id : this.session_id, event_id : this.event_id, scheme_id : this.scheme_id, created : this.created}, function(data){
			if(data){
				
      			$(data).find("p").each(function(){

      				placeArray = $(this).text().split(";");

      				tThis.place[placeArray[0]] = new classPlace;

      				tThis.place[placeArray[0]].id = placeArray[0];
      				tThis.place[placeArray[0]].pg = placeArray[1];
      				tThis.place[placeArray[0]].status = placeArray[2];
      				tThis.place[placeArray[0]].tstatus = placeArray[2];
      				tThis.place[placeArray[0]].color = placeArray[10];
      				tThis.place[placeArray[0]].visibility = placeArray[11];
      				tThis.place[placeArray[0]].number = placeArray[12];
      				tThis.place[placeArray[0]].reserved = (placeArray[2] == 1)? 1 : 0;
      				
      	    	});
      	    	
				tThis.movie.SetMapFromJS(tThis.cfg, data, "/files/7/materials/");				
			}
		}, "html");
		
	},
	
	addUpdate : function(str){
		this.movie.UpdateobjectsFromJS('<update>' + str + '</update>');
	},
	
	addReserve: function(idList){

		xmlLixt = '';		
		tArray  = idList.split(';');

		for (var key in tArray){
			this.place[tArray[key]].reserved = 1;
			this.place[tArray[key]].pg       = 0;
			xmlLixt += '<p>' + tArray[key] + ';;1;;;;;;;;;</p>';
		}

		this.addUpdate(xmlLixt);
	},

	removeReserve: function(idList){

		xmlLixt = '';		
		tArray  = idList.split(';');
		
		for (var key in tArray){		
			this.place[tArray[key]].pg       = 0;
			this.place[tArray[key]].reserved = 0;
			xmlLixt += '<p>' + tArray[key] + ';;4;;;;;;;' + this.status.free + ';;</p>';
		}
			
		this.addUpdate(xmlLixt);
	},
	
	addPriceGroup: function(idList, id, color){
			
		xmlLixt = '';
		tArray  = idList.split(';');
	
		for (var key in tArray){			
			this.place[tArray[key]].pg       = id;
			this.place[tArray[key]].color    = color;
			this.place[tArray[key]].reserved = 0;
			xmlLixt += '<p>' + tArray[key] + ';;;;;;;;;0x' + color + ';;</p>';
		}			
				
		this.addUpdate(xmlLixt);
	},
	
	getPGPlace : function(){

		tempString = '';

		for (var key in this.place){	
			if( this.place[key].reserved == 0){		
				tempString += (tempString == '') ? '"' + key + '":"' + this.place[key].pg + '"' : ',"' + key + '":"' + this.place[key].pg + '"';
			}
		}

		return '{' + tempString + '}';

	},
	
	getPGReserved : function(){

		tempString = '';

		for (var key in this.place){	
			if( this.place[key].reserved == 1){
				tempString += (tempString == '') ? '"' + key + '":"1"' : ',"' + key + '":"1"';
			}
		}

		return '{' + tempString + '}';

	},
		
	//Запрос готовности карты из JS.
	SendIsMapReady : function(){
		return this.movie.IsMapReady();
	},

	//запрашивает у Flash список выделенных мест, ids_str  - строка айдишников мест (разделитель – “;”)
	SendGetSelectionFromJS : function(){
		return this.movie.GetSelectionFromJS();
	},

	//Обновление статуса мест
	//update_str - строка айдишников и их статусов,
	//где на нечетном месте отображается id клипа,
	//а на четном – статус (разделитель – “;”)
	SendUpdatePlacesStatus : function(update_str){
		this.movie.UpdatePlacesStatus(update_str);
	},


	//принудительно задает местам из ids_str эффект бликования,
	//остальные места – не бликуют
	//ids_str  - строка айдишников мест (разделитель – “;”)
	SendSetBlikFromJS : function(ids_str){
		this.movie.SetBlikFromJS(ids_str)
	}

}

// Flash2Js

var MovieIsReady = function(flashID){		
	fArray[flashID].getFlash()
	fArray[flashID].SetMapFromJS();	
}

var MapIsReady = function(flashID){
	fArray[flashID].movie.SetPanelVisible(0);  
	fArray[flashID].SetAdmin();
}