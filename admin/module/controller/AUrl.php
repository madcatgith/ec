<?php

class AUrl extends Registry {

    public static function parse($uri) {
        
        $parts = array_filter(explode('/', $uri));
        
        self::set('module', $parts[3]);
        
        foreach (array(
            'banner',
            'form',
            'media',
            'element',
            'view'
        ) as $controller) {
            $matches = array();
            if (preg_match('/' . $controller . '/usi', $uri, $matches)) {
                self::set('controller', $controller);
                self::set('action', 'index');
            }
            if (preg_match('/' . $controller . '\/([\d]+)/usi', $uri, $matches)) {
                self::set($controller . 'ID', $matches[1]);
                self::set('action', 'update');
            }
            if (preg_match('/' . $controller . '\/insert/usi', $uri, $matches)) {
                self::set('action', 'insert');
            }
            if (preg_match('/' . $controller . '\/insert\/([\w]+)/usi', $uri, $matches)) {
                self::set('type', $matches[1]);
            }
        }                
    }

}
