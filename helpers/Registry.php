<?php

class Registry {

    protected static $_storage = array();

    public static function getStorage() {
        return self::$_storage;
    }

    protected function __construct() {
        
    }

    protected function __clone() {
        
    }

    public static function is($key = '') {
        return isset(self::$_storage[$key]);
    }

    public static function get($key = '') {
        return (isset(self::$_storage[$key])) ? self::$_storage[$key] : null;
    }

    public static function set($key, $value) {
        return self::$_storage[$key] = $value;
    }

    public static function setArray(array $array = array()) {
        self::$_storage = array_merge(self::$_storage, $array);
    }

    public static function getInArray($key, $second_key) {
        return self::is($key) && isset(self::$_storage[$key][$second_key]) ? self::$_storage[$key][$second_key] : null;
    }

}

class Config extends Registry {

    public static $ajaxPost = null;

    public static function prepareValue() {

        $args = func_get_args();

        if (count($args)) {
            foreach ($args as $arg) {
                self::set($arg, ConvertAltToHtml(self::get($arg)));
            }
        }
    }

}
