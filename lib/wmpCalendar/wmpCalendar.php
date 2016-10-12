<?php

class Calendar
{

    protected static $month = array(
        1 => array(
            1  => 'Январь',
            2  => 'Февраль',
            3  => 'Март',
            4  => 'Апрель',
            5  => 'Май',
            6  => 'Июнь',
            7  => 'Июль',
            8  => 'Август',
            9  => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь'
        ), 2 => array(
            1  => 'Январь',
            2  => 'Февраль',
            3  => 'Март',
            4  => 'Апрель',
            5  => 'Май',
            6  => 'Июнь',
            7  => 'Июль',
            8  => 'Август',
            9  => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь'
    ));
    protected static $monthDeclination = array(
        1 => array(
            1  => 'Января',
            2  => 'Февраля',
            3  => 'Марта',
            4  => 'Апреля',
            5  => 'Мая',
            6  => 'Июня',
            7  => 'Июля',
            8  => 'Августа',
            9  => 'Сентября',
            10 => 'Октября',
            11 => 'Ноября',
            12 => 'Декабря'
        ), 2 => array(
            1  => 'Января',
            2  => 'Февраля',
            3  => 'Марта',
            4  => 'Апреля',
            5  => 'Мая',
            6  => 'Июня',
            7  => 'Июля',
            8  => 'Августа',
            9  => 'Сентября',
            10 => 'Октября',
            11 => 'Ноября',
            12 => 'Декабря'
    ));
    protected static $dayOfWeek = array(
        1 => array(
            1 => 'пн',
            2 => 'вт',
            3 => 'ср',
            4 => 'чт',
            5 => 'пт',
            6 => 'сб',
            7 => 'вс'
        ), 2 => array(
            1 => 'пн',
            2 => 'вт',
            3 => 'ср',
            4 => 'чт',
            5 => 'пт',
            6 => 'сб',
            7 => 'вс'
    ));

    public static function getMonth($langID, $month)
    {
        return !empty(self::$month[$langID]) ? self::$month[$langID][intval($month)] : null;
    }

    public static function getDayOfWeek($langID, $day)
    {
        return !empty(self::$dayOfWeek[$langID]) ? self::$dayOfWeek[$langID][$day] : null;
    }

    public static function getMonthDeclination($langID, $month)
    {
        return !empty(self::$monthDeclination[$langID]) ? self::$monthDeclination[$langID][intval($month)] : null;
    }

}
