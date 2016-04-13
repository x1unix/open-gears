<?php
  /**
   * OpenGears Core Functions
   * @version 1.0
   * @package com.opengears.libcore
   * @author Denis Sedchenko [sedchenko.in.ua]
   */

if(!function_exists('mb_ucfirst')) {
    function mb_ucfirst($str, $enc = 'utf-8') { 
            return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc).mb_substr($str, 1, mb_strlen($str, $enc), $enc); 
    }
}
if(!function_exists('typeof')) {
    function typeof($obj)
    {
        $t = gettype($obj);
        if($t != "object") return $t; else return get_class($obj);
    }
}
?>
