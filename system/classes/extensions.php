<?php
class Extensions
{
    public static function load($a)
    {
        if(!is_array($a)) return self::call($a);
        foreach ($a as $key => $value) {
            self::call($value);
        }
        return new self;
    }
    public static function call($e) {
        $origin = "KEXT_".strtoupper($e);
        $e = EXTENSIONS.str_replace(array("\\","/",".."),"", $e).".php";
        if(!file_exists($e)) throw new ExtensionNotFoundException("Selected extension file not found and cannot be loaded: '".$e."'");
        define(strtoupper($origin)."",true);
        include($e);
        return new self;
    }
    public static function exists($e) { return defined("KEXT_".strtoupper($e)); }
    public static function def($e) {
        define("KEXT_".strtoupper($e),true);
        return "KEXT_".strtoupper($e);
    }
    public static function request($e)
    {
        if(!self::exists($e)) throw new ExtensionNotLoadedException("This module requires a extension, which has not been loaded: '".$e."'");
    }
}

class ExtensionNotFoundException extends Exception { }
class ExtensionNotLoadedException extends Exception { }
?>