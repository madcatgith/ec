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

		new google.maps.Geocoder().geocode({
			{if $gMap->getLat() neq null and $gMap->getLng() neq null}
				location : new google.maps.LatLng({$gMap->getLat()}, {$gMap->getLng()})
			{else}
  				address : '{$gMap->getAddress()}'
  			{/if}
  		}, function(geocoder)
        {            

			map  = new google.maps.Map(document.getElementById('mainMap'), {
				center           : new google.maps.LatLng(geocoder[0].geometry.location.lat(), geocoder[0].geometry.location.lng()),
				zoom             : {$gMap->getZoom()},
				mapTypeId        : google.maps.MapTypeId.ROADMAP
			});		

    		{if $gMap->hasMarkers()}
				{foreach $gMap->getMarkers() as $k => $m}
  					google.maps.event.addListener(new google.maps.Marker({
						position: new google.maps.LatLng({$m->getLat()}, {$m->getLng()}),
						map: map,
						url: '{$m->getUrl()}',
						title: '{$m->getTitle()|addslashes}',
						zIndex: {$k + 1}
					}), 'click', function()
					{
						window.location.href = ('' + this.url).replace(/\&amp;/g, '&');
					});
				{/foreach}
			{/if}

        });                  
	}
</script>