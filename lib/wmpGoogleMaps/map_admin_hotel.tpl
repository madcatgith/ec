<style type="text/css">
    #mapCanvas {
        border: 1px solid #a5906b;
        padding: 1px;
        margin: 2px;
        width: 600px;
        height: 350px;
        background: #f2f2f2;
        position: relative;
        z-index: 1;
    }
    #gAddress{
        border: 1px solid #a5906b;
        width: 556px;
        font-size: 14px;
        padding: 2px;
    }
    #gButton {
        font-size: 14px;
        padding: 2px;
        width: 58px;
        cursor: pointer;
    }
</style>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<input type="hidden" id="{$data.name}" name="{$data.name}" value="{$data.value|escape}" />
<div id="mapCanvas"></div>
<script type="text/javascript">
$('#coordsRow').on('show', function()
{
    function toggleBounce()
    {
        if (marker.getAnimation() !== null) {
            marker.setAnimation(null);
        } else {
            marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    }

    function changePosition(coords)
    {
        $('#{$data.name}').val(coords.latLng.lat() + ';' + coords.latLng.lng());
    }

    new google.maps.Geocoder().geocode({
        address: '{$gMap->getAddress()}'
    }, function(geocoder)
    {

        map = new google.maps.Map($('#mapCanvas').get(0), {
            center: new google.maps.LatLng(geocoder[0].geometry.location.lat(), geocoder[0].geometry.location.lng()),
            zoom: {$gMap->getZoom()},
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        {if count($gMap->getMarkers())}
            {foreach $gMap->getMarkers() as $marker}
                marker = new google.maps.Marker({
                    map: map,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    position: new google.maps.LatLng({$marker->getLat()}, {$marker->getLng()})
                });
                map.setCenter(new google.maps.LatLng({$marker->getLat()}, {$marker->getLng()}));
            {/foreach}
        {else}
            marker = new google.maps.Marker({
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                position: new google.maps.LatLng(geocoder[0].geometry.location.lat(), geocoder[0].geometry.location.lng())
            });
            map.setCenter(new google.maps.LatLng(geocoder[0].geometry.location.lat(), geocoder[0].geometry.location.lng()));
        {/if}

        google.maps.event.addListener(marker, 'click', toggleBounce);
        google.maps.event.addListener(marker, 'dragend', changePosition);

        $('{$data.search}').click(function()
        {
            new google.maps.Geocoder().geocode({
                address: $.trim($('{$data.title} option:selected').text()) + ', ' + $(this).prev().val()
            }, function(geocoder)
            {
                marker.setPosition(new google.maps.LatLng(geocoder[0].geometry.location.lat(), geocoder[0].geometry.location.lng()));
                map.setCenter(new google.maps.LatLng(geocoder[0].geometry.location.lat(), geocoder[0].geometry.location.lng()));
                map.setZoom({$data.zoom});
                $('#{$data.name}').val(geocoder[0].geometry.location.lat() + ';' + geocoder[0].geometry.location.lng());
            });
        });
    });
});
</script>