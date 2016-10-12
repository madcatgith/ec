<script type="text/javascript">
    $('#contacts .gMap').each(function()
    {
        $(this).append($(document.createElement('img')).attr({
            src : "http://maps.googleapis.com/maps/api/staticmap?center=" + $(this).data('coords') + "&zoom={$gMap->getZoom()}&size=" + $(this).width() + "x" + $(this).height() + "&sensor=false&markers=color:blue%7C" + $(this).data('coords'),
            alt : $(this).data('title'),
            title : $(this).data('title')
        }));
    });
</script>