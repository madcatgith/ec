<div id="map_{$ID}" style="width:{$gMap->getWidth()}px;height:{$gMap->getHeight()}px;"></div>
<script type="text/javascript">
    $(function() {
        map = new google.maps.Map(document.getElementById('map_{$ID}'), {
            center    : new google.maps.LatLng({$gMap->getLat()}, {$gMap->getLng()}),
            zoom      : {$gMap->getZoom()},
            mapTypeId : google.maps.MapTypeId.ROADMAP
        });	
        new google.maps.Marker({
            position: new google.maps.LatLng({$gMap->getLat()}, {$gMap->getLng()}),
            map: map
        });
    });
</script>