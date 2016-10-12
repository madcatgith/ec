<?php

class IBlocks {

    protected static $_tables = array(
        'portfolio' => '?_portfolio',
        'contacts' => '?_contacts',
		'productattr' => '?_product_attr',
		'elproduct' => '?_product_electric',
		'use' => '?_use'
    );

    protected static $_labels = array(
        'portfolio' => 'Portfolio',
        'contacts' => 'Contacts',
		'productattr' => 'Product Attributes',
		'elproduct' => 'Electronic product attributes',
		'use' => 'Slides for uses'
    );
    protected static $hasAlias      = array('portfolio', 'contacts', 'elproduct', 'use');
    protected static $withoutLang   = array('productattr');

    public static function getTable($table) {
        return isset(self::$_tables[$table]) ? self::$_tables[$table] : null;
    }

    public static function getTablesHasAlias() {        
        return self::$hasAlias;
    }
    
    public static function tableHasAlias($iblock) {
        return (bool) in_array($iblock, self::$hasAlias) ? 1 : 0;
    }

    public static function isWithoutLang($iblock) {
        return (bool) in_array($iblock, self::$withoutLang) ? 1 : 0;
    }
    
    public static function getLabel($table) {
        return isset(self::$_labels[$table]) ? self::$_labels[$table] : null;
    }

}
