<?php

function afterPoint($value, $number = 2, $doubleZero = false)
{
    $value = explode('.', $value);

    if (isset($value[1])) {
        $value[1] = substr($value[1], 0, $number);
        
        if(empty($value[1])) {
            unset($value[1]);
        }
    }

    return ($doubleZero == true) ? str_replace('.00', '', implode('.', $value)) : implode('.', $value);
}

function getPriceZero($value)
{
    $parts = explode('.', $value);
    if(!empty($parts[1])) {
        return '.' . $parts[1];
    }

    return '.00';
}
/*
 * участник, участника, участников  
 */
function declensionOfVerbs($number, $strings = array()) 
{
    $cases  = array(2, 0, 1, 1, 1, 2);
    $word   = ($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)];
    return !empty($strings[$word]) ? $strings[$word] : $strings[0];   
}
