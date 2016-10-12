(function()
{

    loadMap = function(callback)
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

        $('#eventMapSvg', Helpers.map).append(xml.documentElement);

        loadMap(function(data)
        {

            svg = d3.select('#eventMapSvg svg');
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
                var el = svg.select('#p' + v.placeID);
                el.datum($.extend(el.datum(), {
                    id : parseInt(v.id, 10),
                    row : parseInt(v.row, 10),
                    ceil : parseInt(v.ceil, 10)
                }));
                el = null;
            });

            Helpers.brush(svg);
            Helpers.navigation();

            svg.selectAll('#root g[place]').on('click', function()
            {

                var node = d3.select(this);

                if (svg.selectAll('#root g[place].selected').size() === 1) {
                    $('#placeRow').val(node.datum().row);
                    $('#placeCeil').val(node.datum().ceil);
                } else {
                    $('#placeRow').val('');
                    $('#placeCeil').val('');
                }
            }).on('mouseover', function()
            {

                var node = d3.select(this);

                $(this).tooltip({
                    title : 'Ряд: ' + node.datum().row + '; Место: ' + node.datum().ceil,
                    container : 'body'
                }).tooltip('show');

                node = null;

            }).on('mouseleave', function()
            {
                $(this).tooltip('destroy');
            });

            $('#savePlace').on('click', function()
            {
                
                row = $('#placeRow').val().match(/([\d]+)/gi);
                ceil = $('#placeCeil').val().match(/([\d]+)/gi);
                
                if (svg.selectAll('#root g[place].selected').size() === 0 || row === null || ceil === null) {
                    $('#modalPlace').modal('show');
                } else {
                    
                    row = parseInt(row[0], 10);
                    ceil = parseInt(ceil[0], 10);
                    data = {
                        row : row,
                        ceil : ceil,
                        places : []
                    };

                    $.each(usort(svg.selectAll('#root g[place].selected').data(), function(a, b)
                    {
                        return a.x < b.x ? -1 : 1;
                    }), function(index, place) 
                    {
                        data.places.push(place.id);
                    });

                    $.post('/admin/activity/request.php?fn=place/update', {
                        uri : location.pathname,
                        data : data
                    }, function(data)
                    {
                        if (data.action) {
                            
                            $.each(data.places, function(index, place)
                            {
                                var el = svg.select('#p' + place.placeID);
                                
                                el.datum($.extend(el.datum(), {
                                    row : parseInt(place.row, 10),
                                    ceil : parseInt(place.ceil, 10)
                                }));
                                el.select('text').remove();
                                el.append(place.element).attr(place.attributes).text(place.value);

                                el = null;
                            });
                            
                            svg.selectAll('#root g[place]').attr('class', '');
                        }
                    }, 'json');
                }
            });

            $('#eventMapLoader').remove();

        });
    });
})();