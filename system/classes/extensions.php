<?php

/**
 * OpenGears Extensions API
 *
 * Provides a easy extensions management API
 *
 * @author Denis Sedchenko
 * @date 31.10.2015
 * @version 1.1.0
 *
 * Class Extensions
 */
class Extensions
{

    /**
     * Load extension(s) into system
     *
     * @param $a
     * @return Extensions
     * @throws ExtensionNotFoundException
     */
    public static function load($a)
    {
        if(!is_array($a)) return self::call($a);
        foreach ($a as $key => $value) {
            self::call($value);
        }
        return new self;
    }

    /**
     * Call aand register a single extension
     *
     * @param $e
     * @return Extensions
     * @throws ExtensionNotFoundException
     */
    private static function call($e) {
        if(!isset(System::$Scope["extensions"]))System::$Scope["extensions"] = array();
        $origin = "KEXT_".strtoupper($e);
        array_push(System::$Scope["extensions"],$e);
        $e = EXTENSIONS.str_replace(array("\\","/",".."),"", $e).".php";
        if(!file_exists($e)) throw new ExtensionNotFoundException("Selected extension file not found and cannot be loaded: '".$e."'");
        define(strtoupper($origin)."",true);
        include($e);
        return new self;
    }

    /**
     * Check if extension is defined
     *
     * @param string $e
     * @return bool
     */
    public static function exists($e) { return defined("KEXT_".strtoupper($e)); }


    /**
     * Check if depended extension exists and drop error if not
     *
     * @param string $e Extension name
     * @throws ExtensionNotLoadedException
     */
    public static function request($e)
    {
        if(!self::exists($e)) throw new ExtensionNotLoadedException("This module requires a extension, which has not been loaded: '".$e."'");
    }
}

class ExtensionNotFoundException extends Exception { }
class ExtensionNotLoadedException extends Exception { }
?>