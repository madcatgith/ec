<div id="mainMap" class="exPage" style="width: 100%; height: 600px;" ></div>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">

	
	var MY_MAPTYPE_ID = 'coalMap';

	var script  = document.createElement("script");
    script.src  = "http://www.google.com/jsapi?callback=loadGoogleMapsApi";
    script.type = "text/javascript";
    
    document.getElementsByTagName("head")[0].appendChild(script);
    
    loadGoogleMapsApi = function()
    {
		google.load("maps", "3", {
			'callback'       : 'gMap'
			, 'other_params' : 'sensor=false&language={Lang::getAliasByID(Lang::getID())}'
		});
	}

	gMap = function()
	{

		new google.maps.Geocoder().geocode({
			address : '{$gMap->getAddress()}'
  		}, function(geocoder)
        {            

			map  = new google.maps.Map(document.getElementById('mainMap'), {
				center     : new google.maps.LatLng(geocoder[0].geometry.location.lat(), geocoder[0].geometry.location.lng()),
				zoom       : {$gMap->getZoom()},
				mapTypeId  : google.maps.MapTypeId.ROADMAP
			});

  			polyline = new google.maps.Polyline({
							strokeColor   : '#000000',
							strokeOpacity : 1,
							strokeWeight  : 1,
							map           : map,
							path          : [
  					{foreach $data as $str}
  						{$arr = explode(' ', $str)}
							new google.maps.LatLng({$arr.0}, {$arr.1}),
  					{/foreach}
  					{$arr = explode(' ', $data[0])}
  					new google.maps.LatLng({$arr.0}, {$arr.1})
  				]
  			});  		

  			new google.maps.Marker({
				position: new google.maps.LatLng(50.432602, 30.520205),
				map: map,
				zIndex: 1,
			});

        });                  
	}

</script>