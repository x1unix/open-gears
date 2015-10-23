<?php
/**
 * i18n module for OpenGears
 * 
 * @author Denis Sedchenko
 * @date   24.10.2015
 */
class i18n {
    /**
     * Contains a current dictionary
     * @var array
     */
    public static $container;

    /**
     * If i18n is loaded
     * @var bool
     */
    public static $loaded;

    /**
     * Load a dictionary file
     * @param string $lang 
     * @throws i18nFileNotFoundException 
     * @return bool
     */
    public static function load($lang) {

        if(!file_exists(LANG.$lang.'.php')) {
            throw new i18nFileNotFoundException("language resource file not found: {$lang}.php",1);
            self::$loaded = false;
            return false;
        }

        self::$container = include(LANG.$lang.'.php');
        self::$loaded = true;
        return true;
    }

    /**
     * Extract a string from dictionary
     * @param string $string 
     * @return string
     */
    public static function extract($string) {
        if(self::$loaded == false || !isset(self::$container[$string])) return $string;
        return self::$container[$string];
    }
}    

/**
 * Get a string from i18n resource
 * @param string $str 
 * @return string
 */
function __($str) {
    return i18n::extract($str);
}

class i18nFileNotFoundException extends Exception { }
?>