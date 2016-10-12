<?php

function getTH($field, $name, $options = '')
{

    $class = '';
    $sord  = '';
    $input = '';
    $parts = array();

    parse_str($_SERVER['QUERY_STRING'], $parts);

    $parts['sort'] = $field;

    if (isset($_REQUEST['sort']) && $_REQUEST['sort'] == $field) {

        $class = ' sorted';

        if ($_REQUEST['sord'] == 'asc') {
            $sord          = ' <i class="icon-chevron-up datagrid-sort"></i>';
            $parts['sord'] = 'desc';
        } else if ($_REQUEST['sord'] == 'desc') {
            $sord          = ' <i class="icon-chevron-down datagrid-sort"></i>';
            $parts['sord'] = 'asc';
        }

        $input .= '<input type="hidden" name="sort" value="' . $parts['sort'] . '" />';
        $input .= '<input type="hidden" name="sord" value="' . $_REQUEST['sord'] . '" />';
    } else {
        $parts['sord'] = 'asc';
    }

    return '<th class="tha sortable' . $class . '" ' . $options . '><a href="/admin/orders/?' . http_build_query(array_filter($parts)) . '">' . $name . $sord . '</a>' . $input . '</th>';
}

function getFilters($name, $type = 'text', $options = '')
{

    if (isset($_REQUEST['filters'][$name])) {
        if (is_array($_REQUEST['filters'][$name])) {
            $value = clearVal($_REQUEST['filters'][$name]['value']);
        } else {
            $value = clearVal($_REQUEST['filters'][$name]);
        }
    } else {
        $value = '';
    }

    switch ($type) {
        case 'text':
            return '<input style="width: 100%;" class="' . $options . '" type="text" name="filters[' . $name . ']" value="' . addslashes($value) . '" />';
            break;
        case 'date':
            return '<div data-date="' . $value . '"  data-date-format="yyyy-mm-dd" class="input-append date"><input type="text" name="filters[' . $name . ']" value="' . $value . '" size="16" style="width: 90px;"><span class="add-on"><i class="icon-calendar"></i></span></div>';
            break;
        case 'select':
            $select = '<select name="filters[' . $name . ']" class="input-medium">';
            foreach ($options as $key => $value) {
                $select .= '<option value="' . $key . '"' . (isset($_REQUEST['filters'][$name]) && $_REQUEST['filters'][$name] == $key ? ' selected="selected"' : '') . '>' . $value . '</option>';
            }
            return $select . '</select>';
            break;
        case 'integer':
            $str = '<div class="input-append"><select style="width: 50px;" name="filters[' . $name . '][type]" class="input-mini">';
            foreach (array('lt' => '<', 'eq' => '=', 'gt' => '>') as $k => $v) {
                $str .= '<option' . (isset($_REQUEST['filters'][$name]['type']) && $_REQUEST['filters'][$name]['type'] == $k ? ' selected="selected"' : '') . ' value="' . $k . '">' . $v . '</option>';
            }
            return $str . '</select><input class="input-small" style="width: 70px;" type="text" name="filters[' . $name . '][value]" value="' . addslashes($value) . '" /></div>';
            break;
    }
}

function pagination($page, $total, $onPage, $numLink = 5)
{

    $parts = array();

    parse_str($_SERVER['QUERY_STRING'], $parts);

    $numPages  = ceil($total / $onPage);
    $loopStart = ($page - $numLink > 0) ? $page - ($numLink - 1) : 1;
    $loopEnd   = ($page + $numLink < $numPages) ? $page + $numLink - 1 : $numPages;
    $str       = '<div class="pagination" id="page" style="margin: 15px 0 0;"><ul>';

    if ($page > 1) {
        $parts['page'] = $page - 1;
        $str .= '<li><a href="/admin/orders/?' . http_build_query(array_filter($parts)) . '">&laquo;</a></li>';
    } else {
        $str .= '<li class="disabled"><a href="#">&laquo;</a></li>';
    }

    if ($page - $numLink > 1) {
        $parts['page'] = $page - $numLink;
        $str .= '<li><a href="/admin/orders/?' . http_build_query(array_filter($parts)) . '">...</a></li>';
    }

    for ($loop = $loopStart; $loop <= $loopEnd; $loop++) {
        if ($page == $loop) {
            $str .= '<li class="active"><a href="#">' . $loop . '</a></li>';
        } else {
            $parts['page'] = $loop;
            $str .= '<li><a href="/admin/orders/?' . http_build_query(array_filter($parts)) . '">' . $loop . '</a></li>';
        }
    }

    if ($page < $numPages - $numLink - 1) {
        $parts['page'] = $page + $numLink;
        $str .= '<li><a href="/admin/orders/?' . http_build_query(array_filter($parts)) . '">...</a></li>';
    }

    if ($page < $numPages) {
        $parts['page'] = $page + 1;
        $str .= '<li><a href="/admin/orders/?' . http_build_query(array_filter($parts)) . '">&raquo;</a></li>';
    } else {
        $str .= '<li class="disabled"><a href="#">&raquo;</a></li>';
    }

    return $str . '</ul></div>';
}

function fixStrTime($time)
{
    if ($time < 10) {
        return '0' . $time;
    } else {
        return $time;
    }
}

function getTimerBadge($timerSecond)
{

    $hours   = floor($timerSecond / 3600);
    $minuts  = floor($timerSecond % 3600 / 60);
    $seconds = floor($timerSecond % 3600 % 60);

    $class  = '';
    $config = array(
        0  => 'badge badge-success',
        36 => 'badge badge-warning',
        48 => 'badge badge-important'
    );

    foreach ($config as $k => $v) {
        if ($hours >= $k) {
            $class = $v;
        }
    }

    return '<span style="margin-left: 4px;" class="timerLink ' . $class . '">' . fixStrTime($hours) . ':' . fixStrTime($minuts) . ':' . fixStrTime($seconds) . '</span>';
}

function getTimer($timerSecond, $timeDate, $status, $id)
{

    if ($status) {

        $dateStart = new DateTime($timeDate);
        $dateEnd   = new DateTime();
        $diff      = $dateEnd->diff($dateStart);
        $timerSecond += $diff->d * 86400 + $diff->h * 3600 + $diff->i * 60 + $diff->s;
    }

    $class   = '';
    $hours   = floor($timerSecond / 3600);
    $minuts  = floor($timerSecond % 3600 / 60);
    $seconds = floor($timerSecond % 3600 % 60);
    $config  = array(
        0  => 'btn btn-success',
        36 => 'btn btn-warning',
        48 => 'btn btn-danger'
    );

    foreach ($config as $k => $v) {
        if ($hours >= $k) {
            $class = $v;
        }
    }

    if ($status == false) {
        $class .= ' stopped';
    }

    return '<button type="button" data-action="' . intval($status) . '" data-id="' . $id . '" data-seconds="' . $timerSecond . '" class="timerButton ' . $class . '">' . fixStrTime($hours) . ':' . fixStrTime($minuts) . ':' . fixStrTime($seconds) . '</button>';
}