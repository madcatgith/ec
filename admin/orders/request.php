<?php

# подключаем конфиг
include getenv('DOCUMENT_ROOT') . '/config.php';

# пытаемся сжать контент
$buffer = Buffer::getInstance();
$buffer->obStart();

header("Content-Type: text/html; charset=utf-8");

# Заносим переменные из конфига
Config::setArray(DB::GetRow(DB::Query("SELECT * FROM `?_config` WHERE id=0 and lang_id=" . Lang::getID())));
Config::prepareValue('above_menu', 'text');

include dirname(__FILE__) . '/helpers/tables.php';

switch (isset($_REQUEST['fn']) ? $_REQUEST['fn'] : '') {
    case 'edit/info':

        $data  = explode('-', $_REQUEST['elementid']);
        $ID    = intval(preg_replace('/[^\d]+/usi', '', $data[0]));
        $field = $data[1];

        if ($ID) {

            switch ($field) {
                case 'address':
                case 'name':
                case 'email':
                case 'phone':
                case 'paymentMethod':
                    DB::Query('update ?_order set `' . $field . '`="' . mysql_real_escape_string($value = clearVal($_REQUEST['value'])) . '" where id=' . $ID);
                    break;
            }
            
            if ($field == 'paymentMethod') {
                $paymentMethods = Config::set('paymentMethods');
                echo $paymentMethods[$value];
            
            } else {
                echo $value;
            }
        }
        break;
    case 'edit/comment':

        $ID = intval(preg_replace('/[^\d]+/usi', '', $_REQUEST['elementid']));

        if ($ID) {
            DB::Query('update ?_order set comment="' . mysql_real_escape_string($value = clearVal($_REQUEST['value'])) . '" where id=' . $ID);
            echo $value;
        }
        break;
    case 'report':
        include dirname(__FILE__) . '/helpers/genExcel.php';
        break;
    case 'remove/all':
        DB::Query('update ?_order set isDeleted=1 where id in (' . implode(',', $_REQUEST['IDs']) . ')');
        break;
    case 'remove/one':
        DB::Query('update ?_order set isDeleted=1 where id=' . intval($_REQUEST['ID']));
        break;
    case 'inPrint/all':
    case 'newOrder/all':
        DB::Query('update ?_order set deliveryStatus=' . intval($_REQUEST['value']) . ' where id in (' . implode(',', $_REQUEST['IDs']) . ')');
    break;
    case 'confirmed/all':
        DB::Query('update ?_order set timerDate="' . date('Y-m-d H:i:s') . '", isRunning=1, deliveryStatus=' . intval($_REQUEST['value']) . ' where id in (' . implode(',', $_REQUEST['IDs']) . ')');
        break;
    case 'execution/all':

        DB::Query('update ?_order set deliveryStatus=' . intval($_REQUEST['value']) . ' where id in (' . implode(',', $_REQUEST['IDs']) . ')');

        $query = DB::Query('select * from ?_order where id in (' . implode(',', $_REQUEST['IDs']) . ')');

        while ($data = DB::GetArray($query)) {

            $timerSecond = $data['timerSecond'];
            $dateStart   = new DateTime($data['timerDate']);
            $dateEnd     = new DateTime;
            $diff        = $dateEnd->diff($dateStart);
            $timerSecond += $diff->d * 86400 + $diff->H * 3600 + $diff->i * 60 + $diff->s;

            DB::Query('update ?_order set timerSecond=' . $timerSecond . ', timerDate="' . date('Y-m-d H:i:s') . '", isRunning=0 where id=' . intval($data['id']));
        }
        break;
    case 'delivered/all':
        Registry::get('db')->exec('update ?_order set deliveryStatus=' . intval($_REQUEST['value']) . ' where id in (' . implode(',', $_REQUEST['IDs']) . ')');
        /*
        if ($_REQUEST['value'] == 4) {

            DB::Query('update ?_order set paymentStatus=1, deliveryStatus=' . intval($_REQUEST['value']) . ', orderDelivered=NOW() where id in (' . implode(',', $_REQUEST['IDs']) . ')');

            $query = DB::Query('select * from ?_orders where id in (' . implode(',', $_REQUEST['IDs']) . ')');

            while ($data = DB::GetArray($query)) {

                $timerSecond = $data['timerSecond'];
                $dateStart   = new DateTime($data['timerDate']);
                $dateEnd     = new DateTime;
                $diff        = $dateEnd->diff($dateStart);
                $timerSecond += $diff->d * 86400 + $diff->H * 3600 + $diff->i * 60 + $diff->s;

                DB::Query('update ?_orders set timerSecond=' . $timerSecond . ', timerDate="' . date('Y-m-d H:i:s') . '", isRunning=0 where id=' . intval($data['id']));
            }
        } else {
            DB::Query('update ?_orders set deliveryStatus=' . intval($_REQUEST['value']) . ', orderDelivered="0000-00-00 00:00:00" where id in (' . implode(',', $_REQUEST['IDs']) . ')');
        }
         * 
         */
        break;
    case 'delivered/one':
        Registry::get('db')->exec('update ?_order set deliveryStatus=' . intval($_REQUEST['value']) . ' where id=' . intval($_REQUEST['ID']));
        /*
        if ($_REQUEST['value'] == 4) {

            DB::Query('update ?_orders set paymentStatus=1, deliveryStatus=' . intval($_REQUEST['value']) . ', orderDelivered=NOW() where id=' . intval($_REQUEST['ID']));

            $data        = DB::GetArray(DB::Query('select * from ?_orders where id=' . intval($_REQUEST['ID'])));
            $timerSecond = $data['timerSecond'];
            $dateStart   = new DateTime($data['timerDate']);
            $dateEnd     = new DateTime;
            $diff        = $dateEnd->diff($dateStart);
            $timerSecond += $diff->d * 86400 + $diff->H * 3600 + $diff->i * 60 + $diff->s;

            DB::Query('update ?_orders set timerSecond=' . $timerSecond . ',timerDate="' . date('Y-m-d H:i:s') . '", isRunning=0 where id=' . intval($_REQUEST['ID']));
        } else if ($_REQUEST['value'] == 3) {

            DB::Query('update ?_orders set deliveryStatus=' . intval($_REQUEST['value']) . ' where id=' . intval($_REQUEST['ID']));

            $data        = DB::GetArray(DB::Query('select * from ?_orders where id=' . intval($_REQUEST['ID'])));
            $timerSecond = $data['timerSecond'];
            $dateStart   = new DateTime($data['timerDate']);
            $dateEnd     = new DateTime;
            $diff        = $dateEnd->diff($dateStart);
            $timerSecond += $diff->d * 86400 + $diff->H * 3600 + $diff->i * 60 + $diff->s;

            DB::Query('update ?_orders set timerSecond=' . $timerSecond . ',timerDate="' . date('Y-m-d H:i:s') . '", isRunning=0 where id=' . intval($_REQUEST['ID']));
        } else if ($_REQUEST['value'] == 1) {
            DB::Query('update ?_orders set timerDate="' . date('Y-m-d H:i:s') . '", isRunning=1, deliveryStatus=' . intval($_REQUEST['value']) . ' where id=' . intval($_REQUEST['ID']));
        } else {
            DB::Query('update ?_orders set deliveryStatus=' . intval($_REQUEST['value']) . ', orderDelivered="0000-00-00 00:00:00" where id=' . intval($_REQUEST['ID']));
        }
         * 
         */
        break;
    case 'printed/all':
        DB::Query('update ?_cart set isPrinted=1 where id in (' . implode(',', $_REQUEST['IDs']) . ')');
        break;
    case 'printed/one':
        DB::Query('update ?_cart set isPrinted=' . intval($_REQUEST['value']) . ' where id=' . intval($_REQUEST['ID']));
        break;
    case 'timer/start':

        $data = DB::GetArray(DB::Query('select timerSecond, isRunning from ?_orders where id=' . intval($_REQUEST['ID'])));

        if (!$data['isRunning']) {
            DB::Query('update ?_orders set timerDate="' . date('Y-m-d H:i:s') . '", isRunning=1 where id=' . intval($_REQUEST['ID']));
        } else {
            echo json_encode(array(
                'action'      => false,
                'timerSecond' => $data['timerSecond']
            ));
        }
        break;
    case 'timer/stop':

        $data = DB::GetArray(DB::Query('select * from ?_orders where id=' . intval($_REQUEST['ID'])));

        if ($data['isRunning']) {
            $timerSecond = $data['timerSecond'];
            $dateStart   = new DateTime($data['timerDate']);
            $dateEnd     = new DateTime('now');
            $diff        = $dateEnd->diff($dateStart);
            $timerSecond += $diff->d * 86400 + $diff->h * 3600 + $diff->i * 60 + $diff->s;
            DB::Query('update ?_orders set timerSecond=' . $timerSecond . ',timerDate="' . date('Y-m-d H:i:s') . '", isRunning=0 where id=' . intval($_REQUEST['ID']));
        } else {
            echo json_encode(array(
                'action'      => false,
                'timerSecond' => $data['timerSecond']
            ));
        }

        break;
}

$buffer->obEndFlush();
