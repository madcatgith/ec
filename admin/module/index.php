<?php
$include = include ($_SERVER['DOCUMENT_ROOT'] . "/admin/admin_top.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

AUrl::parse(getenv('REQUEST_URI'));

define('URL', '/admin/module/'. AUrl::get('module'));
define('VIEW', APP . '/view/' . AUrl::get('module') . '/' . AUrl::get('controller') . '/');

?>
<link rel="stylesheet" type="text/css" title="text style" href="/admin/css/bootstrap.min.css" />
<script type="text/javascript" src="/admin/js/bootstrap.min.js"></script>
<div class="container">
    <?php
    try {
        if(!AUrl::get('module') || !is_dir(APP . '/controller/'. AUrl::get('module') .'/')) {
            throw new Exception('Module does not exist!');
        }
        if (class_exists($class = ucfirst(AUrl::get('module')) . '\\' . ucfirst(AUrl::get('controller')) . 'Controller')) {
            $controller = new $class;
            if (method_exists($controller, $action = 'action' . ucfirst(AUrl::get('action')))) {
                echo $controller->$action();
            } else {
                throw new Exception('Action does not exist!');
            }
        } else {
            throw new Exception('Controller does not exist!');
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    ?>
</div>
<div class="popup-holder">
    <div class="modal fade" id="modalRemove" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">	
                <div class="modal-body">
                    <button class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title">Вы действительно хотите удалить запись?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary btn-yes">Да</button>
                </div>
            </div>
        </div>
    </div>	
    <div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">	
                <div class="modal-body">
                    <button class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function()
    {
        $('#tabs a, .langs a').click(function()
        {
            $(this).tab('show');
        });
        $('#tabs a:first').tab('show');
        $('.langs').each(function() {
            $(this).find('a').eq(0).tab('show');
        });

        if (document.location.hash) {
            $('#tabs a[href="' + document.location.hash + '"]').click();
        }

        _c = function(a) {
            return $(document.createElement(a))
        };

        $(document).on('click', '.addRow', function()
        {
            var lang = $(this).data('lang');

            $('.multyValues[data-lang="' + lang + '"]').append(
                    _c('div').append(_c('input').attr({
                'type': 'text',
                'class': 'form-control',
                'placeholder': 'Значение'
            })).append(_c('span').addClass('glyphicon glyphicon-resize-vertical'))
                    );

            updateNames(lang);
        });

        $('.multyValues').each(function()
        {
            $(this).sortable({
                handle: '.glyphicon-resize-vertical',
                update: function(event, ui)
                {
                    updateNames($(ui.item).closest('.multyValues').data('lang'));
                }
            });
        });

        updateSort = function()
        {
            var ids = [];
            $('.table-elements tbody tr').each(function()
            {
                ids.push({
                    id: $(this).data('id'),
                    place: $('.table-elements tbody tr').index(this) + 1
                });
            });

            $.post('/admin/module/request.php?fn=<?=AUrl::get('module')?>/<?=AUrl::get('controller')?>/sort', {
                uri: location.pathname,
                ids: ids
            }, function(data) {
            }, 'json');

            delete ids;
        };

        $('.table-elements tbody').sortable({
            handle: '.glyphicon-resize-vertical',
            update: updateSort
        });

        updateNames = function(lang)
        {
            $('.multyValues[data-lang="' + lang + '"] div').each(function(i)
            {
                $(this).data('row', i).find('input').attr('name', 'data[' + lang + '][value][' + i + ']');
            });
        };

        $(document).on('click', '.delRow', function()
        {
            var lang = $(this).data('lang');

            if ($('.multyValues[data-lang="' + lang + '"] div').size() > 1) {
                $('.multyValues[data-lang="' + lang + '"] div:last').remove();
            }
        });

        $('a.removeData').on('click', function()
        {
            var self = $(this);

            $('#modalRemove .btn-yes').off('click').on('click', function()
            {
                $.post('/admin/module/request.php?fn=' + self.data('action'), {
                    uri: location.pathname,
                    id: self.data('id')
                }, function(data) {
                    $('#modalRemove').modal('hide');
                    if (data.action) {
                        location.reload();
                    }
                }, 'json');
            });

            $('#modalRemove').modal('show');
        });
        
        if($('#filter').length) {
            var label = {'contentType': 'Контент', 'iblockType': 'Инфоблок', 'productType': 'Товары'};
            $('#filter').on('change', '.type select', function() {       
                var filterID = $(this).closest('.filterHolder').data('filter'), 
                    cont = $('#filter .filterHolder[data-filter="'+ filterID +'"]'),
                    type = $(this).val(), mType = cont.find('.menuType'), 
                    pType = cont.find('.pType'), cType = cont.find('.contentType'), 
                    ibType = cont.find('.iblockType');

                $('#filterAdd').prop('disabled', true);

                if(!type) {
                    unSelect([mType, cType, pType, ibType]);
                    return false;
                } else {      
                    unSelect([mType]);

                    var current = cont.find('.'+ type);

                    if(type === 'contentType') {
                        unSelect([pType, ibType]);
                    } else if(type === 'iblockType') {
                        unSelect([cType, pType]);
                    } else if(type === 'productType') {
                        unSelect([cType, ibType]);
                    } else {
                        unSelect([cType, pType, ibType]);
                    }

                    var options = [];
                    if(type === 'menuType') {
                        for(m in data['menuType']) {
                            options.push(_c('option').attr('value', data['menuType'][m].id).text(data['menuType'][m].title));
                        }
                    } else {
                        for(m in data['menuType']) {
                            options.push(_c('option').attr({
                                'value': data['menuType'][m].id,
                                'disabled': (typeof data[type][data['menuType'][m].id] !== "undefined" ? false : true)
                            }).text(data['menuType'][m].title));
                        }            
                    }
                    mType.addClass('active').find('select').empty().append(options).multiselect({
                        buttonText: function(options, select) {
                            if (options.length === 0) {
                                return 'Пункты меню';
                            } else {
                                return 'Элементов - '+ options.length;
                            }
                        },
                        onChange: function(element, checked) {
                            if(type === 'menuType') {
                                $('#filterAdd').prop('disabled', (current.find('select option:selected').length ? false : true));
                                return false;
                            }

                            if(mType.find('select option:selected').length === 0) {
                                unSelect([$('#filter .'+ type)]);
                                $('#filterAdd').prop('disabled', true);
                                return false;
                            }

                            var IDs = getSelected(mType), typeOptions = [];

                            for(i in IDs) {
                                for(y in data[type][IDs[i]]) {
                                    typeOptions.push(_c('option').attr('value', data[type][IDs[i]][y].id).text(data[type][IDs[i]][y].title));
                                }
                            }

                            current.addClass('active').find('select').empty().append(typeOptions).multiselect('destroy').multiselect({
                                buttonText: function(options, select) {
                                    if (options.length === 0) {
                                        return label[type];
                                    } else {
                                        return 'Элементов - '+ options.length;
                                    }
                                },
                                onChange: function(element, checked) {
                                    $('#filterAdd').prop('disabled', (current.find('select option:selected').length ? false : true));
                                }
                            });
                            delete IDs; delete typeOptions;
                        }
                    });
                    delete options; 
                }
                delete filterID;delete type;delete mType;delete cType;delete ibType;delete pType;delete cont;delete current;
            });

            $('#filterAdd').on('click', function() {
                var ID = +$('#filter .filterHolder:last').data('filter') + 1;
                $('#filterTpl .filterHolder').clone(true, true).attr('data-filter', ID).appendTo('#filter');
                changeSelect(ID);
                $(this).prop('disabled', true);
                delete ID;
            });
  
            var unSelect = function(data) {
                $.each(data, function(i, obj) {
                    if(obj.hasClass('active')) {
                        obj.find('select').multiselect('destroy').end().removeClass('active');
                    }                
                });

                return false;
            }, getSelected = function(obj) {
                var IDs = [];
                obj.find('select option:selected').each(function() {
                    IDs.push($(this).val());
                });
                return IDs;
            }, changeSelect = function(ID) {
                $('#filter .filterHolder[data-filter="'+ ID +'"] select').each(function() {
                    var name = $(this).attr('name');
                    $(this).attr('name', name.replace('X', ID));
                });
                return false;
            };
            
            if($('#filter .filterHolder.updateFilter').length) {
                $('#filter .filterHolder.updateFilter').each(function() {
                    var type = $(this).find('.type select option:selected').val(),
                        current = $(this).find('.'+ type),
                        menuIDs = getSelected($(this).find('.menuType'));
                        
                    $(this).find('.type select').trigger('change');
                    $(this).find('.menuType select').multiselect('select', menuIDs); 
                    delete menuIDs;
                    
                    if(type !== 'menuType') {
                        var currentIDs = getSelected(current);
                        $(this).find('.menuType select').trigger('change');
                        current.find('select').multiselect({                      
                            buttonText: function(options, select) {
                                if (options.length === 0) {
                                    return label[type];
                                } else {
                                    return 'Элементов - '+ options.length;
                                }
                            },
                            onChange: function(element, checked) {
                                $('#filterAdd').prop('disabled', (current.find('select option:selected').length ? false : true));
                            }
                        }).multiselect('select', currentIDs);
                        delete currentIDs;
                    }
                    delete current; delete type;
                });
            }
        }
        
    });
</script>
<?
if(Registry::get('db')->errorCode() && Registry::get('db')->errorCode() != '00000') {
    _d(Registry::get('db')->errorInfo());
}
?>
