<div id="gMap"></div>
{if count($data.coords) eq 2}
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript">
        map = new google.maps.Map($('#gMap').get(0), {
            center: new google.maps.LatLng({$data.coords[0]}, {$data.coords[1]}),
            zoom: {$gMap->getZoom()},
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: new google.maps.LatLng({$data.coords[0]}, {$data.coords[1]})
        });
    </script>
{/if}