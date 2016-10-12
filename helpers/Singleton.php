<?php

abstract class Singleton
{

    public static function getInstance()
    {
        if (is_null(static::$_instance) === true) {
            return (static::$_instance = new static);
        } else {
            return static::$_instance;
        }
    }

    protected function __construct()
    {
        
    }

    private function __clone()
    {
        
    }

    private function __wakeup()
    {
        
    }

}
