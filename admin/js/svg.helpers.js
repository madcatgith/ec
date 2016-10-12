Helpers = {
    map : null,
    config : {
        min : 1,
        max : 5,
        step : 0.5
    },
    getCoord : function()
    {

        match = d3.select(this).attr('transform').match(/translate\(([\d\s.,]+)\)/gi)[0].match(/[\d.]+/gi),
                bbox = d3.select(this).node().getBBox();

        return {
            x : parseFloat(bbox.x, 10) + parseFloat(match[0] || 0, 10),
            y : parseFloat(bbox.y, 10) + parseFloat(match[1] || 0, 10)
        };
    },
    setSelected : function()
    {
        ($(this).hasClass('selected') === false) ? $(this).addClass('selected') : $(this).removeClass('selected');
    },
    slideZoom : function(event, ui)
    {
        $('#eventMapSvg #root', Helpers.map).panzoom('zoom', ui.value, {
            animate : true
        });
    },
    brush : function(svg)
    {

        var viewBox = svg.attr('viewBox').split(' ');
        svg.insert('g', 'defs').attr('class', 'brush').call(d3.svg.brush()
                .x(d3.scale.identity().domain([0, viewBox[2]]))
                .y(d3.scale.identity().domain([0, viewBox[3]]))
                .on('brush', function()
                {
                    extent = d3.event.target.extent();
                    panzzom = $('#eventMapSvg #root', Helpers.map).panzoom('instance');
                    matrix = panzzom.getMatrix();
                    // fixX = $('svg', Helpers.map).width() - d3.select('svg').node().getBBox().width;
                    // fixY = $('svg', Helpers.map).height() - d3.select('svg').node().getBBox().height;
                    zoom = {
                        x : parseFloat(matrix[4], 10),
                        y : parseFloat(matrix[5], 10)
                    };
                    node.classed('selected', function()
                    {
                        coord = d3.select(this).datum(),
                                x = coord.x + zoom.x, y = coord.y + zoom.y;
                        return extent[0][0] <= x && x < extent[1][0] && extent[0][1] <= y && y < extent[1][1];
                    });
                }).on('brushend', function()
        {
            d3.event.target.clear();
            d3.select(this).call(d3.event.target);
        }));
        viewBox = null;
    },
    navigation : function()
    {

        $('#eventMapSvg #root', Helpers.map).panzoom({
            minScale : Helpers.config.min,
            maxScale : Helpers.config.max,
            disablePan : true,
            easing : 'linear',
            duration : 400
        });

        $('#slider-vertical div').slider({
            orientation : 'vertical',
            min : Helpers.config.min,
            max : Helpers.config.max,
            step : Helpers.config.step,
            value : Helpers.config.min,
            slide : Helpers.slideZoom,
            change : Helpers.slideZoom
        });

        $('div.navigation-slider a.m-btn', Helpers.map).click(function()
        {

            if ($(this).data('direction') === 'up') {
                $('#slider-vertical div').slider('value', $('#slider-vertical div').slider('value') + Helpers.config.step);
            } else {
                $('#slider-vertical div').slider('value', $('#slider-vertical div').slider('value') - Helpers.config.step);
            }

            return false;
        });

        $('#navigation area').hover(function()
        {
            $('#navigationCircle').addClass($(this).data('direction'));
        }, function()
        {
            $('#navigationCircle').removeClass($(this).data('direction'));
        }).on('click', function()
        {

            var panzoom = $('#eventMapSvg #root', Helpers.map).panzoom('instance'),
                    matrix = panzoom.getMatrix();

            switch ($(this).data('direction')) {
                case 'up':
                    matrix[5] = parseFloat(matrix[5], 10) + 40 * $('#slider-vertical div').slider('value');
                    break;
                case 'down':
                    matrix[5] = parseFloat(matrix[5], 10) - 40 * $('#slider-vertical div').slider('value');
                    break;
                case 'left':
                    matrix[4] = parseFloat(matrix[4], 10) + 40 * $('#slider-vertical div').slider('value');
                    break;
                case 'right':
                    matrix[4] = parseFloat(matrix[4], 10) - 40 * $('#slider-vertical div').slider('value');
                    break;
            }

            panzoom.setMatrix(matrix, {
                animate : true
            });

            return false;
        });
    }
};

$(function()
{
    Helpers.map = $('#svgMap');
});