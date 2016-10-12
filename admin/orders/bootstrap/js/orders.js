$(function()
{

    $('#collapse').click(function()
    {
        if ($(this).data('active')) {
            $(this).data('active', false).find('i').removeClass('icon-minus');
            $('a[data-link].active').click();
        } else {
            $(this).data('active', true).find('i').addClass('icon-minus');
            $('a[data-link]').not('.active').click();
        }

    });

    $('button[data-link]').click(function()
    {
        
        console.log(1);

        if ($(this).data('active')) {
            $('tr[data-link=' + $(this).data('link') + ']').hide();
            $(this).data('active', false).removeClass('active').find('i').removeClass('icon-minus');
        } else {
            $('tr[data-link=' + $(this).data('link') + ']').show();
            $(this).data('active', true).addClass('active').find('i').addClass('icon-minus');
        }

        if ($('button[data-link]').filter('.active').size() === 0) {
            $('#collapse').data('active', false).find('i').removeClass('icon-minus');
        } else if ($('button[data-link]').filter('active').size() === $('button[data-link]').size()) {
            $('#collapse').data('active', true).find('i').addClass('icon-minus');
        }
    });

    if (location.hash) {
        $('a[href="' + location.hash + '"]').click();
    }

    $('input[data-checkgroup]').change(function()
    {
        switch ($(this).data('checkgroup')) {
            case 'all':
                if ($(this).is(':checked')) {
                    $('input[name^="checkgroup[order]"]').prop('checked', true);
                } else {
                    $('input[name^="checkgroup[order]"]').prop('checked', false);
                }
                break;
            case 'inner':
                if ($(this).is(':checked')) {
                    $(this).closest('table').find('input[name^="checkgroup[cart]"]').prop('checked', true);
                } else {
                    $(this).closest('table').find('input[name^="checkgroup[cart]"]').prop('checked', false);
                }
                break;
        }
    });

    $('div.date').each(function()
    {
        var checkin = $(this).datepicker().on('changeDate', function()
        {
            checkin.hide();
        }).data('datepicker');
    });

    $('#onPage').change(function()
    {
        $(this).closest('form').submit();
    });

    var _c = function(el)
    {
        return $(document.createElement(el));
    };
    String.prototype.repeat = function(num)
    {
        return new Array(num + 1).join(this);
    };
    showProcessing = function(process)
    {

        var i = 0,
                modal = _c('div')
                .attr({id : 'showProcessing-js'})
                .addClass('modal hide fade md-modal')
                .append(_c('div').addClass('modal-body').html(_c('div').addClass('md-icon md-sync').html('Обработка<i>.</i>')))
                .appendTo('body')
                .modal({
            backdrop : 'static',
            keyboard : false
        })
                .modal('show')
                .on('hidden', function()
        {
            clearInterval(loadID);
            modal.remove();
            delete modal;
        }), loadID = setInterval(function()
        {
            modal.find('.modal-body i').text('.'.repeat(1 + ++i % 3));
        }, 400);

        process.always(function()
        {
            modal.modal('hide');
        });
    };
    showWarning = function(text)
    {
        _c('div').attr({id : 'showWarning-js'}).addClass('modal hide fade md-modal').append(_c('div').addClass('modal-header').append(_c('button').attr({
            type : 'button',
            'data-dismiss' : 'modal',
            'aria-hidden' : true
        }).addClass('close').html('&times;'))).append(_c('div').addClass('modal-body').html(_c('div').addClass('md-icon md-warning').html(text)))
                .append(_c('div').addClass('modal-footer')
                .append(_c('button').attr({
            type : 'button',
            'data-dismiss' : 'modal',
            'aria-hidden' : true
        }).addClass('btn').text('Закрыть'))).appendTo('body').modal({
            backdrop : 'static'
        }).modal('show').on('hidden', function()
        {
            $(this).remove();
        });
    };
    showError = function(text, callback)
    {
        _c('div').attr({id : 'showError-js'}).addClass('modal hide fade md-modal').append(_c('div').addClass('modal-header').append(_c('button').attr({
            type : 'button',
            'data-dismiss' : 'modal',
            'aria-hidden' : true
        }).addClass('close').html('&times;'))).append(_c('div').addClass('modal-body').html(_c('div').addClass('md-icon md-error').html(text)))
                .append(_c('div').addClass('modal-footer')
                .append(_c('button').attr({
            type : 'button',
            'data-dismiss' : 'modal',
            'aria-hidden' : true
        }).addClass('btn').text('Закрыть'))).appendTo('body').modal({
            backdrop : 'static'
        }).modal('show').on('hidden', function()
        {
            $(this).remove();
            if ($.isFunction(callback)) {
                callback();
            }
        });
    };
    showInfo = function(text)
    {
        _c('div').attr({id : 'showInfo-js'}).addClass('modal hide fade md-modal').append(_c('div').addClass('modal-header').append(_c('button').attr({
            type : 'button',
            'data-dismiss' : 'modal',
            'aria-hidden' : true
        }).addClass('close').html('&times;'))).append(_c('div').addClass('modal-body').html(_c('div').addClass('md-icon md-info').html(text)))
                .append(_c('div').addClass('modal-footer')
                .append(_c('button').attr({
            type : 'button',
            'data-dismiss' : 'modal',
            'aria-hidden' : true
        }).addClass('btn').text('Закрыть'))).appendTo('body').modal({
            backdrop : 'static'
        }).modal('show').on('hidden', function()
        {
            $(this).remove();
        });
    };
    showQuestion = function(text, button)
    {
        _c('div').attr({id : 'showQuestion-js'}).addClass('modal hide fade md-modal').append(_c('div').addClass('modal-header').append(_c('button').attr({
            type : 'button',
            'data-dismiss' : 'modal',
            'aria-hidden' : true
        }).addClass('close').html('&times;'))).append(_c('div').addClass('modal-body').html(_c('div').addClass('md-icon md-question').html(text)))
                .append(_c('div').addClass('modal-footer')
                .append(_c('button').attr({
            type : 'button',
            'data-dismiss' : 'modal',
            'aria-hidden' : true
        }).addClass('btn').text('Закрыть')).append(button.click(function()
        {
            $('#showQuestion-js').modal('hide');
        }))).appendTo('body').modal({
            backdrop : 'static'
        }).modal('show').on('hidden', function()
        {
            $(this).remove();
        });
    };

    $('button[data-action=remove]').click(function()
    {
        var self = $(this);
        showQuestion('Вы действительно хотите удалить выделенные записи?', _c('button').attr({
            type : 'button'
        }).addClass('btn btn-inverse').text('Да').click(function()
        {
            if (self.data('range') === 'one') {
                $.post('/admin/orders/request.php?fn=remove/one', {
                    ID : self.data('value')
                }, function()
                {
                    location.reload();
                });
            } else if (self.data('range') === 'all') {
                $.post('/admin/orders/request.php?fn=remove/all', {
                    IDs : $.makeArray($('input[name^="checkgroup[order]"]:checked').map(function()
                    {
                        return $(this).data('value');
                    }))
                }, function()
                {
                    location.reload();
                });
            }
        }));
        delete self;
    });

    $('select[name=deliveryStatus]').change(function()
    {
        $.post('/admin/orders/request.php?fn=delivered/one', {
            ID : $(this).data('id'),
            value : $(this).val()
        }, function()
        {
            location.reload();
        });
    });

    $('button[data-action=newOrder]').click(function()
    {
        var self = $(this);
        showQuestion('Изменить статус на "Новый заказ" для всех выделенных записей?', _c('button').attr({
            type : 'button'
        }).addClass('btn btn-inverse').text('Да').click(function()
        {
            if (self.data('range') === 'all') {
                $.post('/admin/orders/request.php?fn=newOrder/all', {
                    value : self.data('value'),
                    IDs : $.makeArray($('input[name^="checkgroup[order]"]:checked').map(function()
                    {
                        return $(this).data('value');
                    }))     
                }, function()
                {
                    location.reload();
                });
            }
        }));
        delete self;
    });

    $('button[data-action=confirmed]').click(function()
    {
        var self = $(this);
        showQuestion('Изменить статус на "Подтвержден" для всех выделенных записей?', _c('button').attr({
            type : 'button'
        }).addClass('btn btn-inverse').text('Да').click(function()
        {
            if (self.data('range') === 'all') {
                $.post('/admin/orders/request.php?fn=confirmed/all', {
                    value : self.data('value'),
                    IDs : $.makeArray($('input[name^="checkgroup[order]"]:checked').map(function()
                    {
                        return $(this).data('value');
                    }))     
                }, function()
                {
                    location.reload();
                });
            }
        }));
        delete self;
    });

    $('button[data-action=inPrint]').click(function()
    {
        var self = $(this);
        showQuestion('Изменить статус на "В печати" для всех выделенных записей?', _c('button').attr({
            type : 'button'
        }).addClass('btn btn-inverse').text('Да').click(function()
        {
            if (self.data('range') === 'all') {
                $.post('/admin/orders/request.php?fn=inPrint/all', {
                    value : self.data('value'),
                    IDs : $.makeArray($('input[name^="checkgroup[order]"]:checked').map(function()
                    {
                        return $(this).data('value');
                    }))     
                }, function()
                {
                    location.reload();
                });
            }
        }));
        delete self;
    });

    $('button[data-action=execution]').click(function()
    {
        var self = $(this);
        showQuestion('Изменить статус на "Доставляется / Вывозится" для всех выделенных записей?', _c('button').attr({
            type : 'button'
        }).addClass('btn btn-inverse').text('Да').click(function()
        {
            if (self.data('range') === 'all') {
                $.post('/admin/orders/request.php?fn=execution/all', {
                    value : self.data('value'),
                    IDs : $.makeArray($('input[name^="checkgroup[order]"]:checked').map(function()
                    {
                        return $(this).data('value');
                    }))     
                }, function()
                {
                    location.reload();
                });
            }
        }));
        delete self;
    });

    $('button[data-action=delivered]').click(function()
    {
        var self = $(this);
        showQuestion('Изменить статус на "Выполнен" для всех выделенных записей?', _c('button').attr({
            type : 'button'
        }).addClass('btn btn-inverse').text('Да').click(function()
        {
            if (self.data('range') === 'all') {
                $.post('/admin/orders/request.php?fn=delivered/all', {
                    value : self.data('value'),
                    IDs : $.makeArray($('input[name^="checkgroup[order]"]:checked').map(function()
                    {
                        return $(this).data('value');
                    }))     
                }, function()
                {
                    location.reload();
                });
            }
        }));
        delete self;
    });
 
    $('select[name=isPrinted]').change(function()
    {
        $.post('/admin/orders/request.php?fn=printed/one', {
            ID : $(this).data('id'),
            value : $(this).val()
        }, function()
        {
            location.reload();
        });
    });

    $('button[data-action=printed]').click(function()
    {
        var self = $(this);
        showQuestion('Изменить статус на "рапечатано" для всех выделенных записей?', _c('button').attr({
            type : 'button'
        }).addClass('btn btn-inverse').text('Да').click(function()
        {
            if (self.data('range') === 'all') {
                $.post('/admin/orders/request.php?fn=printed/all', {
                    IDs : $.makeArray(self.closest('tr').find('input[name^="checkgroup[cart]"]:checked').map(function()
                    {
                        return $(this).data('value');
                    }))
                }, function()
                {
                    location.reload();
                });
            }
        }));
        delete self;
    });
    
    getSecondsTime = function(seconds, element)
    {

        time    = [];
        seconds = Math.round(seconds);
        h       = Math.floor(seconds / 3600);
        m       = Math.floor(seconds % 3600 / 60);
        s       = Math.floor(seconds % 3600 % 60);

        if (h >= 48 && element.hasClass('btn-danger') === false) {
            element.addClass('btn-danger');
        } else if (m > 36 && element.hasClass('btn-warning') === false) {
            element.addClass('btn-warning');
        }

        $.each([h, m, s], function(k, v)
        {
            if (v > 0) {
                if (v > 9) {
                    time.push(v);
                } else {
                    time.push('0' + v);
                }
            } else {
                time.push('00');
            }
        });

        element.text(time.join(':'));
    };
    
    timers = [];
    
    $('.timerButton').click(function()
    {
        var self = $(this);
        
        if (self.data('action')) {

            self.data('action', 0).trigger('timer/stop');
            
            $.post('/admin/orders/request.php?fn=timer/stop', {
                ID : self.data('id')
            }, function(data)
            {
                if (data.action === false) {
                    showInfo('Таймер был остановлен другим пользователем');
                    self.data('seconds', data.timerDate);
                    getSecondsTime(parseInt(self.data('seconds')), self);
                }
            }, 'json');
        } else {
            
            self.data('action', 1).trigger('timer/start');
            
            $.post('/admin/orders/request.php?fn=timer/start', {
                ID : self.data('id')
            }, function(data)
            {
                if (data.action === false) {
                    showInfo('Таймер был запущен другим пользователем');
                    self.data('seconds', data.timerDate);
                    getSecondsTime(parseInt(self.data('seconds')), self);
                }
            }, 'json');
        }

        delete self;
    }).bind('timer/start', function()
    {
        var self = $(this);
        timers[$(this).data('id')] = setInterval(function()
        {
            self.data('seconds', parseInt(self.data('seconds'), 10) + 1);
            getSecondsTime(parseInt(self.data('seconds')), self);
        }, 1000);
        delete self;
    }).bind('timer/stop', function()
    {
        clearInterval(timers[$(this).data('id')]);
    }).filter('[data-action=1]').trigger('timer/start');
   

    $('button[data-action="download-zip"]').click(function()
    {

        var btn = $(this);

        if (btn.data('request') === false) {

            btn.data('request', true).find('i').addClass('load');
            
            $.ajax('/admin/PDF/create.php', {
                dataType: "json", 
                type : 'GET',
                data : {
                    order : $(this).data('order')
                },
                success : function(data)
                {

                    if (data.action) {
                        window.location = data.src;
                    } else {
                        showError(data.message);
                    }

                    btn.data('request', true).find('i').removeClass('load');
                }
            });
        }
        
        delete btn;
    });

});