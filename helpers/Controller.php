<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Controller
{

    protected static $_errors = array();

    public static function run($controller, array $context = array(), $return = false)
    {
        $fConteoller = BASEPATH . 'controller/' . $controller . '.php';

        if (file_exists($fConteoller)) {
            if ($return) {
                return include $fConteoller;
            } else {
                include $fConteoller;
            }
        } else {
            self::$_errors = 'Файл "' . $fConteoller . '" не существует';
        }
    }

}
