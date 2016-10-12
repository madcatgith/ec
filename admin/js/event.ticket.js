(function()
{

    var loadMap = function(callback)
    {
        $.post('/admin/activity/request.php?fn=ticket/get/all', {
            uri : location.pathname
        }, function(data)
        {
            if ($.isFunction(callback)) {
                callback(data);
            }
        }, 'json');
    }, loadHint = function(callback)
    {
        $.post('/admin/activity/request.php?fn=place/get/all', {
            uri : location.pathname
        }, function(data)
        {
            if ($.isFunction(callback)) {
                callback(data);
            }
        }, 'json');
    };

    
    d3.xml($('#svgMap').data('src'), 'image/svg+xml', function(xml)
    {

        $('#eventMapSvg').append(xml.documentElement);

        loadMap(function(data)
        {

            svg = d3.select('#eventMapSvg svg'),
            node = svg.selectAll('g[place]').datum(Helpers.getCoord).on('mousedown', Helpers.setSelected);

            d3.selectAll('#eventMapSvg svg > g').attr('class', 'node');
            svg.insert('g').attr({
                id : 'root'
            });

            d3.selectAll('#eventMapSvg svg > g.node').each(function()
            {
                document.getElementById('root').appendChild(this);
            });

            $.each(data.data, function(i, v)
            {
                svg.select('#p' + v.placeID).attr('class', 'bg' + v.priceGroupID);
            });

            Helpers.brush(svg);
            Helpers.navigation();

            loadHint(function(data)
            {

                $.each(data.data, function(i, v)
                {
                    svg.select('#p' + v.placeID).datum({
                        id : v.id,
                        row : v.row,
                        ceil : v.ceil
                    });
                });

                node.on('mouseover', function()
                {

                    var data = d3.select(this).datum();

                    $(this).tooltip({
                        title : 'Ряд: ' + data.row + '; Место: ' + data.ceil,
                        container : 'body'
                    }).tooltip('show');

                    data = null;

                }).on('mouseleave', function()
                {
                    $(this).tooltip('destroy');
                });
                
                $('#eventMapLoader').remove();
            });

            $('#replacePrice').click(function()
            {

                place = [];

                svg.selectAll('g.selected').each(function()
                {
                    place.push($(this).attr('place'));
                });

                if (place.length) {
                    $.post('/admin/activity/request.php?fn=ticket/replace', {
                        uri : location.pathname,
                        data : {
                            place : place,
                            price : $('#priceGroup').val()
                        }
                    }, function(data)
                    {

                        $.each(place, function(i, v)
                        {
                            svg.select('#p' + v).attr('class', '');
                        });

                        $.each(data.data, function(i, v)
                        {
                            svg.select('#p' + v.placeID).attr('class', 'bg' + v.priceGroupID);
                        });

                        $('#modalPlace').modal('hide');

                    }, 'json');
                } else {
                    $('#placeValue').val('');
                    $('#modalPlace').modal('hide');
                }
            });
        });
    });
})();