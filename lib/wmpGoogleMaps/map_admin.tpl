<html>
    <head>
        <title>Редактор карты</title>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
        <meta name="robots" content="noindex, nofollow">
        <style type="text/css">
            #map_canvas {
                float: left;
                border: 1px solid #a5906b;
                padding: 1px;
                margin: 3px;
                width: 660px;
                height: 560px;
            }		
            body {
                margin:0; 
                background-color: #fcf7d5;
            }
            #gMapTable{
                width: 100%;
            }
            #gMapTable td{
                padding: 5px;
                vertical-align: top;
                text-align: left;
            }
            #gMapTable td label{
                font-size: 16px;
                display: inline-block;
                margin: 16px 0 0;
                cursor: pointer;
            }
            #gMapTable td.input input{
                display: block;
                width: 100%;
                padding: 4px;
                border: 1px solid #ccc;
            }
            #gMapTable td#gMapSubmit{
                vertical-align: bottom;
                text-align: right;
            }			
        </style>
        <script type="text/javascript" src="///code.jquery.com/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="https://maps.google.com/maps/api/js?v=3&sensor=false"></script>
    </head>
    <body>
        <table id="gMapTable">
            <tr>
                <td id="gMapTD" rowspan="2">
                    <div id="map_canvas"></div>
                    <script type="text/javascript">
                        (function()
                        {

                            function toggleBounce()
                            {

                                if (marker.getAnimation() != null)
                                    marker.setAnimation(null);
                                else
                                    marker.setAnimation(google.maps.Animation.BOUNCE);

                            }

                            function changePosition(coords)
                            {
                                $('#coordx').val(coords.latLng.lat());
                                $('#coordy').val(coords.latLng.lng());
                            }

                            new google.maps.Geocoder().geocode({
                                address: '{$gMap->getAddress()}'
                            }, function(geocoder)
                            {
                                map = new google.maps.Map(document.getElementById('map_canvas'), {
                                    center: new google.maps.LatLng(geocoder[0].geometry.location.lat(), geocoder[0].geometry.location.lng()),
                                    zoom: {$gMap->getZoom()},
                                    mapTypeId: google.maps.MapTypeId.ROADMAP
                                });

                        {if $data.marker->getLat() neq null && $data.marker->getLng() neq null}

                                marker = new google.maps.Marker({
                                    map: map
                                    , draggable: true
                                    , animation: google.maps.Animation.DROP
                                    , position: new google.maps.LatLng({$data.marker->getLat()}, {$data.marker->getLng()})
                                });

                                google.maps.event.addListener(marker, 'click', toggleBounce);
                                google.maps.event.addListener(marker, 'dragend', changePosition);
                                map.setCenter(new google.maps.LatLng(geocoder[0].geometry.location.lat(), geocoder[0].geometry.location.lng()));

                                $('#coordx').val({$data.marker->getLat()});
                                $('#coordy').val({$data.marker->getLng()});

                        {else}

                                new google.maps.Geocoder().geocode({
                                    address: '{$data.marker->getAddress()}'
                                }, function(geocoder)
                                {

                                    marker = new google.maps.Marker({
                                        map: map
                                        , draggable: true
                                        , animation: google.maps.Animation.DROP
                                        , position: new google.maps.LatLng(geocoder[0].geometry.location.lat(), geocoder[0].geometry.location.lng())
                                    });

                                    map.setCenter(new google.maps.LatLng(geocoder[0].geometry.location.lat(), geocoder[0].geometry.location.lng()));
                                    alert(geocoder[0].formatted_address);

                                    $('#coordx').val(geocoder[0].geometry.location.lat());
                                    $('#coordy').val(geocoder[0].geometry.location.lng());

                                });
                        {/if}

                            });
                        })();
                    </script>					
                </td>
                <td class="input" width="50%">
                    <label for='coordx' >Координата Х</label>
                    <input id="coordx" name='coordx' type="text" value="">
                    <label for='coordy' >Координата Y</label>
                    <input id="coordy" name='coordy' type="text" value="" >					
                </td>
            </tr>
            <tr>
                <td id="gMapSubmit">
                    <input type="submit" value="Передать >>>" id="gMapSubmitButton">
                    <script type="text/javascript">
                        $('#gMapSubmitButton').click(function()
                        {
                            opener.document.getElementById('{$smarty.request.name}').value = "" + $('#coordx').val() + "," + $('#coordy').val() + "";
                            window.close();
                        })
                    </script>
                </td>
            </tr>
        </table>
    </body>
</html>
