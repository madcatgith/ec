<div class="row" id="mediaRow-{$mediaID}">
    {foreach $elements as $element}
        <div class="imageCell" data-id="{$element[0].id}">
            <div class="thumbnail">
                <div class="thumbnail-holder">
                    {if $element[0].isImage}
                        <img src="{$element[0].src}" />
                    {/if}
                </div>
                <div class="caption">
                    <div class="checkbox-inline">
                        <label>
                            <input name="deleteElements" value="{$element[0].id}" type="checkbox"> Удалить
                        </label>
                    </div>
                    <div class="navbar-right">
                        <span class="glyphicon glyphicon-resize-vertical"></span>
                        <a class="elementEdit" data-id="{$element[0].id}" href="javascript:void(0);"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                    </div>
                </div>
            </div>
          </div>
    {/foreach}
</div>
<script>
    $(function() {
        $('#mediaRow-{$mediaID}').sortable({
            handle: '.glyphicon-resize-vertical',
            update: function() {
                var ids = [];
                $('#mediaRow-{$mediaID} .imageCell').each(function() {
                    ids.push({
                        id: $(this).data('id'),
                        place: $('#mediaRow-{$mediaID} .imageCell').index(this) + 1
                    });
                });

                $.post('/admin/module/request.php?fn=media/element/sort', {
                    uri: location.pathname,
                    ids: ids
                }, function(data) {
                }, 'json');

                delete ids;
            }
        });
    });
</script>