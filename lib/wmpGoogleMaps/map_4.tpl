<div id="mainMapHolder">
	<div id="mainMap"></div>
</div>
<div id="mapButtonHolder">
	<a id="mapButton" href="javascript:void(0);"><span>{Dictionary::GetUniqueWord(528)}</span></a>
</div>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">

	var script  = document.createElement("script");
    script.src  = "http://www.google.com/jsapi?callback=loadGoogleMapsApi";
    script.type = "text/javascript";
    
    document.getElementsByTagName("head")[0].appendChild(script);
    
    loadGoogleMapsApi = function()
    {
		google.load("maps", "3", {
			'callback'       : 'gMap'
			, 'other_params' : 'sensor=false&amp;language={Lang::getAliasByID(Lang::getID())}'
		});
	}

	gMap = function()
	{

		map  = new google.maps.Map(document.getElementById('mainMap'), {
			center    : new google.maps.LatLng({$gMap->getLat()}, {$gMap->getLng()}),
			zoom      : {$gMap->getZoom()},
			mapTypeId : google.maps.MapTypeId.ROADMAP
		});		

		{foreach $data as $k => $h}
  			new google.maps.Marker({
				position: new google.maps.LatLng({$h->getLatitude()}, {$h->getLongitude()}),
				map: map,
				title: '{$h->getName()|htmlspecialchars}',
				zIndex: {$k + 1}
			});
		{/foreach}

	}

</script>